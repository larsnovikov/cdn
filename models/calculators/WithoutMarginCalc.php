<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 10.02.2018
 * Time: 20:53
 */

namespace app\models\calculators;
use app\helpers\Calculate;
use app\models\Image;
use app\models\UploadRequestStorage;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Imagick\Imagine;

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
        var_dump(111);
        Calculate::$params['param_1'] = Calculate::$toParams['width'];
        Calculate::$params['param_2'] = Calculate::$toParams['height'];
    }

    /**
     *
     */
    public function minimaze()
    {
        var_dump(222);
        Calculate::$params['param_1'] = Calculate::$toParams['width'];
        Calculate::$params['param_2'] = Calculate::$toParams['height'];
    }

    /**
     *
     */
    public function customize()
    {
        var_dump(333);
        Calculate::$params['param_1'] = Calculate::$toParams['width'];
        Calculate::$params['param_2'] = Calculate::$toParams['height'];
    }

    public function beforeExecution()
    {
        $file = 'C:\OpenServer\domains\cdn.loc\directory\output\tmp/'.time().rand().'.jpg';
        $mode    = ImageInterface::THUMBNAIL_OUTBOUND;
        $imagine = new Imagine();
        $size    = new Box(UploadRequestStorage::getObject()->toParams['width'], UploadRequestStorage::getObject()->toParams['height']);
        $imagine->open(UploadRequestStorage::getObject()->sourcePath)
            ->thumbnail($size, $mode)
            ->save($file);

        UploadRequestStorage::getObject()->sourcePath = $file;
    }
}