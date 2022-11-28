<pre>
<?php

const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";

$jsonData = json_decode(file_get_contents('php://input'), true);

$jsonData = $jsonData['callback_query'] ? $jsonData['callback_query'] : $jsonData['message'];
$message = mb_strtolower(($jsonData['text'] ? $jsonData['text'] : $jsonData['data']),'utf-8');
$userName = $jsonData['chat']['first_name'];

switch ($message) {
    case '/start':
        $method = 'sendMessage';
        $options = [
            'parse_mode' => 'HTML',
            'text' => "Добрый день, <b>{$userName}</b>!" . PHP_EOL .
                      "Чтобы записаться к нашему стоматологу необходимо:" . PHP_EOL .
                      "1. Кликнуть на кнопку - Записаться." . PHP_EOL .
                      "2. Выбрать удобную дату приёма",
            'resize_keyboard' => true,
             'keyboard' => [
              [
                        ['text' => 'Записаться'],
                        ['text' => 'Мои записи'],
              ],
            ]
        ];
        break;

    default:
        $method = 'sendMessage';
        $options = [
            'parse_mode' => 'HTML',
            'text' => "<b>{$userName}</b>, я не знаю такой команды"
        ];
        break;
}

$options['chat_id'] = $jsonData['chat']['id'];
sendRequest($method, $options);

function sendRequest(string $method, array $options = []) {
    $url = API_URL . API_TOKEN . '/' . $method . '?' . http_build_query($options);

    $initializer = curl_init();

    curl_setopt_array($initializer, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10
    ));

    $response = curl_exec($initializer);

    curl_close($initializer);

    return json_decode($response, true);
}

?>
</pre>
