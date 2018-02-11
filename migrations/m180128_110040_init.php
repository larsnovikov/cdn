<?php

use yii\db\Migration;

/**
 * Class m180128_110040_init
 */
class m180128_110040_init extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $inputPath = Yii::$app->params['cdn']['inputPath'];
        $outputPath = Yii::$app->params['cdn']['outputPath'];
        $watermarkPath = Yii::$app->params['cdn']['watermarkPath'];

        if (!file_exists($inputPath)) {
            \yii\helpers\FileHelper::createDirectory($inputPath);
        }

        if (!file_exists($outputPath)) {
            \yii\helpers\FileHelper::createDirectory($outputPath);
        }

        if (!file_exists($watermarkPath)) {
            \yii\helpers\FileHelper::createDirectory($watermarkPath);
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $inputPath = Yii::$app->params['cdn']['inputPath'];
        $outputPath = Yii::$app->params['cdn']['outputPath'];

        if (file_exists($inputPath)) {
            \yii\helpers\FileHelper::removeDirectory($inputPath);
        }

        if (file_exists($outputPath)) {
            \yii\helpers\FileHelper::removeDirectory($outputPath);
        }
    }
}
