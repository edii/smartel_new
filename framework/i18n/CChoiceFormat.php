<?php
/**
 * Base class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


class CChoiceFormat
{
	/**
	 * Formats a message according to the specified number value.
	 * @param string $messages the candidate messages in the format of 'expr1#message1|expr2#message2|expr3#message3'.
	 * See {@link CChoiceFormat} for more details.
	 * @param mixed $number the number value
	 * @return string the selected message
	 */
	public static function format($messages, $number)
	{
		$n=preg_match_all('/\s*([^#]*)\s*#([^\|]*)\|/',$messages.'|',$matches);
		if($n===0)
			return $messages;
		for($i=0;$i<$n;++$i)
		{
			$expression=$matches[1][$i];
			$message=$matches[2][$i];
			if($expression===(string)(int)$expression)
			{
				if($expression==$number)
					return $message;
			}
			elseif(self::evaluate(str_replace('n','$n',$expression),$number))
				return $message;
		}
		return $message; // return the last choice
	}

	/**
	 * Evaluates a PHP expression with the given number value.
	 * @param string $expression the PHP expression
	 * @param mixed $n the number value
	 * @return boolean the expression result
	 */
	protected static function evaluate($expression,$n)
	{
		return @eval("return $expression;");
	}
}