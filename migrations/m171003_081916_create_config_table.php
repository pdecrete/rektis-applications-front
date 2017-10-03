<?php

use yii\db\Migration;

/**
 * Handles the creation of table `config`.
 */
class m171003_081916_create_config_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%config}}', [
            'id' => $this->primaryKey(),
            'enable_applications' => $this->boolean()->notNull()->defaultValue(false)
        ]);
        
        $this->insert('{{%config}}', ['id' => 1, 'enable_applications' => TRUE]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('config');
    }
}
