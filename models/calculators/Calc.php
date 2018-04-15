<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 10.02.2018
 * Time: 20:52
 */

namespace app\models\calculators;

/**
 * Class Calc
 * @package app\models\calculators
 */
abstract class Calc
{
    /**
     * Предобработка
     *
     * @return mixed
     */
     public function beforeExecution()
     {}

    /**
     * Увеличение
     */
     abstract public function maximize(): void;

    /**
     * Уменьшение
     */
    abstract public function minimaze(): void;

    /**
     * Особое изменение размеров
     */
    abstract public function customize(): void;
}