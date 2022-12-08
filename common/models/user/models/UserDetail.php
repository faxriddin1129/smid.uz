<?php

namespace common\modules\user\models;

use common\modules\file\models\File;
use Yii;

/**
 * This is the model class for table "user_detail".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $middle_name
 * @property int|null $birthdate
 * @property string|null $passport_number
 * @property string|null $address
 * @property string|null $birthplace
 * @property string|null $chat_id
 * @property string|null $diploma_number
 * @property int|null $experience
 * @property int|null $avatar_id
 *
 * @property File $avatar
 * @property User $user
 */
class UserDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['birthdate', 'experience', 'avatar_id'], 'default', 'value' => null],
            [['user_id', 'birthdate', 'experience', 'avatar_id'], 'integer'],
            [['first_name', 'last_name', 'middle_name', 'passport_number', 'chat_id'], 'string', 'max' => 45],
            [['diploma_number', 'address', 'birthplace'], 'string', 'max' => 255],
            [['avatar_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::class, 'targetAttribute' => ['avatar_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'first_name' => Yii::t('app', 'Firs Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'middle_name' => Yii::t('app', 'Middle Name'),
            'birthdate' => Yii::t('app', 'Birthdate'),
            'passport_number' => Yii::t('app', 'Passport Number'),
            'address' => Yii::t('app', 'Address'),
            'birthplace' => Yii::t('app', 'Birthplace'),
            'chat_id' => Yii::t('app', 'Chat ID'),
            'diploma_number' => Yii::t('app', 'Diploma Number'),
            'experience' => Yii::t('app', 'Experience'),
            'avatar_id' => Yii::t('app', 'Avatar ID'),
        ];
    }

    /**
     * Gets query for [[Avatar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvatar()
    {
        return $this->hasOne(File::class, ['id' => 'avatar_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function fields()
    {
        return [
            'first_name' ,
            'last_name' ,
            'middle_name' ,
            'birthdate' ,
            'passport_number' ,
            'address' ,
            'birthplace' ,
            'chat_id' ,
            'diploma_number' ,
            'experience' ,
            'avatar_id' ,
        ];
    }

    public function extraFields()
    {
        return [
            'avatar' => function () {
                return $this->avatar;
            }
        ];
    }
}
