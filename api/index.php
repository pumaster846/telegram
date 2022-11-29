<?php

const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";

$jsonData = json_decode(file_get_contents('php://input'), true);

$jsonData = $jsonData['callback_query'] ? $jsonData['callback_query'] : $jsonData['message'];

$message = mb_strtolower(($jsonData['text'] ? $jsonData['text'] : $jsonData['data']),'utf-8');

$chatId = $jsonData['chat']['id'];
$messageId = $jsonData['message_id'];
$userName = $jsonData['chat']['first_name'];

switch ($message) {
    case '/start':
        $method = 'sendMessage';
        $methodOptions = [
            'chat_id' => $chatId,
            'parse_mode' => 'HTML',
            'text'   =>
                "Привет, <b>{$userName}</b>!" . PHP_EOL .
                "Я pet проект Мирсала, меня зовут MirBellGet и я умею:" . PHP_EOL .
                "<b>1.</b> Lorem ipsum dolor sit" . PHP_EOL .
                "<b>2.</b> Lorem ipsum dolor sit" . PHP_EOL .
                "<b>3.</b> Lorem ipsum dolor sit" . PHP_EOL .
                "<b>4.</b> Lorem ipsum dolor sit",
            'reply_markup' => [
                'resize_keyboard' => true,
                'keyboard' => [
                    [
                        ['text' => 'Кнопка1'],
                        ['text' => 'Кнопка2']
                    ],
                    [
                        ['text' => 'Кнопка3'],
                        ['text' => 'Кнопка4'],
                        ['text' => 'Кнопка5']
                    ],
                    [
                        ['text' => 'Кнопка6']
                    ]
                ]
            ]
        ];

    default:
        $method = 'sendMessage';
        $methodOptions = [
            'chat_id' => $chatId,
            'parse_mode' => 'HTML',
            'text' => "<b>{$userName}</b>, я не знаю такой команды"
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
