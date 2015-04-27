<?php

class HomeController extends \Controller
{
	public $layout = 'dashboard'; //'column1'

	private $_users;

        private $_auth = false;
        private $_validate = false;
        
        
        
//       function __construct($id, $module = null) {
//           parent::__construct($id, $module);
//           //$this -> _model = \init::app() -> getModels('auth/users');
//       }


       /**
         * construct
         */
        public function init() {
           $this -> _users = \init::app() -> getModels('auth/users');
        }
        
        /**
         * load index admin
         */
	public function actionIndex() {
            
            $_session = \init::app() -> getSession() -> all_userdata();
            $this ->_auth = $this -> _users -> getValidate() -> getSession();
           
            
            if($this ->_auth) :
                $this->_validate = true; 
            endif;
            
           // echo "<hr /> session";
            // вид 1
            //\init::app() -> getSession() -> set_userdata(array('test' => 'params'));
            //$_session = \init::app() -> getSession() -> all_userdata();
            
            // вариант 2
            //$_sess = \init::app() -> getSession();
            //$_session = $_sess->setSession(array('test' => 'params'))-> all_userdata();
            
            // вариант 3
           // $_session = \init::app() -> getSession() -> set_userdata(array('test' => 'params')) -> all_userdata();
            
            
            
            $_data = \init::app() -> getRequest() -> getParam('data');
            
            
            
            if(is_array($_data) and count($_data) > 0 and !$this->_validate) :
                
                if(empty($_data['username'])) $this -> redirect('/'._request_uri.'/home/login', array('error' => true));
                if(empty($_data['password'])) $this -> redirect('/'._request_uri.'/home/login', array('error' => true));
                
                $this-> _auth = $this -> _users 
                            -> getValidate( $_data['username'], $_data['password'] ) 
                            -> setSession();
                if($this-> _auth) {
                    $this->_validate = true;
                } else {
                    $this -> redirect('/'._request_uri.'/home/login');
                }
                
                \init::app() -> getRequest() -> getDelete('data');
            
            else:  
                
                if(!$right = $this -> _users -> getRight()) {
                    $this -> redirect('/'._request_uri.'/home/login');
//                } else if (!isset($this -> _auth['login']) or empty($this -> _auth['login']) 
//                        and !isset($this -> _auth['password']) or empty($this -> _auth['password'])) {
//                    $this -> redirect('/'._request_uri.'/home/login');
                } else if(!$this -> _auth and (!isset($_data) or empty($_data)) ) :
                    $this -> redirect('/'._request_uri.'/home/login');
                else: 
                    $this->_validate = true; 
                endif;
            endif;
            
            // var_dump( $_data ); die('stop');
            
            
            $this->render('index', array(
                        'validate' => $this->_validate,
                        '_session' => $this-> _auth
                    )); 
            
            

	}
        
        /**
         * controller detected Header
         * return (array) _session, validate load header 
         */
        public function actionHeader() {
            $this->layout( false );
            
            
            $this->render('header', array(
                        'validate' => $this -> _users -> getRight(),
                        '_session' => $this -> _users -> getValidate() -> getSession()
                    ));
        }
        
        /* 
         * controller Login 
         */
        public function actionLogin() {
                $this->layout( 'index' );
                $this->render('login');
        }
        
        /* 
         * controller Logout 
         */
        public function actionLogout() {
            
            $user_data = \init::app() -> getSession()->all_userdata();
            foreach ($user_data as $key => $value) {
                if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
                    \init::app() -> getSession() ->unset_userdata($key);
                }
            }
            \init::app() -> getSession()->sess_destroy();
            if(!$this -> _users->getLogin() and !$this -> _users->getPassword()) :
                $this -> redirect('/'._request_uri.'/home/login'); 
            else:
                throw new \CException(\init::t('init','CSession, not destroy session. try egaine!'));
            endif;
        }
        
        

        public function actionTest() {
            
           
        }
}
