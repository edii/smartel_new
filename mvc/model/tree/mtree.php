<?php

/*
 * model Input
 */

class Mtree extends \CDetectedModel { //extends \CDetectedModel 
    
    public static $db;
    private $_tableName = 'section';
    
    private $_mod_page = false; // admin or front
    private $_mod_access = true; // true or false
    
    private $_level = 1;
    private $_type; // type panel
    
    private $_cowner = false;
    private $_tree = array();
    // protected $_sections;
    
    public function init() {
        self::$db = \init::app() -> getDBConnector(); // connected DB
        if(!$this->_mod_access) throw new \CException(\init::t('init','Not access this controller!'));
        $this -> _type = \init::app() -> _getPanel();
        $this->_cowner = \init::app() -> getOwner();
    }
    
    public function attributeNames() {
        
    }
    
    /**
     * 
     * @param type $attributes
     */
    public function getTree() {
        if(!$this->_cowner) return NULL;
        
        $sql = self::$db -> select($this->_tableName, 'sec', array('target' => 'main'))
                         -> fields('sec', array('SectionID', 
                                                'SectionParentID', 
                                                'SectionAlias',
                                                'SectionUrl',
                                                'SectionType',
                                                'SectionName'
                                                ));
        $sql ->condition('SectionType', $this -> _type, '=') 
             ->condition('SectionParentID', 0, '=')
             ->condition('OwnerID', $this->_cowner -> getOwnerID(), '=')
             ->condition('SectionInMenu', 0, '=')   
             ->condition('hidden', 0, '='); 

        $sections = $sql -> execute()->fetchAll(); 
 
        if(is_array($sections) and count($sections) > 0) :
            foreach($sections as $key=>$_section):
                $this->_tree[$_section -> SectionID] = (array)$_section;
                $this->_tree[$_section -> SectionID]['childs'] = $this->_getCreateTree((array)$_section, 0);
            endforeach;
        endif;
        
        
        
        return $this->_tree;
    }
    
    
    
    public function _getCreateTree( $section, $level ) {
        
        $tree = NULL;
	if(!is_array($section)) return false;
	
	if($section['SectionID'] > 0) {
		
                $sql = self::$db -> select($this->_tableName, 'sec', array('target' => 'main'))
                         -> fields('sec', array('SectionID', 
                                                'SectionParentID', 
                                                'SectionAlias',
                                                'SectionUrl',
                                                'SectionType',
                                                'SectionName'
                                                ));
                $sql ->condition('SectionType', $this -> _type, '=') 
                     ->condition('SectionParentID', $section['SectionID'], '=')
                     ->condition('OwnerID', $this->_cowner -> getOwnerID(), '=')
                     ->condition('SectionInMenu', 0, '=')   
                     ->condition('hidden', 0, '='); 

                $_childs = $sql -> execute()->fetchAll(); 
            
                
                
                if(is_array($_childs) and count($_childs) > 0) {
                    
                    foreach($_childs as $key => $_val):
                        $_subcat = (array)$_val;
                        $tree[$key] = $_subcat; 
                        if($_subcat['SectionParentID'] != 0)
                            $tree[$key]['childs'] = $this->_getCreateTree($_subcat, $level + 1);
                        
                    endforeach;
                    
                } 
                
                return $tree;
                
		
	} 
        
    }
    
   
}
