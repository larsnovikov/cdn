<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 14:40
 */

namespace app\models;

use Imagine\Image\Palette\RGB;

/**
 * Class Palette
 * @package app\models
 */
class Palette
{
    /**
     * @return \Imagine\Gd\Image|\Imagine\Image\ImageInterface
     */
    public static function create()
    {
        $color = Upload::getObject()->format['background']['color'];
        $palette = new RGB();
        return $palette->color("#{$color}", 100);
    }
}
