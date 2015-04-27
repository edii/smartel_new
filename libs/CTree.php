<?php
/**
 * CSpace class file.
 *
 * @author Sergei Novickiy <edii87shadow@gmail.com>
 * @copyright Copyright &copy; 2013 
 */


class CTree extends \CApplicationComponent
{

        private $data = array();
        private $_tree = array();
    
        private $type = array('id' => '_id', 'p_id' => 'parent_id');

        /**
        * Fetches children data of the givven node
        *
        * @access private
        * @param int $id Node ID
        * @return array Children data
        */
        protected function getChildren($id) {
            $res = array();
            foreach ($this->data as $node) :
                if ($node[$this->type['p_id']] == $id) {
                    $res[] = $node;
                }
            endforeach;
            
            return $res;
        }

        /**
        * Builds sub tree from given nodes data
        *
        * @access private
        * @param array $data Nodes data
        * @return string XHTML sub tree
        */
       protected function buildNodes($data) {
            $tree = array();
            
            foreach ($data as $key => $row) {
                $childNodes = false;
                $children = $this->getChildren($row[$this->type['id']]);
                
                if (count($children) > 0) {
                    $childNodes = $this->buildNodes($children);
                }

                $tree[$key] = $row;
                if (!empty($childNodes)) {
                    $tree[$key]['childs'] = $childNodes;
                }
                
            }
            
            
            return $tree;
        }

        /**
        * Class constructor
        *
        * @access public
        * @param array $data Tree data [id, parent, text]
        * @param mixed $callback Callback function to retrieve a node
        * String function name or Array(class name, function name)
        * For more info see PHP's call_user_func()
        * @return void
        */
        public function set($data, $_type) {
            $this->data = $data;
            $this->type = $_type;
            return $this;
        }
        
        /**
        * Builds the tree
        *
        * @access public
        * @return string XHTML tree
        */
        public function get() {
            $rootNodes = $this->getChildren(0);
            return $this->_tree = $this->buildNodes($rootNodes);
        }
        
}
