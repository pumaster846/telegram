<?php
const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";

class Api {
    protected int $chatId;
    protected string $userName;
    protected string $userMessage;

    public function getTelegramData() {
        $data = json_decode(file_get_contents('php://input'), true);
        return $data['callback_query'] ? $data['callback_query'] : $data['message'];
    }

    public function setTelegramData() {
        $data = getTelegramData();

        $this->chatId = $data['chat']['id'];
        $this->userName = $data['chat']['first_name'];
        $this->userMessage = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');
    }
}

$api = new Api();
$data = $api->setTelegramData();

switch ($api->userMessage) {
    case '/start':
        $method = 'sendMessage';
        $methodOptions = [
            'chat_id' => $data->chatId,
            'parse_mode' => 'HTML',
            'text' =>
                "Привет, <b>{$data->userName}</b>!" . PHP_EOL .
                "Я бот <b>MirBellGet</b>." . PHP_EOL .
                "Моя версия:" . PHP_EOL .
                "Дата выпуска:",
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
    break;

    default:
        $method = 'sendMessage';
        $methodOptions = [
            'chat_id' => $data->chatId,
            'parse_mode' => 'HTML',
            'text' =>
                "<b>Не знаю</b>"
        ];
    break;
}

sendRequest($method, $methodOptions);

function sendRequest($method, $data, $headers = []) {
    $initializer = curl_init();
    
    curl_setopt_array($initializer, [
        CURLOPT_POST => true,
        CURLOPT_HEADER => false,
        CURLOPT_URL => API_URL . API_TOKEN . '/' . $method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"), $headers)
    ]);   
    
    $response = curl_exec($initializer);
    curl_close($initializer);
    
    return (json_decode($response, 1) ? json_decode($response, 1) : $response);
}
