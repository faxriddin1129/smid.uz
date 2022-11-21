<?php
/**
 * @author Izzat <i.rakhmatov@list.ru>
 * @package playmobile
 */

namespace common\components;


use common\models\Smslog;

use GuzzleHttp\Client;
use yii\base\Component;

class Connection extends Component
{
    public $username;
    public $password;
    public $originator = '3700';
    public $baseUrl = 'http://91.204.239.42:8083/broker-api';

    public function sendSms($recipient, $text,$type = 2)
    {
        $client = new  Client([
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        $message_id = strtotime(date("Y-m-d H:i:s"));

        $message = array(
            'messages' => array(
                array(
                    'recipient' => $recipient,
                    'message-id' => $message_id,
                    'sms' => array(
                        'originator' => $this->originator,
                        'content' => array(
                            'text' => $text
                        )
                    )
                ),
            )
        );

        $smslog = new Smslog();
        $smslog->recipient = $recipient;
        $smslog->originator = $this->originator;
        $smslog->message_id = $message_id;
        $smslog->status = Smslog::STATUS_SENDED;
        $smslog->text = $text;
        $smslog->type = $type;
        $smslog->save();

        try {
            $client->request('POST', "{$this->baseUrl}/send", [
                'auth' => array($this->username, $this->password),
                'body' => json_encode($message)
            ]);
        } catch (\Exception $e) {
            $smslog->updateAttributes(['status' => Smslog::STATUS_NOTSENDED]);
            $smslog->save();
            echo $e->getMessage();
            return false;
        }

        return $smslog;

    }

    public function getAuthorizationToken()
    {
        return base64_encode("{$this->username}/{$this->password}");
    }

}
