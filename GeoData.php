<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 29.07.2016
 * Time: 22:47
 */
namespace phpnt\geoData;

use yii\base\Object;
use jisoft\sypexgeo\Sypexgeo;

class GeoData extends Object
{
    public $addToCookie         = true;
    public $addToSession        = true;
    public $setTimezoneApp      = true;

    public $cookieDuration      = 2592000;

    private $timezoneSessionKey = '_timezone';
    private $timezoneCookieName = '_timezone';

    private $citySessionKey     = '_city';
    private $cityCookieName     = '_city';
    private $regionSessionKey   = '_region';
    private $regionCookieName   = '_region';
    private $countrySessionKey  = '_country';
    private $countryCookieName  = '_country';

    public function init()
    {
        parent::init();
        $geo = new Sypexgeo();
        $data = $geo->get();

        if ($this->setTimezoneApp) {
            $this->setTimeZone();
        }

        if ($this->addToCookie) {
            if (isset($geo->city['id'])) {
                $timezone = \Yii::$app->request->cookies->getValue($this->timezoneCookieName);
                if ($timezone === null) {
                    if ($this->cookieDuration) {
                        if ($this->setTimezoneApp) {
                            if (isset($geo->region['timezone'])
                                && $geo->region['timezone'] != ''
                                && \Yii::$app->formatter->timeZone != $geo->region['timezone']
                            ) {
                                $cookies = \Yii::$app->response->cookies;
                                $cookies->add(new \yii\web\Cookie([
                                    'name' => $this->timezoneCookieName,
                                    'value' => $geo->region['timezone'],
                                    'expire' => time() + (int) $this->cookieDuration,
                                ]));
                                \Yii::$app->formatter->timeZone = $geo->region['timezone'];
                            } elseif (isset($geo->country['timezone'])
                                && $geo->country['timezone'] != ''
                                && \Yii::$app->formatter->timeZone != $geo->country['timezone']
                            ) {
                                $cookies = \Yii::$app->response->cookies;
                                $cookies->add(new \yii\web\Cookie([
                                    'name' => $this->timezoneCookieName,
                                    'value' => $geo->country['timezone'],
                                    'expire' => time() + (int) $this->cookieDuration,
                                ]));
                                \Yii::$app->formatter->timeZone = $geo->country['timezone'];
                            }
                        }
                    }
                }

                $city = \Yii::$app->request->cookies->getValue($this->cityCookieName);
                if ($city === null) {
                    if ($this->cookieDuration) {
                        $cookies = \Yii::$app->response->cookies;
                        $cookies->add(new \yii\web\Cookie([
                            'name' => $this->cityCookieName,
                            'value' => $geo->city['id'],
                            'expire' => time() + (int) $this->cookieDuration,
                        ]));
                    }
                }

                $region = \Yii::$app->request->cookies->getValue($this->regionCookieName);
                if ($region === null) {
                    if ($this->cookieDuration) {
                        $cookies = \Yii::$app->response->cookies;
                        $cookies->add(new \yii\web\Cookie([
                            'name' => $this->regionCookieName,
                            'value' => $geo->region['id'],
                            'expire' => time() + (int) $this->cookieDuration,
                        ]));
                    }
                }

                $country = \Yii::$app->request->cookies->getValue($this->countryCookieName);
                if ($country === null) {
                    if ($this->cookieDuration) {
                        $cookies = \Yii::$app->response->cookies;
                        $cookies->add(new \yii\web\Cookie([
                            'name' => $this->countryCookieName,
                            'value' => $geo->country['id'],
                            'expire' => time() + (int) $this->cookieDuration
                        ]));
                    }
                }
            }
        } elseif ($this->addToSession && $geo->city['id']) {
            if (isset($data)) {
                $timezone = \Yii::$app->session->get($this->timezoneSessionKey);
                if ($timezone === null) {
                    \Yii::$app->session[$this->timezoneSessionKey] = $geo->region['timezone'];
                }

                $city = \Yii::$app->session->get($this->citySessionKey);
                if ($city === null) {
                    \Yii::$app->session[$this->citySessionKey] = $geo->city['id'];
                }

                $region = \Yii::$app->session->get($this->regionSessionKey);
                if ($region === null) {
                    \Yii::$app->session[$this->regionSessionKey] = $geo->region['id'];
                }

                $country = \Yii::$app->session->get($this->countrySessionKey);
                if ($country === null) {
                    \Yii::$app->session[$this->countrySessionKey] = $geo->country['id'];
                }
            }
        }
    }

