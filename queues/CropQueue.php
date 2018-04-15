<?php

namespace app\queues;

use app\models\Format;
use app\models\Upload;
use yii\base\BaseObject;
use yii\helpers\Console;

/**
 * Class CropQueue
 * @package app\queues
 */
class CropQueue extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * исходник
     * @var string
     */
    public $inputFile;

    /**
     * Выходной файл
     * @var string
     */
    public $outputFile;

    /**
     * Название формата
     * @var string
     */
    public $format;


    /**
     * Обработчик очереди
     * @param string $queue
     */
    public function execute($queue): void
    {
        $format = Format::find()
            ->where([
                'name' => $this->format
            ])
            ->asArray()
            ->one();

        $object = Upload::getObject(true, $this->inputFile, json_decode($format['data'], true), $this->outputFile);
        $outFile = $object->build();

        Console::output("Created: $outFile");
    }

    /**
     * Положить в очередь
     *
     * @param string $inputFile
     * @param string $outputFile
     * @param string $format
     */
    public static function putInQueue(string $inputFile, string $outputFile, string $format): void
    {
        \Yii::$app->cdnCropQueue->push(new self([
            'inputFile' => $inputFile,
            'outputFile' => $outputFile,
            'format' => $format
        ]));
    }
}
