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
use app\models\calculators\WithMarginCalc;
use app\models\calculators\WithoutMarginCalc;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Imagick\Imagine;

/**
 * Class Upload
 * @package app\models
 */
class Upload
{
    /**
     * Сопоставление mime и формата
     */
    const MIME_TYPE_MAP = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png'
    ];
    /**
     * @var null|string
     */
    public $sourcePath = null;

    /**
     * @var Imagine|null
     */
    public $image = null;

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
     * @var string
     */
    public $webFileName = '';

    /**
     * @var array
     */
    public $watermarkParams = [];

    /**
     * @var string
     */
    public $fileType = '';

    /**
     * @var Upload|null
     */
    private static $object = null;

    /**
     * Получить путь исходника
     *
     * @param string $source
     * @return string
     */
    public static function getSourcePath(string $source): string
    {
        return \Yii::$app->params['cdn']['inputPath'] . DIRECTORY_SEPARATOR . $source;
    }

    /**
     * Получить тип файла
     *
     * @param string $source
     * @return string
     */
    public static function getFileType(string $source): string
    {
        return self::MIME_TYPE_MAP[mime_content_type(self::getSourcePath($source))];
    }

    /**
     * Получить путь файла
     *
     * @param string $formatName
     * @param string $fileType
     * @return string
     */
    public static function getWebFilePath(string $formatName, string $fileType): string
    {
        return Storage::chooseStorage()
        . DIRECTORY_SEPARATOR . $formatName
        . '_' . time()
        . '_' . rand(0, 99999)
        . '.' . $fileType;
    }


    /**
     * Получить полный путь к файлу
     *
     * @param string $formatName
     * @param string $source
     * @return string
     */
    public static function getOutFileName(string $formatName, string $source): string
    {
        $fileType = self::getFileType($source);

        $webFilePath = self::getWebFilePath($formatName, $fileType);

        return Storage::getFullPath($webFilePath);
    }

    /**
     * Upload constructor.
     * @param string $source
     * @param array $format
     * @param null|string $webFileName
     */
    public function __construct(string $source, array $format, ?string $webFileName = null)
    {
        // пишем себя в атрибут
        self::$object = $this;

        // пишем путь к исходнику
        $this->sourcePath = self::getSourcePath($source);

        // пишем запрос формата
        $this->format = $format;

        // пишем параметры вотермарка
        $this->watermarkParams = $format['watermark'];

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

        $this->fileType = self::getFileType($source);

        // выполняем предобработку
        /** @var Calc $calculationClass */
        $calculationClass = new $this->calculationClass();
        $calculationClass->beforeExecution();

        if ($webFileName !== null) {
            $this->webFilePath = $webFileName;
        } else {
            $this->webFilePath = self::getWebFilePath($this->format['name'], $this->fileType);
        }

        $this->outFileName = Storage::getFullPath($this->webFilePath);
    }

    /**
     * Получение объекта
     *
     * @param bool $force
     * @param string $source
     * @param array $format
     * @param null|string $webFileName
     * @return Upload|null
     */
    public static function getObject(bool $force = false, string $source = '', array $format = [], ?string $webFileName = null)
    {
        if (self::$object === null || $force) {
            self::$object = new self($source, $format, $webFileName);
        }

        return self::$object;
    }

    /**
     * Установить размеры входного и выходного
     *
     * @param array $from
     * @param array $to
     */
    public function setSourceSizes(array $from, array $to): void
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
    public function build(): string
    {
        // создаем подложку размером с выходное и указанным цветом
        $this->image = new Imagine();
        $this->image = $this->image->create(new Box($this->format['width'], $this->format['height']), Palette::create());

        // расчитываем параметры
        $calculatedParams = Calculate::execute();

        // ресайзим исходное изображение
        $this->source->resize(new Box($calculatedParams['width'], $calculatedParams['height']));

        // собираем картинку
        $this->image->paste($this->source, new Point($calculatedParams['left_margin'], $calculatedParams['top_margin']));

        if ($this->format['watermark']['image']) {
            Watermark::create();
        }

        $this->image->save($this->outFileName);

        if ($this->format['optimize']) {
            $this->optimize();
        }

        return $this->webFilePath;
    }

    /**
     * Оптимизация картинки
     */
    private function optimize(): void
    {
        exec("jpegoptim --strip-all --all-progressive -ptm100 {$this->outFileName}");
    }
}
