<?php

namespace app\commands;

use yii\console\Controller;
use yii\helpers\FileHelper;

/**
 * Class CommandController
 * @package app\commands
 */
class CommandController extends Controller
{
    /**
     * @param $name
     * @throws \yii\base\Exception
     */
    public function actionAddStorage($name)
    {
        $newPath = \Yii::$app->params['cdn']['outputPath'] . DIRECTORY_SEPARATOR . $name;
        FileHelper::createDirectory($newPath, true);

        // TODO примонтировать директорию

    }

    /**
     * Инициализация
     */
    public function actionInit(string $inputPath = '/var/www/input',
                               string $outputPath = '/var/www/output',
                               string $watermarkPath = '/var/www/watermark'): int
    {
        if (!file_exists($inputPath)) {
            FileHelper::createDirectory($inputPath);
        }

        if (!file_exists($outputPath)) {
            FileHelper::createDirectory($outputPath, 0777);
        }

        if (!file_exists($watermarkPath)) {
            FileHelper::createDirectory($watermarkPath);
        }

        $configTpl = file_get_contents(\Yii::getAlias('@app/config/cdn-local.tpl.php'));

        $content = str_replace([
            '{{inputPath}}',
            '{{outputPath}}',
            '{{watermarkPath}}'
        ], [
            $inputPath,
            $outputPath,
            $watermarkPath
        ], $configTpl);

        $out = fopen(\Yii::getAlias('@app/config/cdn-local.php'), 'w');
        fwrite($out, $content);
        fclose($out);

        return self::EXIT_CODE_NORMAL;
    }
}