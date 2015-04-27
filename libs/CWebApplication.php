<?php
/**
 * CWebApplication class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

class CWebApplication extends \CApplication {
        /**
	 * default themes base path
	 */
	const DEFAULT_BASEPATH = 'schemas'; //.DS._detected.DS
    
	/**
	 * @return string the route of the default controller, action or module. Defaults to 'site'.
	 */
	public $defaultController = 'mvc';
	/**
	 * @var mixed the application-wide layout. Defaults to 'main' (relative to {@link getLayoutPath layoutPath}).
	 * If this is false, then no layout will be used.
	 */
	public $layout = 'schemas';
	/**
	 * @var array mapping from controller ID to controller configurations.
	 * Each name-value pair specifies the configuration for a single controller.
	 * A controller configuration can be either a string or an array.
	 * If the former, the string should be the class name or
	 * {@link Base::getPathOfAlias class path alias} of the controller.
	 * If the latter, the array must contain a 'class' element which specifies
	 * the controller's class name or {@link Base::getPathOfAlias class path alias}.
	 * The rest name-value pairs in the array are used to initialize
	 * the corresponding controller properties. For example,
	 * <pre>
	 * array(
	 *   'post'=>array(
	 *      'class'=>'path.to.PostController',
	 *      'pageTitle'=>'something new',
	 *   ),
	 *   'user'=>'path.to.UserController',,
	 * )
	 * </pre>
	 *
	 * Note, when processing an incoming request, the controller map will first be
	 * checked to see if the request can be handled by one of the controllers in the map.
	 * If not, a controller will be searched for under the {@link getControllerPath default controller path}.
	 */
	public $controllerMap=array();
	/**
	 * @var array the configuration specifying a controller which should handle
	 * all user requests. This is mainly used when the application is in maintenance mode
	 * and we should use a controller to handle all incoming requests.
	 * The configuration specifies the controller route (the first element)
	 * and GET parameters (the rest name-value pairs). For example,
	 * <pre>
	 * array(
	 *     'offline/notice',
	 *     'param1'=>'value1',
	 *     'param2'=>'value2',
	 * )
	 * </pre>
	 * Defaults to null, meaning catch-all is not effective.
	 */
	public $catchAllRequest;

	/**
	 * @var string Namespace that should be used when loading controllers.
	 * Default is to use global namespace.
	 * @since 1.1.11
	 */
	public $controllerNamespace;

	private $_controllerPath;
	private $_viewPath;
	private $_systemViewPath;
	private $_layoutPath;
	private $_controller;
	private $_theme;


        private $_definition = array(); 
        
        /* load model */
        private $_model = false;

        private $_tree_sections = null;
        private $_tree_section = null;
        private $_tree_params = false;
        
        /* language */
        private $_language;

        /**
	 * Processes the current request.
	 * It first resolves the request into controller and action,
	 * and then creates the controller to perform the action.
	 */
	public function processRequest()
	{
		if(is_array($this->catchAllRequest) && isset($this->catchAllRequest[0]))
		{
			$route=$this->catchAllRequest[0];
			foreach(array_splice($this->catchAllRequest,1) as $name=>$value)
				$_GET[$name]=$value;
		}
		else
			$route=$this->getUrlManager()->parseUrl($this->getRequest());
                
                /* detected language and delete route url */
                if($_langs = explode('/', $route) and is_array($_langs) and count($_langs) > 0) {
                    if(\init::app() -> getCLanguage() -> issetLanguage( (string)$_langs[0] ))
                        unset($_langs[0]);
                    
                    $route=  implode('/', $_langs);
                }
                
		$this->runController($route);
	}

        /**
         * Выгрузка дерева Section
         * return array $_tree_section
         */
        
        public function getTreeSection() {
            return $this->_tree_section;
        }
        
        public function getTreeSections() {
            return $this->_tree_sections;
        }
        
        public function getTreeParams() {
            return $this->_tree_params;
        }
        
//        public function getLanguage() {
//            return $this->_language;
//        }
        
        /**
         * Загрузка дерева Section
         * input (array) $_tree
         * return array $_tree_section
         */
        
        protected function setTreeSection( $_tree ) {
            if(isset($_tree) and !empty($_tree)) {
                $this->_tree_section = $_tree;
            }
            return $this;
        }
        
        protected function setTreeSections( $_trees ) {
            if(isset($_trees) and !empty($_trees)) {
                $this->_tree_sections = $_trees;
            }
            return $this;
        }
        
        protected function setTreeParams( $_params ) {
            if(isset($_params) and !empty($_params)) {
                $this->_tree_params = $_params;
            }
           return $this;
        }
        
