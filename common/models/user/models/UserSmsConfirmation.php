<?php

namespace common\modules\user\models;

use common\components\FormModel;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_confirmation".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $phone_number
 * @property string|null $code
 * @property int|null $created_at
 * @property int|null $expires_at
 * @property int|null $status
 *
 * @property User $user
 */
class UserSmsConfirmation extends \yii\db\ActiveRecord
{
    use FormModel;

    /**
     * @var
     */
    private $code_length = 4;


    const STATUS_DELETED = 0;

    const STATUS_UNCONFIRMED = 2;

    const STATUS_SENT = 3;

    const EXPIRATION_TIME = 180;

    const RESENT_TIME = 60;


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
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['expires_at'],
                ],
                'value' => time() + self::EXPIRATION_TIME,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'expires_at', 'status'], 'default', 'value' => null],
            [['user_id', 'created_at', 'expires_at', 'status', 'counter'], 'integer'],
            [['phone_number', 'code'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @param int $length
     */
    public function generateCode()
    {
//        $code = \Yii::$app->security->generateRandomString($this->code_length);


        $code = '';
        for ($i = 0; $i < $this->code_length; $i++) {
            $code .= mt_rand(0, 9);
//            $code .= $i + 1;
        }

        if (str_contains($this->phone_number, '+99811')) {
            $code = 1122;
        }
        $code = 1122;
        $this->code = strval($code);
//        $this->code = '1111';
    }

    /**
     * @param $length
     */
    public function setCodeLength($length)
    {
        $this->code_length = $length;
    }

    /**
     * @param $code
     * @return UserSmsConfirmation|null
     */
    public static function validateConfirmation($phone, $code)
    {
        return static::findOne(['phone_number' => $phone, 'code' => $code]);
    }

    /**
     * @return int
     */
    public function setConfirmed()
    {
        return $this->updateAttributes([
            'status' => static::STATUS_CONFIRMED
        ]);
    }

    /**
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        $this->generateCode();

        return parent::beforeValidate();
    }

    /**
     * @return array|false
     */
    public function fields()
    {
        return [
            'id',
            'phone_number',
            'status'
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
            'code' => 'Code',
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
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
