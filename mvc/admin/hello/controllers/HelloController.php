<?php

class HelloController extends \Controller
{
	public $layout = 'column1'; //'column1'

	private $_model;

	
	public function actionIndex() {
            
            $this->layout( 'column2' ); //'column2'
            
            echo "<hr /> session";
            // вид 1
            //\init::app() -> getSession() -> set_userdata(array('test' => 'params'));
            //$_session = \init::app() -> getSession() -> all_userdata();
            
            // вариант 2
            //$_sess = \init::app() -> getSession();
            //$_session = $_sess->setSession(array('test' => 'params'))-> all_userdata();
            
            // вариант 3
            $_session = \init::app() -> getSession() -> set_userdata(array('test' => 'params')) -> all_userdata();
            
            
            echo "<pre>";
            var_dump( $_session );
            echo "</pre>";
            
            
            $this ->redirect('hello/login');
            
            // $this->render('index', array(
	    //		'dataProvider'=>'Admin',
            // ));

	}
        
        /* controller Login */
        public function actionLogin() {
            echo "this login controllers!";
        }
        /* end */
        
        public function actionTest1($tt = "BLAAAAAAAAA") {
            echo "action Test1".$tt;
        }
        
        
        public function actionDB() {
            $this->layout( 'column1' );
            
            echo "DB";
            
            $this->render('db', array(
			'dataProvider'=>'Admin',
            ));
            

	}
        
        public function actionTest() {
//            $this->layout( false );
//            
//            echo "load test params!";
//            
//            $this->render('test', array(
//			'dataProvider'=>'Admin',
//            ));
           
        }
}
