<?php
Yii::setAlias('api', dirname(__DIR__));
$params = require(__DIR__ . '/params.php');
return [
    'id' => 'app-api',
	'name' => '',
	'version' => "0.0.0",
    'basePath' => dirname(__DIR__),
	'timeZone' => 'Europe/Kiev',

	'vendorPath' => dirname(dirname(dirname(__DIR__))) . '/vendor',
    'controllerNamespace' => 'api\controllers',
	'defaultRoute' => 'product',

	'bootstrap' => ['log'],
    'modules' => [
		'oauth2' => [
			'class' => 'filsh\yii2\oauth2server\Module',
			'options' => [
				'token_param_name' => 'access-token',
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
		'urlManager' => [
			'enablePrettyUrl' => true,
			'rules' => [
				'POST /oauth2/<action:\w+>' => 'oauth2/default/<action>',
				[
					'class' => 'yii\rest\UrlRule',
					'controller' => 'v1/product',
					'extraPatterns' => [
						'GET custom' => 'custom'
					],
				],
			]
		],
		'request' => [
			'parsers' => [
				'application/json' => 'yii\web\JsonParser',
			]
		],
		'response' => [
			'class' => 'yii\web\Response',
			'on beforeSend' => function (\yii\base\Event $event) {
				$response = $event->sender;
				// catch situation, when no controller hasn't been loaded
				// so no filter wasn't loaded too. Need to understand in which format return result
				if(empty(Yii::$app->controller)) {
					$content_neg = new \yii\filters\ContentNegotiator();
					$content_neg->response = $response;
					$content_neg->formats = Yii::$app->params['formats'];
					$content_neg->negotiate();
					// TODO: add parsing error
				}
				if ($response->data !== null && !empty(Yii::$app->request->get('suppress_response_code'))) {
					$response->data = [
						'success' => $response->isSuccessful,
						'data' => $response->data,
					];
					$response->statusCode = 200;
				}
			},
		],
		'user' => [
			'identityClass' => 'api\models\User',
			'loginUrl' => null,
        ],
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
