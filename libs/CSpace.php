<?php
/**
 * CSpace class file.
 *
 * @author Sergei Novickiy <edii87shadow@gmail.com>
 * @copyright Copyright &copy; 2013 
 */


class CSpace extends \CApplicationComponent
{
        /**
	 * default
	 */
        static private $ds = false;


	private $_total_space = false;
        private $_free_space = false;
        
	/**
	 * Constructor.
	 */
	public function __construct() {
		self::$ds = \init::getFrameworkPath();
                $this->setTotalSpace(disk_total_space(self::$ds));
                $this->setFreeSpace(disk_free_space(self::$ds));
	}
        
        public function init() {
            parent::init();
        } 

        /**
	 * Executes the widget.
	 * This method is called by 
	 */
	public function run()
	{
	}
        
        
        
	/**
	 * @return total space ds
	 */
	public function getTotalSpace() {
		return $this->_total_space;
	}

	/**
	 * @return free space ds
	 */
	public function getFreeSpace() {
		return $this->_free_space;
	}

	/**
         * type (int)$total_space
	 * @return total space ds
	 */
	protected function setTotalSpace( $total_space ) {
		return (empty($this->_total_space)) ? $this->_total_space = $total_space : $this->_total_space;
	}

	/**
         * type (int)$free_space
	 * @return free space ds
	 */
	protected function setFreeSpace( $free_space ) {
		return (empty($this->_free_space)) ? $this->_free_space = $free_space : $this->_free_space;
	}
        
        /**
         * convert bytes to kb,mb and other
         * type (int)$number
         * return (string)$format.
         */
        
        static public function getConvertBytes($number) {
            $len = strlen($number);
            
            if($len < 4) {
                return sprintf("%d b", $number);
            }
            
            if($len >= 4 && $len <=6) {
                return sprintf("%0.2f Kb", $number/1024);
            }
            
            if($len >= 7 && $len <=9) {
                return sprintf("%0.2f Mb", $number/1024/1024);
            }

            return sprintf("%0.2f Gb", $number/1024/1024/1024);
        }
        
}
