<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 10.02.2018
 * Time: 23:12
 */

namespace app\models;

use app\helpers\Calculate;
use app\models\calculators\Calc;
use app\models\calculators\InterfaceCalc;
use app\models\calculators\WithMarginCalc;
use app\models\calculators\WithoutMarginCalc;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Imagick\Imagine;

class Upload
{
    /**
     * @var null|string
     */
    public $sourcePath = null;

    /**
     * @var Imagine|null
     */
    public $image = null;
    /**
     * @var \Imagine\Gd\Image|\Imagine\Image\ImageInterface|null
     */
    public $palette = null;
    /**
     * @var \Imagine\Image\ImageInterface|\Imagine\Imagick\Image|null
     */
    public $source = null;

    /**
     * @var array
     */
    public $format = [];

    /**
     * Выходные параметры
     * @var array
     */
    public $params = [
        'param_1' => 0,
        'param_2' => 0,
        'param_1_margin' => 0,
        'param_2_margin' => 0
    ];

    /**
     * Размеры исходника
     *
     * @var array
     */
    public $fromParams = [];

    /**
     * Размеры выходного изображения
     *
     * @var array
     */
    public $toParams = [];

    /**
     * Была ли ротация
     *
     * @var bool
     */
    public $rotate = false;

    /**
     * @var
     */
    public $calculationClass;

    /**
     * @var string
     */
    public $outFileName = '';

    /**
     * @var Upload|null
     */
    private static $object = null;

    /**
     * Upload constructor.
     * @param $source
     * @param $format
     */
    public function __construct($source, $format)
    {
        // пишем себя в атрибут
        self::$object = $this;

        // пишем путь к исходнику
        $this->sourcePath = \Yii::$app->params['cdn']['inputPath'] . DIRECTORY_SEPARATOR . $source;

        // пишем запрос формата
        $this->format = $format;

        // выбираем класс для обработки с ушами/без ушей
        if ($format['margins']) {
            // режим с ушами
            $this->calculationClass = WithMarginCalc::getClassName();
        } else {
            // режим без ушей
            $this->calculationClass = WithoutMarginCalc::getClassName();
        }

        // инициализируем source;
        /** @var Imagine $imagine */
        $image = new Imagine();
        $this->source = $image->open($this->sourcePath);

        // инициализируем размеры
        self::setSourceSizes([
            'width' => $this->source->getSize()->getWidth(),
            'height' => $this->source->getSize()->getHeight()
        ], [
            'width' => $this->format['width'],
            'height' => $this->format['height']
        ]);

        // выполняем предобработку
        /** @var Calc $calculationClass */
        $calculationClass = new $this->calculationClass();
        $calculationClass->beforeExecution();

        $this->outFileName = Storage::chooseStorage()
            . DIRECTORY_SEPARATOR. time()
            . '_' . $this->format['width']
            . '_' . $this->format['height']
            . '_' . rand(0, 99999)
            . '.jpg';
    }

    /**
     * Получение объекта
     *
     * @param bool $force
     * @param bool $source
     * @param array $format
     * @return Upload|null
     */
    public static function getObject($force = false, $source = false, $format = [])
    {
        if (self::$object === null || $force) {
            self::$object = new self($source, $format);
        }

        return self::$object;
    }

    /**
     * Установить размеры входного и выходного
     *
     * @param $from
     * @param $to
     */
    public function setSourceSizes($from, $to)
    {
        if ($from['width'] > $from['height']) {
            $this->fromParams = $from;
            $this->toParams = $to;
        } else {
            $this->fromParams['width'] = $from['height'];
            $this->fromParams['height'] = $from['width'];

            $this->toParams['width'] = $to['height'];
            $this->toParams['height'] = $to['width'];

            $this->rotate = true;
        }
    }

    /**
     * Собрать все воедино
     *
     * @return string
     */
    public function build()
    {
        // создаем подложку размером с выходное и указанным цветом
        $this->palette = Palette::create();
        $image = new Imagine();
        $palette = $image->create(new Box($this->format['width'], $this->format['height']), $this->palette);

        // расчитываем параметры
        $calculatedParams = Calculate::execute();;

        // ресайзим исходное изображение
        $this->source->resize(new Box($calculatedParams['width'], $calculatedParams['height']));

        // собираем картинку
        $palette->paste($this->source, new Point($calculatedParams['left_margin'], $calculatedParams['top_margin']))
            ->save($this->outFileName);

        if ($this->format['optimize']) {
            $this->optimize();
        }

        return $this->outFileName;
    }

    /**
     * Оптимизация картинки
     */
    private function optimize()
    {
        var_dump("jpegoptim --strip-all --all-progressive -ptm100 {$this->outFileName}");
        exec("jpegoptim --strip-all --all-progressive -ptm100 {$this->outFileName}");
    }
}