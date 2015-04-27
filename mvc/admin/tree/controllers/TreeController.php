<?php

class TreeController extends \Controller
{
	public $layout = false; //'column1'

	private $_mtree;
        private $_users;
        /**
         * construct
         */
        public function init() {
            $this -> _mtree = \init::app() -> getModels('tree/mtree');
            $this -> _users = \init::app() -> getModels('auth/users');
        }
	
        
        /**
         * box cotroller
         * return array
         */
        public function actionIndex() {
                        
            $this->layout( false );
            $_tree = $this -> _mtree -> getTree();
            
            $this->render('index', array(
                'tree'      => $_tree,
                'parent'    => true,
                'validate'  => $this -> _users -> getRight() 
            ));
        }
        
}
