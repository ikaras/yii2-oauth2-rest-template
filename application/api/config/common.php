<?php
Yii::setAlias('api', dirname(__DIR__));
$params = require(__DIR__ . '/params.php');
return [
	'version' => "0.0.0",
    'basePath' => dirname(__DIR__),
	'timeZone' => 'Europe/Kiev',

	'vendorPath' => dirname(dirname(dirname(__DIR__))) . '/vendor',

	'bootstrap' => ['log'],
    'modules' => [
		'oauth2' => [
			'class' => 'filsh\yii2\oauth2server\Module',
			'options' => [
				'token_param_name' => 'access_token',
				'access_lifetime' => 3600 * 24
			],
			'storageMap' => [
				'user_credentials' => 'api\models\User'
			],
			'grantTypes' => [
				'client_credentials' => [
					'class' => 'OAuth2\GrantType\ClientCredentials',
					'allow_public_clients' => false
				],
				'user_credentials' => [
					'class' => 'OAuth2\GrantType\UserCredentials'
				],
				'refresh_token' => [
					'class' => 'OAuth2\GrantType\RefreshToken',
					'always_issue_new_refresh_token' => true
				]
			],
		],
		'v1' => [
			'class' => 'api\versions\v1\Module',
		],

	],
    'components' => [
		'db' => [
			'class' => 'yii\db\Connection',
			'dsn' => 'mysql:host=dbserver;dbname=api',
			'username' => 'apiuser',
			'password' => 'somegoodpassword',
			'charset' => 'utf8',
		],
		'authManager' => [
			'class' => 'yii\rbac\DbManager',
		],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => $params,
];
