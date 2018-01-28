<?php

namespace app\controllers;

use app\prototypes\ApiController;
use app\validators\RemoveValidator;
use app\validators\UploadValidator;
use Yii;

/**
 * Class ImageController
 * @package app\controllers
 */
class ImageController extends ApiController
{

    /**
     * Загрузка изображения
     */
    public function actionUpload()
    {
        UploadValidator::validateRequest();
    }

    /**
     * Удаление изображения
     */
    public function actionRemove()
    {
        RemoveValidator::validateRequest();
    }
}
