<?php

namespace common\modules\user\models;

use common\modules\file\models\File;
use Yii;

/**
 * This is the model class for table "{{%user_document}}".
 *
 * @property int $id
 * @property int $file_id
 * @property int $user_id
 * @property int|null $type
 * @property int|null $sort
 *
 * @property File $file
 * @property User $user
 */
class UserDocument extends \yii\db\ActiveRecord
{

    const TYPE_DIPLOMA = 1;
    const TYPE_CERTIFICATE = 2;
    const TYPE_ANOTHER = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_document}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_id', 'user_id'], 'required'],
            [['file_id', 'user_id', 'type', 'sort'], 'default', 'value' => null],
            [['file_id', 'user_id', 'type', 'sort'], 'integer'],
            [['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::class, 'targetAttribute' => ['file_id' => 'id']],
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
            'file_id' => Yii::t('app', 'File ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'type' => Yii::t('app', 'Type'),
            'sort' => Yii::t('app', 'Sort'),
        ];
    }

    /**
     * Gets query for [[File]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(File::class, ['id' => 'file_id']);
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

    public function extraFields()
    {
        return [
            'file' => function(){
                return $this->file;
            }
        ];
    }
}
