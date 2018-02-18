<?php

namespace app\models;

use Yii;

class Storage
{
    /**
     * Выбор хранилища
     *
     * @return string
     */
    public static function chooseStorage()
    {
        $storagePaths = scandir(\Yii::$app->params['cdn']['outputPath'] . DIRECTORY_SEPARATOR);

        $maxSize = 0;
        $directory = '';
        foreach ($storagePaths as $path) {
            if ($path === '.' || $path === '..') {
                continue;
            }
            $storePath = \Yii::$app->params['cdn']['outputPath'] . DIRECTORY_SEPARATOR . $path;
            $pathFreeSpace = disk_free_space($storePath);
            if ($pathFreeSpace > $maxSize) {
                $maxSize = $pathFreeSpace;
                $directory = DIRECTORY_SEPARATOR . $path;
            }
        }

        return $directory;
    }

    /**
     * @param $path
     * @return string
     */
    public static function getFullPath($path)
    {
        return \Yii::$app->params['cdn']['outputPath'] . DIRECTORY_SEPARATOR . $path;
    }
}

