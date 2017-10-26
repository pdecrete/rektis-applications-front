<?php

use yii\db\Migration;

class m171025_092922_ApplicantsPoints extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%applicant}}', 'points', $this->decimal(9, 3));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%applicant}}', 'points');
    }
}
