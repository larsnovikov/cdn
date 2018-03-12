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
    private static function getParams(): array
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
     * 
     * @return array
     */
    public static function execute(): array 
    {
        $object = Upload::getObject();

        /** @var Calc $calculationClass */
        $calculationClass = new $object->calculationClass();
        if ($object->fromParams['width'] > $object->toParams['width'] && $object->fromParams['height'] > $object->toParams['height']) {
            $calculationClass->minimaze();
        } elseif ($object->fromParams['width'] < $object->toParams['width'] && $object->fromParams['height'] < $object->toParams['height']) {
            $calculationClass->maximize();
        } else {
            $calculationClass->customize();
        }

        self::margins();

        return self::getParams();
    }

    /**
     * Расчет отступа
     * @return void
     */
    private static function margins(): void
    {
        $object = Upload::getObject();
        $object->params['param_2_margin'] = ($object->toParams['height'] - $object->params['param_2']) / 2;
        $object->params['param_1_margin'] = ($object->toParams['width'] - $object->params['param_1']) / 2;
    }
}
