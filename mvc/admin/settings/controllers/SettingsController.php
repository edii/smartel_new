<?php

class SettingsController extends \Controller
{
	public $layout = 'dashboard';

	private $_users;
        
        /**
         * construct
         */
        public function init() {
           $this -> _users = \init::app() -> getModels('auth/users');
        }
	
	public function actionIndex() {
            
             $this->render('index');
	}
        
        /*
         * (sidebar left) bandwidth-transfer-widget
         */
        public function actionBandwidthTransfer() {
            
             $this->render('bandwidth_transfer', array(
                 'validate' => $this -> _users -> getRight()
             ));
	}
        
        /*
         * (sidebar left) disk-space-widget
         */
        public function actionDiskSpace() {
            $_result = array();
            
            $_obj = \init::app() -> getCSpace();
            if(is_object($_obj)) {
                $_result['total'] = $_obj::getConvertBytes($_obj -> getTotalSpace());
                $_result['free'] = $_obj::getConvertBytes($_obj -> getFreeSpace());
                
                $_p = (($_obj -> getTotalSpace() - $_obj -> getFreeSpace()) / $_obj -> getTotalSpace()) * 100;
                $_result['parcent'] = ceil($_p); 
            }
           
             $this->render('disk_space', array(
                 'validate' => $this -> _users -> getRight(),
                 '_space' => $_result
             ));
	}
        
        
        /*
         * (sidebar left) stats-widget
         */
        public function actionStats() {
            
             $this->render('stats', array(
                 'validate' => $this -> _users -> getRight()
             ));
	}
        
        /*
         * (sidebar left) site-info-widget
         */
        public function actionSiteInfo() {
            
             $this->render('site_info', array(
                 'validate' => $this -> _users -> getRight()
             ));
	}
}
