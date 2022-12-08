<?php

namespace common\modules\user\models;

use Yii;

/**
 * This is the model class for table "{{%worked_hour}}".
 *
 * @property int $id
 * @property int|null $start_at
 * @property int|null $end_at
 * @property int|null $user_id
 * @property int|null $late_time
 * @property int|null $working_hour_id
 *
 * @property User $user
 * @property WorkingHour $workingHour
 */
class WorkedHour extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%worked_hour}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start_at', 'end_at', 'user_id', 'working_hour_id'], 'required'],
            [['late_time'], 'default', 'value' => 0],
            [['start_at', 'end_at', 'user_id', 'late_time', 'working_hour_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['working_hour_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorkingHour::class, 'targetAttribute' => ['working_hour_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'start_at' => Yii::t('app', 'Start At'),
            'end_at' => Yii::t('app', 'End At'),
            'user_id' => Yii::t('app', 'User ID'),
            'late_time' => Yii::t('app', 'Late Time'),
            'working_hour_id' => Yii::t('app', 'Working Hour ID'),
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
     * Gets query for [[WorkingHour]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkingHour()
    {
        return $this->hasOne(WorkingHour::class, ['id' => 'working_hour_id']);
    }
}
