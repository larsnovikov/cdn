<?php

use yii\db\Migration;

/**
 * Class m180401_105713_create_format
 */
class m180401_105713_create_format extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%format}}', [
            'name' => $this->string(255)
                ->unique()
                ->notNull(),
            'data' => $this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%format}}');
    }
}
