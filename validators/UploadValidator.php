<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 13:53
 */

namespace app\validators;

use app\models\Watermark;
use yii\base\Exception;
use yii\httpclient\Client;

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
        'image/jpeg',
        'image/png'
    ];

    /**
     * @var array
     */
    private static $validWatermarkTypes = [
        'image/png'
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
     *         'optimize' => true,
     *         'watermark' => [
     *             'image' => '',
     *             'width' => 500,
     *             'height' => 500,
     *             'position' => 'pos_center'
     *         ]
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
     * @param string $formats
     * @throws Exception
     */
    private static function validateFormats(string $formats): void
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

                if (!in_array(mime_content_type($filePath), self::$validWatermarkTypes)) {
                    throw new Exception('Invalid mime type of watermark source file!');
                }

                // проверка правильно ли указана позиция
                if (!in_array($format['watermark']['position'], Watermark::POSITIONS)) {
                    throw new Exception('Watermark param \'position\' is not valid');
                }
            }
        }
    }

    /**
     * @param array $request
     * @throws Exception
     */
    public static function validateRequest(array &$request): void
    {
        // проверим наличие параметра исходика
        if (!array_key_exists('source', $request)) {
            throw new Exception('Source param does not exists!');
        }

        // Если передана ссылка на файл, его надо подтянуть
        if (filter_var($request['source'], FILTER_VALIDATE_URL)) {
            $request['source'] = self::getFromUrl($request['source']);
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

    /**
     * Получить картинку с URL
     *
     * @param string $url
     * @return string
     */
    private static function getFromUrl(string $url): string
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('get')
            ->setUrl($url)
            ->send();

        if (!$response->isOk) {
            throw new Exception('Can\t load file from url');
        }

        $fileName = time() . '_' . rand(0, 9999) . '.jpg';

        // сохраним картинку к себе
        file_put_contents(\Yii::$app->params['cdn']['inputPath'] . DIRECTORY_SEPARATOR . $fileName, $response->getContent());

        return $fileName;
    }
}
