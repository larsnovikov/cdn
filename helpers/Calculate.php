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
        } elseif ($from['width']< $to['width'] && $from['height'] < $to['height']) {
            // maximize image
        } else {

        }
    }
}