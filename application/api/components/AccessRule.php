<?php
/**
 * Created by PhpStorm.
 * @author Ihor Karas <ihor@karas.in.ua>
 */

namespace api\components;


class AccessRule extends \yii\filters\AccessRule
{
	/** @var array list of scopes, used for setting scope for controller */
	public $scopes=[];
}