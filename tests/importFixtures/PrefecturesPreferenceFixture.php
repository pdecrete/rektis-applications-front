<?php
namespace app\tests\importFixtures;

class PrefecturesPreferenceFixture extends BaseImportFixture
{
    public $tableName = '{{%prefectures_preference}}';
    public $depends = [
        'app\tests\importFixtures\PrefectureFixture',
        'app\tests\importFixtures\ApplicantFixture',
    ];

    protected function getData()
    {
        $data = $this->getSerializedData();
        return $data['prefecture_preference'];
    }
}
