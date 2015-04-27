<?php
/**
 * CBox class file.
 *
 * @author Sergei Novickiy <edii87shadow@gmail.com>
 * @copyright Copyright &copy; 2013 
 */


class COwner extends \CApplicationComponent {
    
        /* settings */
        public static $db;
        private $_table_name = 'owner';
        private $_host;
        
        /* DB */
        private $_owner;
       
    
        /* layout */
        public $data = array();
        
       
	/**
	 * Constructor.
	 */
	public function __construct() {
            self::$db = \init::app() -> getDBConnector(); // connected DB    
            $this -> _host = \init::app() -> getRequest() -> getHost();
	}
        
        public function init() {
            parent::init();
            $this->getOwner();
        } // init load Box

        /**
	 * Executes the widget.
	 * This method is called by {@link CBaseController::endWidget}.
	 */
	public function run() {
	}
        
        public function getOwnerID() {
            return (isset($this->_owner['OwnerID']) and !empty($this->_owner['OwnerID'])) ? $this->_owner['OwnerID'] : null;
        }
        
        public function getOwnerCode() {
            return (isset($this->_owner['OwnerCode']) and !empty($this->_owner['OwnerCode'])) ? $this->_owner['OwnerCode'] : null;
        }
        /**
         * Detected Owners ( ID )
         */
        public function getOwner() {
            $_owner = null; 
            if($this -> _host) {
                
                $_ownreDB = self::$db  -> select($this->_table_name, 'o', array('target' => 'main'))
                           -> fields('o', array('OwnerID', 
                                                'UserID', 
                                                'TimeCreated',
                                                'TimeSaved',
                                                'OwnerCode',
                                                'OwnerStatus',
                                                'OwnerType',
                                                'OwnerDomain'
                                                ));
                $_ownreDB ->condition('OwnerDomain', $this -> _host, '=');
                $_arr_owner = $_ownreDB -> execute()->fetchAssoc();
                
                if(is_array($_arr_owner) and count($_arr_owner) > 0) $_owner = $_arr_owner;
                
            }  
            
            if(!$this->_owner)
                $this->_owner = $_owner;
            
            return $this;
        }
        
        
}
