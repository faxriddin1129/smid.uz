<?php

namespace common\modules\user\models;

use common\modules\file\models\File;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "visa_request".
 *
 * @property int $id
 * @property int|null $file_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $phone
 * @property string|null $email
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property File $file
 */
class VisaRequest extends \yii\db\ActiveRecord
{
    /**
     *
     */
    const STATUS_DELETED = 0;
    /**
     *
     */
    const STATUS_INACTIVE = 2;
    /**
     *
     */
    const STATUS_ACTIVE = 1;
    /**
     *
     */
    const STATUS_MODERATE = 3;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visa_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['file_id', 'created_at', 'updated_at'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_MODERATE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED, self::STATUS_MODERATE]],
            [['first_name', 'last_name', 'phone', 'email'], 'string', 'max' => 255],
            [['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::className(), 'targetAttribute' => ['file_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_id' => 'File ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'STATUS',
        ];
    }

    /**
     * Gets query for [[File]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(File::className(), ['id' => 'file_id']);
    }

    public static function getModeratesCount()
    {
        if (Yii::$app->user->isGuest)
            return 0;

        return static::find()->andWhere(['status' => self::STATUS_MODERATE])->count();
    }
}
