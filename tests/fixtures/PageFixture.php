<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class PageFixture extends ActiveFixture
{
    public $tableName = '{{%page}}';

    protected function getData()
    {
        return [
            ['identity' => 'info_denial', 'title' => 'Κείμενο αρνητικής δήλωσης', 'content' => '<p>Κείμενο αρνητικής δήλωσης.</p>', 'created_at' => time(), 'updated_at' => time()],
            ['identity' => 'info_terms', 'title' => 'Κείμενο οδηγιών', 'content' => '<p>Κείμενο οδηγιών.</p>', 'created_at' => time(), 'updated_at' => time()],
            ['identity' => '_info_apply_usage', 'title' => 'Σύντομο κείμενο οδηγιών', 'content' => '<p>Σύντομο κείμενο οδηγιών.</p>', 'created_at' => time(), 'updated_at' => time()],
            ['identity' => 'about', 'title' => 'Σχετικά', 'content' => '<p>Σχετικά.</p>', 'created_at' => time(), 'updated_at' => time()],
        ];
    }
}
