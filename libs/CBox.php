<?php
/**
 * CBox class file.
 *
 * @author Sergei Novickiy <edii87shadow@gmail.com>
 * @copyright Copyright &copy; 2013 
 */


class CBox extends \CApplicationComponent
{
        /**
	 * default themes base path
	 */
	const DEFAULT_BASEPATH = 'schemas'; //.DS._detected.DS
    
        private $widgets;
        
	private $_name;
	private $_basePath;
	private $_baseUrl;

        public $boxes=array();
        
        public $view = array();
        public $data = array();
        
        public $_boxesDefinition = array();
        
        public $themeClass = 'CBoxLayout';
        
	/**
	 * Constructor.
	 */
	public function __construct($name = false,$basePath = false,$baseUrl = false) {
		$this->_name     =   $name;
		$this->_baseUrl  =   $baseUrl;
		$this->_basePath =   $basePath;
                
                $this-> _loadWebDefinitions(); // load WebDefinitions
                
                
	}
        
        public function init() {
            parent::init();
        } // init load Box

        /**
	 * Executes the widget.
	 * This method is called by {@link CBaseController::endWidget}.
	 */
	public function run()
	{
	}
        
        /**
	 * @param string $name name of the theme to be retrieved
	 * @return CTheme the theme retrieved. Null if the theme does not exist.
	 */
	public function getTheme($name) {
                
		$themePath = self::DEFAULT_BASEPATH.$this->getBasePath().DS._detected.DS.'layout';
                
                
		if(is_dir($themePath)) {
			$class = \init::import($this->themeClass, true);
			return new $class($name, $themePath);
		}
		else
			return null;
	}
        
        protected function _loadWebDefinitions() {
             $_definitions = \init::app()->getDefinition();
             if( isset($_definitions) and is_array($_definitions) ) {
                 if(isset($_definitions['boxesDefinition']) and is_array($_definitions['boxesDefinition']))
                    $this->setBoxesDefinition( $_definitions['boxesDefinition'] );
             }
        }
        
        /**
         * register variable, settings boxesDefinition
         * @return boxesDefinition ['name_controllers'][['aling']   => 'left', 
         *                                              ['name']    => 'index', 
         *                                              ['module']  => 'index', 
         *                                              ['layout']  => 'index']
         */
        public function getBoxesDefinition() {
            return $this-> _boxesDefinition;
        }
        protected function setBoxesDefinition( array $_boxesDefinition ) {
            $this-> _boxesDefinition = $_boxesDefinition;
            return $this-> _boxesDefinition;
        }
        
        /**
	 * Creates a new widget based on the given class name and initial properties.
	 * @param CBaseController $owner the owner of the new widget
	 * @param string $className the class name of the widget. This can also be a path alias (e.g. system.web.widgets.COutputCache)
	 * @param array $properties the initial property values (name=>value) of the widget.
	 * @return CWidget the newly created widget whose properties have been initialized with the given values.
	 */
	public function createBox($owner,$className,$properties=array())
	{
		$className=\init::import($className,true);
		$box = new $className($owner);

		if(isset($this->boxes[$className]))
			$properties=$properties===array() ? $this->boxes[$className] : CMap::mergeArray($this->boxes[$className],$properties);

               // load scins
                
		foreach($properties as $name=>$value)
			$box->$name=$value;
		return $box;
	}
        
        
	/**
	 * @return string theme name
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * @return string the relative URL to the theme folder (without ending slash)
	 */
	public function getBaseUrl() {
		return $this->_baseUrl;
	}

