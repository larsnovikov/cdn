<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 13:54
 */

namespace app\validators;

use app\models\Format;
use yii\base\Exception;

/**
 * Валидатор для удаления формата
 *
 * Class RemoveFormatValidator
 * @package app\validators
 */
class RemoveFormatValidator
{
    /**
     * @param array $request
     * @throws Exception
     */
    public static function validateRequest(array $request): void
    {
        if (!array_key_exists('name', $request)) {
            throw new Exception('Name param does not exists!');
        }

        $isFormatExists = Format::find()
            ->where(['name' => $request['name']])
            ->exists();

        if (!$isFormatExists) {
            throw new Exception('Format with same name does\'not exists');
        }
    }
}
