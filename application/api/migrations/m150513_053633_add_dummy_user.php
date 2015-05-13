<?php

use yii\db\Migration;
use \api\models\User;

class m150513_053633_add_dummy_user extends Migration
{
    public function up()
    {
		$user = new User();
		$user->email = 'admin@api.loc';
		$user->password = '123123123';
		$user->generateAuthKey();
		$user->save();
    }

    public function down()
    {
		$user = User::findByEmail('admin@api.loc');
		if (!empty($user)) {
			$user->delete();
		}
    }
}
