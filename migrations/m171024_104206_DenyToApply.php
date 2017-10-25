<?php

use yii\db\Migration;

class m171024_104206_DenyToApply extends Migration
{
    public function safeUp()
    {
		//Applicant states are defined in config/params.php file
        $this->addColumn('{{%applicant}}', 'state', $this->integer()->defaultValue(NULL));
        
/*        $this->createTable('{{%status}}', [
            'id' => $this->primaryKey(),
            'state' => $this->string(100)->notNull()
        ]);

        $this->insert('{{%status}}', ['id' => 1, 'state' => 'DENIED_TO_APPLY']);
        
        $this->addForeignKey('fk_applicant_status_id', '{{%applicant}}', 'state', '{{%status}}', 'id', 'SET NULL', 'CASCADE'); */
    }

    public function safeDown()
    {
		//$this->dropForeignKey('fk_applicant_status_id', '{{%applicant}}');
        //$this->dropTable('{{%status}}'); 
        $this->dropColumn('{{%applicant}}', 'state');
    }
}
