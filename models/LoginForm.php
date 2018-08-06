<?php
namespace app\models;

use Yii;
use yii\base\Model;
use himiklab\yii2\recaptcha\ReCaptchaValidator;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $specialty;
    public $captchavalidation;
    public $rememberMe = true;
    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        $rules = [
            // username and password are both required
            [['username', 'password', 'specialty'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
        if (\Yii::$app->params['allow-recaptcha']) {
            $rules[] = ['captchavalidation', ReCaptchaValidator::className(), 'uncheckedMessage' => 'Παρακαλώ επιβεβαιώστε ότι είστε άνθρωπος.'];
        }
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Α.Φ.Μ.',
            'password' => 'Α.Δ.Τ.',
            'specialty' => 'Ειδικότητα',
            'rememberMe' => 'Να με θυμάσαι',
            'captchavalidation' => 'Κωδικός επιβεβαίωσης'
        ];
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || ($this->_user->identity !== null) && (strcasecmp(strtr($this->_user->identity, [' ' => '']), strtr($this->password, [' ' => ''])) !== 0) || ($this->_user->identity === null && !$user->validateAdmin($this->password))) {
                $this->addError($attribute, 'Λανθασμένα στοιχεία πρόσβασης.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $tmp = Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            if ($tmp) {
                Yii::info('Successful login', 'user.login');
            } else {
                Yii::error('No login', 'user.login');
            }
            return $tmp;
        } else {
            Yii::error('Failed login', 'user.login');
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username, $this->specialty);
        }

        return $this->_user;
    }
}
