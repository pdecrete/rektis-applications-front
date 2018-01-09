<?php

use yii\db\Migration;

/**
 * Class m180109_101650_denydate
 */
class m180109_101650_denydate extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%applicant}}', 'statets', $this->integer()->defaultValue(null));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%applicant}}', 'statets');
    }
}
