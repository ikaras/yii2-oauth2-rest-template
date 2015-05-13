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
				'allow' => false,
				'actions' => ['custom'],
				'roles' => ['?'],
			]
		];
	}

	public function actionCustom()
	{
		return ['status' => 'yes'];
	}
}