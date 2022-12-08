<?php

namespace common\modules\user\forms;

use common\models\User;
use common\modules\user\models\UserPhoneConfirmation;
use yii\base\Model;
use common\components\FormModel;

/**
 * ForgotPasswordForm form
 */
class ForgotPasswordForm extends Model
{
    use FormModel;
    /**
     * @var
     */
    public $phone;

    /**
     * @var
     */
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['phone', 'trim'],
            ['phone', 'required'],
            ['phone', 'integer'],
            ['phone', 'string', 'max' => 13, 'min'=>13],
        ];
    }

    /**
     * @return $this|bool|UserPhoneConfirmation
     */
    public function save()
    {
        if (!$this->validate()) {
            return  false;
        }

        return $this;
    }

    public function checkUser(): bool
    {
        $this->_user = User::findOne(['phone'=>$this->phone]);

        if (!$this->_user){
            return false;
        }

        return true;
    }

}
