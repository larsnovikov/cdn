<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 13:54
 */

namespace app\validators;

use app\models\Format;
use app\models\Watermark;
use yii\base\Exception;

/**
 * Валидатор для добавления формата изображения
 *
 * Class AddFormatValidator
 * @package app\validators
 */
class AddFormatValidator
{
    /**
     * @param array $request
     * @throws Exception
     */
    public static function validateRequest(array &$request): void
    {
        // проверим наличие параметра форматов
        if (!array_key_exists('format', $request)) {
            throw new Exception('Format param does not exists!');
        }

        $format = json_decode($request['format'], true);

        if (!$format) {
            throw new Exception('Invalid param \'format\'');
        }

        // проверки на наличие параметров в запросе
        if (!array_key_exists('name', $format)) {
            throw new Exception('Format param \'name\' does not exists!');
        }

        $isFormatExists = Format::find()
            ->where(['name' => $format['name']])
            ->exists();

        if ($isFormatExists) {
            throw new Exception('Format with same name already exists');
        }
        
        $request['name'] = $format['name'];
        
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

        // проверка вотермарка
        if (array_key_exists('watermark', $format)) {
            if (!array_key_exists('image', $format['watermark'])) {
                throw new Exception('Watermark param \'image\' does not exists!');
            }
            if (!array_key_exists('width', $format['watermark'])) {
                throw new Exception('Watermark param \'width\' does not exists!');
            }
            if (!array_key_exists('height', $format['watermark'])) {
                throw new Exception('Watermark param \'height\' does not exists!');
            }
            if (!array_key_exists('position', $format['watermark'])) {
                throw new Exception('Watermark param \'position\' does not exists!');
            }

            // проверка файла
            $filePath = \Yii::$app->params['cdn']['watermarkPath'] . DIRECTORY_SEPARATOR . $format['watermark']['image'];
            if (!file_exists($filePath)) {
                throw new Exception('Watermark source file does not exists!');
            }

            if (!in_array(mime_content_type($filePath), Watermark::TYPES)) {
                throw new Exception('Invalid mime type of watermark source file!');
            }

            // проверка правильно ли указана позиция
            if (!in_array($format['watermark']['position'], Watermark::POSITIONS)) {
                throw new Exception('Watermark param \'position\' is not valid');
            }
        }
    }
}
