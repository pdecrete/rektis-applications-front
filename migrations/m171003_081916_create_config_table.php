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
            'name' => $this->string(256)->notNull(),
            'type' => $this->string(256)->notNull()->defaultValue('string'),
            'value' => $this->string(512)->notNull()->defaultValue('')
        ]);

        $this->insert('{{%config}}', [
            'id' => 1,
            'name' => 'enable_applications',
            'type' => 'int',
            'value' => '1'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%config}}');
    }
}
