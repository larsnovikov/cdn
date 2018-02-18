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
}