    /* Установить timezone в formatter (для вывода) */
    public function setTimeZone() {
        $timezone = \Yii::$app->session->get($this->timezoneSessionKey);
        if ($timezone === null) {
            $timezone = \Yii::$app->request->cookies->getValue($this->timezoneCookieName);
            \Yii::$app->formatter->timeZone = $timezone;
        } else {
            \Yii::$app->formatter->timeZone = $timezone;
        }

    }

    /* Обновить сессии и куки */
    public function setData($timezone, $city, $region, $country)
    {
        $this->removeData();

        \Yii::$app->session[$this->timezoneSessionKey] = $timezone;
        $cookies = \Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => $this->timezoneCookieName,
            'value' => $timezone,
            'expire' => time() + (int) $this->cookieDuration,
        ]));
        \Yii::$app->formatter->timeZone = $timezone;

        \Yii::$app->session[$this->citySessionKey] = $city;
        $cookies->add(new \yii\web\Cookie([
            'name' => $this->cityCookieName,
            'value' => $city,
            'expire' => time() + (int) $this->cookieDuration,
        ]));

        \Yii::$app->session[$this->regionSessionKey] = $region;
        $cookies->add(new \yii\web\Cookie([
            'name' => $this->regionCookieName,
            'value' => $region,
            'expire' => time() + (int) $this->cookieDuration,
        ]));

        \Yii::$app->session[$this->countrySessionKey] = $country;
        $cookies->add(new \yii\web\Cookie([
            'name' => $this->countryCookieName,
            'value' => $country,
            'expire' => time() + (int) $this->cookieDuration,
        ]));
    }

    /* Чистим сессиии и куки */
    public function removeData()
    {
        $cookies = \Yii::$app->response->cookies;

        \Yii::$app->session->remove($this->timezoneSessionKey);
        $cookies->remove($this->timezoneCookieName);

        \Yii::$app->session->remove($this->citySessionKey);
        $cookies->remove($this->cityCookieName);

        \Yii::$app->session->remove($this->regionSessionKey);
        $cookies->remove($this->regionCookieName);

        \Yii::$app->session->remove($this->countrySessionKey);
        $cookies->remove($this->countryCookieName);
    }

    public function getData()
    {
        $geo = new Sypexgeo();
        return $geo->get();
    }

    public function getDataIp($ip)
    {
        $geo = new Sypexgeo();
        return $geo->get($ip);
    }

    public function getCity()
    {
        $city = \Yii::$app->session->get($this->citySessionKey);
        if ($city === null) {
            $city = \Yii::$app->request->cookies->getValue($this->cityCookieName);
            if ($city === null) {
                $geo = new Sypexgeo();
                $geo->get();
                if (isset($geo->city['id'])) {
                    $city = $geo->city['id'];
                }
            }
        }
        return $city;
    }

    public function getRegion()
    {
        $region = \Yii::$app->session->get($this->regionSessionKey);
        if ($region === null) {
            $region = \Yii::$app->request->cookies->getValue($this->regionCookieName);
            if ($region === null) {
                $geo = new Sypexgeo();
                $geo->get();
                if (isset($geo->city['id'])) {
                    $region = $geo->region['id'];
                }
            }
        }
        return $region;
    }

    public function getCountry()
    {
        $country = \Yii::$app->session->get($this->countrySessionKey);
        if ($country === null) {
            $country = \Yii::$app->request->cookies->getValue($this->countryCookieName);
            if ($country === null) {
                $geo = new Sypexgeo();
                $geo->get();
                if (isset($geo->city['id'])) {
                    $country = $geo->country['id'];
                }
            }
        }
        return $country;
    }
}