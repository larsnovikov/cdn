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