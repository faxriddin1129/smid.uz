<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $bet_user_id
 * @property int|null $mow_user_id
 * @property int|null $type
 * @property int|null $count
 * @property int|null $status
 * @property int|null $packaging_user_id
 * @property int|null $bet_date
 * @property int|null $mow_date_integer
 * @property int|null $packaging_date
 * @property string|null $month
 *
 * @property User $betUser
 * @property User $createdBy
 * @property User $mowUser
 * @property User $packagingUser
 * @property Product $product
 * @property User $updatedBy
 */
class Order extends \yii\db\ActiveRecord
{

    const TYPE_SEWING = 1;
    const TYPE_WEAVING = 2;

    const STATUS_BET = 1;
    const STATUS_MOV = 2;
    const STATUS_PACKAGING = 3;

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
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'type', 'count'], 'required'],
            [['product_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'bet_user_id', 'mow_user_id', 'packaging_user_id', 'bet_date', 'mow_date_integer', 'packaging_date', 'type'], 'integer'],
            [['month'], 'string', 'max' => 55],
            [['bet_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['bet_user_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['mow_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['mow_user_id' => 'id']],
            [['packaging_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['packaging_user_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'bet_user_id' => 'Bet User ID',
            'mow_user_id' => 'Mow User ID',
            'packaging_user_id' => 'Packaging User ID',
            'bet_date' => 'Bet Date',
            'mow_date_integer' => 'Mow Date Integer',
            'packaging_date' => 'Packaging Date',
            'month' => 'Month',
        ];
    }

    /**
     * Gets query for [[BetUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBetUser()
    {
        return $this->hasOne(User::class, ['id' => 'bet_user_id']);
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
     * Gets query for [[MowUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMowUser()
    {
        return $this->hasOne(User::class, ['id' => 'mow_user_id']);
    }

    /**
     * Gets query for [[PackagingUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPackagingUser()
    {
        return $this->hasOne(User::class, ['id' => 'packaging_user_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
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
}
