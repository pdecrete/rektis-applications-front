<?php
namespace app\components;

use Yii;
use yii\base\Component;

/**
 * Helper functions for administrative purposes
 */
class AdminHelper extends Component
{

    /**
     * @inheritdoc
     *
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Clears application data
     *
     * @return boolean|string returns boolean true on success or an error message on failure
     */
    public function clearData()
    {
        $cleared = 'Unknown error';

        Yii::trace('Clearing data', 'admin');
        try {
            \Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();
            \Yii::$app->db->createCommand()->truncateTable('{{%prefectures_preference}}')->execute();
            \Yii::$app->db->createCommand()->truncateTable('{{%application}}')->execute();
            \Yii::$app->db->createCommand()->truncateTable('{{%choice}}')->execute();
            \Yii::$app->db->createCommand()->truncateTable('{{%applicant}}')->execute();
            \Yii::$app->db->createCommand()->truncateTable('{{%prefecture}}')->execute();
            \Yii::$app->db->createCommand()->truncateTable('{{%audit_log}}')->execute();
            \Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
            Yii::info('Cleared data', 'admin');
            $cleared = true;
        } catch (\Exception $e) {
            Yii::error('Data not cleared due to error: ' . $e->getMessage(), 'admin');
            $cleared = $e->getMessage();
        }

        return $cleared;
    }
}
