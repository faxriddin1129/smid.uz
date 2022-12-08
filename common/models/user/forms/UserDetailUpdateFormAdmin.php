<?php

namespace common\modules\user\forms;

use common\modules\file\models\File;
use common\modules\user\models\User;
use common\modules\user\models\UserDetail;
use yii\base\Model;

class UserDetailUpdateFormAdmin extends Model
{

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public $id;
    public $username;
    public $phone;
    public $status;
    public $role;
    public $position_id;
    public $specialization_id;
    public $first_name;
    public $last_name;
    public $middle_name;
    public $birthdate;
    public $passport_number;
    public $address;
    public $birthplace;
    public $chat_id;
    public $diploma_number;
    public $experience;
    public $avatar_id;
    public $password;
    public $password_confirm;

    public function rules()
    {
        return [
            [['experience', 'avatar_id', 'specialization_id', 'status', 'role', 'position_id', 'specialization_id', 'chat_id', 'avatar_id'], 'integer'],
            [['username', 'first_name', 'last_name', 'middle_name', 'passport_number', 'address', 'birthplace', 'diploma_number', 'birthdate'], 'string'],
            [['role','phone'], 'required'],
            [['phone'], 'safe'],
            [['password', 'username'], 'string', 'min' => 4],
            [['username'], 'validateUsername', 'message' => 'This username already use!'],
            [['password', 'password_confirm', 'username', 'position_id', 'specialization_id', 'passport_number',  'first_name', 'last_name', 'middle_name',], 'required', 'on' => self::SCENARIO_CREATE],
            [['password', 'password_confirm'], 'trim'],
            [['password', 'password_confirm'], 'string'],
            [['password_confirm'], 'compare', 'compareAttribute' => 'password'],
            [['avatar_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::class, 'targetAttribute' => ['avatar_id' => 'id']],
        ];
    }

    public function validateUsername($attribute)
    {
       $user = User::find()->andWhere(['username' => $this->username])->andWhere(['<>', 'id', \Yii::$app->user->id])->andWhere(['<>', 'status', User::STATUS_DELETED])->exists();
        return;
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $this->birthdate = strtotime($this->birthdate);

        $transaction = \Yii::$app->db->beginTransaction();
        $user = new User();
        $userDetail =  new UserDetail();

        if ($this->id){
            $user = User::findOne(['id' => $this->id]);
            $userDetail = UserDetail::findOne(['user_id' => $this->id]);
        }
        $user->setAttributes($this->attributes);
        $userDetail->setAttributes($this->attributes);
        if ($this->password){
            $user->setPassword($this->password);
        }
        if (!$this->id){
            $user->generateAuthKey();
            $user->setToken();
        }
        if (!$user->save()){
            $transaction->rollBack();
            return false;
        }
        if (!$userDetail->save()){
            $transaction->rollBack();
            return false;
        }


        $transaction->commit();
        return true;
    }


    public function getModel(){
        $user = User::findOne(['id' => $this->id]);
        $this->setAttributes($user->attributes);
        $this->setAttributes($user->userDetail->attributes);
        $this->birthdate = date('Y-m-d',$this->birthdate);
        return $this;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['password', 'password_confirm','experience', 'avatar_id', 'specialization_id', 'status', 'role', 'position_id', 'specialization_id', 'chat_id', 'avatar_id','username', 'first_name', 'last_name', 'last_name', 'middle_name', 'passport_number', 'address', 'birthplace', 'diploma_number', 'birthdate', 'role','phone'];
        $scenarios[self::SCENARIO_UPDATE] = ['password', 'password_confirm','experience', 'avatar_id', 'specialization_id', 'status', 'role', 'position_id', 'specialization_id', 'chat_id', 'avatar_id','username', 'first_name', 'last_name', 'last_name', 'middle_name', 'passport_number', 'address', 'birthplace', 'diploma_number', 'birthdate', 'role','phone'];

        return $scenarios;
    }
}