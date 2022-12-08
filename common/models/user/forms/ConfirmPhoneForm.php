<?php

namespace common\modules\user\forms;

use Yii;
use yii\base\Model;
use common\components\FormModel;
use common\modules\user\models\User;
use common\modules\user\models\UserSmsConfirmation;

/**
 * Confirm form
 */
class ConfirmPhoneForm extends Model
{
    use FormModel;

    /**
     * @var
     */
    public $phone;

    /**
     * @var
     */
    public $code;

    /**
     * @var
     */
    private $_user;

    /**
     * @var
     */
    private $_email;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone', 'code'], 'required'],
            [['code'], 'validateCode']
        ];
    }


    public function validateCode($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
//            if ($user && $user->status === User::STATUS_ACTIVE) {
//                $this->setResponseCode(104);
//                $this->addError($attribute, 'This user was already confirmed.');
//            }

            if (!$user || !$this->getPhone()) {
                $this->setResponseCode(105);
                $this->addError($attribute, 'Confirmation code is not valid.');
                \Yii::$app->session->setFlash('login', __('Confirm code is expired!'));
            }
        }
    }


    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        /**
         * @var $user User
         */

        $user = $this->getUser();
        if ($user->step == User::STEP_REGISTRATION_STARTED) {
            $user->updateAttributes(['step' => User::STEP_PHONE_CONFIRMED]);
        }
        $user->setActivated();
//        $user->setPhoneConfirmed();

//        $email = $this->getPhone();
        //$email->setConfirmed();

        return $user;
    }


    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByPhone($this->phone);
        }

        return $this->_user;
    }


    protected function getPhone()
    {
        if ($this->_email === null) {
            $this->_email = UserSmsConfirmation::validateConfirmation($this->phone, $this->code);
        }

        return $this->_email;
    }
}
