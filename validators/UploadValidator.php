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
     * @var array
     */
    private static $validFileTypes = [
        'image/jpeg'
    ];

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
     *         'margins' => true,
     *         'optimize' => true
     *     ],
     *     'medium' => [
     *         'name' => 'medium',
     *         'height' => 300,
     *         'width' => 400,
     *         'background' => [
     *             'color' => '000'
     *         ],
     *         'margins' => false,
     *         'optimize' => true
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

            // проверки на наличие параметров в запросе
            if (!array_key_exists('name', $format)) {
                throw new Exception('Format param \'name\' does not exists!');
            }
            if (!array_key_exists('height', $format)) {
                throw new Exception('Format param \'height\' does not exists!');
            }
            if (!array_key_exists('width', $format)) {
                throw new Exception('Format param \'width\' does not exists!');
            }
            if (!array_key_exists('background', $format)) {
                throw new Exception('Format param \'background\' does not exists!');
            }
            if (!array_key_exists('margins', $format)) {
                throw new Exception('Format param \'margins\' does not exists!');
            }
            if (!array_key_exists('optimize', $format)) {
                throw new Exception('Format param \'optimize\' does not exists!');
            }

            // проверки на валидность
            if (!is_int($format['width'])) {
                throw new Exception('Format param \'width\' must be int');
            }
            if (!is_int($format['height'])) {
                throw new Exception('Format param \'height\' must be int');
            }
            if (!is_bool($format['margins'])) {
                throw new Exception('Format param \'margins\' must be boolean');
            }
            if (!is_bool($format['optimize'])) {
                throw new Exception('Format param \'optimize\' must be boolean');
            }
        }
    }

    /**
     * @throws Exception
     */
    public static function validateRequest($request)
    {
        // проверим наличие параметра исходика
        if (!array_key_exists('source', $request)) {
            throw new Exception('Source param does not exists!');
        }

        // проверим наличие файла исходника
        $filePath = \Yii::$app->params['cdn']['inputPath'] . DIRECTORY_SEPARATOR . $request['source'];
        if (!file_exists($filePath)) {
            throw new Exception('Source file does not exists!');
        }

        if (!in_array(mime_content_type($filePath), self::$validFileTypes)) {
            throw new Exception('Invalid mime type of source file!');
        }

        // проверим наличие параметра форматов
        if (!array_key_exists('formats', $request)) {
            throw new Exception('Formats param does not exists!');
        }

        self::validateFormats($request['formats']);
    }
}
