<?php
namespace app\models;

use Yii;
use \yii\web\IdentityInterface;

class User extends Applicant implements IdentityInterface
{
    public $authKey;
    public $accessToken;
    public $role;

    public static function tableName()
    {
        return '{{%applicant}}';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $users = Yii::$app->params['users'];
        foreach ($users as $user) {
            if ($user['id'] === $id) {
                return new static($user);
            }
        }

        $tmp = static::findOne(['id' => $id]);
        //if(!$tmp) echo "NULL"; die();
        return $tmp;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $users = Yii::$app->params['users'];
        foreach ($users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username, $specialty)
    {
        $users = Yii::$app->params['users'];
        foreach ($users as $user) {
            if ($user['vat'] === $username) {
                return new static($user);
            }
        }

        return static::findOne(['vat' => $username, 'specialty' => $specialty]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     *
     * @return boolean
     */
    public function isSupervisor()
    {
        return $this->role === 'supervisor';
    }

    public function validateAdmin($password)
    {
        $users = Yii::$app->params['users'];
        return strcasecmp($users['-1']['identity'], $password) === 0;
    }
}
