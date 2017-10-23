<?php

use yii\db\Migration;

class m171018_085826_change_unique_constr_Choice_tbl extends Migration
{
	/* Initial contraint uniqueness of (specialty, position)
	 * changes to (specialty, position, prefecture_id) 
	 * (The change is necessary, as the same text of a group 
	 * of schools for a specialty could exist in two or more prefectures)
	 */
    public function safeUp()
    {
		$this->dropIndex('idx_choice_unique', '{{%choice}}');
		$this->createIndex('idx_choice_unique', '{{%choice}}', ['specialty', 'position', 'prefecture_id'], true);

    }

    public function safeDown()
    {
        $this->dropIndex('idx_choice_unique', '{{%choice}}');
        $this->createIndex('idx_choice_unique', '{{%choice}}', ['specialty', 'position'], true);    
    }

}
