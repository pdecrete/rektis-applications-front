<?php

use yii\db\Migration;

class m170919_103746_initdb extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%applicant}}', [
            'id' => $this->primaryKey(),
            'vat' => $this->string(9)->notNull()->unique(),
            'identity' => $this->string(32)->notNull()->unique(),
            'specialty' => $this->string(8)->notNull()
            ], $tableOptions);

        $this->createTable('{{%choice}}', [
            'id' => $this->primaryKey(),
            'specialty' => $this->string(8)->notNull(),
            'count' => $this->smallInteger()->notNull(),
            'position' => $this->string(500)->notNull(),
            'reference' => $this->text()->notNull()
            ], $tableOptions);
        $this->createIndex('idx_choice_unique', '{{%choice}}', ['specialty', 'position'], true);

        $this->createTable('{{%application}}', [
            'id' => $this->primaryKey(),
            'applicant_id' => $this->integer(),
            'choice_id' => $this->integer(),
            'order' => $this->smallInteger()->notNull(),
            'updated' => $this->integer()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false)
            ], $tableOptions);
        $this->addForeignKey('fk_applicant_id', '{{%application}}', 'applicant_id', '{{%applicant}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_choice_id', '{{%application}}', 'choice_id', '{{%choice}}', 'id', 'SET NULL', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%application}}');
        $this->dropTable('{{%choice}}');
        $this->dropTable('{{%applicant}}');
    }
}
