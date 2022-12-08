<?php

namespace common\modules\user\forms;

use common\components\FormModel;
use common\modules\user\models\User;
use common\modules\user\models\UserSmsConfirmation;
use yii\base\Model;

/**
 * Register form
 */
class RegisterForm extends Model
{
    use FormModel;

    /**
     * @var
     */
    public $email;

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
            [['email'], 'required'],
            ['email', 'unique', 'targetClass' => User::class],
        ];
    }

    /**
     * @return $this|bool|UserSmsConfirmation
     * @throws \yii\base\Exception
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }


        $user = $this->createUser();

        if ($this->_confirmation !== null) {

            /**
             * @var UserSmsConfirmation $model
             */
            $model = $this->_confirmation;
            $model->setResponseCode(101);
            $model->setResponseBody(true);

            return $model;
        }

        return $this;
    }

    /**
     * @return User
     * @throws \yii\base\Exception
     */
    private function createUser()
    {
        $user = new User();
        $user->setAttributes([
            'email' => $this->email,
        ]);
        $user->generateAuthKey();
        $user->setToken();

        $user->save();

        $this->_confirmation = $user->sendSmsConfirmationCode();

        return $user;
    }

}
