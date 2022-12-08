<?php

namespace common\modules\user\forms;

use Yii;
use yii\base\Model;
use common\components\FormModel;
use common\modules\user\models\User;

/**
 * ChangePasswordForm form
 */
class ChangePasswordForm extends Model
{
    use FormModel;

    /**
     * @var
     */
    public $password;

    /**
     * @var
     */
    public $password_confirm;

    /**
     * @var
     */
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'password_confirm'], 'required'],
            [['password'], 'string', 'min' => 6],
            [['password'], 'compare', 'compareAttribute' => 'password_confirm'],
        ];
    }

    /**
     * @return User
     * @throws \yii\base\Exception
     */
    public function save()
    {
        if (!$this->validate()) {
            return  null;
        }

        /**
         * @var $user User
         */
        $user = $this->getUser();
        $user->setPassword($this->password);
        $user->save();

        return $user;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findIdentity(Yii::$app->user->id);
        }

        return $this->_user;
    }

}
