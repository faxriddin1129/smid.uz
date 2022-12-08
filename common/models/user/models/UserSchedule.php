<?php

namespace common\modules\user\models;

use SebastianBergmann\CodeCoverage\StaticAnalysis\ExecutableLinesFindingVisitor;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_schedule".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $start_at
 * @property int|null $end_at
 * @property int|null $status
 * @property string|null $title
 * @property string|null $description
 *
 * @property User $user
 */
class  UserSchedule extends \yii\db\ActiveRecord
{

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_READY = 2;
    const STATUS_CANCELLED = 3;
    const STATUS_DONE = 4;

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'start_at', 'end_at'], 'default', 'value' => null],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['user_id', 'start_at', 'end_at', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['user_id', 'start_at', 'end_at'], 'required'],
            [['title', 'description'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'start_at' => 'Start At',
            'end_at' => 'End At',
            'status' => 'Status',
            'title' => 'Title',
            'description' => 'Description',
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
