<?php
const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";

$jsonData = $jsonData['callback_query'] ? $jsonData['callback_query'] : $jsonData['message'];

$message = mb_strtolower(($jsonData['text'] ? $jsonData['text'] : $jsonData['data']),'utf-8');
$chatId = $jsonData['chat']['id'];

# Обрабатываем сообщение
switch ($message)
{
    case '/start':
        $method = 'sendMessage';
        $methodSettings = [
            'chat_id' => $chatId,
            'parse_mode' => 'HTML',
            'text' =>
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
        $methodSettings = [
            'chat_id' => $chatId,
            'parse_mode' => 'HTML',
            'text' => "<b>{$userName}</b>, я не знаю такой команды"
        ];
        break;
}

sendRequest($method, $methodSettings);

function sendRequest($method, $data, $headers = [])
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => API_URL . API_TOKEN . '/' . $method,
        CURLOPT_CONNECTTIMEOUT => 10
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"), $headers)
    ]);   
    
    $result = curl_exec($curl);
    curl_close($curl);
    return (json_decode($result, 1) ? json_decode($result, 1) : $result);
}
