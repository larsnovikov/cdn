<?php

namespace app\models;

use Yii;

/**
 * Class Storage
 * @package app\models
 */
class Storage
{
    /**
     * Выбор хранилища
     *
     * @return string
     */
    public static function chooseStorage(): string 
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
     * @param  string $path
     * @return string
     */
    public static function getFullPath(string $path): string
    {
        return \Yii::$app->params['cdn']['outputPath'] . DIRECTORY_SEPARATOR . $path;
    }
}
