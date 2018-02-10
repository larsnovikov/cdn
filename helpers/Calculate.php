<?php

/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 16:53
 */
namespace app\helpers;
use app\models\calculators\AbstractCalc;
use app\models\calculators\InterfaceCalc;

/**
 * Class Calculate
 * @package app\helpers
 */
class Calculate
{
    /**
     * Выходные параметры
     * @var array
     */
    public static $params = [
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
    public static $fromParams = [];

    /**
     * Размеры выходного изображения
     *
     * @var array
     */
    public static $toParams = [];

    /**
     * Была ли ротация
     *
     * @var bool
     */
    public static $rotate = false;

    /**
     * @var
     */
    public static $calculationClass;

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
            self::customize();
        }

        // TODO это проверка соотношения сторон исходника и выходного изображения
      //  $fromCoef = self::$fromParams['width'] / self::$fromParams['height'];
      //  $outCoef = self::$params['param_1'] / self::$params['param_2'];
      //  var_dump($fromCoef, $outCoef);

        self::margins();
    }

    /**
     * Расчет отступа
     */
    private static function margins()
    {
        self::$params['param_2_margin'] = (self::$toParams['height'] - self::$params['param_2']) / 2;
        self::$params['param_1_margin'] = (self::$toParams['width'] - self::$params['param_1']) / 2;
    }

    /**
     * При уменьшении
     */
    private static function minimaze()
    {
        /** @var InterfaceCalc $calculationClass */
        $calculationClass = new self::$calculationClass();

        $calculationClass->minimaze();
    }

    private static function customize()
    {
        /** @var InterfaceCalc $calculationClass */
        $calculationClass = new self::$calculationClass();

        $calculationClass->customize();
    }

    /**
     * При увеличении
     */
    private static function maximize()
    {
        /** @var InterfaceCalc $calculationClass */
        $calculationClass = new self::$calculationClass();

        $calculationClass->customize();
    }
}
