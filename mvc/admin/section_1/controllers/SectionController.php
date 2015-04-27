<?php

class SectionController extends \Controller
{
	public $layout = 'dashboard';

	private $_users;
        
        // section (model)
        private $_msection = false;
        
        /**
         * construct
         */
        public function init() {
           $this -> _users = \init::app() -> getModels('auth/users');
           if(empty($this -> _msection))
               $this -> _msection = \init::app() -> getModels('section/msection');
        }
        
        public function actionIndex() {
            $_lang = \init::app() -> getLanguage();
            
            $this->layout( false );
            
            $_items = array();
            $_sections = $this -> _msection -> getSections();
            if(is_array($_sections) and count($_sections) > 0) {
                foreach($_sections as $_key => $_element) :
                    $_items[$_key] = (array)$_element;
                endforeach;
            }
            
            $_tree = \init::app() -> getCTree()-> set( $_items, array('id' => 'SectionID', 'p_id' => 'SectionParentID') ) -> get();
            
            $validate = $this -> _users -> getRight();
            if(!$validate)
                $this -> redirect('/'._request_uri.'/home/login');
            
            $this->render('index', array(
                'sections_actual' => \init::app()->getTreeSection(),
                'section_list'   => $_tree,
                'validate'  => $validate,
                '_lang' => $_lang
            ));
	}
        
        public function actionManager() {
            $this->layout( false );
            
            $_error = false;
            $_id = \init::app() ->getRequest() -> getParam('id'); 
            $_method = \init::app() ->getRequest() -> getParam('method');
            $_section = \init::app() ->getRequest() -> getParam('section');
            if(empty($_method) or !isset($_method)) {
                // fatal error ( rediract listings owners )
               $_error = true;
            }
            
            if($_method == 'edit') {
                 $_title = 'Редактирование';
                 
                 if(!(int)$_id) {
                    $_error = true;
                 } else {
                     if(is_array($_section) and count($_section) > 0) {
                        $this->_msection ->save(true, $_section);
                     }
                 }
                 
            } else if($_method == 'add'){
                // add
                $_title = 'Добавить';               
                if(!(int)$_id) {
                    if(is_array($_section) and count($_section) > 0) {
                        $this->_msection ->save(true, $_section);
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
                    'listing'   => $this->_msection -> getSectionID($_id),
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
            $this -> _msection -> delete(array('SectionID' => $_id));
            
            $this ->redirect('/'._request_uri.'/section');
        }
        
}
