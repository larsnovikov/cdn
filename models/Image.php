<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 14:39
 */

namespace app\models;
use Imagine\Gd\Imagine;

/**
 * Class Image
 * @package app\models
 */
class Image
{
    /**
     * @var null|string
     */
    public $sourcePath = null;

    /**
     * @var null
     */
    public static $image = null;

    /**
     * @var null
     */
    public $filePath = null;

    /**
     * Image constructor.
     * @param $source
     * @param $format
     */
    public function __construct($source, $format)
    {
        $this->sourcePath = \Yii::$app->params['cdn']['inputPath'] . DIRECTORY_SEPARATOR . $source;
        self::$image = new Imagine();
        self::$image = Background::createBackground($format);
    }

    public function saveImage()
    {
        $this->filePath = \Yii::$app->params['cdn']['outputPath'] . DIRECTORY_SEPARATOR . time() . '_' . rand(0, 999) . '.jpg';
        self::$image->save($this->filePath);
    }

    public function getFilePath()
    {
        return $this->filePath;
    }
}