<?php

use yii\db\Migration;

/**
 * Class m180218_081755_remove_storage
 */
class m180218_081755_remove_storage extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropTable('storage');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->createTable('storage', [
            'id' => $this->primaryKey(10),
            'name' => $this->string(255),
            'free_size' => $this->integer(10),
            'time' => $this->timestamp()
        ]);
    }
}
