<?php

namespace App\Telegram;

class TelegramApiImp implements TelegramApi
{
    const ENDPOINT = 'https://api.telegram.org/bot';

    private int $offset;
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @param int $offset
     * Этот код использует бесконечный цикл (while (true))
     * для отправки запроса к $url с определенным смещением (offset)
     * и получения данных в формате JSON с помощью библиотеки cURL.
     * Затем данные декодируются из JSON в массив.
     * Если ответ ($response) содержит ошибку или результат пустой, цикл прерывается.
     *
     * Для каждого элемента $data в результате ответа, код добавляет текст сообщения в массив результатов,
     * используя идентификатор чата как ключ. Затем обновляется значение смещения (offset) для дальнейшего запроса.
     *
     * Цикл продолжается, пока количество результатов меньше 100.
     * По завершении цикла, возвращается массив, содержащий последнее значение смещения (offset)
     * и собранные тексты сообщений в структурированном виде.
     *
     * Таким образом, этот код осуществляет повторные запросы к URL,
     * собирает данные и возвращает их в виде ассоциативного массива,
     * содержащего информацию о сообщениях из чата.
     */
    public function getMessage(int $offset): array
    {
        $url = self::ENDPOINT . $this->token . '/getUpdates?timeout=1';
        $result = [];

        while (true) {
            $ch = curl_init("{$url}&offset={$offset}");

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json')); // Set the content type to application/json
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response instead of printing it

            $response = json_decode(curl_exec($ch), true);

            if (!$response['ok'] || empty($response['result'])) break;
            foreach ($response['result'] as $data) {
                $result[$data['message']['chat']['id']] = [...$result[$data['message']['chat']['id']] ?? [], $data['message']['text']];
                $offset = $data['update_id'] + 1;
            }
            curl_close($ch);

            if (count($response['result']) < 100) break;
        }

        return [
            'offset' => $offset,
            'result' => $result,
        ];
    }

    /**
     * @param int $chatId
     * @param string $text
     * Устанавливает различные параметры cURL для запроса:
     * указывает метод запроса POST,
     * прикрепляет закодированные данные JSON,
     * устанавливает тип контента как application/json
     * и устанавливает флаг для возврата ответа вместо его печати.
     */
    public function sendMessage(int $chatId, string $text): void
    {
        $url = self::ENDPOINT . $this->token . '/sendMessage';

        $data = [
            'chat_id' => $chatId,
            'text' => $text,
        ];

        $ch = curl_init($url);

        $jsonData = json_encode($data);

        curl_setopt($ch, CURLOPT_POST, true); // Specify the request method as POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData); // Attach the encoded JSON data
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json')); // Set the content type to application/json
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response instead of printing it

        curl_exec($ch);

        curl_close($ch);
    }
}