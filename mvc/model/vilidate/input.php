<?php

/*
 * model Input
 */

class Input extends \CDetectedModel {
    
    public static $db;

    private static $_tabelName = ['user'];
    
    public function init() {
    }
    
    public function attributeNames() {
        
    }
    
    /**
     * input ( create fields )
     */
    public function input() {       
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
