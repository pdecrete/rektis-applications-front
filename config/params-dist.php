<?php

return [
    'companyName' => 'Η υπηρεσία μου',
    'adminEmail' => 'admin@example.com',
    'users' => [
        '-1' => [
            'id' => '-1',
            'vat' => 'admin',
            'identity' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
            'role' => 'admin'
        ],
	],
    'crypt-key-file' => __DIR__ . "/path/to/your/key.file",
    'max-application-items' => 10
];
