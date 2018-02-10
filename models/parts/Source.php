<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 15:46
 */
namespace app\models\parts;

use app\helpers\Calculate;
use app\models\Image;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Imagick\Imagine;

/**
 * Class Source
 * @package app\models
 */
class Source {

    public static $sourceSizes = [];

    /**
     * @param $sourcePath
     * @return \Imagine\Image\ImageInterface|\Imagine\Imagick\Image
     */
    public static function create($sourcePath)
    {
        /** @var Imagine $imagine */
        $imagine = Image::$image;

        $image = $imagine->open($sourcePath);

        /** @var Calculate $calculationClass */
        $calculationClass = Image::getCalculationClassName();
        $calculationClass::setSourceSizes([
            'width' => $image->getSize()->getWidth(),
            'height' => $image->getSize()->getHeight()
        ], [
            'width' => Image::$format['width'],
            'height' => Image::$format['height']
        ]);

        $calculationClass::execute();

        $calculatedParams = $calculationClass::getParams();

        return $image->resize(new Box($calculatedParams['width'], $calculatedParams['height']));
    }
}
