<?php
return [
    'id' => 'app-api',
	'name' => '',

    'controllerNamespace' => 'api\controllers',
	'defaultRoute' => 'product',

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
    ],
    'params' => [],
];
