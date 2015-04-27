<?php

class PostController extends \Controller
{
	public $layout = 'test'; //'column1'

	private $_model;

	

	public function actionIndex() {
            
            $this->layout( false );
            $this->render('index'); 
            
	}
}
