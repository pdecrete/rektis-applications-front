<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class ApplicantFixture extends ActiveFixture
{

    public $tableName = '{{%applicant}}';

    protected function getData()
    {
        $common_ref = [
            'sid' => 1,
            'msg' => 'None'
        ];

        $data = [
            ['vat' => '012345678', 'identity' => 'ΑΑ1234', 'specialty' => 'ΠΕ 19', 'reference' => ''],
            ['vat' => '112345678', 'identity' => 'ΒΒ5678', 'specialty' => 'ΠΕ 60', 'reference' => ''],
            ['vat' => '212345678', 'identity' => 'ΑΒ9874', 'specialty' => 'ΠΕ 60', 'reference' => ''],
            ['vat' => '012345678', 'identity' => 'ΑΑ1234', 'specialty' => 'ΠΕ 20', 'reference' => ''],
        ];

        array_walk($data, function (&$v, $k) use ($common_ref) {
            $v['reference'] = \Yii::$app->crypt->encrypt(
                json_encode(
                    array_merge($common_ref, [
                'firstname' => "{$v['identity']}-firstname",
                'lastname' => "{$v['identity']}-lastname",
                'fathername' => "{$v['identity']}-fathername",
                'mothername' => "{$v['identity']}-mothername",
                'email' => "{$v['identity']}@email",
                'phone' => "2810{$v['vat']}",
                    ])
                )
            );
        });

        return $data;
    }
}
