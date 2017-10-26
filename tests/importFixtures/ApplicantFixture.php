<?php
namespace app\tests\importFixtures;

class ApplicantFixture extends BaseImportFixture
{
    public $tableName = '{{%applicant}}';

    protected function getData()
    {
        $data = $this->getSerializedData();
        return $data['applicant'];
    }
}
