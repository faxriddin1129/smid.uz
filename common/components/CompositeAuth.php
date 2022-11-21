<?php

namespace common\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\filters\auth\AuthInterface;

class CompositeAuth extends \yii\filters\auth\CompositeAuth
{
    public function authenticate($user, $request, $response)
    {
        foreach ($this->authMethods as $i => $auth) {
            if (!$auth instanceof AuthInterface) {
                $this->authMethods[$i] = $auth = Yii::createObject($auth);
                if (!$auth instanceof AuthInterface) {
                    throw new InvalidConfigException(get_class($auth) . ' must implement yii\filters\auth\AuthInterface');
                }
            }

//            if (isset($this->owner->action) && $auth->isActive($this->owner->action)) {
                $identity = $auth->authenticate($user, $request, $response);
                if ($identity !== null) {
                    return $identity;
                }
//            }
        }

        return null;
    }
}