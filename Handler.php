#!/usr/bin/php -q
<?php

require_once __DIR__.'/vendor/autoload.php';

try {
    $parser = new eXorus\PhpMimeMailParser\Parser();

    $parser->setStream(fopen("php://stdin", "r"));

    $rawHeaderTo = $parser->getHeader('to');
    $rawHeaderFrom = $parser->getHeader('from');
    $subject = $parser->getHeader('subject');
    $text = $parser->getMessageBody('text');
    $html = $parser->getMessageBody('html');

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

