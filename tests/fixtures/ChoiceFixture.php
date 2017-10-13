<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class ChoiceFixture extends ActiveFixture
{

    public $tableName = '{{%choice}}';
    public $depends = [
        'app\tests\fixtures\PrefectureFixture'
    ];

    protected function getData()
    {
        $common_ref = json_encode([
            'sid' => 1,
            'pid' => 100,
            'msg' => 'None'
        ]);
        return [
            ['specialty' => 'ΠΕ 19', 'count' => 1, 'prefecture_id' => 1, 'position' => 'Σχολείο #1 και Σχολείο #2', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 19', 'count' => 2, 'prefecture_id' => 1, 'position' => 'Σχολείο #3', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 60', 'count' => 2, 'prefecture_id' => 1, 'position' => 'Σχολείο #3', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 60', 'count' => 2, 'prefecture_id' => 1, 'position' => 'Σχολείο #2', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 60', 'count' => 1, 'prefecture_id' => 1, 'position' => 'Σχολείο #1', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 70', 'count' => 1, 'prefecture_id' => 1, 'position' => 'Σχολείο #1', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 19', 'count' => 2, 'prefecture_id' => 2, 'position' => 'Σχολείο #2.1', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 60', 'count' => 2, 'prefecture_id' => 2, 'position' => 'Σχολείο #2.2', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 19', 'count' => 1, 'prefecture_id' => 3, 'position' => 'Σχολείο #11 και Σχολείο #12', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 19', 'count' => 2, 'prefecture_id' => 3, 'position' => 'Σχολείο #13', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 60', 'count' => 2, 'prefecture_id' => 3, 'position' => 'Σχολείο #13', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 60', 'count' => 2, 'prefecture_id' => 3, 'position' => 'Σχολείο #12', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 60', 'count' => 1, 'prefecture_id' => 3, 'position' => 'Σχολείο #11', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 70', 'count' => 1, 'prefecture_id' => 3, 'position' => 'Σχολείο #11', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 19', 'count' => 2, 'prefecture_id' => 4, 'position' => 'Σχολείο #22.1', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 60', 'count' => 2, 'prefecture_id' => 4, 'position' => 'Σχολείο #22.2', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 20', 'count' => 1, 'prefecture_id' => 1, 'position' => 'Σχολείο #31 και Σχολείο #32', 'reference' => $common_ref],
            ['specialty' => 'ΠΕ 20', 'count' => 2, 'prefecture_id' => 2, 'position' => 'Σχολείο #33', 'reference' => $common_ref],
        ];
    }
}
