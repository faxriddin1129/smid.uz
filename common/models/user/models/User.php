<?php

namespace common\modules\user\models;

use common\components\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $password_hash
 * @property string $username
 * @property string $phone
 * @property string $auth_key
 * @property integer $status
 * @property integer $role
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $step
 *
 * @property UserDetail $userDetail
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const ROLE_ADMIN = 10;
    const ROLE_USER = 9;
    const ROLE_OPERATOR = 2;

    public $password;

    /**
     * @var
     */
    public $password_confirm;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password'], 'safe'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['username'], 'string'],
            [['status',], 'required'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            [['username'], 'unique', 'targetClass' => User::class, 'targetAttribute' => ['username' => 'username']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'status' => 'Статус',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['remember_token' => $token, 'status' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByActiveAccessToken($token, $type = null)
    {
        return static::findOne(['remember_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }


    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public static function generateToken()
    {
        return \Yii::$app->security->generateRandomString(64);
    }

    /**
     * @throws \yii\base\Exception
     */
    public function setToken()
    {
        $this->token = self::generateToken();
    }


    /**
     * @return int
     */
    public function setActivated()
    {
        return $this->updateAttributes([
            'status' => static::STATUS_ACTIVE
        ]);
    }

    /**
     * @return int
     * @throws \yii\base\Exception
     */
    public function updateToken()
    {
        return $this->updateAttributes([
            'token' => self::generateToken()
        ]);
    }


    public static function getDropdownList()
    {
        return ArrayHelper::map(static::find()->all(), 'id', 'username');
    }


    public static function getDebDrop($position_id)
    {
        return static::find()->select(['id', 'username as name'])->where(['position_id' => $position_id])->asArray()->all();
    }


    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => __('Active'),
            self::STATUS_INACTIVE => __('InActive'),
            self::STATUS_DELETED => __('Deleted'),
        ];
    }

    public function sendSmsConfirmationCode()
    {
        $confirmation = UserSmsConfirmation::find()->andWhere(['phone_number' => $this->phone])
            ->andWhere(['>', 'created_at', time() - UserSmsConfirmation::RESENT_TIME])->andWhere(['status' => UserSmsConfirmation::STATUS_SENT])->one();
        if ($confirmation) {
            throw new BadRequestHttpException(__('Вы можете отправить смс через {time} сек', ['time' => $confirmation->created_at + UserSmsConfirmation::RESENT_TIME - time()]));
        }

        UserSmsConfirmation::updateAll(['status' => UserSmsConfirmation::STATUS_DELETED], ['phone_number' => $this->phone]);

        $confirmation = new UserSmsConfirmation();
        $confirmation->setCodeLength(4);
        $confirmation->setAttributes([
            'user_id' => $this->id,
            'phone_number' => $this->phone
        ]);

        if ($confirmation->save()) {
            $confirmation->updateAttributes(['status' => UserPhoneConfirmation::STATUS_SENT]);
        }

        return $confirmation;
    }

    public static function findByPhone($phone)
    {
        if (strlen($phone) == 9) {
            $phone = "+998" . $phone;
        }

        if (strpos($phone, '+') === false) {
            $phone = "+" . $phone;
        }

        return static::find()->andWhere(['phone' => $phone])
            ->andWhere(['phone' => "$phone"])
            ->andWhere(['<>', 'status', self::STATUS_DELETED])->one();
    }

    public function getUserDetail()
    {
        return $this->hasOne(UserDetail::class, ['user_id' => 'id']);
    }



    public function fields()
    {
        return [
            'id',
            'username',
            'status',
            'token',
            'step',
            'role',
            'created_at',
            'updated_at',
        ];
    }
}
