<?php

define("API_KEY", "");

function bot($method, $datas = []) {
    $url = "https://api.telegram.org/bot". API_KEY . "/". $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    if (curl_error($ch)) {
        var_dump(curl_error($ch));
    } else {
        return json_decode(true);
    }
}

$update = json_decode(file_get_contents("php://input"));
$message = $update->message;
$cid = $message->chat->id;
$text = $message->text;
$step = file_get_contents("step/$cid.txt");
mkdir("step");

if($text == "/start") {
    bot('SendMessage', [
        'chat_id' => $cid,
        'text' => "Kerakli matnni kiriting",
        'parse_mode' => "html",
    ]);
    file_put_contents("step/$cid.txt","matn");
}

if($step == "matn") {
    bot('SendPhoto', [
        'chat_id' => $cid,
        'photo' => "https://api.qrserver.com/v1/create-qr-code/?data=$text",
    ]);
    unlink("step/$cid.txt");
}