<?php
/**
 * CFilterWidget class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

class CFilterWidget extends CWidget implements IFilter
{
	/**
	 * @var boolean whether to stop the action execution when this widget is used as a filter.
	 * This property should be changed only in {@link CWidget::init} method.
	 * Defaults to false, meaning the action should be executed.
	 */
	public $stopAction=false;

	private $_isFilter;

	/**
	 * Constructor.
	 * @param CBaseController $owner owner/creator of this widget. It could be either a widget or a controller.
	 */
	public function __construct($owner=null)
	{
		parent::__construct($owner);
		$this->_isFilter=($owner===null);
	}

	/**
	 * @return boolean whether this widget is used as a filter.
	 */
	public function getIsFilter()
	{
		return $this->_isFilter;
	}

	/**
	 * Performs the filtering.
	 * The default implementation simply calls {@link init()},
	 * {@link CFilterChain::run()} and {@link run()} in order
	 * Derived classes may want to override this method to change this behavior.
	 * @param CFilterChain $filterChain the filter chain that the filter is on.
	 */
	public function filter($filterChain)
	{
		$this->init();
		if(!$this->stopAction)
		{
			$filterChain->run();
			$this->run();
		}
	}
}