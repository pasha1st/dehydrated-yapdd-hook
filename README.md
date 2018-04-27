# dehydrated-yapdd-hook.php

This is PDD Yandex API implementation for **DNS-01** challenge hook used in dehydrated, a Let's Encrypt client.

This script require administrative token for Yandex API, see **configuration file** section.
Details about Yandex API: [English](https://tech.yandex.com/domain/doc/about-docpage/)
[Russian](https://tech.yandex.ru/pdd/doc/about-docpage/)

## Requirements
PHP 5.3+/PHP7
Curl extention for PHP

## Configuration file
This script uses API tokens from **pdd-config.txt** file its directory.
Format: ``domain.name=TOKEN``
Default token (not recomended): ``default=TOKEN`` or ``TOKEN``

## Examples

``/path/to/dehydrated -c --domain example.com -t dns-01 -k /path/to/dehydrated-yapdd-hook.php ``

## Donate

If you like this script you are welcome to send me any donation:
* Bitcoin: ``19mwci9V9vKwzM7UuySYCfdVuYeX5tmQZy``
* WebMoney: ``R426393569840`` ``Z292591296225``
* YandexMoney: <https://money.yandex.ru/to/41001287590231>
* PayPal: <https://paypal.me/pasha1st>

## More info

Dehydrated: <https://github.com/lukas2511/dehydrated>

More hooks: <https://github.com/lukas2511/dehydrated/wiki/Examples-for-DNS-01-hooks>

