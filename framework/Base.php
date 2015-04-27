<?php
namespace framework;

class Base
{
	/**
	 * @var array class map used by the autoloading mechanism.
	 * The array keys are the class names and the array values are the corresponding class file paths.
	 * @since 1.1.5
	 */
	public static $classMap=array();
	/**
	 * @var boolean whether to rely on PHP include path to autoload class files. Defaults to true.
	 * You may set this to be false if your hosting environment doesn't allow changing the PHP
	 * include path, or if you want to append additional autoloaders to the default autoloader.
	 * @since 1.1.8
	 */
	public static $enableIncludePath=true;

	private static $_aliases=array('system'=>PATH,'zii'=>ZII_PATH); // alias => path
	private static $_imports=array();					// alias => class name or directory
	private static $_includePaths;						// list of include paths
	private static $_app;
	private static $_logger;



	/**
	 * @return string the version
	 */
	public static function getVersion()
	{
		return '1.0';
	}

	/**
	 * Creates a Web application instance.
	 * @param mixed $config application configuration.
	 * If a string, it is treated as the path of the file that contains the configuration;
	 * If an array, it is the actual configuration information.
	 * Please make sure you specify the {@link CApplication::basePath basePath} property in the configuration,
	 * which should point to the directory containing all application logic, template and data.
	 * If not, the directory will be defaulted to 'protected'.
	 * @return CWebApplication
	 */
	public static function createWebApplication($config=null)
	{
		return self::createApplication('CWebApplication', $config);
	}

	/**
	 * Creates a console application instance.
	 * @param mixed $config application configuration.
	 * If a string, it is treated as the path of the file that contains the configuration;
	 * If an array, it is the actual configuration information.
	 * Please make sure you specify the {@link CApplication::basePath basePath} property in the configuration,
	 * which should point to the directory containing all application logic, template and data.
	 * If not, the directory will be defaulted to 'protected'.
	 * @return CConsoleApplication
	 */
	public static function createConsoleApplication($config=null)
	{
		return self::createApplication('CConsoleApplication',$config);
	}

	/**
	 * Creates an application of the specified class.
	 * @param string $class the application class name
	 * @param mixed $config application configuration. This parameter will be passed as the parameter
	 * to the constructor of the application class.
	 * @return mixed the application instance
	 */
	public static function createApplication($class,$config=null)
	{
		return new $class($config);
	}

	/**
	 * Returns the application singleton or null if the singleton has not been created yet.
	 * @return CApplication the application singleton, null if the singleton has not been created yet.
	 */
	public static function app()
	{
		return self::$_app;
	}

	/**
	 * Stores the application instance in the class static member.
	 * This method helps implement a singleton pattern for CApplication.
	 * Repeated invocation of this method or the CApplication constructor
	 * will cause the throw of an exception.
	 * To retrieve the application instance, use {@link app()}.
	 * @param CApplication $app the application instance. If this is null, the existing
	 * application singleton will be removed.
	 * @throws CException if multiple application instances are registered.
	 */
	public static function setApplication($app)
	{
		if(self::$_app===null || $app===null)
			self::$_app=$app;
		else
			throw new \CException('init','Init application can only be created once.');
	}

	/**
	 * @return string the path of the framework
	 */
	public static function getFrameworkPath()
	{
		return PATH;
	}