	/**
	 * @return string the file path to the theme folder
	 */
	public function getBasePath() {
		return $this->_basePath;
	}

        
        
	
        //show box by id or by alias
        public function getBox( $boxID, $layout = false ) {
            $_controller = array();
            if(is_string($boxID)) {
                if(strpos($boxID, '/')) :
                    $_run = explode('/', trim($boxID));
//                    if(_detected == 'admin') {
//                         \init::app()->setTheme( false );
//                    } else { 
//                        \init::app()->setTheme( false ); 
//                    }
                    
                    //\init::app()->setTheme( false ); 
                    
                    
                    
                
                    list($controller) = \init::app()->createController($_run[0], null, true);
                    $controller -> layout = false;
                    
//                    $controller->init();
//                    
//                    echo "<pre>";
//                    var_dump( $controller );
//                    echo "</pre>"; die('stop');
//                    
//                    $method='action'.ucfirst((string)$_run[1]);
//                    $_run = $controller->$method();
                    
                     $controller->init();
                     $controller->createAction((string)$_run[1]) -> run();
                    
                   
                endif;
            } elseif( is_array( $boxID ) ) {
                echo "<hr /> box load";
                echo "<pre>";
                var_dump( $boxID );
                echo "</pre>";
            
            } else {
                return null;
            }
            
        }

        
        public function boxHeader($data='',$mode='') {
                global $CORE, $currentBoxParams;
                //$boxes = $CORE->getBoxesDefinition();

                $boxparams = $currentBoxParams;

                $input = $CORE->getInput();
                $config = $CORE->getConfig();
                $setting = $config;
                $user = $CORE->getUser();
                $clientType = $config['ClientType'];
                $windowMode = $input['windowMode'];
                if(!empty($input['windowMode'])) {
                        $templateFile = $config['RootPath'].'templates/'.$clientType.'/layouts/'.$config['Layout']."/".$windowMode."/boxHeader.tpl.php";
                } else {
                        $templateFile = $config['RootPath'].'templates/'.$clientType.'/layouts/'.$config['Layout'].'/boxHeader.tpl.php';
                }	
                $out=$data;
                //print_r($setting);
                if(!empty($boxparams['boxtitle'])) { 
                        $out['title'] = $boxparams['boxtitle']; 
                } else { 
                        if(preg_match('#\.#is',$out['title'])) { 
                                $out['title'] = lang($out['title']); 
                        } 
                }


                $tabs = $data['tabs'];
                if(!empty($tabs) && !is_array($tabs)) {
                        $tabLink=$input['tabLink'];
                        $DS = new DataSource('main');
                        $tabsRS = $DS->query("SELECT * FROM TabLink WHERE TabLinkAlias='$tabs' ORDER BY TabLinkPosition");
                        $out['DB']['tabs'] = $tabsRS;
                        $out['tabs'] = $tabsRS;
                        //print_r($out['DB']['tabs']);
                        if($tabLink==1) {
                                $CORE->setSessionVar('tabLink',$tabsRS[0]['TabLinkID']);
                                $tabLink = $tabsRS[0]['TabLinkID'];
                        } elseif(!empty($tabLink)) {
                                $CORE->setSessionVar('tabLink',$tabLink);
                        }
                }
                if(is_file($templateFile)) {
                        include($templateFile);
                }
        }

        public function boxFooter($data='',$mode='') {
                global $CORE;
                $boxes = $CORE->getBoxesDefinition();
                $input = $CORE->getInput();
                $config = $CORE->getConfig();
                $setting = $config;
                $user = $CORE->getUser();
                $clientType = $config['ClientType'];
                $windowMode = $input['windowMode'];
                if(!empty($input['windowMode'])) {
                        $templateFile = $config['RootPath'].'templates/'.$clientType.'/layouts/'.$config['Layout']."/$windowMode/boxFooter.tpl.php";
                } else {
                        $templateFile = $config['RootPath'].'templates/'.$clientType.'/layouts/'.$config['Layout'].'/boxFooter.tpl.php';
                }		
                $out=$data;
                if(is_file($templateFile)) {
                        include($templateFile);
                }	

        }
        
        
        
        /**
         * 
         * runControllers
         * 
         */
        public function runController( $route, $layout = false ) {
             \init::app()->setTheme( $layout );
            
             /*
              * должно не равнятся текущему контролеру что бы не было зацикливания
              */
             
            $p = \init::app()->createController('test'); // name controllers
            $run = $p[0]->createAction('view')-> run(); // load action controllers
            
            //$p = \init::app()->createController('hello'); // name controllers
            //$run = $p[0]->createAction('db')-> run(); // load action controllers
        }
}
