<?php

class CatsController extends \Controller
{
	public $layout = 'dashboard';

	private $_users;
        
        // owner (model)
        private $_mcats = false;
        /**
         * construct
         */
        public function init() {
           $this -> _users = \init::app() -> getModels('auth/users');
           if(empty($this -> _mcats))
            $this -> _mcats = \init::app() -> getModels('cats/mcats');
        }
	
	 /**
         * Cats site
         * load listing
         */
        
        public function actionIndex() {
            $this->layout( false );
            
            $validate = $this -> _users -> getRight();
            if(!$validate)
                $this -> redirect('/'._request_uri.'/home/login');
            
            $this->render('index', array(
                        'sections_actual' => \init::app()->getTreeSection(),
                        'listing'   => $this->_mcats -> getCats(),
                        'validate'  => $validate,
                        '_session'  =>  $this -> _users -> getValidate() -> getSession()
                    ));

	}
        
        public function actionManager() {
            $this->layout( false );
            
            $_error = false;
            $_id = \init::app() ->getRequest() -> getParam('id'); 
            $_method = \init::app() ->getRequest() -> getParam('method');
            $_cats = \init::app() ->getRequest() -> getParam('cats');
            if(empty($_method) or !isset($_method)) {
                // fatal error ( rediract listings owners )
               $_error = true;
            }
            
            if($_method == 'edit') {
                 $_title = 'Редактирование';
                 
                 if(!(int)$_id) {
                    $_error = true;
                 } else {
                     if(is_array($_cats) and count($_cats) > 0) {
                        $this->_mcats ->save(true, $_cats);
                     }
                 }
                 
            } else if($_method == 'add'){
                // add
                $_title = 'Добавить';               
                if(!(int)$_id) {
                    if(is_array($_cats) and count($_cats) > 0) {
                        $this->_mcats ->save(true, $_cats);
                    }
                }
                
            } else {
                $_error = true;
            }
            
            // update info
            $validate = $this -> _users -> getRight();
            if(!$validate)
                $this -> redirect('/'._request_uri.'/home/login');
               
            if(!$_error) {   
                $this->render('form',array(
                    'title'   => $_title,
                    'sections_actual' => \init::app()->getTreeSection(),
                    'listing'   => $this->_mcats -> getCatsID($_id),
                    'validate'  => $validate,
                    '_session'  =>  $this -> _users -> getValidate() -> getSession()
                ));
            } else {
                 $this ->redirect('/'._request_uri.'/error/404/');
            }                 

	}

        public function actionDelete(){
            $this->layout( false );
     
            $_id = \init::app() ->getRequest() -> getParam('id');
            $this -> _mcats -> delete(array('CatsID' => $_id));
            
            $this ->redirect('/'._request_uri.'/cats/');
        }
        
}
