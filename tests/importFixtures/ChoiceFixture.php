<?php
namespace app\tests\importFixtures;

use Yii;

class ChoiceFixture extends BaseImportFixture
{
    public $tableName = '{{%choice}}';
    public $depends = [
        'app\tests\importFixtures\PrefectureFixture'
    ];

    protected function getData()
    {
        $common_ref = Yii::$app->crypt->encrypt(
            json_encode([
            'sid' => -1,
            ])
        );

        return [
            ['specialty' => 'ΕΒΠ', 'count' => 1, 'prefecture_id' => 1, 'position' => 'Σχολείο #1 και Σχολείο #2', 'reference' => $common_ref],
            ['specialty' => 'ΕΒΠ', 'count' => 2, 'prefecture_id' => 1, 'position' => 'Σχολείο #3', 'reference' => $common_ref],
            ['specialty' => 'ΕΒΠ', 'count' => 2, 'prefecture_id' => 1, 'position' => 'Σχολείο #2', 'reference' => $common_ref],
            ['specialty' => 'ΕΒΠ', 'count' => 1, 'prefecture_id' => 1, 'position' => 'Σχολείο #1', 'reference' => $common_ref],
            ['specialty' => 'ΕΒΠ', 'count' => 2, 'prefecture_id' => 2, 'position' => 'Σχολείο #2.1', 'reference' => $common_ref],
            ['specialty' => 'ΕΒΠ', 'count' => 2, 'prefecture_id' => 2, 'position' => 'Σχολείο #2.2', 'reference' => $common_ref],
            ['specialty' => 'ΕΒΠ', 'count' => 1, 'prefecture_id' => 3, 'position' => 'Σχολείο #11 και Σχολείο #12', 'reference' => $common_ref],
            ['specialty' => 'ΕΒΠ', 'count' => 2, 'prefecture_id' => 3, 'position' => 'Σχολείο #13', 'reference' => $common_ref],
            ['specialty' => 'ΕΒΠ', 'count' => 2, 'prefecture_id' => 3, 'position' => 'Σχολείο #12', 'reference' => $common_ref],
            ['specialty' => 'ΕΒΠ', 'count' => 1, 'prefecture_id' => 3, 'position' => 'Σχολείο #11', 'reference' => $common_ref],
            ['specialty' => 'ΕΒΠ', 'count' => 2, 'prefecture_id' => 4, 'position' => 'Σχολείο #22.1', 'reference' => $common_ref],
            ['specialty' => 'ΕΒΠ', 'count' => 2, 'prefecture_id' => 4, 'position' => 'Σχολείο #22.2', 'reference' => $common_ref],
        ];
    }
}
