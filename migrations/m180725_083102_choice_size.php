<?php

use yii\db\Migration;

/**
 * Class m180725_083102_choice_size
 */
class m180725_083102_choice_size extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%choice}}', 'position', $this->string(300)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%choice}}', 'position', $this->string(150)->notNull());
    }
}
