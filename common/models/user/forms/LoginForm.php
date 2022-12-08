<?php

namespace common\modules\user\forms;

use Yii;
use yii\base\Model;
use common\components\FormModel;
use common\modules\user\models\User;

/**
 * Login form
 */
class LoginForm extends Model
{
    use FormModel;

    /**
     * @var
     */
    public $login;

    /**
     * @var
     */
    public $password;

    /**
     * @var
     */
    private $_user;
    /**
     * @var
     */
    private $_confirmation;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['login', 'password'], 'required'],
            [['password', 'login'], 'string'],
            [['password'], 'string', 'min' => 3],
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
            if (!$user) {
                $this->addError($attribute, 'Incorrect username');
                return;
            }
            if (!$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect password.');
                return;
            }
        }
    }

    /**
     * @throws \yii\base\Exception
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = $this->getUser();

        if ($user->status === User::STATUS_INACTIVE) {
            $this->setResponseCode(101);
            $this->setResponseBody(true);
        }

        if ($user->status === User::STATUS_DELETED) {
            $this->setResponseCode(103);
            $this->addError("login", "User was deleted.");
        }

        if ($user->status === User::STATUS_ACTIVE) {
            $user->updateToken();
            Yii::$app->user->login($user, 3600 * 24 * 30);
            return $user;
        }

        return $this;
    }

    /**
     * Finds user by [[login]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->login);
        }

        return $this->_user;
    }
}
