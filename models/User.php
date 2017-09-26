<?php
namespace app\models;

use \yii\web\IdentityInterface;

class User extends Applicant implements IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;
    public $role;
    private static $users = [
        '-1' => [
            'id' => '-1',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
            'role' => 'admin'
        ]
    ];


    public static function tableName()
    {
        return '{{%applicant}}';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
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
    public static function findByUsername($username)
    {
		foreach (self::$users as $user) {
            if ($user['username'] === $username) {
                return new static($user);
            }
        }
		//if(strcasecmp(self::$users['-1']['username'], $username) === 0)
		//	return new static(self::$users['-1']);
		
		return static::findOne(['vat' => $username]);
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
    
    public function validateAdmin($password)
    {
		return strcasecmp(self::$users['-1']['password'], $password) === 0;
	}
}
