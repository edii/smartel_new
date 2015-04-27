<?php

class TestController extends \Controller
{
	public $layout = 'column1';
	private $_model;

	public function filters() {
		
	}

	public function accessRules() {
		
	}

	public function actionView() {
             // \init::app()->setTheme( false ); // disabled layout
            $this->layout( 'dddd' );
		//$post=$this->loadModel();
		//$comment=$this->newComment($post);
            echo "Controller actionc";
		
                $this->render('view', [
                    'data' => 'Maks Buc'
                ]);
                
                
	}

	public function actionCreate() {
            $model=$this->loadModel();
            
            echo "create Action";
            
            $_gets = $this->getActionParams();
            echo "<pre>";
            var_dump( $_gets );
            echo "</rpe>";
            
            $dataProvider = ['blaaa', 'ddddd'];
            
            
            // include view default index
            $this->render('index',array(
                        'dataProvider' => $dataProvider,
			'model'=> [1,2],
			'comment' => 'component',
		));
            
            
            //if(!$_GET['q']) 
                //throw new \CHttpException(404,'The requested page does not exist.');
	}

	public function actionUpdate() {

	}

	public function actionDelete() {
            echo "action delete";
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
            $this->layout( 'test' );
            
            echo 'Blaaaaaaaaaaaaa';

            $dataProvider = ['blaaa', 'ddddd'];
            
            $this->render('index', array(
			'dataProvider'=>$dataProvider,
		));
            
	}

	
	
	public function actionSuggestTags() {
		if(isset($_GET['q']) && ($keyword=trim($_GET['q']))!=='')
		{
			$tags=Tag::model()->suggestTags($keyword);
			if($tags!==array())
				echo implode("\n",$tags);
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
//		if($this->_model===null)
//		{
//			if(isset($_GET['id']))
//			{
//				if(Yii::app()->user->isGuest)
//					$condition='status='.Post::STATUS_PUBLISHED.' OR status='.Post::STATUS_ARCHIVED;
//				else
//					$condition='';
//				$this->_model=Post::model()->findByPk($_GET['id'], $condition);
//			}
//			if($this->_model===null)
//				throw new CHttpException(404,'The requested page does not exist.');
//		}
//		return $this->_model;
	}

	/**
	 * Creates a new comment.
	 * This method attempts to create a new comment based on the user input.
	 * If the comment is successfully created, the browser will be redirected
	 * to show the created comment.
	 * @param Post the post that the new comment belongs to
	 * @return Comment the comment instance
	 */
	public function actionBox() {
            echo "load test box";
	}
}
