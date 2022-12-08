<?php

namespace common\modules\user\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "{{%working_hour}}".
 *
 * @property int $id
 * @property int|null $day
 * @property int|null $start_at
 * @property int|null $end_at
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $type
 * @property int|null $user_id
 *
 * @property User $user
 * @property WorkedHour[] $workedHours
 */
class WorkingHour extends \yii\db\ActiveRecord
{

    const TYPE_SHIFT_NIGHT = 1;
    const TYPE_SHIFT_DAY = 2;

    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 7;

    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%working_hour}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['day', 'start_at', 'end_at', 'created_at', 'updated_at', 'type', 'user_id'], 'default', 'value' => null],
            [['day', 'start_at', 'end_at', 'type', 'user_id'], 'required'],
            [['day'], 'in', 'range' => range(self::MONDAY, self::SUNDAY), 'message' => __('The "Day" value is incorrect. Only 1 and 7 must be in the range! Days of the week')],
            [['day', 'start_at', 'end_at', 'created_at', 'updated_at', 'type', 'user_id'], 'integer'],
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
            'day' => Yii::t('app', 'Day'),
            'start_at' => Yii::t('app', 'Start At'),
            'end_at' => Yii::t('app', 'End At'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'type' => Yii::t('app', 'Type'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }

    public static function getDropDownListWeekDays()
    {
        return [
            self::MONDAY => 'Monday',
            self::TUESDAY => 'Tuesday',
            self::WEDNESDAY => 'Wednesday',
            self::THURSDAY => 'Thursday',
            self::FRIDAY => 'Friday',
            self::SATURDAY => 'Saturday',
            self::SUNDAY => 'Sunday',
        ];
    }

    public static function getDropDownListType()
    {
        return [
            self::TYPE_SHIFT_DAY => 'SHIFT DAY',
            self::TYPE_SHIFT_NIGHT => 'SHIFT NIGHT',
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

    /**
     * Gets query for [[WorkedHours]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkedHours()
    {
        return $this->hasMany(WorkedHour::class, ['working_hour_id' => 'id']);
    }

    public function extraFields()
    {
        return [
            'user'
        ];
    }
}
