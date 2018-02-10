<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 14:39
 */

namespace app\models;
use app\helpers\Calculate;
use app\models\calculators\InterfaceCalc;
use app\models\calculators\NoMarginCalc;
use app\models\calculators\WithMarginCalc;
use app\models\calculators\WithoutMarginCalc;
use app\models\parts\Palette;
use app\models\parts\Source;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;

/**
 * Class Image
 * @package app\models
 */
class Image
{

    /**
     * Image constructor.
     * @param $source
     * @param $format
     */
    public function __construct($source, $format)
    {

        /** @var InterfaceCalc $calculationClass */
        $calculationClass = new Calculate::$calculationClass();

        $calculationClass->beforeExecution();

        self::$image = new \Imagine\Imagick\Imagine();
        self::$palette = Palette::create($format);
        self::$source = Source::create(self::$sourcePath);
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

        $calculatedParams = Calculate::getParams();

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
            unlink(self::$sourcePath);
        } else {
            // TODO тут по идее должен быть перенос в хранилище
        }
    }
}
