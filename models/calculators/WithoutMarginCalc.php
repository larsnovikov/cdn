<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 10.02.2018
 * Time: 20:53
 */

namespace app\models\calculators;

/**
 * Class WithoutMarginCalc
 * @package app\models\calculators
 */
class WithoutMarginCalc implements InterfaceCalc
{
    /**
     * @return string
     */
    public static function getClassName()
    {
        return __CLASS__;
    }

    /**
     *
     */
    public function maximize()
    {

    }

    /**
     *
     */
    public function minimaze()
    {

    }

    /**
     *
     */
    public function customize()
    {

    }
}