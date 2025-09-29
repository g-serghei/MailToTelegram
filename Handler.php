#!/usr/bin/php -q
<?php

require_once __DIR__.'/vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    var_dump($_ENV['TELEGRAM_BOT_TOKEN']);
    die();

    $rawEmail = file_get_contents('php://stdin');
    $message = ZBateson\MailMimeParser\Message::from($rawEmail, true);

    $to = $message->getHeaderValue('to');
    $from = $message->getHeaderValue('from');
    $subject = $message->getHeaderValue('subject');
    $text = $message->getTextContent();
    $html = $message->getHtmlContent();
    $attachmentsCount = $message->getAttachmentCount();

    file_put_contents(__DIR__.'/out.txt', print_r([
        'rawHeaderTo' => $to,
        'rawHeaderFrom' => $from,
        'subject' => $subject,
        'text' => $text,
        'html' => $html,
    ], true));


    $message = "📧 *New Email Received*\n";
    $message .= "*From:* $from\n";
    $message .= "*To:* $to\n";
    $message .= "*Subject:* $subject\n";
    $message .= "*Body:*\n";
    $message .= $text ? $text : ($html ? strip_tags($html) : "[No text body]") . "\n";
    $message .= "*Attachments:* " . $attachmentsCount . "\n";

    // Telegram API request
    $botToken = getenv('TELEGRAM_BOT_TOKEN');
    $chatId = getenv('TELEGRAM_CHAT_ID');

    $sendTextUrl = "https://api.telegram.org/bot$botToken/sendMessage";
    file_get_contents($sendTextUrl . "?chat_id=$chatId&parse_mode=Markdown&text=" . urlencode($message));
} catch (Throwable $t) {
    file_put_contents(__DIR__.'/out.txt', print_r([
        'error' => $t->getMessage(),
    ], true));
}

