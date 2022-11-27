<pre>

<?php
var_dump(opcache_get_status());

const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";

$update = json_decode(file_get_contents('php://input'), true);

function sendRequest(string $method, array $options = []) {
    $requestURL = API_URL . API_TOKEN . '/' . $method;

    if(!empty($options)) {
        $requestURL .= '?' . http_build_query($options);
    }
    // Декодинг полученных данных
    return json_decode(file_get_contents($requestURL), true);
}

$chat_id = $update['message']['chat']['id'];
sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => 'Privet']);

var_dump($update);
?>

</pre>
