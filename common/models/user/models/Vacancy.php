<?php

namespace common\modules\user\models;

use common\components\ActiveRecord;
use Yii;

/**
 * This is the model class for table "{{%vacancy}}".
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $type
 * @property int|null $place_count
 * @property int|null $position_id
 * @property string|null $salary
 * @property int|null $percent
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property User $createdBy
 * @property Position $position
 * @property User $updatedBy
 */
class Vacancy extends ActiveRecord
{

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 9;
    const STATUS_DELETED = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%vacancy}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'place_count', 'position_id', 'percent', 'status','salary','title'], 'required'],
            [['title'], 'safe'],
            [['type', 'place_count', 'position_id', 'percent', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['type', 'place_count', 'position_id', 'percent', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['salary'], 'string', 'max' => 255],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => Position::class, 'targetAttribute' => ['position_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'type' => Yii::t('app', 'Type'),
            'place_count' => Yii::t('app', 'Place Count'),
            'position_id' => Yii::t('app', 'Position ID'),
            'salary' => Yii::t('app', 'Salary'),
            'percent' => Yii::t('app', 'Percent'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Position]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosition()
    {
        return $this->hasOne(Position::class, ['id' => 'position_id']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    public function fields()
    {
        return [
            'id',
            'title',
            'type',
            'place_count',
            'position_id',
            'salary',
            'percent',
            'status',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
        ];
    }
}
