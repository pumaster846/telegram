<?php
phpinfo();


const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";

echo API_URL;
print API_URL;
var_dump(API_URL);

$update = json_decode(file_get_contents('php://input'), true); //Прочитать POST запрос весь целиком

function sendRequest(string $method, array $options = []) {
    $requestURL = API_URL . API_TOKEN . '/' . $method;

    if(!empty($options)) {
        $requestURL .= '?' . http_build_query($options);
    }

    // Декодинг полученных данных
    return json_decode(file_get_contents($requestURL), true);
}

$chat_id = $update['message']['chat']['id'];
sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => 'Privet'])

print_r($update);

var_dump(opcache_get_status());
