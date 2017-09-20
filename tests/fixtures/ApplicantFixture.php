<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class ApplicantFixture extends ActiveFixture
{

    public $tableName = '{{%applicant}}';
    public $depends = [
        'app\tests\fixtures\ChoiceFixture'
    ];

    protected function getData()
    {
        return [
            ['vat' => '012345678', 'identity' => 'ΑΑ1234', 'specialty' => 'ΠΕ 19'],
            ['vat' => '112345678', 'identity' => 'ΒΒ5678', 'specialty' => 'ΠΕ 60'],
            ['vat' => '212345678', 'identity' => 'ΑΒ9874', 'specialty' => 'ΠΕ 60'],
        ];
    }
}
