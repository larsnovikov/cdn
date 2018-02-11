<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 14:40
 */

namespace app\models;

use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Imagick\Imagine;

/**
 * Class Watermark
 * @package app\models
 */
class Watermark
{
    /**
     * @return \Imagine\Gd\Image|\Imagine\Image\ImageInterface
     */
    public static function create($palette)
    {
        $resizeWidth = Upload::getObject()->format['watermark']['width'];
        $resizeHeight = Upload::getObject()->format['watermark']['height'];

        $watermarkPath = \Yii::$app->params['cdn']['watermarkPath'] . DIRECTORY_SEPARATOR . Upload::getObject()->format['watermark']['image'];
        $imagine = new Imagine();
        $watermark = $imagine->open($watermarkPath)->resize(new Box($resizeWidth, $resizeHeight));

        /** @var \Imagine\Imagick\Image $palette */
        $size = $palette->getSize();
        $wSize = $watermark->getSize();

        $leftMargin = $size->getWidth() - $wSize->getWidth();
        $topMargin = $size->getHeight() - $wSize->getHeight();

        $position = new Point($leftMargin, $topMargin);

        $palette->paste($watermark, $position);
    }
}
