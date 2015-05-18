<?php
/**
 * Controller for manage products
 *
 * @author ihor@karas.in.ua
 * Date: 03.04.15
 * Time: 00:35
 */

namespace api\common\controllers;
use \Yii as Yii;


class ProductController extends \api\components\ActiveController
{
	public $modelClass = '\api\common\models\Product';

	public function accessRules()
	{
		return [
			[
				'allow' => true,
				'roles' => ['?'],
			],
			[
				'allow' => true,
				'actions' => [
					'view',
					'create',
					'update',
					'delete'
				],
				'roles' => ['@'],
			],
			[
				'allow' => true,
				'actions' => ['custom'],
				'roles' => ['@'],
				'scopes' => ['custom'],
			],
			[
				'allow' => true,
				'actions' => ['protected'],
				'roles' => ['@'],
				'scopes' => ['protected'],
			]
		];
	}

	public function actionCustom()
	{
		return ['status' => 'ok', 'underScope' => 'custom'];
	}

	public function actionProtected()
	{
		return ['status' => 'ok', 'underScope' => 'protected'];
	}
}