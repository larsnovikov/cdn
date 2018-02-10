<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 14:39
 */

namespace app\models;
use app\helpers\Calculate;
use app\helpers\CalculateMargins;
use app\helpers\CalculateNoMargins;
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

    /**
     * @var array
     */
    public static $format = [];

    /**
     * @var
     */
    private static $calculationClass;

    /**
     * Image constructor.
     * @param $source
     * @param $format
     */
    public function __construct($source, $format)
    {
        $this->sourcePath = \Yii::$app->params['cdn']['inputPath'] . DIRECTORY_SEPARATOR . $source;

        self::$format = $format;

        if ($format['margins']) {
            // режим с ушами
            self::$calculationClass = CalculateMargins::getClassName();
        } else {
            // режим без ушей
            self::$calculationClass = CalculateNoMargins::getClassName();
        }

        self::$image = new \Imagine\Imagick\Imagine();
        self::$palette = Palette::create($format);
        self::$source = Source::create($this->sourcePath);
    }

    public static function getCalculationClassName()
    {
        return self::$calculationClass;
    }

    /**
     * Сборщик
     *
     * @return string
     */
    public function build()
    {
        $filePath = Storage::chooseStorage()
            . DIRECTORY_SEPARATOR. time()
            . '_' . self::$format['width']
            . '_' . self::$format['height']
            . '_' . rand(0, 99999)
            . '.jpg';

        $collage = self::$image->create(new Box(self::$format['width'], self::$format['height']), Image::$palette);

        /** @var Calculate $calculationClass */
        $calculationClass = self::getCalculationClassName();
        $calculatedParams = $calculationClass::getParams();

        self::$image = $collage
            ->paste(self::$source, new Point($calculatedParams['left_margin'], $calculatedParams['top_margin']))
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
