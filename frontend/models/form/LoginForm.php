<?php

namespace api\models\form;

use common\modules\user\models\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
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
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @throws Exception
     */
    public function save()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            /**
             * Bu vaqtincha comment qilib qo'yildi testiviy ishlatib turish uchun;
             */
            $user->updateToken();
            Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
            return $user;
        }

        return false;
    }

    /**
     * @return array|User|\yii\db\ActiveRecord|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username) ? User::findByUsername($this->username) : User::findByPhone($this->username);
        }

        return $this->_user;
    }

//	protected function getUserByPincode()
//	{
//		if ($this->_user === null) {
//			$this->_user = User::findOne(['pin_code' => md5($this->pin_code)]);
//		}
//
//		return $this->_user;
//	}
}
