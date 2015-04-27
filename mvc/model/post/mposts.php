<?php

/*
 * model Input
 */

class Mpost extends \CDetectedModel { //extends \CDetectedModel 
    
    public static $db;
    public $_table_name = 'post';
    
    private $_mod_access = true; // true or false
    private $_type; // type panel

    
    public function init() {
        self::$db = \init::app() -> getDBConnector(); // connected DB
        if(!$this->_mod_access) throw new \CException(\init::t('init','Not access this controller!'));
        $this -> _type = \init::app() -> _getPanel();
        
        $this -> _pk = 'PostID';
        $this -> _table_name = 'post';
    }
    
    public function attributeNames() {
        
    }
    
    /**
     * getPostID
     * @param type $_id
     * @return type array post
     */
    public function getPostID( $_id ) {
        $_posts = false;
        if((int)$_id) {
            
            $sql = self::$db -> select( $this->_table_name , 'post', array('target' => 'main'))
                         -> fields('post', array('PostID',
                                                  'cats_id',
                                                  'post_author',
                                                  'lang_id',
                                                  'post_date',
                                                  'post_date_gmt',  
                                                  'post_description',
                                                  'post_intro',
                                                  'post_title',
                                                  'post_excerpt',
                                                  'post_status',
                                                  'post_name',
                                                  'to_ping',
                                                  'pinged',
                                                  'post_modified',
                                                  'post_modified_gmt',
                                                  'post_parent',
                                                  'guid',
                                                  'post_type',
                                                  'comment_count'));
            $sql ->condition('PostID', (int)$_id, '='); 
            $_posts = $sql -> execute()->fetchAssoc(); 
            
        } 
         
        return $_posts;
    }
    
    public function getPost() {

        $sql = self::$db -> select( $this->_table_name , 'post', array('target' => 'main'))
                     -> fields('post', array('PostID',
                                              'cats_id',
                                              'post_author',
                                              'lang_id',
                                              'post_date',
                                              'post_date_gmt',  
                                              'post_description',
                                              'post_intro',
                                              'post_title',
                                              'post_excerpt',
                                              'post_status',
                                              'post_name',
                                              'to_ping',
                                              'pinged',
                                              'post_modified',
                                              'post_modified_gmt',
                                              'post_parent',
                                              'guid',
                                              'post_type',
                                              'comment_count'));
        $_posts = $sql -> execute()->fetchAll(); 
         
        return $_posts;
    }
    
}
