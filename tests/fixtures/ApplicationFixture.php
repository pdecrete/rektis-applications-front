<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class ApplicationFixture extends ActiveFixture
{

    public $tableName = '{{%application}}';
    public $depends = [
        'app\tests\fixtures\ChoiceFixture',
        'app\tests\fixtures\PrefecturesPreferenceFixture',
    ];

    protected function getData()
    {
        return [
        ];
    }
}
