<?php
/**
 * @author Izzat <i.rakhmatov@list.ru>
 * @package uzbekkonsert
 */

namespace common\components;


class View extends \yii\web\View {

    public $description;

    public function getAssetUrl($bundleClass, $assetName) {
        return $this->assetManager->getBundle($bundleClass)->baseUrl . "/" . $assetName;
    }

}