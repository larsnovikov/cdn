<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 10.02.2018
 * Time: 20:52
 */

namespace app\models\calculators;

/**
 * Interface InterfaceCalc
 * @package app\models\calculators
 */
interface InterfaceCalc
{
    /**
     * @return mixed
     */
    public function maximize();

    /**
     * @return mixed
     */
    public function minimaze();

    /**
     * @return mixed
     */
    public function customize();

    public function beforeExecution();
}