	/**
	 * Creates an object and initializes it based on the given configuration.
	 *
	 * The specified configuration can be either a string or an array.
	 * If the former, the string is treated as the object type which can
	 * be either the class name or {@link YiiBase::getPathOfAlias class path alias}.
	 * If the latter, the 'class' element is treated as the object type,
	 * and the rest of the name-value pairs in the array are used to initialize
	 * the corresponding object properties.
	 *
	 * Any additional parameters passed to this method will be
	 * passed to the constructor of the object being created.
	 *
	 * @param mixed $config the configuration. It can be either a string or an array.
	 * @return mixed the created object
	 * @throws CException if the configuration does not have a 'class' element.
	 */
	public static function createComponent($config)
	{
		if(is_string($config))
		{
			$type=$config;
			$config=array();
		}
		elseif(isset($config['class']))
		{
			$type=$config['class'];
			unset($config['class']);
		}
		else
			throw new \CException('init','Object configuration must be an array containing a "class" element.');

		if(!class_exists($type,false))
			$type = \init::import($type, true);

		if(($n=func_num_args())>1)
		{
			$args=func_get_args();
			if($n===2)
				$object=new $type($args[1]);
			elseif($n===3)
				$object=new $type($args[1],$args[2]);
			elseif($n===4)
				$object=new $type($args[1],$args[2],$args[3]);
			else
			{
				unset($args[0]);
				$class=new ReflectionClass($type);
				// Note: ReflectionClass::newInstanceArgs() is available for PHP 5.1.3+
				// $object=$class->newInstanceArgs($args);
				$object=call_user_func_array(array($class,'newInstance'),$args);
			}
		}
		else
			$object=new $type;

		foreach($config as $key=>$value)
			$object->$key=$value;

		return $object;
	}

	/**
	 * Imports a class or a directory.
	 *
	 * Importing a class is like including the corresponding class file.
	 * The main difference is that importing a class is much lighter because it only
	 * includes the class file when the class is referenced the first time.
	 *
	 * Importing a directory is equivalent to adding a directory into the PHP include path.
	 * If multiple directories are imported, the directories imported later will take
	 * precedence in class file searching (i.e., they are added to the front of the PHP include path).
	 *
	 * Path aliases are used to import a class or directory. For example,
	 * <ul>
	 *   <li><code>application.components.GoogleMap</code>: import the <code>GoogleMap</code> class.</li>
	 *   <li><code>application.components.*</code>: import the <code>components</code> directory.</li>
	 * </ul>
	 *
	 * The same path alias can be imported multiple times, but only the first time is effective.
	 * Importing a directory does not import any of its subdirectories.
	 *
	 * Starting from version 1.1.5, this method can also be used to import a class in namespace format
	 * (available for PHP 5.3 or above only). It is similar to importing a class in path alias format,
	 * except that the dot separator is replaced by the backslash separator. For example, importing
	 * <code>application\components\GoogleMap</code> is similar to importing <code>application.components.GoogleMap</code>.
	 * The difference is that the former class is using qualified name, while the latter unqualified.
	 *
	 * Note, importing a class in namespace format requires that the namespace corresponds to
	 * a valid path alias once backslash characters are replaced with dot characters.
	 * For example, the namespace <code>application\components</code> must correspond to a valid
	 * path alias <code>application.components</code>.
	 *
	 * @param string $alias path alias to be imported
	 * @param boolean $forceInclude whether to include the class file immediately. If false, the class file
	 * will be included only when the class is being used. This parameter is used only when
	 * the path alias refers to a class.
	 * @return string the class name or the directory that this alias refers to
	 * @throws CException if the alias is invalid
	 */
	public static function import($alias,$forceInclude=false)
	{
		if(isset(self::$_imports[$alias]))  // previously imported
			return self::$_imports[$alias];

		if(class_exists($alias,false) || interface_exists($alias,false))
			return self::$_imports[$alias]=$alias;

		if(($pos=strrpos($alias,'\\'))!==false) // a class name in PHP 5.3 namespace format
		{
			$namespace=str_replace('\\','.',ltrim(substr($alias,0,$pos),'\\'));
			if(($path=self::getPathOfAlias($namespace))!==false)
			{
				$classFile=$path.DS.substr($alias,$pos+1).'.php';
				if($forceInclude)
				{
					if(is_file($classFile))
						require($classFile);
					else
						throw new \CException(\init::t('yii','Alias "{alias}" is invalid. Make sure it points to an existing PHP file and the file is readable.',array('{alias}'=>$alias)));
					self::$_imports[$alias]=$alias;
				}
				else
					self::$classMap[$alias]=$classFile;
				return $alias;
			}
			else
				throw new \CException(\init::t('yii','Alias "{alias}" is invalid. Make sure it points to an existing directory.',
					array('{alias}'=>$namespace)));
		}

		if(($pos=strrpos($alias,'.'))===false)  // a simple class name
		{
			if($forceInclude && self::autoload($alias))
				self::$_imports[$alias]=$alias;
			return $alias;
		}

		$className=(string)substr($alias,$pos+1);
		$isClass=$className!=='*';

		if($isClass && (class_exists($className,false) || interface_exists($className,false)))
			return self::$_imports[$alias]=$className;

		if(($path=self::getPathOfAlias($alias))!==false)
		{
			if($isClass)
			{
				if($forceInclude)
				{
					if(is_file($path.'.php'))
						require($path.'.php');
					else
						throw new \CException(\init::t('yii','Alias "{alias}" is invalid. Make sure it points to an existing PHP file and the file is readable.',array('{alias}'=>$alias)));
					self::$_imports[$alias]=$className;
				}
				else
					self::$classMap[$className]=$path.'.php';
				return $className;
			}
			else  // a directory
			{
				if(self::$_includePaths===null)
				{
					self::$_includePaths=array_unique(explode(PATH_SEPARATOR,get_include_path()));
					if(($pos=array_search('.',self::$_includePaths,true))!==false)
						unset(self::$_includePaths[$pos]);
				}

				array_unshift(self::$_includePaths,$path);

				if(self::$enableIncludePath && set_include_path('.'.PATH_SEPARATOR.implode(PATH_SEPARATOR,self::$_includePaths))===false)
					self::$enableIncludePath=false;

				return self::$_imports[$alias]=$path;
			}
		}
		else
			throw new CException(\init::t('yii','Alias "{alias}" is invalid. Make sure it points to an existing directory or file.',
				array('{alias}'=>$alias)));
	}

