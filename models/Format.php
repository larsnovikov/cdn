<?php

namespace app\models;

/**
 * This is the model class for table "format".
 *
 * @property string $name
 * @property string $data
 */
class Format extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%format}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['data'], 'string']
        ];
    }
}
