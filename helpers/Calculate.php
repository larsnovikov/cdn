<?php

/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 16:53
 */
namespace app\helpers;

use app\models\calculators\InterfaceCalc;
use app\models\UploadRequestStorage;

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
    public static function getParams()
    {
        if (!UploadRequestStorage::getObject()->rotate) {
            return [
                'width' => (int)UploadRequestStorage::getObject()->params['param_1'],
                'height' => (int)UploadRequestStorage::getObject()->params['param_2'],
                'left_margin' => (int)UploadRequestStorage::getObject()->params['param_1_margin'],
                'top_margin' => (int)UploadRequestStorage::getObject()->params['param_2_margin']
            ];
        }
        return [
            'width' => (int)UploadRequestStorage::getObject()->params['param_2'],
            'height' => (int)UploadRequestStorage::getObject()->params['param_1'],
            'left_margin' => (int)UploadRequestStorage::getObject()->params['param_2_margin'],
            'top_margin' => (int)UploadRequestStorage::getObject()->params['param_1_margin']
        ];
    }


    /**
     * Обработка
     */
    public static function execute()
    {
        $object = UploadRequestStorage::getObject();
        if ($object->fromParams['width'] > $object->toParams['width'] && $object->fromParams['height'] > $object->toParams['height']) {
            // minimaze image
            self::minimaze();
        } elseif ($object->fromParams['width'] < $object->toParams['width'] && $object->fromParams['height'] < $object->toParams['height']) {
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
        $object = UploadRequestStorage::getObject();
        $object->params['param_2_margin'] = ($object->toParams['height'] - $object->params['param_2']) / 2;
        $object->params['param_1_margin'] = ($object->toParams['width'] - $object->params['param_1']) / 2;
    }

    /**
     * При уменьшении
     */
    private static function minimaze()
    {
        $object = UploadRequestStorage::getObject();
        /** @var InterfaceCalc $calculationClass */
        $calculationClass = new $object->calculationClass();

        $calculationClass->minimaze();
    }

    private static function customize()
    {
        $object = UploadRequestStorage::getObject();
        /** @var InterfaceCalc $calculationClass */
        $calculationClass = new $object->calculationClass();

        $calculationClass->customize();
    }

    /**
     * При увеличении
     */
    private static function maximize()
    {
        $object = UploadRequestStorage::getObject();
        /** @var InterfaceCalc $calculationClass */
        $calculationClass = new $object->calculationClass();

        $calculationClass->maximize();
    }
}
