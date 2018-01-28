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

        if (!file_exists($inputPath)) {
            \yii\helpers\FileHelper::createDirectory($inputPath);
        }

        if (!file_exists($outputPath)) {
            \yii\helpers\FileHelper::createDirectory($outputPath);
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
