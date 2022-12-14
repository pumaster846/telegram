<?php
const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";
const PAYMENT_TOKEN = "401643678:TEST:ad1681c1-36b6-491b-9579-b8527f60a7e8";

class Bot {
    protected int $chat_id;
    protected string $user_name;
    protected string $user_message;

    public function getChatId() {
        return $this->chat_id;
    }
    public function getUserName() {
        return $this->user_name;
    }
    public function getUserMessage() {
        return $this->user_message;
    }

    protected function getJsonData() {
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
$bot->setJsonData();

switch ($bot->getUserMessage()) {
    case '/start':
        $methodOptions = array(
            'chat_id' => $bot->getChatId(),
            'parse_mode' => 'HTML',
            'text' => "???????????? ????????, <b>{$bot->getUserName()}</b>!" . PHP_EOL . "?? ?????? <b>...</b>",
            'reply_markup' => array(
            'resize_keyboard' => true,
                'keyboard' => array(
                    [
                        ['text' => '????????????']
                    ],
                    [
                        ['text' => '?? ??????'],
                        ['text' => '????????????????']
                    ]
                )
            )
        );
        $bot->sendRequest('sendMessage', $methodOptions);
        $bot->sendRequest('sendMessage', ['chat_id' => $bot->getChatId(), 'text' => hex2bin('F09FA496')]);
    break;

    case '????????????':
        $methodOptions = array(
            'chat_id' => $bot->getChatId(),
            'title' => '??????-????????????????????',
            'description' => '???????????????? ????????????',
            'payload' => 'service-001',
            'provider_token' => PAYMENT_TOKEN,
            'currency' => 'RUB',
            'prices' => array(
                [
                    'label' => 'PHP',
                    'amount' => 129900
                ]
            ),
            'photo_url' => 'https://luxe-host.ru/wp-content/uploads/d/2/3/d23bafd6830cf7f5cf220c6de9761223.jpeg',
            'photo_width' => 150,
            'photo_height' => 200,
            'need_name' => true,
            'need_phone_number' => true,
            'protect_content' => true
        );
        $bot->sendRequest('sendInvoice', $methodOptions);
    break;

    case '?? ??????':
        $methodOptions = array(
            'chat_id' => $bot->getChatId(),
            'parse_mode' => 'HTML',
            'text' => "<b>?? ????????????????</b>" . PHP_EOL . "" . PHP_EOL . "???????????????????? ?? ????????????????"
        );
        $bot->sendRequest('sendMessage', $methodOptions);
    break;

    case '????????????????':
        $methodOptions = array(
            'chat_id' => $bot->getChatId(),
            'phone_number' => '8(900)000-00-00',
            'first_name' => '??????',
            'last_name' => '??????????????'
        );
        $bot->sendRequest('sendContact', $methodOptions);
    break;

    default:
        $methodOptions = array(
            'chat_id' => $bot->getChatId(),
            'parse_mode' => 'HTML',
            'text' => "<b>{$bot->getUserName()}</b>, ?? ???? ???????? ?????????? ??????????????"
        );
        $bot->sendRequest('sendMessage', $methodOptions);
    break;
}

/*
const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";

class Bot {
    protected int $chat_id;
    protected string $user_name;
    protected string $user_message;

    public function getChatId() {
        return $this->chat_id;
    }
    public function getUserName() {
        return $this->user_name;
    }
    public function getUserMessage() {
        return $this->user_message;
    }

    protected function getJsonData() {
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

            // $response = curl_exec($initializer);
            // return (json_decode($response, 1) ? json_decode($response, 1) : $response);
    }

    public function setWebhook() {}
}

$bot = new Bot();
$bot->setJsonData();

switch ($bot->getUserMessage()) {
    case '/start':
        $methodOptions = array(
            'chat_id' => $bot->getChatId(),
            'parse_mode' => 'HTML',
            'text' => "???????????? ????????, <b>{$bot->getUserName()}</b>!" . PHP_EOL . "?? ?????? <b>...</b>",
            'reply_markup' => array(
            'resize_keyboard' => true,
                'keyboard' => array(
                    [
                        ['text' => '????????????']
                    ],
                    [
                        ['text' => '?? ??????'],
                        ['text' => '????????????????']
                    ]
                )
            )
        );
        $bot->sendRequest('sendMessage', $methodOptions);
        $bot->sendRequest('sendMessage', ['chat_id' => $bot->getChatId(), 'text' => hex2bin('F09FA496')]);
    break;

    case '?? ??????':
        $methodOptions = array(
            'chat_id' => $bot->getChatId(),
            'parse_mode' => 'HTML',
            'text' => "<b>?? ????????????????</b>" . PHP_EOL . "" . PHP_EOL . "???????????????????? ?? ????????????????"
        );
        $bot->sendRequest('sendMessage', $methodOptions);
    break;

    case '????????????????':
        $methodOptions = array(
            'chat_id' => $bot->getChatId(),
            'phone_number' => '8(900)000-00-00',
            'first_name' => '??????',
            'last_name' => '??????????????'
        );
        $bot->sendRequest('sendContact', $methodOptions);
    break;

    default:
        $methodOptions = array(
            'chat_id' => $bot->getChatId(),
            'parse_mode' => 'HTML',
            'text' => "<b>{$bot->getUserName()}</b>, ?? ???? ???????? ?????????? ??????????????"
        );
        $bot->sendRequest('sendMessage', $methodOptions);
    break;
}
*/
