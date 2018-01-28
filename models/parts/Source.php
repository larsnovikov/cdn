<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 15:46
 */
namespace app\models\parts;

use app\models\Image;
use Imagine\Image\Point;
use Imagine\Imagick\Imagine;

/**
 * Class Source
 * @package app\models
 */
class Source {

    public static function create($sourcePath)
    {
        /** @var Imagine $imagine */
        $imagine = Image::$image;
        return $imagine->open($sourcePath);
    }
}