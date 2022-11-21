<?php

namespace common\components;

class ActiveRecord extends \yii\db\ActiveRecord
{
    public function delete()
    {
        $this->updateAttributes(['status' => $this::STATUS_DELETED]);
    }
}
