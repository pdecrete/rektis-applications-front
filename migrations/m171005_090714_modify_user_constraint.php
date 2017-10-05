<?php

use yii\db\Migration;

class m171005_090714_modify_user_constraint extends Migration
{

    public function safeUp()
    {
        $this->dropIndex('vat', '{{%applicant}}');
        $this->dropIndex('identity', '{{%applicant}}');
        $this->createIndex('idx_vat_identity_specialty_unique', '{{%applicant}}', ['vat', 'identity', 'specialty'], true);
    }

    public function safeDown()
    {
        $this->dropIndex('idx_vat_identity_specialty_unique', '{{%applicant}}');
        $this->createIndex('vat', '{{%applicant}}', 'vat', true);
        $this->createIndex('identity', '{{%applicant}}', 'identity', true);
    }
}
