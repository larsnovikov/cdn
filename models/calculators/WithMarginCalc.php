<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 10.02.2018
 * Time: 20:53
 */

namespace app\models\calculators;


use app\helpers\Calculate;
use app\models\UploadRequestStorage;

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
        UploadRequestStorage::getObject()->params['param_1'] = UploadRequestStorage::getObject()->fromParams['width'];
        UploadRequestStorage::getObject()->params['param_2'] = UploadRequestStorage::getObject()->fromParams['height'];
    }

    /**
     *
     */
    public function minimaze()
    {
        // должна влезти и щирина и высота
        // определим базовую сторону
        $coefFrom = UploadRequestStorage::getObject()->fromParams['width'] / UploadRequestStorage::getObject()->fromParams['height'];
        $coefTo = UploadRequestStorage::getObject()->toParams['width'] / UploadRequestStorage::getObject()->toParams['height'];

        if ($coefFrom > $coefTo) {
            $height = UploadRequestStorage::getObject()->toParams['width'] / $coefFrom;
            UploadRequestStorage::getObject()->params['param_1'] = UploadRequestStorage::getObject()->toParams['width'];
            UploadRequestStorage::getObject()->params['param_2'] = $height;
        } else {
            $width = UploadRequestStorage::getObject()->toParams['height'] * $coefFrom;
            UploadRequestStorage::getObject()->params['param_1'] = $width;
            UploadRequestStorage::getObject()->params['param_2'] = UploadRequestStorage::getObject()->toParams['height'];
        }
    }

    /**
     *
     */
    public function customize()
    {
        $fromCoef = UploadRequestStorage::getObject()->fromParams['width'] / UploadRequestStorage::getObject()->fromParams['height'];
        $toCoef = UploadRequestStorage::getObject()->toParams['width'] / UploadRequestStorage::getObject()->toParams['height'];

        if ($toCoef <= 1) {
            UploadRequestStorage::getObject()->params['param_1'] = UploadRequestStorage::getObject()->toParams['width'];
            UploadRequestStorage::getObject()->params['param_2'] = UploadRequestStorage::getObject()->toParams['width'] / $fromCoef;
        } else {
            UploadRequestStorage::getObject()->params['param_1'] = UploadRequestStorage::getObject()->toParams['height'] * $fromCoef;
            UploadRequestStorage::getObject()->params['param_2'] = UploadRequestStorage::getObject()->toParams['height'];
        }
    }

    public function beforeExecution()
    {
        // TODO: Implement beforeExecution() method.
    }
}