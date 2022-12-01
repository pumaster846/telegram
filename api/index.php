<?php
const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";

class Bot {
    protected int    $chat_id;
    protected string $user_name;
    protected string $user_message;

    public function getJsonData() {
        $data = json_decode(file_get_contents('php://input'), true);
        return $data['callback_query'] ? $data['callback_query'] : $data['message'];
    }

    public function setJsonData() {
        $data = self::getJsonData();

        $this->chat_id      = $data['chat']['id'];
        $this->user_name    = $data['from']['first_name'];
        $this->user_message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');
    }

    public function sendRequest(string $method, array $methodOptions = []) {
        $initializer = curl_init();
        
        curl_setopt_array($initializer, array(
            CURLOPT_POST => true,
            CURLOPT_HEADER => false,
            CURLOPT_URL => API_URL . API_TOKEN . '/' . $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_POSTFIELDS => json_encode($methodOptions),
            CURLOPT_HTTPHEADER => array("Content-Type: application/json")
        ));
        
        curl_exec($initializer);
        curl_close($initializer);

        /*
            $response = curl_exec($initializer);
            return (json_decode($response, 1) ? json_decode($response, 1) : $response);
        */
    }

    public function setWebhook() {}
}

$bot = new Bot();

switch ($bot->user_message) {
    case '/start':
        $methodOptions = array(
            'chat_id' => $bot->chat_id,
            'parse_mode' => 'HTML',
            'text' => "Добрый день, <b>{$bot->user_name}</b>!" . PHP_EOL . "Я бот <b>MirBellGet</b>",
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
        );

        sendRequest('sendMessage', $methodOptions);
        sendRequest('sendMessage', ['chat_id' => $bot->chat_id, 'text' => "Смайл"]);
    break;

    case 'о нас':
        $methodOptions = [
            'chat_id' => $bot->chat_id,
            'parse_mode' => 'HTML',
            'text' => "<b>О компании</b>" . PHP_EOL . "" . PHP_EOL . "Информация о компании"
        ];
        sendRequest('sendMessage', $methodOptions);
    break;

    case 'контакты':
        $methodOptions = [
            'chat_id' => $bot->chat_id,
            'phone_number' => '8(900)000-00-00',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия'
        ];
        sendRequest('sendMessage', $methodOptions);
    break;

    default:
        $method = 'sendMessage';
        $methodOptions = [
            'chat_id' => $bot->chat_id,
            'parse_mode' => 'HTML',
            'text' => "Я не знаю такой команды"
        ];
    break;
}
