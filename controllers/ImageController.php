<?php

namespace app\controllers;

use app\models\Upload;
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
    public function actionUpload(): array
    {
        $request = \Yii::$app->request->get();
        UploadValidator::validateRequest($request);

        $formats = json_decode($request['formats'], true);

        $out = [];

        foreach ($formats as $format) {
            $object = Upload::getObject(true, $request['source'], $format);
            $out[$format['name']] = $object->build();
        }

        return $out;
    }

    /**
     * Удаление изображения
     */
    public function actionRemove(): array 
    {
        $request = \Yii::$app->request->get();
        RemoveValidator::validateRequest($request);

        $filePath = \Yii::$app->params['cdn']['outputPath'] . DIRECTORY_SEPARATOR . $request['source'];

        unlink($filePath);

        if (file_exists($filePath)) {
            throw new \Exception('Can\'t remove file');
        }

        return [];
    }
}
