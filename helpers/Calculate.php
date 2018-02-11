<?php

/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 16:53
 */
namespace app\helpers;

use app\models\calculators\Calc;
use app\models\Upload;

/**
 * Class Calculate
 * @package app\helpers
 */
class Calculate
{
    /**
     * Получить результирующие параметры
     *
     * @return array
     */
    private static function getParams()
    {
        if (!Upload::getObject()->rotate) {
            return [
                'width' => (int)Upload::getObject()->params['param_1'],
                'height' => (int)Upload::getObject()->params['param_2'],
                'left_margin' => (int)Upload::getObject()->params['param_1_margin'],
                'top_margin' => (int)Upload::getObject()->params['param_2_margin']
            ];
        }
        return [
            'width' => (int)Upload::getObject()->params['param_2'],
            'height' => (int)Upload::getObject()->params['param_1'],
            'left_margin' => (int)Upload::getObject()->params['param_2_margin'],
            'top_margin' => (int)Upload::getObject()->params['param_1_margin']
        ];
    }


    /**
     * Обработка
     */
    public static function execute()
    {
        $object = Upload::getObject();

        /** @var Calc $calculationClass */
        $calculationClass = new $object->calculationClass();
        if ($object->fromParams['width'] > $object->toParams['width'] && $object->fromParams['height'] > $object->toParams['height']) {
            // minimaze image
            $calculationClass->minimaze();
        } elseif ($object->fromParams['width'] < $object->toParams['width'] && $object->fromParams['height'] < $object->toParams['height']) {
            // maximize image
            $calculationClass->maximize();
        } else {
            $calculationClass->customize();
        }

        // TODO это проверка соотношения сторон исходника и выходного изображения
      //  $fromCoef = self::$fromParams['width'] / self::$fromParams['height'];
      //  $outCoef = self::$params['param_1'] / self::$params['param_2'];
      //  var_dump($fromCoef, $outCoef);

        self::margins();

        return self::getParams();
    }

    /**
     * Расчет отступа
     */
    private static function margins()
    {
        $object = Upload::getObject();
        $object->params['param_2_margin'] = ($object->toParams['height'] - $object->params['param_2']) / 2;
        $object->params['param_1_margin'] = ($object->toParams['width'] - $object->params['param_1']) / 2;
    }
}
