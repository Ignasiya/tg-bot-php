<?php

namespace App\Telegram;

interface TelegramApi
{
    /**
     * @param int $offset
     * @return TelegramMessageDto[]
     */
    public function getMessage(int $offset): array;

    public function sendMessage(int $chatId, string $text);

}