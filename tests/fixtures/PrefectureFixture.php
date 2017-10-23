<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class PrefectureFixture extends ActiveFixture
{

    public $tableName = '{{%prefecture}}';

    protected function getData()
    {
        $common_ref = \Yii::$app->crypt->encrypt(json_encode(['sid' => -1]));

        return [
            ['region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΗΡΑΚΛΕΙΟΥ', 'reference' => $common_ref],
            ['region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΛΑΣΙΘΙΟΥ', 'reference' => $common_ref],
            ['region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΡΕΘΥΜΝΟΥ', 'reference' => $common_ref],
            ['region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΧΑΝΙΩΝ', 'reference' => $common_ref],
        ];
    }
}
