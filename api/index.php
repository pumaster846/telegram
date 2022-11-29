<?php
const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";

$data = json_decode(file_get_contents('php://input'), true);
$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];
$userMessage = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');
$chatId = $data['chat']['id'];
$userName = $data['chat']['first_name'];

switch ($userMessage) {
    case '/start':
        $method = 'sendMessage';
        $methodOptions = [
            'chat_id' => $chatId,
            'parse_mode' => 'HTML',
            'text' =>
                "Привет, <b>{$userName}</b>!" . PHP_EOL .
                "Я бот <b>MirBellGet</b>." . PHP_EOL .
                "Моя версия: {$version}" . PHP_EOL .
                "Дата выпуска: {$releaseDate}",
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
            'chat_id' => $chatId,
            'parse_mode' => 'HTML',
            'text' =>
                "<b>Наши контакты:</b>"
                . PHP_EOL . "" . PHP_EOL .
                "Номер телефона: {$phoneNumber}" . PHP_EOL .
                "Почта: {$emailAdress}"

        ];
    break;
}

sendRequest($method, $methodOptions);

function sendRequest($method, $jsonData, $headers = []) {
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
