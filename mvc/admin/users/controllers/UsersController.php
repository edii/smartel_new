<?php

class UsersController extends \Controller
{
	public $layout = 'dashboard';

	private $_users;
        
        // owner (model)
        private $_musers = false;
        /**
         * construct
         */
        public function init() {
           $this -> _users = \init::app() -> getModels('auth/users');
           if(empty($this -> _musers))
                $this -> _musers = \init::app() -> getModels('users/musers');
        }
	
	 /**
         * Owner site ( settings globalls elements )
          * load listing
         */
        
        public function actionIndex() {
            $this->layout( false );
            
            $validate = $this -> _users -> getRight();
            if(!$validate)
                $this -> redirect('/'._request_uri.'/home/login');
            
            $this->render('index', array(
                        'listing'   => $this -> _musers ->getUsers(),
                        'validate'  => $validate,
                        '_session'  =>  $this -> _users -> getValidate() -> getSession()
                    ));

	}
        
        public function actionManager() {
            $this->layout( false );
            
            $_error = false;
            $_id = \init::app() ->getRequest() -> getParam('id'); 
            $_method = \init::app() ->getRequest() -> getParam('method');
            $_users = \init::app() ->getRequest() -> getParam('users');
            if(empty($_method) or !isset($_method)) {
                // fatal error ( rediract listings owners )
               $_error = true;
            }
            
            if($_method == 'edit' or $_method == 'add') {
                
                 if(!(int)$_id) {
                     // insert
                     $_title = 'Добавить';
                     if(is_array($_users) and count($_users) > 0) {
                        $this->_musers ->save(true, $_users);
                     }
                 } else {
                     // update
                     $_title = 'Редактирование';
                     if(is_array($_users) and count($_users) > 0) {
                        $this->_musers ->save(true, $_users);
                     }
                 }
                 
            }  else {
                $_error = true;
            }
            
            // update info
            $validate = $this -> _users -> getRight();
            if(!$validate)
                $this -> redirect('/'._request_uri.'/home/login');
               
            if(!$_error) {   
                $this->render('form',array(
                    'title'   => $_title,
                    'listing'   => $this->_musers -> getUserID($_id),
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
            $this -> _musers -> delete(array('userID' => $_id));
            
            $this ->redirect('/'._request_uri.'/users/');
        }
}
