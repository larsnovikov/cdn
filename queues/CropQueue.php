<?php

namespace app\queues;

use app\models\Format;
use app\models\Upload;
use yii\base\BaseObject;
use yii\helpers\Console;

/**
 * 
 */
class CropQueue extends BaseObject implements \yii\queue\JobInterface
{
    public $inputFile;
    public $outputFile;
    public $format;

    public function execute($queue)
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
     * 
     */
    public static function putInQueue(string $inputFile, string $outputFile, string $format)
    {
        \Yii::$app->cdnCropQueue->push(new self([
            'inputFile' => $inputFile,
            'outputFile' => $outputFile,
            'format' => $format
        ]));
    }
}
