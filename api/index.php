<pre>
<?php

const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";

$data = json_decode(file_get_contents('php://input'), true);

$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];

$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');

$chatId = $data['chat']['id'];

switch($message) {
    case '/start':
        $method = 'sendMessage';
        $options = array(
            'chat_id' => $chatId,
            'text' =>
                "Добрый день, {$userName}!".PHP_EOL.
                "Вы можете записаться к стоматологу всего за пару кликов:" . PHP_EOL .
                "1. Раскрыть меню с кнопками." . PHP_EOL .
                "2. Нажать на кнопку 'Записаться'." . PHP_EOL .
                "3. Выбрать день записи.",
            'reply_markup' => array(
                'resize_keyboard' => true,
                'keyboard' => array(
                    array(
                        ['text' => 'Записаться'],
                        ['text' => 'Мои записи']
                    )
                )
            )
        );
    break;
    case 'Записаться':

    break;
    case '/my_notes':

    break;
    default:
        $method = 'sendMessage';
        $options = array(
            'chat_id' => $chatId,
            'text' =>
                "{$userName}, я такой команды не знаю."
        );
    break;
}


sendRequest($method, $options);

function sendRequest(string $method, array $options = []) {
    $initializer = curl_init();

    $url = API_URL . API_TOKEN . '/' . $method;

    if(!empty($options)) {
        $url .= '?' . http_build_query($options);
    }

    curl_setopt_array($initializer, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10
        )
    );

    $response = curl_exec($initializer);

    curl_close($initializer);

    return json_decode($response, true);
}
