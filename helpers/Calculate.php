<?php

/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 16:53
 */
namespace app\helpers;

class Calculate
{
    /**
     * Выходные параметры
     * @var array
     */
    private static $params = [
        'param_1' => 0,
        'param_2' => 0,
        'param_1_margin' => 0,
        'param_2_margin' => 0
    ];

    /**
     * Размеры исходника
     *
     * @var array
     */
    private static $fromParams = [];

    /**
     * Размеры выходного изображения
     *
     * @var array
     */
    private static $toParams = [];

    /**
     * Была ли ротация
     *
     * @var bool
     */
    private static $rotate = false;

    /**
     * Получить результирующие параметры
     *
     * @return array
     */
    public static function getParams()
    {
        if (!self::$rotate) {
            return [
                'width' => (int)self::$params['param_1'],
                'height' => (int)self::$params['param_2'],
                'left_margin' => (int)self::$params['param_1_margin'],
                'top_margin' => (int)self::$params['param_2_margin']
            ];
        }
        return [
            'width' => (int)self::$params['param_2'],
            'height' => (int)self::$params['param_1'],
            'left_margin' => (int)self::$params['param_2_margin'],
            'top_margin' => (int)self::$params['param_1_margin']
        ];
    }

    /**
     * Установить размеры входного и выходного
     *
     * @param $from
     * @param $to
     */
    public static function setSourceSizes($from, $to)
    {
        if ($from['width'] > $from['height']) {
            self::$fromParams = $from;
            self::$toParams = $to;
        } else {
            self::$fromParams['width'] = $from['height'];
            self::$fromParams['height'] = $from['width'];

            self::$toParams['width'] = $to['height'];
            self::$toParams['height'] = $to['width'];

            self::$rotate = true;
        }
    }

    /**
     * Обработка
     */
    public static function execute()
    {
        if (self::$fromParams['width'] > self::$toParams['width'] && self::$fromParams['height'] > self::$toParams['height']) {
            // minimaze image
            self::minimaze();
        } elseif (self::$fromParams['width'] < self::$toParams['width'] && self::$fromParams['height'] < self::$toParams['height']) {
            // maximize image
            self::maximize();
        } else {

        }

        self::margins();
    }

    /**
     * Расчет отступа
     */
    private static function margins()
    {
        self::$params['param_2_margin'] = (self::$toParams['height'] - self::$params['param_2'])/2;
        self::$params['param_1_margin'] = (self::$toParams['width'] - self::$params['param_1'])/2;
    }

    /**
     * При уменьшении
     */
    private static function minimaze()
    {
        // должна влезти и щирина и высота
        // определим базовую сторону
        $coefFrom = self::$fromParams['width']/self::$fromParams['height'];
        $coefTo = self::$toParams['width']/self::$toParams['height'];

        if ($coefFrom > $coefTo) {
            $height = self::$toParams['width'] / $coefFrom;
            self::$params['param_1'] = self::$toParams['width'];
            self::$params['param_2'] = $height;
        } else {
            $width = ceil(self::$toParams['height'] * $coefFrom);
            self::$params['param_1'] = $width;
            self::$params['param_2'] = self::$toParams['height'];
        }
    }

    /**
     * При увеличении
     */
    private static function maximize()
    {
        self::$params['param_1'] = self::$fromParams['width'];
        self::$params['param_2'] = self::$fromParams['height'];
    }
}