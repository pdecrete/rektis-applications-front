<?php

use yii\db\Migration;

class m171023_072637_ref_for_applicants extends Migration
{

    public function safeUp()
    {
        $this->addColumn('{{%applicant}}', 'reference', $this->text()->notNull()); // για αναφορά στο backend σύστημα και άλλα στοιχεία
    }

    public function safeDown()
    {
        $this->dropColumn('{{%applicant}}', 'reference');
    }
}
