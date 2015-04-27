<?php

/*
 * model Input
 */

class Msection extends \CDetectedModel { //extends \CDetectedModel 
    
    public static $db;
    public $_table_name = 'section';
    
    private $_mod_access = true; // true or false
    private $_type; // type panel

    
    public function init() {
        self::$db = \init::app() -> getDBConnector(); // connected DB
        if(!$this->_mod_access) throw new \CException(\init::t('init','Not access this controller!'));
        $this -> _type = \init::app() -> _getPanel();
        
        $this -> _pk = 'SectionID';
        $this -> _table_name = 'section';
    }
    
    public function attributeNames() {
        
    }
    
    /**
     * getSectionID
     * @param type $_id
     * @return type array section
     */
    public function getSectionID( $_id ) {
        $_sections = false;
        if((int)$_id) {
            
            $sql = self::$db -> select( $this->_table_name , 'section', array('target' => 'main'))
                         -> fields('section', array('SectionID',
                                                  'hidden',
                                                  'SectionInMenu',
                                                  'SectionAlias',
                                                  'OwnerID',  
                                                  'UserID',
                                                  'TimeCreated',
                                                  'TimeSaved',
                                                  'SectionType',
                                                  'SectionLanguages',
                                                  'SectionParentID',
                                                  'SectionGroupID',
                                                  'SectionLayout',
                                                  'SectionBox',
                                                  'SectionBoxStyle',
                                                  'AccessGroups',
                                                  'SectionArguments',
                                                  'SectionLink',
                                                  'SectionTarget',
                                                  'SectionName',
                                                  'SectionTitle',
                                                  'SectionDescription',
                                                  'SectionKeywords',
                                                  'SectionPosition',
                                                  'SectionIntroContent',
                                                  'SectionContent',
                                                  'SectionClicks',
                                                  'SectionViewOptions',
                                                  'SectionController',
                                                  'SectionAction',
                                                  'SectionView',
                                                  'SectionUrl',
                                                  'SectionRout'));
            $sql ->condition('hidden', 0, '=') 
                 ->condition('SectionID', (int)$_id, '='); 
            $_sections = $sql -> execute()->fetchAssoc(); 
            
        } 
         
        return $_sections;
    }
    
    /**
     * 
     * @param type $attributes
     */
    public function getSections() {
        
        $sql = self::$db -> select( $this->_table_name , 'section', array('target' => 'main'))
                         -> fields('section', array('SectionID',
                                                  'hidden',
                                                  'SectionInMenu',
                                                  'SectionAlias',
                                                  'OwnerID',  
                                                  'UserID',
                                                  'TimeCreated',
                                                  'TimeSaved',
                                                  'SectionType',
                                                  'SectionLanguages',
                                                  'SectionParentID',
                                                  'SectionGroupID',
                                                  'SectionLayout',
                                                  'SectionBox',
                                                  'SectionBoxStyle',
                                                  'AccessGroups',
                                                  'SectionArguments',
                                                  'SectionLink',
                                                  'SectionTarget',
                                                  'SectionName',
                                                  'SectionTitle',
                                                  'SectionDescription',
                                                  'SectionKeywords',
                                                  'SectionPosition',
                                                  'SectionIntroContent',
                                                  'SectionContent',
                                                  'SectionClicks',
                                                  'SectionViewOptions',
                                                  'SectionController',
                                                  'SectionAction',
                                                  'SectionView',
                                                  'SectionUrl',
                                                  'SectionRout'));
        $sql ->condition('hidden', 0, '='); 
        $_sections = $sql -> execute()->fetchAll(); 
        
        return $_sections;
    }
    
}