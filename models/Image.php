<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 14:39
 */

namespace app\models;
use app\helpers\Calculate;
use app\models\parts\Palette;
use app\models\parts\Source;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;

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
     * @var Imagine|null
     */
    public static $image = null;
    /**
     * @var \Imagine\Gd\Image|\Imagine\Image\ImageInterface|null
     */
    public static $palette = null;
    /**
     * @var \Imagine\Image\ImageInterface|\Imagine\Imagick\Image|null
     */
    public static $source = null;

    public static $format = [];

    /**
     * Image constructor.
     * @param $source
     * @param $format
     */
    public function __construct($source, $format)
    {
        $this->sourcePath = \Yii::$app->params['cdn']['inputPath'] . DIRECTORY_SEPARATOR . $source;
        self::$format = $format;

        self::$image = new Imagine();

        self::$palette = Palette::create($format);
        self::$source = Source::create($this->sourcePath);
    }

    /**
     * Сборщик
     *
     * @return string
     */
    public function build()
    {
        $filePath = \Yii::$app->params['cdn']['outputPath'] . DIRECTORY_SEPARATOR . time() . '_' . rand(0, 999) . '.jpg';
        $collage = self::$image->create(new Box(self::$format['width'], self::$format['height']), Image::$palette);
        self::$image = $collage->paste( self::$source, new Point(Calculate::$params['left_margin'], Calculate::$params['top_margin']))
            ->save($filePath);

        return $filePath;
    }

    /**
     * Выполнение после обработки
     */
    public function afterExecution()
    {
        if (array_key_exists('remove_source', self::$format) && self::$format['remove_source']) {
            unlink($this->sourcePath);
        } else {
            // TODO тут по идее должен быть перенос в хранилище
        }
    }
}