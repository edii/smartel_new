<?php
/**
 * CContentDecorator class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

class CContentDecorator extends COutputProcessor
{
	/**
	 * @var mixed the name of the view that will be used to decorate the captured content.
	 * If this property is null (default value), the default layout will be used as
	 * the decorative view. Note that if the current controller does not belong to
	 * any module, the default layout refers to the application's {@link CWebApplication::layout default layout};
	 * If the controller belongs to a module, the default layout refers to the module's
	 * {@link CWebModule::layout default layout}.
	 */
	public $view;
	/**
	 * @var array the variables (name=>value) to be extracted and made available in the decorative view.
	 */
	public $data=array();

	/**
	 * Processes the captured output.
     * This method decorates the output with the specified {@link view}.
	 * @param string $output the captured output to be processed
	 */
	public function processOutput($output)
	{
		$output = $this->decorate($output);
		parent::processOutput($output);
	}

	/**
	 * Decorates the content by rendering a view and embedding the content in it.
	 * The content being embedded can be accessed in the view using variable <code>$content</code>
	 * The decorated content will be displayed directly.
	 * @param string $content the content to be decorated
	 * @return string the decorated content
	 */
	protected function decorate($content)
	{
		$owner=$this->getOwner();
		if($this->view===null)
			$viewFile=\init::app()->getController()->getLayoutFile(null);
		else
			$viewFile=$owner->getViewFile($this->view);
		if($viewFile!==false)
		{
			$data=$this->data;
			$data['content']=$content;
			return $owner->renderFile($viewFile,$data,true);
		}
		else
			return $content;
	}
}
