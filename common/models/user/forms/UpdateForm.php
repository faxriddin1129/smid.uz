<?php

namespace common\modules\user\forms;

use common\modules\country\models\Country;
use common\modules\country\models\Region;
use Yii;
use yii\base\Model;
use common\modules\user\models\User;
use yii\web\UploadedFile;

/**
 * Update form
 */
class UpdateForm extends Model
{

    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $first_name;
    public $last_name;
    public $phone;
    public $password;
    public $password_confirm;
    public $country_id;
    public $region_id;
    /**
     * @var
     */
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'first_name', 'last_name', 'password_confirm', 'phone'], 'required'],
            ['phone', 'safe'],
            [['first_name', 'last_name'], 'string'],
            [['password'], 'string', 'min' => 6],
            ['password', 'compare', 'compareAttribute' => 'password_confirm'],
            [['imageFile'],'safe'],
            [['country_id'], 'exist', 'targetClass' => Country::class, 'targetAttribute' => ['country_id' => 'id']],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => Region::class, 'targetAttribute' => ['region_id' => 'id']]
        ];
    }

    /**
     * @return array|bool|User|null
     */
    public function save()
    {
        if (!$this->validate()) {
            return  false;
        }

        /**
         * @var $user User
         */
        $user = $this->getUser();
        $user->setAttributes([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'country_id' => $this->country_id,
            'region_id' => $this->region_id,
            'phone' => $this->phone,
            'affiliate_key' => Yii::$app->security->generateRandomString(16)
        ]);

        $image_file = \yii\web\UploadedFile::getInstanceByName('imageFile');
        if ($image_file instanceof UploadedFile) {
            $file = new \common\modules\file\models\File();
            $file->file_data = $image_file;
            $file->user_id = \Yii::$app->user->id;
            $file->save();
            $user->image_id = $file->id;
        }
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->setToken();

        $user->save();

        return $user;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findIdentity(Yii::$app->user->id);
        }

        return $this->_user;
    }
}
