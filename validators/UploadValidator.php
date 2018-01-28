<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 13:53
 */

namespace app\validators;

use yii\base\Exception;

/**
 * Валидатор запроса для обработки изображения
 *
 * Class UploadValidator
 * @package app\validators
 */
class UploadValidator
{
    /**
     * Вадидатор массива параметров
     * Пример
     * [
     *     'min' => [
     *         'name' => 'min',
     *         'height' => 100,
     *         'width' => 150,
     *         'background' => [
     *             'color' => '000'
     *         ],
     *         'maximize' => 'mode_1',
     *         'minimaze' => 'mode_2'
     *     ],
     *     'medium' => [
     *         'name' => 'medium',
     *         'height' => 300,
     *         'width' => 400,
     *         'background' => [
     *             'color' => '000'
     *         ],
     *         'maximize' => 'mode_2',
     *         'minimaze' => 'mode_1'
     *     ]
     * ]
     * @param $formats
     * @throws Exception
     */
    private static function validateFormats($formats)
    {
        $formats = json_decode($formats, true);

        if (!$formats) {
            throw new Exception('Invalid param \'formats\'');
        }

        foreach ($formats as $format) {
            if (!array_key_exists('name', $format)) {
                throw new Exception('Format param \'name\' does not exists!');
            }

            // TODO тут должна быть валидация методов обработки
        }
    }

    /**
     * @throws Exception
     */
    public static function validateRequest()
    {
        $request = \Yii::$app->request->get();

        // проверим наличие параметра исходика
        if (!array_key_exists('source', $request)) {
            throw new Exception('Source param does not exists!');
        }

        // проверим наличие файла исходника
        $filePath = \Yii::$app->params['cdn']['inputPath'] . DIRECTORY_SEPARATOR . $request['source'];
        if (!file_exists($filePath)) {
            throw new Exception('Source file does not exists!');
        }

        // проверим наличие параметра форматов
        if (!array_key_exists('formats', $request)) {
            throw new Exception('Formats param does not exists!');
        }

        self::validateFormats($request['formats']);
    }
}