<?php

class Api{
    protected int $chatId;
    protected string $userName;

    public function getData() {
        $data = json_decode(file_get_contents('php://input'), true);
        $data['callback_query'] ? $data['callback_query'] : $data['message'];
        $this->chatId = $data['chat']['id'];
        $this->userName = $data['chat']['first_name'];
        return $data;
    }

    public function createRequest($message) {
        switch ($message) {
            case '/start':
                $method = 'sendMessage';
                $methodOptions = [
                    'chat_id' => $this->chatId,
                    'parse_mode' => 'HTML',
                    'text' =>
                        "Привет, <b>{$this->userName}</b>!" . PHP_EOL .
                        "Я бот <b>MirBellGet</b>." . PHP_EOL .
                        "Моя версия: 1.0" . PHP_EOL .
                        "Дата выпуска: 12.03.2022",
                    'reply_markup' => [
                        'resize_keyboard' => true,
                        'keyboard' => [
                            [
                                ['text' => 'Услуги']
                            ],
                            [
                                ['text' => 'О нас'],
                                ['text' => 'Контакты']
                            ]
                        ]
                    ]
                ];
                sendRequest($method, $methodOptions);
            break;
        }
    }

    public function sendRequest($method, $jsonData, $headers = []) {
        $initializer = curl_init();
        
        curl_setopt_array($initializer, [
            CURLOPT_POST => true,
            CURLOPT_HEADER => false,
            CURLOPT_URL => API_URL . API_TOKEN . '/' . $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_POSTFIELDS => json_encode($jsonData),
            CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"), $headers)
        ]);   
        
        $response = curl_exec($initializer);
        curl_close($initializer);
        
        return (json_decode($response, 1) ? json_decode($response, 1) : $response);
    }
}

$api = new Api();

$data = $api->getData();

$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');

$request = $api->createRequest($message);
