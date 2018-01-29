<?php

use yii\db\Migration;

/**
 * Class m180129_181509_storage
 */
class m180129_181509_storage extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('storage', [
            'id' => $this->primaryKey(10),
            'name' => $this->string(255),
            'free_size' => $this->integer(10),
            'time' => $this->timestamp()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('storage');
    }
}
