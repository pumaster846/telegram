<?php
const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";

class Bot {
    protected $chat_id;
    protected $user_name;
    protected $user_message;

    public function commandSetData() {
        $data = json_decode(file_get_contents('php://input'), true);
        $data = $data['callback_query'] ? $data['callback_query'] : $data['message'];
        $this->chat_id = $data['chat']['id'];
        $this->user_name = $data['chat']['first_name'];
        $this->user_message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');
    }
    public function getChatId() {
        return $this->chat_id;
    }
    public function getUserName() {
        return $this->user_name;
    }
    public function getUserMessage() {
        return $this->user_message;
    }
    public function commandSendRequest(string $method, array $methodOptions = []) {
        $initializer = curl_init();
        
        curl_setopt_array($initializer, [
            CURLOPT_POST => true,
            CURLOPT_HEADER => false,
            CURLOPT_URL => API_URL . API_TOKEN . '/' . $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_POSTFIELDS => json_encode($methodOptions),
            CURLOPT_HTTPHEADER => array("Content-Type: application/json")
        ]);
        
        $response = curl_exec($initializer);
        curl_close($initializer);
        
        return (json_decode($response, 1) ? json_decode($response, 1) : $response);
    }
    public function commandBuildRequest() {
        switch ($this->user_message) {
            case '/start':
                $method = 'sendMessage';
                $methodOptions = [
                    'chat_id' => $this->chat_id,
                    'parse_mode' => 'HTML',
        
                    'text' => "Привет, <b>{$this->user_name}</b>! Я бот <b>MirBellGet</b>. Моя версия: {$version}. Дата выпуска: {$releaseDate}",
        
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
                
                $this->commandSendRequest('sendMessage', ['chat_id' => $this->chat_id, 'text' => "Привет?"]);
            break;
        
            case 'о нас':
                $method = 'sendMessage';
                $methodOptions = [
                    'chat_id' => $this->chat_id,
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
                    'chat_id' => $this->chat_id,
                    'phone_number' => '8(900)000-00-00',
                    'first_name' => 'Имя',
                    'last_name' => 'Фамилия'
                ];
            break;
        
            default:
                $method = 'sendMessage';
                $methodOptions = [
                    'chat_id' => $this->chat_id,
                    'parse_mode' => 'HTML',
                    'text' => "Хз"
                ];
            break;
            $this->commandSendRequest($method, $methodOptions);
        }
    }
}

$bot  = new Bot();
$bot->commandSetData();
$bot->commandBuildRequest();

// $chat_id      = $data['chat']['id'];
// $user_name    = $data['chat']['first_name'];
// $user_message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');