	/**
	 * Translates an alias into a file path.
	 * Note, this method does not ensure the existence of the resulting file path.
	 * It only checks if the root alias is valid or not.
	 * @param string $alias alias (e.g. system.web.CController)
	 * @return mixed file path corresponding to the alias, false if the alias is invalid.
	 */
	public static function getPathOfAlias($alias)
	{
		if(isset(self::$_aliases[$alias]))
			return self::$_aliases[$alias];
		elseif(($pos=strpos($alias,'.'))!==false)
		{
			$rootAlias=substr($alias,0,$pos);
			if(isset(self::$_aliases[$rootAlias]))
				return self::$_aliases[$alias]=rtrim(self::$_aliases[$rootAlias].DIRECTORY_SEPARATOR.str_replace('.',DIRECTORY_SEPARATOR,substr($alias,$pos+1)),'*'.DIRECTORY_SEPARATOR);
			elseif(self::$_app instanceof \CWebApplication)
			{
				if(self::$_app->findModule($rootAlias)!==null)
					return self::getPathOfAlias($alias);
			}
		}
		return false;
	}

	/**
	 * Create a path alias.
	 * Note, this method neither checks the existence of the path nor normalizes the path.
	 * @param string $alias alias to the path
	 * @param string $path the path corresponding to the alias. If this is null, the corresponding
	 * path alias will be removed.
	 */
	public static function setPathOfAlias($alias,$path)
	{
		if(empty($path))
			unset(self::$_aliases[$alias]);
		else
			self::$_aliases[$alias]=rtrim($path,'\\/');
	}

	/**
	 * Class autoload loader.
	 * This method is provided to be invoked within an __autoload() magic method.
	 * @param string $className class name
	 * @return boolean whether the class has been loaded successfully
	 */
	public static function autoload($className) {
            // echo $className."<br />";
		// use include so that the error PHP file may appear
		if(isset(self::$classMap[$className]))
			include(self::$classMap[$className]);
		elseif(isset(self::$_coreClasses[$className]))
			include(PATH.self::$_coreClasses[$className]);
		else {
			// include class file relying on include_path
			if(strpos($className,'\\')===false)  // class without namespace
			{
				if(self::$enableIncludePath===false) {
					foreach(self::$_includePaths as $path) {
						$classFile=$path.DIRECTORY_SEPARATOR.$className.'.php';
						if(is_file($classFile)) {
							require_once($classFile);
							if(DEBUG && basename(realpath($classFile))!==$className.'.php')
								throw new \CException(\init::t('init','Class name "{class}" does not match class file "{file}".', array(
									'{class}'=>$className,
									'{file}'=>$classFile,
								)));
							break;
						}
					}
				}
				else
                                    include($className.'.php');
			}
			else  // class name with namespace in PHP 5.3
			{
				$namespace=str_replace('\\','.',ltrim($className,'\\'));
				if(($path=self::getPathOfAlias($namespace))!==false)
					require_once($path.'.php');
				else
					return false;
			}
			return class_exists($className,false) || interface_exists($className,false);
		}
		return true;
                
                
	}

