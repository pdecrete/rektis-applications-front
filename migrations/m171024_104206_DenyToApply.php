<?php

use yii\db\Migration;

class m171024_104206_DenyToApply extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%applicant}}', 'state', $this->integer()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%applicant}}', 'state');
    }
}
