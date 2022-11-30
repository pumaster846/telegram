<?php
const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";

class Bot {
    public function commandGetData() {
        $data = json_decode(file_get_contents('php://input'), true);
        return $data['callback_query'] ? $data['callback_query'] : $data['message'];
    }
}


$bot = new Bot();
$data = $bot->commandGetData();
$chat_id      = $data['chat']['id'];
$user_name    = $data['chat']['first_name'];
$user_message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');

switch ($user_message) {
    case '/start':
        $method = 'sendMessage';
        $methodOptions = [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',

            'text' => "Привет, <b>{$user_name}</b>! Я бот <b>MirBellGet</b>. Моя версия: {$version}. Дата выпуска: {$releaseDate}",

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
        
    sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => "Привет?"]);
    break;

    case 'о нас':
        $method = 'sendMessage';
        $methodOptions = [
            'chat_id' => $chat_id,
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
            'chat_id' => $chat_id,
            'phone_number' => '8(900)000-00-00',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия'
        ];
    break;

    default:
        $method = 'sendMessage';
        $methodOptions = [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => "Хз"
        ];
    break;
}
sendRequest($method, $methodOptions);

function sendRequest($method, $jsonData) {
    $initializer = curl_init();
    
    curl_setopt_array($initializer, [
        CURLOPT_POST => true,
        CURLOPT_URL => API_URL . API_TOKEN . '/' . $method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_POSTFIELDS => json_encode($jsonData)
    ]);   
    
    $response = curl_exec($initializer);
    curl_close($initializer);
    
    return (json_decode($response, 1) ? json_decode($response, 1) : $response);
}
