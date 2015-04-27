<?php

/*
 * model Input
 */

class Mcats extends \CDetectedModel { //extends \CDetectedModel 
    
    public static $db;
    public $_table_name = 'cats';
    
    private $_mod_access = true; // true or false
    private $_type; // type panel

    
    public function init() {
        self::$db = \init::app() -> getDBConnector(); // connected DB
        if(!$this->_mod_access) throw new \CException(\init::t('init','Not access this controller!'));
        $this -> _type = \init::app() -> _getPanel();
        
        $this -> _pk = 'CatsID';
        $this -> _table_name = 'cats';
    }
    
    public function attributeNames() {
        
    }
    
    /**
     * getPostID
     * @param type $_id
     * @return type array post
     */
    public function getCatsID( $_id ) {
        $_cats = false;
        if((int)$_id) {
            
            $sql = self::$db -> select( $this->_table_name , 'cats', array('target' => 'main'))
                         -> fields('cats', array('CatsID',
                                                  'cats_parent_id',
                                                  'cats_name',
                                                  'cats_desc',
                                                  'lang_id',
                                                  'cats_url',
                                                  'guid',
                                                  'type',
                                                  'cats_create_date',
                                                  'cats_create_date_gmt',
                                                  'cats_mod_date',
                                                  'cats_mod_date_gmt'));
            $sql ->condition('CatsID', (int)$_id, '='); 
            $_cats = $sql -> execute()->fetchAssoc(); 
            
        } 
         
        return $_cats;
    }
    
    public function getCats() {
        
        $sql = self::$db -> select( $this->_table_name , 'cats', array('target' => 'main'))
                         -> fields('cats', array('CatsID',
                                                  'cats_parent_id',
                                                  'cats_name',
                                                  'cats_desc',
                                                  'lang_id',
                                                  'cats_url',
                                                  'guid',
                                                  'type',
                                                  'cats_create_date',
                                                  'cats_create_date_gmt',
                                                  'cats_mod_date',
                                                  'cats_mod_date_gmt'));
 
        $_cats = $sql -> execute()->fetchAll(); 
        
        return $_cats;
    }
    
}
