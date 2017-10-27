<?php
namespace app\tests\importFixtures;

use Yii;
use yii\test\ActiveFixture;

class BaseImportFixture extends ActiveFixture
{
    protected function getSerializedData()
    {
        if (($defined_base = Yii::getAlias('@import-data-dir', false)) === false) {
            //            $defined_base = '@app/commands/data-files';
            $defined_base = '@app/non-existent';
        }
        $serialized_data = file_get_contents(Yii::getAlias("{$defined_base}/data.serialized.txt"));
        $data = unserialize($serialized_data);
        return $data;
    }
}