        public static function t($category,$message,$params=array(),$source=null,$language=null) {
		if(self::$_app!==null) {
			if($source===null)
				$source=($category==='yii'||$category==='zii')?'coreMessages':'messages';
			if(($source=self::$_app->getComponent($source))!==null)
				$message=$source->translate($category,$message,$language);
		}
		if($params===array())
			return $message;
		if(!is_array($params))
			$params=array($params);
		if(isset($params[0])) // number choice
		{
			if(strpos($message,'|')!==false)
			{
				if(strpos($message,'#')===false)
				{
					$chunks=explode('|',$message);
					$expressions=self::$_app->getLocale($language)->getPluralRules();
					if($n=min(count($chunks),count($expressions)))
					{
						for($i=0;$i<$n;$i++)
							$chunks[$i]=$expressions[$i].'#'.$chunks[$i];

						$message=implode('|',$chunks);
					}
				}
				$message=CChoiceFormat::format($message,$params[0]);
			}
			if(!isset($params['{n}']))
				$params['{n}']=$params[0];
			unset($params[0]);
		}
		return $params!==array() ? strtr($message,$params) : $message;
	}
        
	/**
	 * Writes a trace message.
	 * This method will only log a message when the application is in debug mode.
	 * @param string $msg message to be logged
	 * @param string $category category of the message
	 * @see log
	 */
	public static function trace($msg,$category='application') {
		if(DEBUG)
			self::log($msg, \CLogger::LEVEL_TRACE, $category);
	}

	/**
	 * Logs a message.
	 * Messages logged by this method may be retrieved via {@link CLogger::getLogs}
	 * and may be recorded in different media, such as file, email, database, using
	 * {@link CLogRouter}.
	 * @param string $msg message to be logged
	 * @param string $level level of the message (e.g. 'trace', 'warning', 'error'). It is case-insensitive.
	 * @param string $category category of the message (e.g. 'system.web'). It is case-insensitive.
	 */
	public static function log($msg,$level = \CLogger::LEVEL_INFO, $category='application') {
		if(self::$_logger===null)
			self::$_logger=new \CLogger;
		if(DEBUG && TRACE_LEVEL > 0 && $level!== \CLogger::LEVEL_PROFILE) {
			$traces = debug_backtrace();
			$count = 0;
			foreach($traces as $trace) {
				if(isset($trace['file'],$trace['line']) && strpos($trace['file'],PATH)!==0) {
					$msg.="\nin ".$trace['file'].' ('.$trace['line'].')';
					if(++$count>=TRACE_LEVEL)
						break;
				}
			}
		}
		self::$_logger->log($msg,$level,$category);
	}

	/**
	 * Marks the beginning of a code block for profiling.
	 * This has to be matched with a call to {@link endProfile()} with the same token.
	 * The begin- and end- calls must also be properly nested, e.g.,
	 * <pre>
	 * Yii::beginProfile('block1');
	 * Yii::beginProfile('block2');
	 * Yii::endProfile('block2');
	 * Yii::endProfile('block1');
	 * </pre>
	 * The following sequence is not valid:
	 * <pre>
	 * Yii::beginProfile('block1');
	 * Yii::beginProfile('block2');
	 * Yii::endProfile('block1');
	 * Yii::endProfile('block2');
	 * </pre>
	 * @param string $token token for the code block
	 * @param string $category the category of this log message
	 * @see endProfile
	 */
	public static function beginProfile($token, $category='application') {
		self::log('begin:'.$token, \CLogger::LEVEL_PROFILE, $category);
	}

