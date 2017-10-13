<?php

use yii\db\Migration;

class m171005_115335_applicants_prefectures_association extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%prefectures_preference}}', [
            'id' => $this->primaryKey(),
            'prefect_id' => $this->integer(),
            'applicant_id' => $this->integer(),
            'order' => $this->smallInteger()->notNull()
        ]);		
        $this->addForeignKey('fk_prefect_preference_applicant_id', '{{%prefectures_preference}}', 'applicant_id', '{{%applicant}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_prefect_preference_id', '{{%prefectures_preference}}', 'prefect_id', '{{%prefecture}}', 'id', 'SET NULL', 'CASCADE');

    }

    public function safeDown()
    {
        $this->dropTable('{{%prefectures_preference}}');
    }
}
