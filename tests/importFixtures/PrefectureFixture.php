<?php
namespace app\tests\importFixtures;

class PrefectureFixture extends BaseImportFixture
{
    public $tableName = '{{%prefecture}}';

    protected function getData()
    {
        $data = $this->getSerializedData();
        return $data['prefecture'];
    }
}
