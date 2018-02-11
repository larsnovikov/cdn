<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 13:54
 */

namespace app\validators;

use yii\base\Exception;

/**
 * Валидатор для удаления изображения
 *
 * Class RemoveValidator
 * @package app\validators
 */
class RemoveValidator
{
    /**
     * @param $request
     * @throws Exception
     */
    public static function validateRequest($request)
    {
        // проверим наличие параметра исходика
        if (!array_key_exists('source', $request)) {
            throw new Exception('Source param does not exists!');
        }

        $filePath  = \Yii::$app->params['cdn']['outputPath'] . DIRECTORY_SEPARATOR . $request['source'];
        if (!file_exists($filePath)) {
            throw new Exception('File not found');
        }
    }
}