	/**
	 * Marks the end of a code block for profiling.
	 * This has to be matched with a previous call to {@link beginProfile()} with the same token.
	 * @param string $token token for the code block
	 * @param string $category the category of this log message
	 * @see beginProfile
	 */
	public static function endProfile($token, $category='application') {
		self::log('end:'.$token, \CLogger::LEVEL_PROFILE, $category);
	}

	/**
	 * @return CLogger message logger
	 */
	public static function getLogger() {
		if(self::$_logger!==null)
			return self::$_logger;
		else
			return self::$_logger=new \CLogger;
	}

	/**
	 * Sets the logger object.
	 * @param CLogger $logger the logger object.
	 * @since 1.1.8
	 */
	public static function setLogger($logger) {
		self::$_logger=$logger;
	}

	/**
	 * Returns a string that can be displayed on your Web page showing Powered-by information
	 * @return string a string that can be displayed on your Web page showing Powered-by information
	 */
	public static function powered() {
		return 'Powered by {init}.';
	}

	
	
	/**
	 * Registers a new class autoloader.
	 * The new autoloader will be placed before {@link autoload} and after
	 * any other existing autoloaders.
	 * @param callback $callback a valid PHP callback (function name or array($className,$methodName)).
	 * @param boolean $append whether to append the new autoloader after the default autoloader.
	 */
	public static function registerAutoloader($callback, $append=false)
	{
		if($append)
		{
			self::$enableIncludePath=false;
			\spl_autoload_register($callback);
		} else {
			\spl_autoload_unregister(array('Base','autoload'));
			\spl_autoload_register($callback);
			\spl_autoload_register(array('Base','autoload'));
		}
	}

