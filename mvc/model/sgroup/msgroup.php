<?php

/*
 * model Input
 */

class Msgroup extends \CDetectedModel { //extends \CDetectedModel 
    
    public static $db;
    public $_table_name = 'sectiongroup';
    
    private $_mod_access = true; // true or false
    private $_type; // type panel

    
    public function init() {
        self::$db = \init::app() -> getDBConnector(); // connected DB
        if(!$this->_mod_access) throw new \CException(\init::t('init','Not access this controller!'));
        $this -> _type = \init::app() -> _getPanel();
        
        $this -> _pk = 'SectionGroupID';
        $this -> _table_name = 'sectiongroup';
    }
    
    public function attributeNames() {
        
    }
    
    /**
     * getPostID
     * @param type $_id
     * @return type array post
     */
    public function getSectionGroupID( $_id ) {
        $_sgroup = false;
        if((int)$_id) {
            
            $sql = self::$db -> select( $this->_table_name , 'sectiongroup', array('target' => 'main'))
                         -> fields('sectiongroup', array('SectionGroupID',
                                                  'SectionGroupCode',
                                                  'OwnerID',
                                                  'UserID',
                                                  'PermAll',
                                                  'TimeCreated',
                                                  'TimeSaved',
                                                  'SectionGroupName',
                                                  'AccessGroups',
                                                  'SectionGroupType',
                                                  'SectionGroupPosition',
                                                  'SectionGroupModule',
                                                  'SectionGroupViewOptions'));
            $sql ->condition('SectionGroupID', (int)$_id, '='); 
            $_sgroup = $sql -> execute()->fetchAssoc(); 
            
        } 
         
        return $_sgroup;
    }
    
    public function getSectionGroup() {
        
        $sql = self::$db -> select( $this->_table_name , 'sectiongroup', array('target' => 'main'))
                         -> fields('sectiongroup', array('SectionGroupID',
                                                  'SectionGroupCode',
                                                  'OwnerID',
                                                  'UserID',
                                                  'PermAll',
                                                  'TimeCreated',
                                                  'TimeSaved',
                                                  'SectionGroupName',
                                                  'AccessGroups',
                                                  'SectionGroupType',
                                                  'SectionGroupPosition',
                                                  'SectionGroupModule',
                                                  'SectionGroupViewOptions'));
 
        $_sgroup = $sql -> execute()->fetchAll(); 
        
        return $_sgroup;
    }
    
}
