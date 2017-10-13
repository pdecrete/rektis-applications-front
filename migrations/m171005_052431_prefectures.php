<?php

use yii\db\Migration;

/**
 * Incorporate prefectures into applications.
 *
 */
class m171005_052431_prefectures extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%prefecture}}', [
            'id' => $this->primaryKey(),
            'region' => $this->string(150)->notNull()->defaultValue(''),
            'prefecture' => $this->string(150)->notNull()->unique(),
            'reference' => $this->text()->notNull() // για αναφορά στο backend σύστημα 
            ], $tableOptions);

        $this->batchInsert('{{%prefecture}}', ['region', 'prefecture', 'reference'], [
            ['ΚΡΗΤΗΣ', 'ΗΡΑΚΛΕΙΟΥ', '{sid: -1}'],
            ['ΚΡΗΤΗΣ', 'ΛΑΣΙΘΙΟΥ', '{sid: -1}'],
            ['ΚΡΗΤΗΣ', 'ΡΕΘΥΜΝΟΥ', '{sid: -1}'],
            ['ΚΡΗΤΗΣ', 'ΧΑΝΙΩΝ', '{sid: -1}'],
        ]);

        $this->addColumn('{{%choice}}', 'prefecture_id', $this->integer());
        $this->addForeignKey('fk_prefecture_id', '{{%choice}}', 'prefecture_id', '{{%prefecture}}', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
		$this->dropForeignKey('fk_prefecture_id', '{{%choice}}');
        $this->dropColumn('{{%choice}}', 'prefecture_id');
        $this->dropTable('{{%prefecture}}');
    }
}
