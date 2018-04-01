<?php

namespace app\controllers;

use app\models\Format;
use app\models\Upload;
use app\prototypes\ApiController;
use app\validators\AddFormatValidator;
use app\validators\RemoveFormatValidator;
use app\validators\RemoveValidator;
use app\validators\UploadValidator;

/**
 * Class ImageController
 * @package app\controllers
 */
class ImageController extends ApiController
{
    /**
     * Загрузка изображения
     * 
     * @return array
     */
    public function actionUpload(): array
    {
        $request = \Yii::$app->request->get();
        UploadValidator::validateRequest($request);

        $formats = $request['formats'];

        $out = [];

        foreach ($formats as $format) {
            $object = Upload::getObject(true, $request['source'], $format);
            $out[$format['name']] = $object->build();
        }

        return $out;
    }

    /**
     * Добавление формата
     *
     * @throws \Exception
     * @return array
     */
    public function actionAddFormat(): array
    {
        $request = \Yii::$app->request->get();
        AddFormatValidator::validateRequest($request);
        
        $format = new Format();
        $format->name = $request['name'];
        $format->data = $request['format'];
        $format->save();
        
        return [
            'message' => 'Format created'
        ];
    }

    /**
     * Удаление формата
     *
     * @throws \Exception
     * @return array
     */
    public function actionRemoveFormat(): array
    {
        $request = \Yii::$app->request->get();
        RemoveFormatValidator::validateRequest($request);

        Format::deleteAll(['name' => $request['name']]);

        return [
            'message' => 'Format deleted'
        ];
    }

    /**
     * Список форматов
     *
     * @throws \Exception
     * @return array
     */
    public function actionGetFormats(): array
    {
        return [
            'message' => Format::find()
                ->asArray()
                ->all()
        ];
    }

    /**
     * Удаление изображения
     * 
     * @throws \Exception
     * @return array
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
