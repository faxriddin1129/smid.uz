<?php

namespace common\modules\user\modules\admin\forms;

use common\modules\file\models\File;
use Yii;
use yii\base\Model;
use common\components\FormModel;
use common\modules\user\models\User;
use common\modules\user\models\UserEmailConfirmation;

/**
 * Create form
 */
class CreateForm extends Model
{
    /**
     * @var
     */
    public $email;

    /**
     * @var
     */
    public $phone;

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
    public $first_name;

    /**
     * @var
     */
    public $last_name;

    /**
     * @var
     */
    public $image_id;

    /**
     * @var
     */
    public $status;

    /**
     * @var
     */
    public $organizationIds;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'phone', 'password', 'password_confirm', 'first_name', 'last_name'], 'required'],
            [['email'], 'email'],
            [['email'], 'validateEmail'],
            [['phone', 'first_name', 'last_name'], 'string'],
            [['password'], 'string', 'min' => 6],
            [['password_confirm'], 'compare', 'compareAttribute' => 'password'],
            [['image_id'], 'default', 'value' => null],
            [['image_id'], 'integer'],
            [['image_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::className(), 'targetAttribute' => ['image_id' => 'id']],
            ['status', 'default', 'value' => User::STATUS_INACTIVE],
            ['status', 'in', 'range' => [User::STATUS_ACTIVE, User::STATUS_INACTIVE, User::STATUS_DELETED]],
            [['organizationIds'], 'safe'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validateEmail($attribute, $params)
    {
        if(User::isEmailExists($this->email)){
            $this->addError("email", 'This email already exists');
        }
    }

    /**
     * @return bool|User
     * @throws \yii\base\Exception
     */
    public function save()
    {
        if (!$this->validate()) {
            return  false;
        }

        $model = new User();
        $model->setAttributes([
            'email' => $this->email,
            'phone' => $this->phone,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'image_id' => $this->image_id,
            'status' => $this->status,
        ]);
        $model->setPassword($this->password);
        $model->generateAuthKey();
        $model->setToken();

        if(!$model->save()){
            return false;
        }

        return $model;
    }
}
