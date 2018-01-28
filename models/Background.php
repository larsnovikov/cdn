<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 14:40
 */

namespace app\models;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Palette\RGB;

/**
 * Class Background
 * @package app\models
 */
class Background
{
    /**
     * @param $format
     * @return \Imagine\Gd\Image|\Imagine\Image\ImageInterface
     */
    public static function createBackground($format)
    {
        /** @var Imagine $imagine */
        $imagine = Image::$image;
        $palette = new RGB();
        $size  = new Box($format['width'], $format['height']);
        $color = $palette->color("#{$format['background']['color']}", 100);

        return $imagine->create($size, $color);
    }
}