<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class ChoiceFixture extends ActiveFixture
{

    public $tableName = '{{%choice}}';

    protected function getData()
    {
        $common_ref = json_encode([
            'sid' => 1,
            'pid' => 100,
            'msg' => 'None'
        ]);
        return [
            ['specialty' => 'ΠΕ 19', 'count' => 1, 'position' => 'Σχολείο #1 και Σχολείο #2', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 19', 'count' => 2, 'position' => 'Σχολείο #3', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 60', 'count' => 2, 'position' => 'Σχολείο #3', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 60', 'count' => 2, 'position' => 'Σχολείο #2', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 60', 'count' => 1, 'position' => 'Σχολείο #1', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 70', 'count' => 1, 'position' => 'Σχολείο #1', 'reference' => $common_ref],
        ];
    }
}