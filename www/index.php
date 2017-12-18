<?php
$jsonReply = new lib\JsonReply();

if (file_exists(__DIR__ . 'service.time')) {
    $jsonReply->sendError('Сервисные работы, попробуйте позже.');
}

$jsonReply->data = 'Ok';
$jsonReply->sendData();