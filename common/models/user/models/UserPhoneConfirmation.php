<?php

namespace common\modules\user\models;

use Yii;

/**
 * This is the model class for table "user_confirmation".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $phone_number
 * @property string|null $email
 * @property string|null $code
 * @property string|null $type
 * @property int|null $created_at
 * @property int|null $expires_at
 * @property int|null $status
 *
 * @property User $user
 */
class UserPhoneConfirmation extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_UNCONFIRMED = 2;
    const STATUS_SENT = 3;
    const EXPIRATION_TIME = 3600 * 24 * 7;
    const STATUS_CONFIRMED = 1;

    const TYPE_PHONE = 2;
    const TYPE_EMAIL = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_phone_confirmation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'expires_at', 'status'], 'default', 'value' => null],
            [['user_id', 'created_at', 'expires_at','type', 'status'], 'integer'],
            [['phone_number', 'email', 'code'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'phone_number' => 'Phone Number',
            'email' => 'Email',
            'code' => 'Code',
            'type' => 'Type',
            'created_at' => 'Created At',
            'expires_at' => 'Expires At',
            'status' => 'Status',
        ];
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
}
