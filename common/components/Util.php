<?php

namespace common\components;

use common\modules\language\models\Language;
use common\modules\transaction\models\Transaction;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class Util
{
    const GENDER_MALE = 0;

    const GENDER_FEMALE = 1;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 0;

    const DIRECT_LAB = 1;

    const DIRECT_DOC = 0;

    const DIRECT_PROCEDURE = 2;

    const DIRECT_ANALYZE = 3;

    const DAY = 86399;


    public static function getExistsLanguage($attr)
    {

        if (!$attr) {
            return null;
        }


        if (strpos(\Yii::$app->request->absoluteUrl, '/admin/')) {

//            $languages = ArrayHelper::;
//
//            return $languages;

            return $attr;
        }

        if (is_object($attr)) {
            $attr = (array)$attr;
        }


        if (!is_array($attr)) {
            return $attr;
        }

        if (array_key_exists(\Yii::$app->language, $attr) && !empty($attr[\Yii::$app->language])) {
            return $attr[\Yii::$app->language];
        }

        if (array_key_exists("ru", $attr) && !empty($attr["ru"])) {
            return $attr["ru"];
        }

        if (array_key_exists("uz", $attr) && !empty($attr["uz"])) {
            return $attr["uz"];
        }

        return $attr["en"];
    }

    public static function CutText($attr, $length = 50)
    {
        if (strlen($attr) > $length) {
            return mb_substr(strip_tags($attr), 0, $length, "utf-8") . "...";
        } else {
            return strip_tags($attr);
        }

    }

    public static function getActiveLanguages($except = null)
    {
        if ($except) {
            return Language::find()->active()->andWhere(['<>', 'code', $except])->all();
        }
        return Language::find()->active()->all();
    }

    public static function is_active($url = null)
    {
        if ($url == null) {
            return false;
        }
        $pathInfo = Yii::$app->request->getPathInfo();
        $urlarr = self::getUrlFormat($url);
        $patharr = self::getUrlFormat($pathInfo);
        $is_active = ($urlarr == $patharr);
        return $is_active;
    }

    public static function getStatus()
    {
        return [
            self::STATUS_ACTIVE => 'Активный',
            self::STATUS_INACTIVE => 'Неактивный'
        ];
    }

    public static function getUrlFormat($url = null)
    {
        if ($url == null) {
            return false;
        }
        if (array_key_exists("path", parse_url($url)))
            $url = parse_url($url)['path'];
        else $url = "";
        $url = rawurldecode($url);
        $url = mb_strtolower($url);
        $pattern = '~([a-zA-Z0-9_а-яА-Я-\.\,\s]+)~u';
        preg_match_all($pattern, $url, $urls);
        $url = $urls[1];
        return $url;
    }

    public static function getDirect()
    {
        return [
            self::DIRECT_DOC => "ДОКТОР",
            self::DIRECT_LAB => "ЛАБОРАТОРИЯ",
            self::DIRECT_ANALYZE => "АНАЛИЗ",
        ];
    }

    public static function getCashAmount($payment_method = null)
    {
        $formatter = Yii::$app->formatter;
        $formatter->timeZone = 'Asia/Tashkent';

        if (Yii::$app->request->get('date') == 'all') {
            if ($payment_method == null) {
                return Transaction::find()
                    ->sum('transaction.amount');
            }

            return Transaction::find()
                ->andWhere(['payment_method' => $payment_method])
                ->sum('transaction.amount');
        }

        if ($payment_method == null) {
            return Transaction::find()
                ->andWhere(['between', 'transaction.created_at', $formatter->asTimestamp(date("d-m-Y") . "00:00:00"), $formatter->asTimestamp(date("d-m-Y") . "23:59:59")])
                ->sum('transaction.amount');
        }

        return Transaction::find()
            ->andWhere(['payment_method' => $payment_method])
            ->andWhere(['between', 'transaction.created_at', $formatter->asTimestamp(date("d-m-Y") . "00:00:00"), $formatter->asTimestamp(date("d-m-Y") . "23:59:59")])
            ->sum('transaction.amount');
    }

    public static function getAllLanguages($param)
    {
        $item = [];
        $languages = Language::find()->active()->all();
        foreach ($languages as $index => $language) {
            $item[$language['code']] = $param;
        }

        return $item;
    }

    public static function getRandomColor()
    {
        return sprintf('#%06X', mt_rand(0xAAAAAA, 0xFFFFFF));
    }

    public static function VarDump($data)
    {
        VarDumper::dump($data, 100, true);
    }

    public static function transliterate($textcyr = null, $textlat = null)
    {
        $cyr = array(
            'ж', 'ч', 'щ', 'ш', 'ю', 'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ъ', 'ь', 'я', 'ғ', 'қ', 'ў','ы', 'ҳ', 'ё', 'Э',
            'Ж', 'Ч', 'Щ', 'Ш', 'Ю', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ъ', 'Ь', 'Я', 'Ғ', 'Қ', 'Ў', 'ы', 'Ҳ', 'Ё', 'Э');
        $lat = array(
            'j', 'ch', 'sh', 'sh', 'yu', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', '', '', 'ya','g‘', 'q', 'o‘', 'i', 'x', 'yo', 'e',
            'J', 'Ch', 'Sh', 'Sh', 'Yu', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', '', '', 'Ya', 'G‘', 'Q', 'O‘', 'i', 'X', 'Yo', 'E');
        if ($textcyr) return str_replace($cyr, $lat, $textcyr);
        else if ($textlat) return str_replace($lat, $cyr, $textlat);
        else return null;
    }


}