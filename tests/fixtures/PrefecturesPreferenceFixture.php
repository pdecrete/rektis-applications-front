<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class PrefecturesPreferenceFixture extends ActiveFixture
{

    public $tableName = '{{%prefectures_preference}}';
    public $depends = [
        'app\tests\fixtures\PrefectureFixture',
        'app\tests\fixtures\ApplicantFixture',
    ];

    protected function getData()
    {
        return [
            ['prefect_id' => 1, 'applicant_id' => 1, 'order' => 1],
            ['prefect_id' => 2, 'applicant_id' => 1, 'order' => 2],
            ['prefect_id' => 3, 'applicant_id' => 1, 'order' => 3],
            ['prefect_id' => 4, 'applicant_id' => 1, 'order' => 4],
            ['prefect_id' => 1, 'applicant_id' => 2, 'order' => 1],
            ['prefect_id' => 2, 'applicant_id' => 2, 'order' => 2],
            ['prefect_id' => 3, 'applicant_id' => 2, 'order' => 3],
            ['prefect_id' => 4, 'applicant_id' => 2, 'order' => 4],
            ['prefect_id' => 3, 'applicant_id' => 3, 'order' => 1],
            ['prefect_id' => 4, 'applicant_id' => 3, 'order' => 2],
            ['prefect_id' => 1, 'applicant_id' => 4, 'order' => 1],
        ];
    }
}
