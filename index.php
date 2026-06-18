<?php

$TOKEN = "8811592211:AAHHaFh_BWGgJQRSgjNz7L4QXmkgE-vQdYE";

$update = json_decode(file_get_contents("php://input"), true);

if (!$update) {
    echo "Bot is running";
    exit;
}

$chat_id = $update["message"]["chat"]["id"] ?? null;
$text = trim($update["message"]["text"] ?? "");

function sendMessage($chat_id, $text, $keyboard = null)
{
    global $TOKEN;

    $data = [
        "chat_id" => $chat_id,
        "text" => $text
    ];

    if ($keyboard) {
        $data["reply_markup"] = json_encode($keyboard);
    }

    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);

    file_get_contents(
        "https://api.telegram.org/bot{$TOKEN}/sendMessage",
        false,
        $context
    );
}

/*
|--------------------------------------------------------------------------
| START COMMAND
|--------------------------------------------------------------------------
*/

if ($text == "/start") {

    $keyboard = [
        "keyboard" => [
            [
                ["text" => "🎮 Play Bingo"]
            ],
            [
                ["text" => "💰 Deposit"],
                ["text" => "🏧 Withdraw"]
            ],
            [
                ["text" => "👤 Profile"],
                ["text" => "❓ Help"]
            ]
        ],
        "resize_keyboard" => true
    ];

    sendMessage(
        $chat_id,
        "🎉 Welcome to Bingo Bot!\n\nChoose an option below:",
        $keyboard
    );
}

/*
|--------------------------------------------------------------------------
| PLAY BINGO
|--------------------------------------------------------------------------
*/

if ($text == "🎮 Play Bingo") {

    $miniAppUrl = "https://bad.gamer.gd";

    $keyboard = [
        "inline_keyboard" => [
            [
                [
                    "text" => "▶️ Open Bingo",
                    "web_app" => [
                        "url" => $miniAppUrl
                    ]
                ]
            ]
        ]
    ];

    sendMessage(
        $chat_id,
        "Press the button below to open Bingo:",
        $keyboard
    );
}

/*
|--------------------------------------------------------------------------
| DEPOSIT
|--------------------------------------------------------------------------
*/

if ($text == "💰 Deposit") {

    sendMessage(
        $chat_id,
        "💰 Deposit Instructions\n\nSend payment to:\n09XXXXXXXX\n\nAfter payment send the screenshot to the admin."
    );
}

/*
|--------------------------------------------------------------------------
| WITHDRAW
|--------------------------------------------------------------------------
*/

if ($text == "🏧 Withdraw") {

    sendMessage(
        $chat_id,
        "🏧 Withdrawal\n\nSend:\nAmount\nTelebirr Number\n\nExample:\n50 ETB\n09XXXXXXXX"
    );
}

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

if ($text == "👤 Profile") {

    sendMessage(
        $chat_id,
        "👤 Profile\n\nBalance: 0 ETB\nGames Played: 0\nWins: 0"
    );
}

/*
|--------------------------------------------------------------------------
| HELP
|--------------------------------------------------------------------------
*/

if ($text == "❓ Help") {

    sendMessage(
        $chat_id,
        "❓ Help\n\nComplete your bingo card before everyone else to win."
    );
}

?>