#!/usr/bin/php -q
<?php

require_once __DIR__.'/vendor/autoload.php';

try {
    $rawEmail = file_get_contents('php://stdin');
    $message = ZBateson\MailMimeParser\Message::from($rawEmail, true);

    $rawHeaderTo = $message->getHeaderValue('to');
    $rawHeaderFrom = $message->getHeaderValue('from');
    $subject = $message->getHeaderValue('subject');
    $text = $message->getTextContent();
    $html = $message->getHtmlContent();

    file_put_contents(__DIR__.'/out.txt', print_r([
        'rawHeaderTo' => $rawHeaderTo,
        'rawHeaderFrom' => $rawHeaderFrom,
        'subject' => $subject,
        'text' => $text,
        'html' => $html,
    ], true));
} catch (Throwable $t) {
    file_put_contents(__DIR__.'/out.txt', print_r([
        'error' => $t->getMessage(),
    ], true));
}

