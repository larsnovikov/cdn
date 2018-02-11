<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 10.02.2018
 * Time: 20:53
 */

namespace app\models\calculators;

use app\models\UploadRequestStorage;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Imagick\Imagine;

/**
 * Class WithoutMarginCalc
 * @package app\models\calculators
 */
class WithoutMarginCalc implements InterfaceCalc
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
    }

    /**
     *
     */
    public function minimaze()
    {
    }

    /**
     *
     */
    public function customize()
    {
    }

    /**
     *
     */
    public function beforeExecution()
    {
        $mode    = ImageInterface::THUMBNAIL_OUTBOUND;
        $imagine = new Imagine();

        $thumbWidth = UploadRequestStorage::getObject()->toParams['width'];
        $thumbHeight = UploadRequestStorage::getObject()->toParams['height'];

        if (UploadRequestStorage::getObject()->toParams['width'] > UploadRequestStorage::getObject()->fromParams['width']) {
            $thumbWidth = UploadRequestStorage::getObject()->fromParams['width'];
        }

        if (UploadRequestStorage::getObject()->toParams['height'] > UploadRequestStorage::getObject()->fromParams['height']) {
            $thumbHeight = UploadRequestStorage::getObject()->fromParams['height'];
        }

        //  если включена ротация, перевернем размеры
        if (UploadRequestStorage::getObject()->rotate) {
            $size = new Box($thumbHeight, $thumbWidth);
        } else {
            $size = new Box($thumbWidth, $thumbHeight);
        }

        UploadRequestStorage::getObject()->source = $imagine->open(UploadRequestStorage::getObject()->sourcePath)
            ->thumbnail($size, $mode);

        // Установим размеры по размеру тумбика
        UploadRequestStorage::getObject()->params['param_1'] = $thumbWidth;
        UploadRequestStorage::getObject()->params['param_2'] = $thumbHeight;
    }
}