<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 10.02.2018
 * Time: 20:53
 */

namespace app\models\calculators;


use app\helpers\Calculate;
use app\models\Upload;

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
        Upload::getObject()->params['param_1'] = Upload::getObject()->fromParams['width'];
        Upload::getObject()->params['param_2'] = Upload::getObject()->fromParams['height'];
    }

    /**
     *
     */
    public function minimaze()
    {
        // должна влезти и щирина и высота
        // определим базовую сторону
        $coefFrom = Upload::getObject()->fromParams['width'] / Upload::getObject()->fromParams['height'];
        $coefTo = Upload::getObject()->toParams['width'] / Upload::getObject()->toParams['height'];

        if ($coefFrom > $coefTo) {
            $height = Upload::getObject()->toParams['width'] / $coefFrom;
            Upload::getObject()->params['param_1'] = Upload::getObject()->toParams['width'];
            Upload::getObject()->params['param_2'] = $height;
        } else {
            $width = Upload::getObject()->toParams['height'] * $coefFrom;
            Upload::getObject()->params['param_1'] = $width;
            Upload::getObject()->params['param_2'] = Upload::getObject()->toParams['height'];
        }
    }

    /**
     *
     */
    public function customize()
    {
        $fromCoef = Upload::getObject()->fromParams['width'] / Upload::getObject()->fromParams['height'];
        $toCoef = Upload::getObject()->toParams['width'] / Upload::getObject()->toParams['height'];

        if ($toCoef <= 1) {
            Upload::getObject()->params['param_1'] = Upload::getObject()->toParams['width'];
            Upload::getObject()->params['param_2'] = Upload::getObject()->toParams['width'] / $fromCoef;
        } else {
            Upload::getObject()->params['param_1'] = Upload::getObject()->toParams['height'] * $fromCoef;
            Upload::getObject()->params['param_2'] = Upload::getObject()->toParams['height'];
        }
    }

    public function beforeExecution()
    {
        // TODO: Implement beforeExecution() method.
    }
}