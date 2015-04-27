<?php

class PostController extends \Controller
{
	public $layout = 'dashboard';

	private $_users;
        
        // owner (model)
        private $_mpost = false;
        /**
         * construct
         */
        public function init() {
           $this -> _users = \init::app() -> getModels('auth/users');
           if(empty($this -> _mpost))
                $this -> _mpost = \init::app() -> getModels('post/mpost');
        }
            
        public function actionIndex() {
            $this->layout( false );
            
            $validate = $this -> _users -> getRight();
            if(!$validate)
                $this -> redirect('/'._request_uri.'/home/login');
            
            $this->render('index', array(
                        'sections_actual' => \init::app()->getTreeSection(),
                        'listing'   => $this->_mpost -> getPost(),
                        'validate'  => $validate,
                        '_session'  =>  $this -> _users -> getValidate() -> getSession()
                    ));
	}
        
        public function actionManager() {
            $this->layout( false );
            
            $_error = false;
            $_id = \init::app() ->getRequest() -> getParam('id'); 
            $_method = \init::app() ->getRequest() -> getParam('method');
            $_post = \init::app() ->getRequest() -> getParam('post');
            if(empty($_method) or !isset($_method)) {
                // fatal error ( rediract listings owners )
               $_error = true;
            }
            
            if($_method == 'edit') {
                 $_title = 'Редактирование';
                 
                 if(!(int)$_id) {
                    $_error = true;
                 } else {
                     if(is_array($_post) and count($_post) > 0) {
                        $this->_mpost ->save(true, $_post);
                     }
                 }
                 
            } else if($_method == 'add'){
                // add
                $_title = 'Добавить';               
                if(!(int)$_id) {
                    if(is_array($_post) and count($_post) > 0) {
                        $this->_mpost ->save(true, $_post);
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
                    'listing'   => $this->_mpost -> getPostID($_id),
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
            $this -> _mpost -> delete(array('PostID' => $_id));
            
            $this ->redirect('/'._request_uri.'/post/');
        }
}
