<?php

/*
 * model Input
 */

class Users extends \CDetectedModel { //extends \CDetectedModel
    
    public static $db;

    private $_tableName = 'user';
    
    public $_users = false;
    
    private $_login;
    private $_password;
    
    public function init() {
        self::$db = \init::app() -> getDBConnector();
        
    }
    
    public function attributeNames() {
        
    }
    
    /**
     * input ( create fields )
     */
    public function getValidate($login = false, $password = false) {
       
        $this->_users = false;
        
        if($login and $password) {
            $this->_login = stripcslashes(htmlspecialchars(trim($login)));
            $this->_password = md5(stripcslashes(htmlspecialchars(trim($password))));
            
            if(!$_session = $this -> getSessionValidate( $this->_login, $this->_password )) :
            
                $_query = self::$db -> query("SELECT userID as id, 
                                                     login as login, 
                                                     email as email
                                              FROM ".$this->_tableName." 
                                              WHERE (login = '".$this->_login."' OR email = '".$this->_login."') 
                                                        AND password = '".$this->_password."' ", array('target'=>'main'), array())
                                        -> fetchAll();          
                if(is_array($_query) and count($_query) > 0):
                    $this->_users = (object) array_merge( (array)array_shift($_query), ['validate' => true] );
                endif;
            
            else:
                $this->_users = $_session;
            endif;
        } else {
            if($_session = $this -> getSessionValidate()) :
               $this->_users = $_session;  
            endif; 
        }
        
        
        
        return $this;
        
    }
    
    public function setSession() {
        
        if(is_array($this->_users) or is_object($this->_users)) {
            $_session = \init::app() -> getSession() -> set_userdata($this->_users) -> all_userdata();
            return $_session;
        } else {
            return false;
        }
    }
    
    public function getSession() {
        return (is_array($this->_users) and count($this->_users) > 0) ? $this->_users : false;
    }
    
    public function getSessionValidate($login = false, $password = false) {
        $_session = \init::app() -> getSession() -> all_userdata();
        if($login and $password) {
            $_login = $this->getLogin();
            $_password = $this->getPassword();
            
            if($login == $_login and $password == $_password) {
                return $_session;
            } else {
               return false; 
            }
            
        } else if(is_array($_session) and count($_session) > 0) {
            if($this -> getRight() or $this->getPassword()):
                return $_session;
            else:
                return false;
            endif;
        } else {
            return false;
        }
    }
    
    
    public function getRight() {
       $_validate = \init::app() -> getSession() -> userdata('validate');
       return (isset($_validate) and !empty($_validate)) ? $_validate : false;
    }
    
    public function getLogin() {
        $_login = \init::app() -> getSession() -> userdata('login');
        return (isset($_login) and !empty($_login)) ? $_login : false;    
    }
    
    public function getPassword() {
        $_password = \init::app() -> getSession() -> userdata('password');
        return (isset($_password) and !empty($_password)) ? $_password : false;    
    }
    
    /**
     * LogOut systems 
     */
    public function getLogout(){
        \init::app() -> getSession() -> _clearSession();
        $this->_users = false;
        return $this;
    }
    
    public function update($attributes = NULL) {
    }
    
    /**
     * 
     * save (input or update)
     * 
     */
    //public function save($runValidation = true, $attributes = NULL) {
     //   echo('load model input save');
        // die('stop');
    //}
    
    
}
