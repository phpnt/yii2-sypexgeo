phpNT - Sypex Geo
================================
[![Latest Stable Version](https://poser.pugx.org/phpnt/yii2-sypexgeo/v/stable)](https://packagist.org/packages/phpnt/yii2-sypexgeo) [![Total Downloads](https://poser.pugx.org/phpnt/yii2-sypexgeo/downloads)](https://packagist.org/packages/phpnt/yii2-sypexgeo) [![Latest Unstable Version](https://poser.pugx.org/phpnt/yii2-sypexgeo/v/unstable)](https://packagist.org/packages/phpnt/yii2-sypexgeo) [![License](https://poser.pugx.org/phpnt/yii2-sypexgeo/license)](https://packagist.org/packages/phpnt/yii2-sypexgeo)
### Описание:
#### Определяет местоположение пользователя по ip (по айпи вычисляет))) ). Получает данные о местоположении. Есть возможность записывать эти данные в сессии/куки, для дальнейшего использования. Изменяет временную зону приложения, для вывода времени в значении местоположения пользователя. Позволяет изменять гео данные пользователя.
#### Использует https://sypexgeo.net/

### [DEMO](http://phpnt.com/widget/sypexgeo)

------------
[![Donate button](https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif)](http://phpnt.com/donate/index)
------------

### Социальные сети:
 - [Канал YouTube](https://www.youtube.com/c/phpnt)
 - [Группа VK](https://vk.com/phpnt)
 - [Группа facebook](https://www.facebook.com/Phpnt-595851240515413/)

------------

!!! Перед установкой загрузите пакет https://github.com/JiSoft/yii2-sypexgeo 

------------

Установка:

------------

```
php composer.phar require "phpnt/yii2-sypexgeo" "dev-master"
```
или

```
composer require phpnt/yii2-sypexgeo "dev-master"
```

или добавить в composer.json файл

```
"phpnt/yii2-sypexgeo": "dev-master"
```
## Использование:
### Подключение:
------------
```php
// в файле настройки приложения (main.php - Advanced или web.php - Basic) 
// в загрузку bootstrap
...
'bootstrap' => [
        ...
        'geoData'
    ],
// в components
'components' => [
    ...
    'geoData' => [
            'class'             => 'phpnt\geoData\GeoData',         // путь к классу
            'addToCookie'       => true,                            // сохранить в куки
            'addToSession'      => true,                            // сохранить в сессии
            'setTimezoneApp'    => true,                            // установить timezone в formatter (для вывода)
            'cookieDuration'    => 2592000                          // время хранения в куки
        ],
],
```
### методы:
------------
```php
...
// Получить подробные geo по ip
$data = Yii::$app->geoData->getDataIp('91.144.140.0');
// Получить подробные geo по ip пользователя
$data = Yii::$app->geoData->data;
// Получить город
$city = Yii::$app->geoData->city;
// Получить регион
$region = Yii::$app->geoData->region;
// Получить страну
$country = Yii::$app->geoData->country;
// Установить новые данные
Yii::$app->geoData->setData($timezone = 'Europe/Moscow', $city = 524901, $region = 524894, $country = 185);
// Очистить сессии и куки
Yii::$app->geoData->removeData();
```
# Документация (примеры):
## [Sypex Geo](https://sypexgeo.net/)
------------
### Версия:
### dev-master
------------
### Лицензия:
### [MIT](https://ru.wikipedia.org/wiki/%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F_MIT)
------------