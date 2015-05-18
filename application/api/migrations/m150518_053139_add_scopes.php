<?php

use yii\db\Migration;
use \filsh\yii2\oauth2server\models\OauthScopes;

class m150518_053139_add_scopes extends Migration
{
    public function safeUp()
    {
		$scopes = [
			['scope' => 'default', 'is_default' => 1],
			['scope' => 'custom', 'is_default' => 0],
			['scope' => 'protected', 'is_default' => 0],
		];
		foreach ($scopes as $scope) {
			$so = new OauthScopes();
			$so->attributes = $scope;
			$so->save();
		}
    }
    
    public function safeDown()
    {
		$this->truncateTable(OauthScopes::tableName());
    }
}
