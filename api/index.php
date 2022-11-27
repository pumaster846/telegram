<pre>
<?php

const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";

$jsonData = json_decode(file_get_contents('php://input'), true);

$message = mb_strtolower($jsonData['message']['text'], 'utf-8');

function sendRequest(string $method, array $options = []) {
    $initializer = curl_init();

    $url = API_URL . API_TOKEN . '/' . $method;
    //https://api.telegram.org/bot5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE/getUpdates?offset=607341323

    if(!empty($options)) {
        $url .= '?' . http_build_query($options);
    }

    $setoptsArray = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10
    );

    curl_setopt_array($initializer, $setoptsArray);

    $response = curl_exec($initializer);

    curl_close($initializer);

    return json_decode($response, true);
}

switch ($message)
{
    case 'текст':
        $method = 'sendMessage';
        $send_data = 'Вот мой ответ';
    break;

    default:
        $method = 'sendMessage';
        $send_data = $message;
}

sendRequest($method, ['text' => $send_data]);

?>
</pre>
