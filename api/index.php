<?php
const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";

$jsonData = json_decode(file_get_contents('php://input'), true);

$jsonData = $jsonData['callback_query'] ? $jsonData['callback_query'] : $jsonData['message'];

$message = mb_strtolower(($jsonData['text'] ? $jsonData['text'] : $jsonData['data']),'utf-8');
$chatId = $jsonData['chat']['id'];
$userName = $jsonData['chat']['first_name'];

switch ($message) {
    case '/start':
        $method = 'sendMessage';
        $methodOptions = [
            'chat_id' => $chatId,
            'parse_mode' => 'HTML',
            'text'   =>
                "Добрый день, <b>{$userName}</b>!" . PHP_EOL .
                "Чтобы записаться к нашему стоматологу необходимо:" . PHP_EOL .
                "1. Кликнуть на кнопку - Записаться." . PHP_EOL .
                "2. Выбрать удобную дату приёма",
            'reply_markup' => [
                'resize_keyboard' => true,
                'keyboard' => [
                    [
                        ['text' => 'Записаться'],
                        ['text' => 'Мои записи'],
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
            'text' => "<b>{$userName}</b>, я не знаю такой команды"
        ];
    break;
}

sendRequest($method, $methodOptions);

function sendRequest($method, $jsonData, $headers = [])
{
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
