<?php

namespace app\commands;

use app\queues\CropQueue;
use yii\console\Controller;
use yii\console\ExitCode;
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
     * @return int
     */
    public function actionAddStorage(string $name): int
    {
        $newPath = \Yii::$app->params['cdn']['outputPath'] . DIRECTORY_SEPARATOR . $name;
        FileHelper::createDirectory($newPath, 0777, true);
        
        return ExitCode::OK;
    }
}