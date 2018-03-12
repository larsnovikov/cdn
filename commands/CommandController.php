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
     * Добавление хранилища
     * 
     * @param string $name
     * @throws \yii\base\Exception
     * @return void
     */
    public function actionAddStorage(string $name): void
    {
        $newPath = \Yii::$app->params['cdn']['outputPath'] . DIRECTORY_SEPARATOR . $name;
        FileHelper::createDirectory($newPath, 0777, true);

        // TODO примонтировать директорию

    }

    /**
     * Инициализация
     *
     * @param string $inputPath
     * @param string $outputPath
     * @param string $watermarkPath
     * @return int
     */
    public function actionInit(string $inputPath = '/var/www/input',
                               string $outputPath = '/var/www/output',
                               string $watermarkPath = '/var/www/watermark'): int
    {
        if (!file_exists($inputPath)) {
            FileHelper::createDirectory($inputPath, true);
        }

        if (!file_exists($outputPath)) {
            FileHelper::createDirectory($outputPath, 0777, true);
        }

        if (!file_exists($watermarkPath)) {
            FileHelper::createDirectory($watermarkPath, true);
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

    /**
     * Добавление списка фронтендов
     *
     * @param string $frontends
     * @return int
     */
    public function actionAddFrontends(string $frontends): int
    {
        $frontends = explode(',', $frontends);

        $outFrontends = [];
        foreach ($frontends as $frontend) {
            $outFrontends[$frontend] = [];
        }

        $configTpl = file_get_contents(\Yii::getAlias('@app/config/cdn-local.php'));

        $content = str_replace([
            '{{frontends}}'
        ], [
            json_encode($outFrontends)
        ], $configTpl);

        $out = fopen(\Yii::getAlias('@app/config/cdn-local.php'), 'w');
        fwrite($out, $content);
        fclose($out);

        return self::EXIT_CODE_NORMAL;
    }
}