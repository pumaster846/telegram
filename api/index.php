<?php
const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";

$jsonData = json_decode(file_get_contents('php://input'), true);
$data = $jsonData['callback_query'] ? $jsonData['callback_query'] : $jsonData['message'];

$userData = array(
    'chatId' => $data['chat']['id'],
    'userName' => $data['chat']['first_name'],
    'userMessage' => mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8')
);

switch ($userData['userMessage']) {
    case '/start':
        $method = 'sendMessage';
        $methodOptions = [
            'chat_id' => $userData['chatId'],
            'parse_mode' => 'HTML',

            'text' => "Привет, <b>{$userData['userName']}</b>! Я бот <b>MirBellGet</b>. Моя версия: {$version}. Дата выпуска: {$releaseDate}",

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
        sendRequest('sendMessage', ['text' => "Привет, <b>{$userData['userName']}</b>?"]);
    break;

    case 'о нас':
        $method = 'sendMessage';
        $methodOptions = [
            'chat_id' => $userData['chatId'],
            'parse_mode' => 'HTML',
            'text' =>
                "<b>О компании</b>"
                . PHP_EOL . "" . PHP_EOL .
                "Информация о компании"
        ];
    break;

    case 'контакты':
        $method = 'sendContact';
        $methodOptions = [
            'chat_id' => $userData['chatId'],
            'phone_number' => '8(900)000-00-00',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия'
        ];
    break;

    default:
        $method = 'sendMessage';
        $methodOptions = [
            'chat_id' => $userData['chatId'],
            'parse_mode' => 'HTML',
            'text' => "Хз"
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
