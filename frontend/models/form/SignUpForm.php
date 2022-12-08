<?php


namespace frontend\models\form;

use common\modules\user\models\User;

class SignUpForm extends \yii\base\Model
{
    public $password;
    public $username;
    public $email;

    public function formName()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'username', 'email'], 'required'],
            [['password'], 'string'],
            [['email'], 'email'],
            ['username', 'unique', 'targetClass' => User::class],
            [['password'], 'string', 'min' => 6],
        ];
    }


    public function save()
    {

        if (!$this->validate()) {
            return false;
        }

        $user = $this->createUser();

        return $user;
    }

    private function createUser()
    {
        $transaction = \Yii::$app->db->beginTransaction();

        $user = new User();
        $user->setAttributes([
            'status' => User::STATUS_ACTIVE,
            'username' => $this->username,
        ]);

        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->setToken();
        if (!$user->save()) {
            $this->addErrors($user->errors);
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return false;
    }
}
