<?php

class HomeController extends \Controller
{
	public $layout = 'test'; //'column1'

	private $_model;

	public function actionDB()
	{
            
            
            
            // \init::app()->setTheme( false );
            
            // connect db from controlers
            $_db = new CDatabase( 'main', NULL);
            
            
            // CI (mysql)
            //$_connector = $_db->getConnection()->query("SELECT * FROM section")->result_array();
            
            // CI (pdo)
            // $_connector = $_db->getConnection()->query("SELECT * FROM section")->result_array();
            
            // drupal
             $options['target'] = 'main';   
             $args = array();
             
             $_connector = $_db->getConnection();
             $_dbdefionitions = $_db->getDatabaseDefinition();
             
             $query_res = $_connector -> query("SELECT * FROM section", $args, $options)-> fetchAll();
             //$query_res = $_connector -> select('section', 's', $options) 
                               // -> fields('s', array('SectionID')) 
                                //-> range(0, 1)
                               // -> addTag('section_access')    
                                //-> execute()
                               // -> fetchObject();
             
//             $secondary = \init::app() -> getDBConnector( 'secondary' );
//             $phones_res = $secondary -> query("SELECT * FROM phones LIMIT 1", 
//                     array(), 
//                     array('target' => 'secondary'))-> fetchAll();

            
             $data = ['blaaaa'];
             $this->render('db', array(
			'data'=>$data,
		));
		
	}
        
        /**
	 * Displays a particular model.
	 */
	public function actionTest()
	{
            
                
               \init::app()->setTheme( 'column2' );
                
            
               $dataProvider = ['blaaa', 'ddddd'];
            
                $this->render('index', array(
			'dataProvider'=>$dataProvider,
		));  
	}

	public function actionIndex()
	{
            $this->layout( 'test' );
            
//            $_db = new CDatabase( 'main', NULL);
//            
//            $options['target'] = 'main';   
//            $args = array();
//             
//            $_connector = $_db->getConnection();
//            $_dbdefionitions = $_db->getDatabaseDefinition();
//            
//            $front_section = $_connector -> query("SELECT s.SectionID, s.hidden, s.SectionAlias, s.SectionInMenu, s.SectionParentID, s.SectionName, s.SectionTitle, s.SectionDescription, s.SectionKeywords, s.SectionUrl "
//                                            . " FROM `section` AS s WHERE SectionType = 'front'", $args, $options)-> fetchAll();
//            
//            
////            echo '<pre>';
////            var_dump($front_section);
////            echo '</pre>';
////            die("STOP");
//            
//            
            $this->render('index'); 
	}
       
        public function actionMenu() {
            
            $_db = new CDatabase( 'main', NULL);
            
            $options['target'] = 'main';   
            $args = array();
             
            $_connector = $_db->getConnection();
            $_dbdefionitions = $_db->getDatabaseDefinition();
            
            $front_section = $_connector -> query("SELECT s.SectionID, s.hidden, s.SectionAlias, s.SectionInMenu, s.SectionParentID, s.SectionName, "
                                            . "s.SectionTitle, s.SectionDescription, s.SectionKeywords, s.SectionUrl "
                                            . " FROM `section` AS s WHERE SectionType = 'front'", $args, $options)-> fetchAll();
            
            $this->render('menu', array(
                'front_section'=>$front_section,
                'sections_actual' => \init::app()->getTreeSection(),
            ));
        }
        
        public function actionCarousel() {
            $this->render('carousel');
        }
}
