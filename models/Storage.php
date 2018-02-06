<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "storage".
 *
 * @property int $id
 * @property string $name
 * @property int $free_size
 * @property string $time
 */
class Storage extends \yii\db\ActiveRecord
{

    const CACHE_TIME = 60;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'storage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['free_size'], 'integer'],
            [['time'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'free_size' => 'Free Size',
            'time' => 'Time',
        ];
    }

    /**
     * Выбор хранилища
     *
     * @return string
     */
    public static function chooseStorage()
    {
        $storage = self::find();

        $maxSize = 0;
        $directory = '';
        foreach ($storage->each() as $storageItem) {
            $path = \Yii::$app->params['cdn']['outputPath'] . DIRECTORY_SEPARATOR . $storageItem->name;

            if (time() > $storageItem->time + self::CACHE_TIME) {
                $storageItem->free_size = disk_free_space($path);
                $storageItem->save();
            }
            if ($storageItem->free_size > $maxSize) {
                $maxSize = $storageItem->free_size;
                $directory = $path;
            }
        }

        return $directory;
    }
}

