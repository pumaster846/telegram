<?php
phpinfo();
var_dump(opcache_get_status());

const API_URL = "https://api.telegram.org/bot";
const API_TOKEN = "5888375092:AAGYWV58LLmmDQnvaZv_litXbTnqIg6h1ZE";

$update = json_decode(file_get_contents('php://input'), true);

var_dump($update);