	/**
	 * @var array class map for core classes.
	 * NOTE, DO NOT MODIFY THIS ARRAY MANUALLY. IF YOU CHANGE OR ADD SOME CORE CLASSES,
	 * PLEASE RUN 'build autoload' COMMAND TO UPDATE THIS ARRAY.
	 */
	private static $_coreClasses = array(
                // load memcached
                'CCache'                => '/libs/memcache/CCache.php',
                'CMemCache'             => '/libs/memcache/CMemCache.php',
                'CMemCacheServer'       => '/libs/memcache/CMemCacheServer.php',
            
                // create layout
                'COwner'                  => '/libs/COwner.php',
                
                // CSpace
                'CSpace'                  => '/libs/CSpace.php',  
                
                // CTree
                'CTree'                  => '/libs/CTree.php',
            
                // CLanguage
                'CLanguage'                  => '/libs/CLanguage.php',
                
                // core class
                'CApplicationComponent' => '/framework/base/CApplicationComponent.php',
                'CApplication'          => '/framework/base/CApplication.php',
            
                // drupal
                'DatabaseConnection'        => '/libs/database/database.php',
                'Database'                  => '/libs/database/database.php',
                'DatabaseTransaction'       => '/libs/database/database.php',
                'DatabaseStatementBase'     => '/libs/database/database.php',
                'DatabaseStatementEmpty'    => '/libs/database/database.php',
                'DatabaseLog'               => '/libs/database/log.php',
                'DatabaseStatementPrefetch' => '/libs/database/prefetch.php',
                'QueryConditionInterface'   => '/libs/database/query.php',
                'DatabaseSchema'            => '/libs/database/schema.php',
                
            
                'CDbCommandBuilder'     => '/libs/database/CDbCommandBuilder.php',
                'CDatabase'             => '/libs/database/CDatabase.php',
                'CDbException'          => '/libs/database/CDbException.php',
                
                // module
                'CModel'                => '/framework/base/CModel.php',
		'CModelBehavior'        => '/framework/base/CModelBehavior.php',
		'CModelEvent'           => '/framework/base/CModelEvent.php',
                'CDetectedModel'        => '/libs/CDetectedModel.php',
		'CModule'               => '/framework/base/CModule.php',
                'CComponent'            => '/framework/base/CComponent.php',
            
                // session
                'CSession'               => '/framework/base/CSession.php',
                // create app
                'CWebApplication'       => '/libs/CWebApplication.php',
                // create error
                'CLogger'               => '/framework/logging/CLogger.php',
                'CException'            => '/framework/base/CException.php',
                'CExceptionEvent'       => '/framework/base/CExceptionEvent.php',
                'CErrorEvent'           => '/framework/base/CErrorEvent.php',
		'CErrorHandler'         => '/framework/base/CErrorHandler.php',
                'CHttpException'        => '/framework/base/CHttpException.php',
                // view error source
                 'CPhpMessageSource'     => '/framework/language/CPhpMessageSource.php',
                 'CMessageSource'     => '/framework/language/CMessageSource.php',
            
//                'CPhpMessageSource'     => '/framework/i18n/CPhpMessageSource.php',
//                'CMessageSource'        => '/framework/i18n/CMessageSource.php',
            
                'CHtml'                 => '/libs/helpers/CHtml.php',
                'CMap'                  => '/libs/collections/CMap.php',
		'CMapIterator'          => '/libs/collections/CMapIterator.php',
            
                'CAttributeCollection'  => '/libs/collections/CAttributeCollection.php',
                'CConfiguration'        => '/libs/collections/CConfiguration.php',
		'CList'                 => '/libs/collections/CList.php',
		'CListIterator'         => '/libs/collections/CListIterator.php',
                'CQueue'                => '/libs/collections/CQueue.php',
		'CQueueIterator'        => '/libs/collections/CQueueIterator.php',
		'CStack'                => '/libs/collections/CStack.php',
		'CStackIterator'        => '/libs/collections/CStackIterator.php',
		'CTypedList'            => '/libs/collections/CTypedList.php',
		'CTypedMap'             => '/libs/collections/CTypedMap.php',
            
                'CHttpCookie'           => '/libs/CHttpCookie.php',
		'CHttpRequest'          => '/libs/CHttpRequest.php',
		//'CHttpSession'          => '/libs/CHttpSession.php',
		//'CHttpSessionIterator'  => '/libs/CHttpSessionIterator.php',
		'COutputEvent'          => '/libs/COutputEvent.php',
		// 'CPagination'           => '/libs/CPagination.php',
            
                'CUrlManager'           => '/libs/CUrlManager.php',
            
                // controllers
                'CBaseController'       => '/libs/CBaseController.php',
                'CController'           => '/libs/CController.php',
                'CExtController'        => '/libs/CExtController.php',
            
                'CAction'               => '/libs/actions/CAction.php',
		'CInlineAction'         => '/libs/actions/CInlineAction.php',
		'CViewAction'           => '/libs/actions/CViewAction.php',
            
                'CPradoViewRenderer'    => '/libs/renderers/CPradoViewRenderer.php',
		'CViewRenderer'         => '/libs/renderers/CViewRenderer.php',
            
                // gettext
                'CGettextFile'              => '/framework/gettext/CGettextFile.php',
                'CGettextMoFile'              => '/framework/gettext/CGettextMoFile.php',
                'CGettextPoFile'              => '/framework/gettext/CGettextPoFile.php',
                'CGettextMessageSource'              => '/framework/gettext/CGettextMessageSource.php',
                'CMissingTranslationEvent'              => '/framework/gettext/CMissingTranslationEvent.php',
            
                // create script params
                'CClientScript' => '/libs/CClientScript.php',
            
                // create layout
                'CBox'                  => '/libs/CBox.php',
                'CBoxLayout'            => '/libs/CBoxLayout.php',
            
                // Resize Images
                //'ResizeImages'           => '/framework/imagetoolkit/AcImage.php',
                'ResizeImages'           => '/framework/imagetoolkit/ResizeImages.php',
		
	);
}

\spl_autoload_register('framework\Base::autoload');
//var_dump(spl_autoload_functions());
require(PATH.'/framework/base/interfaces.php');




