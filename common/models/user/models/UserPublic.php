<?php

namespace common\modules\user\models;

use Yii;

/**
 * Class UserPublic
 * @package common\modules\user\models
 */
class UserPublic extends User
{
    /**
     * @return array|false
     */
    public function fields()
    {
        return [
            "id",
            "email",
            "phone",
            "first_name",
            "last_name",
            "country",
            "region",
            "image" => function ($model) {
                $image = $model->image;

                if (!$image) {
                    return null;
                }

                return @$image->getImageThumbs();
            },
            "created_at",
            "updated_at",
            "status",
        ];
    }
}
