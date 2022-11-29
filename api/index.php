<?php
const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";

$jsonData = json_decode(file_get_contents('php://input'), true);
$jsonData = $jsonData['callback_query'] ? $jsonData['callback_query'] : $jsonData['message'];

$chatId = $jsonData['chat']['id'];
$userName = $jsonData['chat']['first_name'];
$userMessage = mb_strtolower(($jsonData['text'] ? $jsonData['text'] : $jsonData['data']),'utf-8');

switch ($userMessage) {
    case '/start':
        $method = 'sendMessage';
        $methodOptions = array(
            'chat_id' => $chatId,
            'parse_mode' => 'HTML',
            'text' =>
                "Привет, <b>{$userName}</b>!" . PHP_EOL .
                "Я бот <b>MirBellGet</b>." . PHP_EOL .
                "Моя версия: {$version}" . PHP_EOL .
                "Дата выпуска: {$releaseDate}",
            'reply_markup' => array(
                [
                    ['text' => 'Услуги']
                ],
                [
                    ['text' => 'О нас'],
                    ['text' => 'Контакты']
                ]
            )
        );

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
