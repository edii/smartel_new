<?php

/*
 * model Input
 */

class Msettings extends \CDetectedModel { //extends \CDetectedModel 
    
    public static $db;
    public $_table_name = 'settings';
    
    private $_mod_access = true; // true or false
    private $_type; // type panel

    
    public function init() {
        self::$db = \init::app() -> getDBConnector(); // connected DB
        if(!$this->_mod_access) throw new \CException(\init::t('init','Not access this controller!'));
        $this -> _type = \init::app() -> _getPanel();
        
        $this -> _pk = 'SettingsID';
        $this -> _table_name = 'settings';
    }
    
    public function attributeNames() {
        
    }
    
    /**
     * getSettingsID
     * @param type $_id
     * @return type array settings
     */
    public function getSectionID( $_id ) {
        
    }
    
    /**
     * 
     * @param type $attributes
     */
    public function getSettings() {
        
        
    }
    
}