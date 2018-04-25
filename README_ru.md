# dehydrated-yapdd-hook.php

Представляю свою реализацию обработчика запросов **DNS-01** для dehydrated, клиента Let's Encrypt, с использованием API Яндекс.ПДД (почта для домена)

Для работы потребуется административный токен API Яндекс.ПДД, см. раздел **файл конфигурации**.
Описание API Яндекс.ПДД: [На английском](https://tech.yandex.com/domain/doc/about-docpage/)
[На русском](https://tech.yandex.ru/pdd/doc/about-docpage/)

## Требования
PHP 5.3+/PHP7
Расширение Curl для PHP

## Файл конфигурации
Скрипт использует файл конфигурации **pdd-config.txt**, файл должен находиться в каталоге со скриптом.
Формат: ``имя.домена=ТОКЕН``
Токен по умолчанию (не рекомендуется к использованию): ``default=ТОКЕН`` или просто ``ТОКЕН``

## Пример

``/path/to/dehydrated -c --domain example.com -t dns-01 -k /path/to/dehydrated-yapdd-hook.php ``

## Пожертвования

Если вам пригодился этот скрипт и будет желание прислать мне небольшую благодарность, это можно сделать следующими способами:
* Bitcoin: ``19mwci9V9vKwzM7UuySYCfdVuYeX5tmQZy``
* WebMoney: ``R426393569840`` ``Z292591296225``
* YandexMoney: ``pasha1st@yandex.ru``
* PayPal: <https://paypal.me/pasha1st>

## См. также

Dehydrated: <https://github.com/lukas2511/dehydrated>

Другие скрипты: <https://github.com/lukas2511/dehydrated/wiki/Examples-for-DNS-01-hooks>

