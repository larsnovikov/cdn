<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 13:54
 */

namespace app\validators;


/**
 * Валидатор для удаления изображения
 *
 * Class RemoveValidator
 * @package app\validators
 */
class RemoveValidator
{
    public static function validateRequest()
    {
        $request = \Yii::$app->request->get();
        var_dump($request); exit;
    }
}
