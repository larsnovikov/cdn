<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 10.02.2018
 * Time: 20:53
 */

namespace app\models\calculators;

use app\models\Upload;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Imagick\Imagine;

/**
 * Class WithoutMarginCalc
 * @package app\models\calculators
 */
class WithoutMarginCalc extends Calc
{
    /**
     * @return string
     */
    public static function getClassName(): string
    {
        return __CLASS__;
    }

    /**
     *
     */
    public function beforeExecution(): void
    {
        $mode    = ImageInterface::THUMBNAIL_OUTBOUND;
        $imagine = new Imagine();

        $thumbWidth = Upload::getObject()->toParams['width'];
        $thumbHeight = Upload::getObject()->toParams['height'];

        if (Upload::getObject()->toParams['width'] > Upload::getObject()->fromParams['width']) {
            $thumbWidth = Upload::getObject()->fromParams['width'];
        }

        if (Upload::getObject()->toParams['height'] > Upload::getObject()->fromParams['height']) {
            $thumbHeight = Upload::getObject()->fromParams['height'];
        }

        //  если включена ротация, перевернем размеры
        if (Upload::getObject()->rotate) {
            $size = new Box($thumbHeight, $thumbWidth);
        } else {
            $size = new Box($thumbWidth, $thumbHeight);
        }

        Upload::getObject()->source = $imagine->open(Upload::getObject()->sourcePath)
            ->thumbnail($size, $mode);

        // Установим размеры по размеру тумбика
        Upload::getObject()->params['param_1'] = $thumbWidth;
        Upload::getObject()->params['param_2'] = $thumbHeight;
    }
}