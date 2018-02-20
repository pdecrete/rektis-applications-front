<?php

use yii\db\Migration;

/**
 * Class m180220_075922_add_school_type
 */
class m180220_075922_add_school_type extends Migration
{
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();
        $this->addColumn(
            '{{%choice}}',
            'school_type',
        $this->smallInteger()
            ->notNull()
            ->defaultValue(1)
            ->after('[[position]]')
            ->comment('1 for SCH.UNITS, 2 for KEDDY')
        );
        $this->addColumn(
            '{{%prefectures_preference}}',
            'school_type',
        $this->smallInteger()
            ->notNull()
            ->defaultValue(0)
            ->after('[[applicant_id]]')
            ->comment('0 for ANY, 1 for SCH.UNITS, 2 for KEDDY')
        );
    }

    public function safeDown()
    {
        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();
        $this->dropColumn('{{%prefectures_preference}}', 'school_type');
        $this->dropColumn('{{%choice}}', 'school_type');
    }
}
