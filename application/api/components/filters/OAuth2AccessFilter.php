<?php
/**
 * Filter for analyze controller's access rules and determine public actions and scopes
 * for private actions. If action is private than check oauth access token and if it has needed
 * scopes to this action
 *
 * @author Ihor Karas <ihor@karas.in.ua>
 */

namespace api\components\filters;
use Yii;
use yii\base\Action;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\web\ForbiddenHttpException;


class OAuth2AccessFilter extends \yii\base\ActionFilter
{
	/**
	 * @param Action $action
	 * @return bool
	 * @throws ForbiddenHttpException
	 * @throws \yii\base\InvalidConfigException
	 */
	public function beforeAction($action)
	{
		$action_name = $action->id;

		list($public_actions, $actions_scopes) = $this->analyzeAccessRules($action_name);

		if (in_array($action_name, $public_actions)) {
			//action is public
			return true;
		}

		// else, if not public, add additional auth filters
		if (Yii::$app->hasModule('oauth2')) {
			/** @var \filsh\yii2\oauth2server\Module $oauth_module */
			$oauth_module = Yii::$app->getModule('oauth2');
			$query_param_auth = ['class' => QueryParamAuth::className()];
			if (!empty($oauth_module->options['token_param_name'])) {
				$query_param_auth['tokenParam'] = $oauth_module->options['token_param_name'];
			}

			$auth_behavior = $this->owner->getBehavior('authenticator');
			$auth_behavior->authMethods = [
				$query_param_auth,
				['class' => HttpBearerAuth::className()],
			];

			$scopes = isset($actions_scopes[$action_name]) ? $actions_scopes[$action_name] : '';
			if (is_array($scopes)) {
				$scopes = implode(' ', $scopes);
			}
			$oauthServer = $oauth_module->getServer();
			$oauthRequest = $oauth_module->getRequest();
			$oauthResponse = $oauth_module->getResponse();
			if (!$oauthServer->verifyResourceRequest($oauthRequest, $oauthResponse, $scopes)) {
				throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
			}
		}
		return parent::beforeAction($action);
	}

	protected function analyzeAccessRules($current_action)
	{
		/** @var \api\components\Controller $this->owner */
		$access_rules = $this->owner->accessRules();
		$public_actions = [];
		$actions_scopes = [];
		$is_met_curr_action = false;

		foreach ($access_rules as $rule) {
			if (empty($rule['controllers']) || in_array($this->owner->uniqueId, $rule['controllers'], true)) {
				if (empty($rule['actions'])) {
					$rule['actions'] = [$current_action];
				}
				if (!empty($rule['actions']) && is_array($rule['actions']) && in_array($current_action, $rule['actions'], true)){
					$is_met_curr_action = true;
					$actions = $rule['actions'];
					$is_public = null;
					if (isset($rule['allow'])) {
						if ($rule['allow'] && (empty($rule['roles']) || in_array('?', $rule['roles']))) {
							$public_actions = array_merge($public_actions, $rule['actions']);
							$is_public = true;
						} elseif (
							(!$rule['allow'] && (empty($rule['roles']) || in_array('?', $rule['roles'])))
							|| ($rule['allow'] && !empty($rule['roles']) && in_array('@', $rule['roles']))
						) {
							$public_actions = array_diff($public_actions, $rule['actions']);
							$is_public = false;
						}
					}
					if ($is_public === false && !empty($rule['scopes'])) {
						$rule_scopes = $rule['scopes'];
						$scopes = is_array($rule_scopes) ? $rule_scopes : explode(' ', trim($rule_scopes));
						foreach ($actions as $a) {
							if (!isset($actions_scopes[$a])) {
								$actions_scopes[$a] = $scopes;
							} else {
								$actions_scopes[$a] = array_merge($actions_scopes[$a], $scopes);
							}
						}
					}
				}
			}
		}
		if (!$is_met_curr_action) {
			$public_actions[] = $current_action;
		}
		return [$public_actions, $actions_scopes];
	}
}