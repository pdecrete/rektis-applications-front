<?php

return [
    'companyName' => 'Η υπηρεσία μου',
    'adminEmail' => 'admin@example.com',
    'users' => [
        // array of special users
        // available roles: 'admin' and 'supevisor' 
        '-1' => [
            'id' => '-1',
            'vat' => 'admin',
            'identity' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
            'role' => 'admin'
        ],
        '-2' => [
            'id' => '-2',
            'vat' => 'spedu',
            'identity' => 'spedu',
            'authKey' => 'test200key234',
            'accessToken' => '234-token',
            'role' => 'supervisor'
        ]
	],
    'crypt-key-file' => __DIR__ . "/path/to/your/key.file",
    'bridge-allowed-ips' => ['127.0.0.*'], // add ips to allow access to web api
    'allow-recaptcha' => (YII_ENV_DEV ? false : true)
];
