<?php
namespace app\tests\importFixtures;

class ChoiceFixture extends BaseImportFixture
{
    public $tableName = '{{%choice}}';
    public $depends = [
        'app\tests\importFixtures\PrefectureFixture'
    ];

    protected function getData()
    {
        $data = $this->getSerializedData();
        return $data['choices'];
    }
}
