<?php
/**
 * Created by PhpStorm.
 * User: Lars
 * Date: 28.01.2018
 * Time: 14:40
 */

namespace app\models;

use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Imagick\Imagine;

/**
 * Class Watermark
 * @package app\models
 */
class Watermark
{
    /** сверху слева */
    const POSITION_TOP_LEFT = 'pos_top_left';
    /** сверху справа */
    const POSITION_TOP_RIGHT = 'pos_top_right';
    /** снизу слева */
    const POSITION_BOTTOM_LEFT = 'pos_bottom_left';
    /** снизу справа */
    const POSITION_BOTTOM_RIGHT = 'pos_bottom_right';
    /** прямо по центру */
    const POSITION_CENTER = 'pos_center';

    /**
     * Возможные позиции
     */
    const POSITIONS = [
        self::POSITION_TOP_LEFT,
        self::POSITION_TOP_RIGHT,
        self::POSITION_BOTTOM_LEFT,
        self::POSITION_BOTTOM_RIGHT,
        self::POSITION_CENTER
    ];

    /**
     * @return \Imagine\Gd\Image|\Imagine\Image\ImageInterface
     */
    public static function create($palette)
    {
        $watermarkParams = Upload::getObject()->watermarkParams;

        $resizeWidth = $watermarkParams['width'];
        $resizeHeight = $watermarkParams['height'];

        $watermarkPath = \Yii::$app->params['cdn']['watermarkPath'] . DIRECTORY_SEPARATOR . $watermarkParams['image'];
        $imagine = new Imagine();
        $watermark = $imagine->open($watermarkPath)->resize(new Box($resizeWidth, $resizeHeight));

        /** @var \Imagine\Imagick\Image $palette */
        $size = $palette->getSize();
        $wSize = $watermark->getSize();

        switch ($watermarkParams['position']) {
            case self::POSITION_TOP_LEFT:
                $leftMargin = 0;
                $topMargin = 0;
                break;
            case self::POSITION_TOP_RIGHT:
                $leftMargin = $size->getWidth() - $wSize->getWidth();
                $topMargin = 0;
                break;
            case self::POSITION_BOTTOM_LEFT:
                $leftMargin = 0;
                $topMargin = $size->getHeight() - $wSize->getHeight();
                break;
            case self::POSITION_BOTTOM_RIGHT:
                $leftMargin = $size->getWidth() - $wSize->getWidth();
                $topMargin = $size->getHeight() - $wSize->getHeight();
                break;
            case self::POSITION_CENTER:
                $leftMargin = ($size->getWidth() - $wSize->getWidth()) / 2;
                $topMargin = ($size->getHeight() - $wSize->getHeight()) / 2;
                break;
        }

        $position = new Point($leftMargin, $topMargin);

        $palette->paste($watermark, $position);
    }
}
