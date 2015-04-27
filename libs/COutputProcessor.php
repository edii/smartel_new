<?php
/**
 * COutputProcessor class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

class COutputProcessor extends CFilterWidget
{
	/**
	 * Initializes the widget.
	 * This method starts the output buffering.
	 */
	public function init() {
		ob_start();
		ob_implicit_flush(false);
	}

	/**
	 * Executes the widget.
	 * This method stops output buffering and processes the captured output.
	 */
	public function run() {
		$output = ob_get_clean();
		$this->processOutput($output);
	}

	/**
	 * Processes the captured output.
	 *
	 * The default implementation raises an {@link onProcessOutput} event.
	 * If the event is not handled by any event handler, the output will be echoed.
	 *
	 * @param string $output the captured output to be processed
	 */
	public function processOutput($output)
	{
		if($this->hasEventHandler('onProcessOutput'))
		{
			$event=new COutputEvent($this,$output);
			$this->onProcessOutput($event);
			if(!$event->handled)
				echo $output;
		}
		else
			echo $output;
	}

	/**
	 * Raised when the output has been captured.
	 * @param COutputEvent $event event parameter
	 */
	public function onProcessOutput($event)
	{
		$this->raiseEvent('onProcessOutput', $event);
	}
}
