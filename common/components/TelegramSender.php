<?php

namespace common\components;

class TelegramSender extends \yii\base\Model
{
    public static function send($method, $data = [], $headers = [])
    {
        $params = [
            'token' => getenv('BOT_TOKEN'), #token
            'bot_url' => 'https://api.telegram.org/bot',
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $params['bot_url'] . $params['token'] . '/' . $method,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array_merge(["Content-Type: application/json"], $headers),
        ]);

        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result, 1) ?: $result;
    }
}