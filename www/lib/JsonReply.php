<?php

namespace lib;

use stdClass;

/**
 * Class JsonReply класс для генерации ответов в формате JSON
 * 
 * @package CProCSP
 */
class JsonReply
{
    /** @var null|stdClass хранит пользовательский данные для отправки */
    public $data;

    /** @var null|stdClass хранит полное тело json ответа */
    private $json;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    /**
     * Отправляет сообщение об ошибке
     * 
     * @param int $status Статус ошибки
     * @param string $message текст сообщения
     */
    public function sendError($message = '', $status = 1)
    {
        $this->json = new stdClass();
        $this->json->status = $status;
        $this->json->mess = $message;
        $this->send();
    }

    /**
     * Отправляет json c данными пользователя
     */
    public function sendData()
    {
        $this->json = new stdClass();
        $this->json->status = 0;
        $this->json->data = $this->data;
        $this->send();
    }

    /**
     * Отправляет json и останавливает приложение
     */
    private function send()
    {
        header('Cache-Control: no-cache, must-revalidate');
        //header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');

        echo json_encode($this->json);
        die();
    }

}