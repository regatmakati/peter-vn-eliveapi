<?php
$chatUrl = DI()->config->get('app.chatUrl');
$port = DI()->config->get('app.chatPort');
$socketSecretKey = DI()->config->get('app.chatSocketSecretKey');
function sendDataToChatServer($sendData, $isJsonEncode = true)
{
    global $chatUrl, $port;
    if ($isJsonEncode) $sendData = json_encode($sendData);
    if ($ws = websocketOpen($chatUrl, $port)) {
        websocketWrite($ws, $sendData);
    }
}
