<?php

// Ваш токен бота
define('TELEGRAM_TOKEN', '6835449179:AAE5GTd0ueRhGlNO787RWu0N9N9NWhzLunw');
// ID чату або користувача, куди будуть надходити повідомлення
define('CHAT_ID', 800858473);

// URL API, який ви надаєте
define('API_URL', 'https://chernihivoblenergo.com.ua/api/list_city_str/?nq=list_accident&s=12348&t=6');

// Функція для надсилання повідомлення в Telegram
function sendMessage($message)
{
      $url = 'https://api.telegram.org/bot' . TELEGRAM_TOKEN . '/sendMessage';
      $data = [
            'chat_id' => CHAT_ID,
            'text' => $message
      ];

      $options = [
            'http' => [
                  'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                  'method'  => 'POST',
                  'content' => http_build_query($data),
            ],
      ];
      $context  = stream_context_create($options);
      print_r($context);
      $result = file_get_contents($url, false, $context);

      // Логуємо результат надсилання повідомлення
      if ($result === FALSE) {
            error_log('Failed to send message.');
      } else {
            error_log('Message sent successfully.');
      }
}
function checkAPI()
{
      global $lastResult, $lastResultFile;

      $response = file_get_contents(API_URL);
      if ($response === FALSE) {
            error_log('Error fetching API');
            return;
      }

      $currentResult = json_decode($response, true);
      // print_r($currentResult);
      // if ($currentResult != $lastResult) {
            // Якщо результат змінився, надсилаємо повідомлення
            $message = 'Зміни в API: ' . json_encode($currentResult, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            sendMessage($message);
            $lastResult = $currentResult;
            file_put_contents($lastResultFile, json_encode($currentResult));

      //       // Логуємо результат змін
      //       error_log('Changes detected. Message sent to Telegram.');
      // } else {
      //       // Логуємо, що змін не виявлено
      //       error_log('No changes detected.');
      // }
}

// Запуск перевірки
checkAPI();
