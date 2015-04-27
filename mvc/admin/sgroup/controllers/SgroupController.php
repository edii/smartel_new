<?php

class SgroupController extends \Controller
{
	public $layout = 'dashboard';

	private $_users;
        
        // owner (model)
        private $_msgroup = false;
        /**
         * construct
         */
        public function init() {
           $this -> _users = \init::app() -> getModels('auth/users');
           if(empty($this -> _msgroup))
            $this -> _msgroup = \init::app() -> getModels('sgroup/msgroup');
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
                        'listing'   => $this->_msgroup -> getSectionGroup(),
                        'validate'  => $validate,
                        '_session'  =>  $this -> _users -> getValidate() -> getSession()
                    ));

	}
        
        public function actionManager() {
            $this->layout( false );
            
            $_error = false;
            $_id = \init::app() ->getRequest() -> getParam('id'); 
            $_method = \init::app() ->getRequest() -> getParam('method');
            $_sgroup = \init::app() ->getRequest() -> getParam('sgroup');
            if(empty($_method) or !isset($_method)) {
                // fatal error ( rediract listings owners )
               $_error = true;
            }
            
            if($_method == 'edit') {
                 $_title = 'Редактирование';
                 
                 if(!(int)$_id) {
                    $_error = true;
                 } else {
                     if(is_array($_sgroup) and count($_sgroup) > 0) {
                        $this->_msgroup ->save(true, $_sgroup);
                     }
                 }
                 
            } else if($_method == 'add'){
                // add
                $_title = 'Добавить';               
                if(!(int)$_id) {
                    if(is_array($_sgroup) and count($_sgroup) > 0) {
                        $this->_msgroup ->save(true, $_sgroup);
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
                    'listing'   => $this->_msgroup -> getSectionGroupID($_id),
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
            $this -> _msgroup -> delete(array('SectionGroupID' => $_id));
            
            $this ->redirect('/'._request_uri.'/sgroup');
        }
        
}
