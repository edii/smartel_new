<?php
//Передавать конструктору масив 

class CJSON implements JsonSerializable {
	public $_result;
    
        public function __construct(array $_result ) {
                $this->_result = $_result;
            
        }

        public function jsonSerialize() {
            return $this->_result;
        }
}