//        public function setLanguage( $_language ) {
//            if(isset($_language) and !empty($_language)) {
//                $this->_language = $_language;
//            }
//           return $this;
//        }
        
	/**
	 * Registers the core application components.
	 * This method overrides the parent implementation by registering additional core components.
	 * @see setComponents
	 */
	protected function registerCoreComponents()
	{
		parent::registerCoreComponents();

		$components=array(
                        // detected session
			'session'=>array(
                            'class'=>'CSession',
			),
                         // detected boxes
			'CBox'=>array(
                            'class'=>'CBox',
			),
                    
                        // detected space
                        'space' => array(
                            'class' => 'CSpace',
                        ),
                    
                        // detected tree
                        'tree' => array(
                            'class' => 'CTree',
                        ),
                    
                        // detected language
                        'language' => array(
                            'class' => 'CLanguage',
                        ),
                    
			'assetManager'=>array(
                            'class'=>'CAssetManager',
			),
			'user'=>array(
                            'class'=>'CWebUser',
			),
			'themeManager'=>array(
                            'class'=>'CThemeManager',
			),
			'authManager'=>array(
                            'class'=>'CPhpAuthManager',
			),
			'clientScript'=>array(
                            'class'=>'CClientScript',
			),
                       
		);

		$this->setComponents($components);
	}
        
        /**
         * return CLanguage (array)
         */
        public function getCLanguage() {
            return $this->getComponent( 'language' );
        }
        
        /**
         * return CTree (array)
         */
        public function getCTree() {
            return $this->getComponent( 'tree' );
        }
        
        /**
         * return CSpace DS
         */
        public function getCSpace() {
            return $this->getComponent( 'space' );
        }

	/**
	 * @return IAuthManager the authorization manager component
	 */
	public function getAuthManager()
	{
		return $this->getComponent('authManager');
	}

	/**
	 * @return CAssetManager the asset manager component
	 */
	public function getAssetManager()
	{
		return $this->getComponent('assetManager');
	}

	/**
	 * @return CHttpSession the session component
	 */
	//public function getSession()
	//{
	//	return $this->getComponent('session');
	//}

	/**
	 * @return CWebUser the user session information
	 */
	public function getUser()
	{
		return $this->getComponent('user');
	}

	/**
	 * Returns the view renderer.
	 * If this component is registered and enabled, the default
	 * view rendering logic defined in {@link CBaseController} will
	 * be replaced by this renderer.
	 * @return IViewRenderer the view renderer.
	 */
	public function getViewRenderer()
	{
		return $this->getComponent('viewRenderer');
	}

	/**
	 * Returns the client script manager.
	 * @return CClientScript the client script manager
	 */
	public function getClientScript()
	{
		return $this->getComponent('clientScript');
	}

	/**
	 * Returns the widget factory.
	 * @return IWidgetFactory the widget factory
	 * @since 1.1
	 */
	public function getBox()
	{
		return $this->getComponent('CBox');
	}


	/**
	 * @return CTheme the theme used currently. Null if no theme is being used.
	 */
	public function getTheme() {
            
                // echo "theme load = ".$this->_theme; die('stop');
            
                if(is_string($this->_theme))
                    $this->_theme = $this->getBox()->getTheme($this->_theme);
                
                //var_dump( $this->_theme ); die('stop');
                
		return $this->_theme;
	}

	/**
	 * @param string $value the theme name
	 */
	public function setTheme($value) {
		$this->_theme = $value;
	}

	/**
	 * Creates the controller and performs the specified action.
	 * @param string $route the route of the current request. See {@link createController} for more details.
	 * @throws CHttpException if the controller could not be created.
	 */
	public function runController($route) {
                
            
		if(($ca=$this->createController($route))!==null) {
                        
			list($controller,$actionID)=$ca;
                        $this->_definition = $this->getLoadDatabaseDefinition( $controller->getId() );
			$oldController=$this->_controller;
			$this->_controller=$controller;
			$controller->init();
			$controller->run($actionID);
			$this->_controller=$oldController;
                        
                        
		}
		else
			throw new CHttpException(404,\init::t('init','Unable to resolve the request "{route}".',
				array('{route}'=>$route===''?$this->defaultController:$route)));
	}

	/**
	 * Creates a controller instance based on a route.
	 */
	public function createController($route, $owner=null, $_box = false) {
                // detected language
//                $_l_code = false;
//                if($_tree_route = explode('/', $route) and is_array($_tree_route) and count($_tree_route) > 0) {
//                    \init::app()->setLanguage( $this->getLangs($_tree_route[0]) );
//                    
//                    if($_lang = \init::app()->getLanguage() and is_array($_lang) and count($_lang) > 0) {
//                        if($_lang[0]->lang_code == $_tree_route[0]) {
//                            array_shift($_tree_route);
//                            $route = implode('/', $_tree_route);
//                        }
//                            
//                    }
//                            
//                } 
                // end
            
                $this -> parseAlies($route);
                
                if($owner===null)
                    $owner=$this;
                if( trim($route,'/') !== '' and _detected == 'front') {
                    
                    // load section
                    if($_section = $this->getTreeSection() and is_array($_section) and count($_section) > 0 and $_box == false) {
                        if(isset($_section['Controller']) and !empty($_section['Controller'])) {
                            $_sec_action = (isset($_section['Action']) and !empty($_section['Action'])) ? $_section['Action'] : 'index';
                            $route = $_section['Controller'].'/'.$_sec_action; 
                        }
                        // load params
                        if($_params = $this->getTreeParams() and isset($route) and !empty($route)) {
                            $manager=$this->getUrlManager();
                            $manager->parsePathInfo((string)$_params);
                        }
                        
                        
                    }    
                    
                    
                } // load DB controller and action
                else if(($route=trim($route,'/'))==='') {
                    $route = $owner->defaultController;
                } else if( _detected == 'admin' and trim($route,'/') !== '' ) {
                    
                        // load section
                        if($_section = $this->getTreeSection() and is_array($_section) and count($_section) > 0 and $_box == false) {
                            
                            
                            if(isset($_section['Controller']) and !empty($_section['Controller'])) {
                                $_sec_action = (isset($_section['Action']) and !empty($_section['Action'])) ? $_section['Action'] : 'index';
                                $route = $_section['Controller'].'/'.$_sec_action; 
                            }
                            // load params
                            if($_params = $this->getTreeParams() and isset($route) and !empty($route)) {
                                $manager=$this->getUrlManager();
                                $manager->parsePathInfo((string)$_params);
                            }
                        }   
                   
                } else {
                    $route= false; 
                }
                
                
                
                
                
//                    if(($route=trim($route,'/'))==='')
//                            $route=$owner->defaultController;
                    $caseSensitive=$this->getUrlManager()->caseSensitive;
                    $route.='/';



                    while(($pos=strpos($route,'/'))!==false) {
                            $id=substr($route,0,$pos);


                            if(!preg_match('/^\w+$/',$id))
                                    return null;
                            if(!$caseSensitive)
                                    $id=strtolower($id);
                            $route=(string)substr($route,$pos+1);

                            if(isset($id) and !empty($id))  {
                                $_path = $this->getMvc().DS._detected.DS.$id.DS.'controllers'.DS;
                                $this->setControllerPath( $_path );
                            }    


                            if(!isset($basePath))  // first segment
                            {
                                    if(isset($owner->controllerMap[$id]))
                                    {
                                            return array(
                                                    \init::createComponent($owner->controllerMap[$id],$id,$owner===$this?null:$owner),
                                                    $this->parseActionParams($route),
                                            );
                                    }

                                    if(($module=$owner->getModule($id))!==null)
                                            return $this->createController($route,$module);

                                    $basePath=$owner->getControllerPath();
                                    $controllerID='';
                            }
                            else
                                    $controllerID.='/';
                            $className=ucfirst($id).'Controller';
                            $classFile=$basePath.DIRECTORY_SEPARATOR.$className.'.php';

                            //echo "route = ".$classFile; die('stop');

                            if($owner->controllerNamespace!==null)
                                    $className=$owner->controllerNamespace.'\\'.$className;

                            if(is_file($classFile))
                            {
                                    if(!class_exists($className,false))
                                            require($classFile);
                                    if(class_exists($className,false) && is_subclass_of($className,'CController'))
                                    {
                                            $id[0]=strtolower($id[0]);
                                            return array(
                                                    new $className($controllerID.$id,$owner===$this?null:$owner),
                                                    $this->parseActionParams($route),
                                            );
                                    }
                                    return null;
                            }
                            $controllerID .= $id;
                            $basePath.= DS.$id;
                    }
                 
	}

        
        /**
         * Detected Alies
         * params $route
         * return (controller, action)
         */
        
        protected function parseAlies($route) {
            $_type = \init::app() -> _getPanel();
            $_owner_code = \init::app() -> getOwner() -> getOwnerCode();
            $_db = \init::app() -> getDBConnector();
            
            if(!strpos($route,'/')){
                $_route = $route.'/';
            } else {
                $_route = $route.'/';
            }
            
            // parce url
            $_tree = explode('/', $_route);
            if(is_array($_tree) and count($_tree) > 0) {
                $_arr_tree = array();
                $_count = count($_tree) - 1;
                
                for($i = 0; $i <= $_count; $i++) {
                   if($i > 0) 
                    $_arr_tree[] = "'".implode('/', array_slice ($_tree, 0,  $i))."'";
                }
                
                if(is_array($_arr_tree) and count($_arr_tree) > 0) {
                    $_route = implode(',', $_arr_tree);
                }
                
            }
            
            
            
            $section = $_db -> query( "SELECT SectionId, 
                                                 SectionController as Controller, 
                                                 SectionAction as Action, 
                                                 SectionUrl as url,
                                                 Sectionname as name
                                                 
                                          FROM section 
                                          WHERE SectionUrl IN (".$_route.") 
                                                            AND OwnerID = '".$_owner_code."'
                                                            AND SectionType = '".$_type."'    
                                                            AND hidden = 0" ) -> fetchAll(); 
            
            
            
                if(is_array($section) and count($section) > 0) {
                    $_trees = array();
                    foreach($section as $key => $value) {
                       $_trees[$key] = (array)$value; 
                    }
                    $this->setTreeSections($_trees);
                    
                    $_tree = array_pop($_trees);
                    $this->setTreeSection($_tree);
                    // load params
                    if($_post = strrpos($route, $_tree['url']) !== false) {
                        $_start = $_post + strlen($_tree['url']);
                        $_end = strlen($route);
                       
                        $_params = substr($route, $_start, $_end);
                        $this->setTreeParams( $_params );
                    }
                   
                    return $this;
                    
                } else {
                    return null;
                }
                
            
        }
        
        
        protected function getLangs( $_code = false ) {
            $_db = \init::app() -> getDBConnector();
           
            $_code_lang = htmlspecialchars(stripslashes(trim($_code)));
            if($_settings = \init::app() -> getSettings() and is_array($_settings)) {
                $_lang = $_settings['lang'];
                if(!$_code) $_code_lang = $_lang;
            } 
            
            $_where = "";
            if($_code_lang) {
                $_where .= " AND `LanguageCode` = '".$_code_lang."'";
            } else {
                 $_where .= " AND `LanguageIsDefault` = 1";
            }
            
            
            $_q_lang = $_db -> query( "SELECT `LanguageID` as `lang_id`, 
                                                  `LanguageCode` as `lang_code`, 
                                                  `LanguageName` as `lang_name`, 
                                                  `LanguageIsDefault` as `lang_def`, 
                                                  `LanguageIcon` as `lang_icon`,
                                                  `LanguageIconActive` as `lang_icon_active`,
                                                  `LanguagePosition` as `lang_pos`,
                                                  `LanguageLocale` as `lang_locale`
                                          FROM `language` 
                                          WHERE `LanguageID` <> 0 AND `hidden` = 0 ". $_where) -> fetchAll();
            
            if(is_array($_q_lang) and count($_q_lang) >0 ) {
                $db_lang = $_q_lang;
            } else {
                return $_db -> query( "SELECT `LanguageID` as `lang_id`, 
                                                  `LanguageCode` as `lang_code`, 
                                                  `LanguageName` as `lang_name`, 
                                                  `LanguageIsDefault` as `lang_def`, 
                                                  `LanguageIcon` as `lang_icon`,
                                                  `LanguageIconActive` as `lang_icon_active`,
                                                  `LanguagePosition` as `lang_pos`,
                                                  `LanguageLocale` as `lang_locale`
                                          FROM `language` 
                                          WHERE `LanguageID` <> 0 AND `hidden` = 0 AND `LanguageIsDefault` = 1 ORDER BY LanguageID ASC LIMIT 1") -> fetchAll();
            }
            
            return $db_lang;
        }
        
	/**
	 * Parses a path info into an action ID and GET variables.
	 * @param string $pathInfo path info
	 * @return string action ID
	 */
	protected function parseActionParams($pathInfo) {
            
		if(($pos=strpos($pathInfo,'/'))!==false)
		{
			$manager=$this->getUrlManager();
			$manager->parsePathInfo((string)substr($pathInfo,$pos+1));
			$actionID=substr($pathInfo,0,$pos);
			return $manager->caseSensitive ? $actionID : strtolower($actionID);
		}
		else
			return $pathInfo;
	}

	/**
	 * @return CController the currently active controller
	 */
	public function getController()
	{
		return $this->_controller;
	}

	/**
	 * @param CController $value the currently active controller
	 */
	public function setController($value)
	{
		$this->_controller=$value;
	}

	/**
	 * @return string the directory that contains the controller classes. Defaults to 'protected/controllers'.
	 */
	public function getControllerPath()
	{
		if($this->_controllerPath!==null)
			return $this->_controllerPath;
		else
			return $this->_controllerPath = $this->getBasePath().DIRECTORY_SEPARATOR.'controllers';
	}

	/**
	 * @param string $value the directory that contains the controller classes.
	 * @throws CException if the directory is invalid
	 */
	public function setControllerPath($value)
	{
		if(($this->_controllerPath=realpath($value))===false || !is_dir($this->_controllerPath))
			throw new CException(\init::t('init','The controller path "{path}" is not a valid directory.',
				array('{path}'=>$value)));
	}

	/**
	 * @return string the root directory of view files. Defaults to 'protected/views'.
	 */
	public function getViewPath()
	{
		if($this->_viewPath!==null)
			return $this->_viewPath;
		else
			return $this->_viewPath=$this->getBasePath().DIRECTORY_SEPARATOR.'views';
	}

	/**
	 * @param string $path the root directory of view files.
	 * @throws CException if the directory does not exist.
	 */
	public function setViewPath($path)
	{
		if(($this->_viewPath=realpath($path))===false || !is_dir($this->_viewPath))
			throw new CException(\init::t('init','The view path "{path}" is not a valid directory.',
				array('{path}'=>$path)));
	}

	/**
	 * @return string the root directory of system view files. Defaults to 'protected/views/system'.
	 */
	public function getSystemViewPath()
	{
		if($this->_systemViewPath!==null)
			return $this->_systemViewPath;
		else
			return $this->_systemViewPath=$this->getViewPath().DIRECTORY_SEPARATOR.'system';
	}

	/**
	 * @param string $path the root directory of system view files.
	 * @throws CException if the directory does not exist.
	 */
	public function setSystemViewPath($path)
	{
		if(($this->_systemViewPath=realpath($path))===false || !is_dir($this->_systemViewPath))
			throw new CException(\init::t('init','The system view path "{path}" is not a valid directory.',
				array('{path}'=>$path)));
	}

	/**
	 * @return string the root directory of layout files. Defaults to 'protected/views/layouts'.
	 */
	public function getLayoutPath() {
		if($this->_layoutPath!==null):
			return $this->_layoutPath;
		else:
                        if(_detected == 'admin') 
                            return $this->_layoutPath = self::DEFAULT_BASEPATH.DS._detected.DS.'layout';
                        else
                            return $this->_layoutPath = $this->getViewPath().DS.'layout';
                endif;
	}

	/**
	 * @param string $path the root directory of layout files.
	 * @throws CException if the directory does not exist.
	 */
	public function setLayoutPath($path) {
		if(($this->_layoutPath=realpath($path))===false || !is_dir($this->_layoutPath))
			throw new CException(\init::t('init','The layout path "{path}" is not a valid directory.',
				array('{path}'=>$path)));
	}

	/**
	 * The pre-filter for controller actions.
	 * This method is invoked before the currently requested controller action and all its filters
	 * are executed. You may override this method with logic that needs to be done
	 * before all controller actions.
	 * @param CController $controller the controller
	 * @param CAction $action the action
	 * @return boolean whether the action should be executed.
	 */
	public function beforeControllerAction($controller,$action) {
		return true;
	}

	/**
	 * The post-filter for controller actions.
	 * This method is invoked after the currently requested controller action and all its filters
	 * are executed. You may override this method with logic that needs to be done
	 * after all controller actions.
	 * @param CController $controller the controller
	 * @param CAction $action the action
	 */
	public function afterControllerAction($controller,$action) {
	}

	/**
	 * Do not call this method. This method is used internally to search for a module by its ID.
	 * @param string $id module ID
	 * @return CWebModule the module that has the specified ID. Null if no module is found.
	 */
	public function findModule($id)
	{
		if(($controller=$this->getController())!==null && ($module=$controller->getModule())!==null)
		{
			do
			{
				if(($m=$module->getModule($id))!==null)
					return $m;
			} while(($module=$module->getParentModule())!==null);
		}
		if(($m=$this->getModule($id))!==null)
			return $m;
	}

	/**
	 * Initializes the application.
	 * This method overrides the parent implementation by preloading the 'request' component.
	 */
	protected function init()
	{
		parent::init();
		// preload 'request' so that it has chance to respond to onBeginRequest event.
		$this->getRequest();
	}
        
        /*
         * register definitopn params
         */
        
        public function getDefinition() {
		return $this->_definition;
	}

	/**
	 * @param CController $value the currently active controller
	 */
	public function setDefinition($definition) {
		$this->_definition = $definition;
	}
        // load databaseDefenitions
        protected function getLoadDatabaseDefinition( $id = null ) {
            $config = $this->getSettings();
            $rootPath = $config['RootPath'];
            
            $_id = (isset($id) and !empty($id)) ? $id : \init::app()->defaultController;
            $_path = $rootPath._detected.DS.$_id.DS."definitions";
            if(($dir = realpath($_path))===false || !is_dir($_path))
			throw new CException(\init::t('init','The databaseDefinition path "{path}" is not a valid directory.',
				array('{path}'=>$_path)));
           
            
            //load boxes definitions
            if ($dp=@opendir($dir)) {
                    while (false!==($file=readdir($dp))) {
                            $filename = $dir.DS.$file;
                            
                            if ($file!='.' && $file!='..' && is_file($filename)) {
                                    $databaseDefinitionFile = $filename;
                                    if(is_file($databaseDefinitionFile)){
                                        include $databaseDefinitionFile;   
                                    }
                            }
                    }
                    closedir($dp);
            }
            
             $_definition['databaseDefinition'] = (isset($databaseDefinition) and !empty($databaseDefinition)) ? $databaseDefinition : null;
             $_definition['boxesDefinition'] = (isset($boxesDefinition) and !empty($boxesDefinition)) ? $boxesDefinition : null;
        
             return $_definition;
        }
        
       
        
        public function getModels($model = false) {
            
               
		if (is_array($model)) {
			foreach ($model as $babe) {
				$this->models($babe);
			}
			return;
		}

		if ($model == '') {
			return;
		}

		$path = '';
		if (($last_slash = strrpos($model, '/')) !== FALSE) {
			$path = substr($model, 0, $last_slash + 1);
			$model = substr($model, $last_slash + 1);
		}

                
		//if (in_array($model, $this->_model, TRUE)) { //array_keys($this->_model)
			//return;
		//}

		//$CI =& get_instance();
		//if (isset($CI->$name))
		//{
		//	show_error('The model name you are loading is the name of a resource that is already being used: '.$name);
		//}

                //echo "url = ".\init::app()->getMvc().DS.'model'.DS.$path.$model.'.php';
                //die('stop');
                
                 
                
		$model = strtolower($model);
                if ( ! file_exists(\init::app()->getMvc().DS.'model'.DS.$path.$model.'.php')) {
                        return false;
                }

                // echo \init::app()->getMvc().DS.'model'.DS.$path.$model.'.php <br />'; 
                
                
                //if ($db_conn !== FALSE AND ! class_exists('CI_DB')) {
                //	if ($db_conn === TRUE) {
                //		$db_conn = '';
                //	}

                //	$CI->load->database($db_conn, FALSE, TRUE);
                //}

                //if ( ! class_exists('CI_Model')) {
                //	load_class('Model', 'core');
                //}

                

                require_once(\init::app()->getMvc().DS.'model'.DS.$path.$model.'.php');
                
                $model = ucfirst($model);
                
                // echo "m = ".$model." <br />";
                
                $_model = new $model();
                if(!$this->_model or is_object($_model))
                    $this->_model = $_model; 
                return $this->_model;
                
	}
        
        
       
}
