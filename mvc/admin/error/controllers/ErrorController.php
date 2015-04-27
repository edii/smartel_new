<?php

class ErrorController extends \Controller
{
	public $layout = 'dashboard';

	private $_model;
        
        /**
         * construct
         */
        public function init() {
            $this -> _model = \init::app() -> getModels('auth/users');
        }
	
	public function actionIndex() {
            $this->render('index');
	}
        
        public function action403() {
            $this->render('403', array(
                 'validate' => $this -> _model -> getRight()
             ));
	}
        
        public function action404() {
            $this->render('404', array(
                 'validate' => $this -> _model -> getRight()
             ));
	}
        
        public function action405() {
            $this->render('405', array(
                 'validate' => $this -> _model -> getRight()
             ));
	}
        
        public function action500() {
            $this->render('500', array(
                 'validate' => $this -> _model -> getRight()
             ));
	}
        
        public function action503() {
            $this->render('503', array(
                 'validate' => $this -> _model -> getRight()
             ));
	}

}
