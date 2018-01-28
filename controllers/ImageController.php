<?php

namespace app\controllers;

use app\models\Image;
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

        $request = Yii::$app->request->get();

        $formats = json_decode($request['formats'], true);

        $out = [];

        foreach ($formats as $format) {
            $image = new Image($request['source'], $format);
            $image->saveImage();
            $out[$format['name']] = $image->getFilePath();
        }

        return $out;
    }

    /**
     * Удаление изображения
     */
    public function actionRemove()
    {
        RemoveValidator::validateRequest();
    }
}
