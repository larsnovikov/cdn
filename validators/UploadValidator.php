<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 13:53
 */

namespace app\validators;

use app\models\Format;
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

        $formatNames = json_decode($request['formats'], true);

        if (!$formatNames) {
            throw new Exception('Invalid param \'formats\'');
        }

        $formatNames = array_unique($formatNames);

        $formats = Format::find()
            ->where([
                'name' => $formatNames
            ])
            ->asArray()
            ->all();

        $outFormats = [];
        foreach ($formats as $format) {
            $outFormats[] = json_decode($format['data'], true);
        }

        $request['formats'] = $outFormats;
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

        $fileName = time() . '_' . rand(0, 99999) . '.jpg';

        // сохраним картинку к себе
        file_put_contents(\Yii::$app->params['cdn']['inputPath'] . DIRECTORY_SEPARATOR . $fileName, $response->getContent());

        return $fileName;
    }
}
