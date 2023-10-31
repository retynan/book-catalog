<?php


namespace app\components;

use GuzzleHttp\Client;
use yii\base\Component;
use yii\helpers\Json;

class SmsPilotComponent extends Component
{
    public string $from;
    public string $apiUrl;
    public string $apiKey;

    public function send(string $to, string $message)
    {
        if (empty($this->from))
        {
            \Yii::error('SMS API error: property from whom is not declared');
            return false;
        }

        if (empty($this->apiUrl))
        {
            \Yii::error('SMS API error: API URL not declared');
            return false;
        }

        if (empty($this->apiKey))
        {
            \Yii::error('SMS API error: API key not declared');
            return false;
        }

        if (empty($message))
        {
            \Yii::error('SMS API error: message is empty');
            return false;
        }

        $params = [
            'to' => $to,
            'from' => $this->from,
            'send' => $message,
            'apiKey' => $this->apiKey
        ];

        $client = new Client(['content-type' => 'application/json',]);

        $response = $client->post($this->apiUrl, ['body' => Json::encode($params)]);

        if ($response->getStatusCode() != 200)
        {
            \Yii::error('SMS API error: ' . $response->getStatusCode());
            return false;
        }

        $result = Json::decode($response->getBody()->getContents());

        if (isset($result['error']))
        {
            \Yii::error('SMS API error: ' . $result['error']['description']);
            return false;
        }

        return true;
    }
}