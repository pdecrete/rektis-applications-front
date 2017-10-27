<?php
namespace app\tests\importFixtures;

class ApplicationFixture extends BaseImportFixture
{
    public $tableName = '{{%application}}';
    public $depends = [
        'app\tests\importFixtures\ChoiceFixture',
        'app\tests\importFixtures\PrefecturesPreferenceFixture',
    ];

    protected function getData()
    {
        return [
        ];
    }
}
