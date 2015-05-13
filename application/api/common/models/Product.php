<?php
/**
 * Model for working with products
 *
 * @author ihor@karas.in.ua
 * Date: 04.05.15
 * Time: 22:57
 */

namespace api\common\models;

class Product extends \api\components\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{products}}';
	}

	public static function find() {
		return new ProductQuery(get_called_class());
	}
}

class ProductQuery extends \api\components\db\ActiveQuery
{
}