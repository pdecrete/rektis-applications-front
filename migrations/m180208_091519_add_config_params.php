<?php

use yii\db\Migration;

/**
 * Class m180208_091519_add_config_params
 */
class m180208_091519_add_config_params extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->batchInsert('{{%config}}', ['id', 'type', 'value', 'name'], [
            [2, 'int', '0', 'enable_data_load'],
            [3, 'int', '0', 'enable_data_unload'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('{{%config}}', ['id' => [2, 3]]);
    }

}
