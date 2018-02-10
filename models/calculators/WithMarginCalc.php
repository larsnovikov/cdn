<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 10.02.2018
 * Time: 20:53
 */

namespace app\models\calculators;


use app\helpers\Calculate;

/**
 * Class WithMarginCalc
 * @package app\models\calculators
 */
class WithMarginCalc implements InterfaceCalc
{
    /**
     * @return string
     */
    public static function getClassName()
    {
        return __CLASS__;
    }

    /**
     *
     */
    public function maximize()
    {
        Calculate::$params['param_1'] = Calculate::$fromParams['width'];
        Calculate::$params['param_2'] = Calculate::$fromParams['height'];
    }

    /**
     *
     */
    public function minimaze()
    {
        // должна влезти и щирина и высота
        // определим базовую сторону
        $coefFrom = Calculate::$fromParams['width'] / Calculate::$fromParams['height'];
        $coefTo = Calculate::$toParams['width'] / Calculate::$toParams['height'];

        if ($coefFrom > $coefTo) {
            $height = Calculate::$toParams['width'] / $coefFrom;
            Calculate::$params['param_1'] = Calculate::$toParams['width'];
            Calculate::$params['param_2'] = $height;
        } else {
            $width = Calculate::$toParams['height'] * $coefFrom;
            Calculate::$params['param_1'] = $width;
            Calculate::$params['param_2'] = Calculate::$toParams['height'];
        }
    }

    /**
     *
     */
    public function customize()
    {
        $fromCoef = Calculate::$fromParams['width'] / Calculate::$fromParams['height'];
        $toCoef = Calculate::$toParams['width'] / Calculate::$toParams['height'];

        if ($toCoef <= 1) {
            Calculate::$params['param_1'] = Calculate::$toParams['width'];
            Calculate::$params['param_2'] = Calculate::$toParams['width'] / $fromCoef;
        } else {
            Calculate::$params['param_1'] = Calculate::$toParams['height'] * $fromCoef;
            Calculate::$params['param_2'] = Calculate::$toParams['height'];
        }
    }
}