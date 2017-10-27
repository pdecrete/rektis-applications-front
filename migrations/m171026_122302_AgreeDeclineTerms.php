<?php

use yii\db\Migration;

class m171026_122302_AgreeDeclineTerms extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%applicant}}', 'agreedterms', $this->integer()->defaultValue(null));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%applicant}}', 'agreedterms');
    }
}
