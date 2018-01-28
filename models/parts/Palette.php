<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 14:40
 */

namespace app\models\parts;

use Imagine\Image\Palette\RGB;

/**
 * Class Palette
 * @package app\models
 */
class Palette
{
    /**
     * @param $format
     * @return \Imagine\Gd\Image|\Imagine\Image\ImageInterface
     */
    public static function create($format)
    {
        $palette = new RGB();
        return $palette->color("#{$format['background']['color']}", 100);
    }
}