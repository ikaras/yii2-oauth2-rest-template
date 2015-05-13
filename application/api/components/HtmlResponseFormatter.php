<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace api\components;

/**
 * Response formatter for represent data in tag <pre>
 *
 * It is used by [[Response]] to format response data.
 *
 * @author Ihor Karas <ihor@karas.in.ua>
 */
class HtmlResponseFormatter extends \yii\web\HtmlResponseFormatter
{
    /**
     * Formats the specified response.
     * @param \yii\web\Response $response the response to be formatted.
     */
    public function format($response)
    {
		parent::format($response);
		if (!is_string($response->content)) {
			$response->content =
				"<PRE>"
					. var_export($response->content, true)
				. "</PRE>";
		}
    }
}
