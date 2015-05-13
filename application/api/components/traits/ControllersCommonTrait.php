<?php
/**
 * Trait that contains needed behaviors for protect controller by OAuth2

 * @author Ihor Karas <ihor@karas.in.ua>
 * Date: 20.04.15
 * Time: 18:54
 */

namespace api\components\traits;
use Yii;
use api\components\filters\OAuth2AccessFilter;
use filsh\yii2\oauth2server\filters\ErrorToExceptionFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

trait ControllersCommonTrait
{
	public function behaviors()
	{
		$behaviors = parent::behaviors();

		// replace to top contentNegotiator filter for displaying errors in correct format
		$content_negotiator = $behaviors['contentNegotiator'];
		unset($behaviors['contentNegotiator']);
		$content_negotiator['formats'] = Yii::$app->params['formats'];

		$behaviors = ArrayHelper::merge(
			[
				'contentNegotiator' => $content_negotiator,
				'oauth2access' => [ // should be before "authenticator" filter
					'class' => OAuth2AccessFilter::className()
				],
				'exceptionFilter' => [
					'class' => ErrorToExceptionFilter::className()
				],
			],
			$behaviors,
			[
				'access' => [ // need to set after contentNegotiator filter for caching errors
					'class' => AccessControl::className(),
					'rules' => $this->accessRules(),
					'ruleConfig' => ['class' => 'api\components\AccessRule'],
				],
			]
		);

		return $behaviors;
	}

	/**
	 * Access rules for access behavior
	 * @return array
	 */
	public function accessRules()
	{
		return [];
	}
}