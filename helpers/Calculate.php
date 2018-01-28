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
    public static $params = [
        'width' => 0,
        'height' => 0,
        'top_margin' => 0,
        'left_margin' => 0
    ];

    /**
     * Расчетный блок
     *
     * @param $from
     * @param $to
     */
    public static function getSourceSizes($from, $to)
    {
        if ($from['width'] > $to['width'] && $from['height'] > $to['height']) {
            // minimaze image
            self::minimaze($from, $to);
        } elseif ($from['width']< $to['width'] && $from['height'] < $to['height']) {
            // maximize image
            self::maximize($from, $to);
        } else {

        }

        self::margins($to);
    }

    private static function margins($to)
    {
        self::$params['top_margin'] = ($to['height'] - self::$params['height'])/2;
        self::$params['left_margin'] = ($to['width'] - self::$params['width'])/2;
    }

    private static function minimaze($from, $to)
    {
        // должна влезти и щирина и высота
        // определим базовую сторону
        $coefFrom = $from['width']/$from['height'];
        $coefTo = $to['width']/$to['height'];

        if ($coefFrom > $coefTo) {
            $height = $to['width'] / $coefFrom;
            self::$params['width'] = $to['width'];
            self::$params['height'] = $height;
        } else {
            $width = ceil($to['height'] * $coefFrom);
            self::$params['width'] = $width;
            self::$params['height'] = $to['height'];
        }
    }

    private static function maximize($from, $to)
    {
        self::$params['width'] = $from['width'];
        self::$params['height'] = $from['height'];
    }
}