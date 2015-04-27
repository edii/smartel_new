<?php

/*
 * model Input
 */

class Mowner extends \CDetectedModel { //extends \CDetectedModel 
    
    public static $db;
    public $_table_name = 'owner';
    
    private $_mod_access = true; // true or false
    private $_type; // type panel

    
    public function init() {
        self::$db = \init::app() -> getDBConnector(); // connected DB
        if(!$this->_mod_access) throw new \CException(\init::t('init','Not access this controller!'));
        $this -> _type = \init::app() -> _getPanel();
        
        $this -> _pk = 'OwnerID';
        $this -> _table_name = 'owner';
    }
    
    public function attributeNames() {
        
    }
    
    /**
     * getOwnerID
     * @param type $_id
     * @return type array owner
     */
    public function getOwnerID( $_id ) {
        $_owners = false;
        if((int)$_id) {
            
            $sql = self::$db -> select( $this->_table_name , 'owner', array('target' => 'main'))
                         -> fields('owner', array('OwnerID',
                                                  'TimeCreated',
                                                  'OwnerCode',
                                                  'hidden',
                                                  'OwnerType',  
                                                  'OwnerDomain',
                                                  'OwnerName',
                                                  'OwnerTitle',
                                                  'OwnerDescription',
                                                  'OwnerKeywords',
                                                  'OwnerIsDefault',
                                                  'OwnerImage'));
            $sql ->condition('hidden', 0, '=') 
                 ->condition('OwnerID', (int)$_id, '='); 
            $_owners = $sql -> execute()->fetchAssoc(); 
            
        } 
         
        return $_owners;
    }
    
    /**
     * 
     * @param type $attributes
     */
    public function getOwners() {
        
        $sql = self::$db -> select( $this->_table_name , 'owner', array('target' => 'main'))
                         -> fields('owner', array('OwnerID',
                                                  'TimeCreated',
                                                  'OwnerCode',
                                                  'hidden',
                                                  'OwnerType',  
                                                  'OwnerDomain',
                                                  'OwnerName',
                                                  'OwnerTitle',
                                                  'OwnerDescription',
                                                  'OwnerKeywords',
                                                  'OwnerIsDefault',
                                                  'OwnerImage'));
        $sql ->condition('hidden', 0, '='); 
        $_owners = $sql -> execute()->fetchAll(); 
        
        
        
        /* memcached test */
        $_cache = \init::app() -> getMemcaches();        
        if(is_object($_cache)) {
            $_res = false;
            $_key = 'owner_cache';
            if(!$_res = $_cache ->getValues($_key)) :
                $_cache -> setValue($_key, $_owners, 86000);
                $_owners = $_cache -> getValues($_key);
            else :
                $_owners = $_cache -> getValues($_key);
            endif;
        } 
        /* end */
        
        return $_owners;
    }
    
    
    /*
     * Example User Save, Delete, Update
     */
    /* public function save($runValidation=true, $attributes = NULL) {
        
        
        if($runValidation and is_array($attributes)) {
            if($_ownerID = (int)$attributes['OwnerID']) {
               $_date['OwnerName'] = (string)$attributes['OwnerName'];  
                
              // update  
              $_update = self::$db -> update($this->_tableName, array('target' => 'main')) 
                        -> fields($_date)
                        ->condition('OwnerID', $_ownerID, '='); 
              if(!$_update -> execute()) 
                  throw new CHttpException(404,\init::t('init','Fatal error dont update Owner'));
              //die('update');
              
            } else {
                // insert
                $attributes['OwnerID'] = null;
                
                
            }
        }
    } */
}
