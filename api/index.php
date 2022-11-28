<pre>
<?php

const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";

$jsonData = json_decode(file_get_contents('php://input'), true);

$jsonData = $jsonData['callback_query'] ? $jsonData['callback_query'] : $jsonData['message'];


$message = mb_strtolower(($jsonData['text'] ? $jsonData['text'] : $jsonData['data']),'utf-8');

switch ($message) {
    case 'текст':
        $method = 'sendMessage';
        $options = [
            'text' => 'privet'
        ];
        break;

    default:
        $method = 'sendMessage';
        $options = [
            'text' => 'Я не знаю такой команды'
        ];
        break;
}

$options['chat_id'] = $jsonData['chat']['id'];
sendRequest($method, $options);

function sendRequest($method, $options = []) {

    $url = API_URL . API_TOKEN . '/' . $method;

    if (!empty($options)) {
        $url .= '?' . http_build_query($options);
    }

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
