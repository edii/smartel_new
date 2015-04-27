<?php

/**
 * CDetectedModel class file.
 *
 */

class CDetectedModel extends \CModel
{
        /**
	 * @var CDbConnection the default database connection for all active record classes.
	 * By default, this is the 'db' application component.
	 * @see getDbConnection
	 */
	public static $db;

	private static $_models=array();			// class name => model

	private $_md;								// meta data
	private $_new=false;						// whether this instance is new or not
	private $_attributes=array();				// attribute name => attribute value
	private $_related=array();					// attribute name => related objects
	private $_c;								// query criteria (used by finder only)
	private $_alias='t';						// the table alias being used for query

        public $_table_name;
        public $_pk;
        
	/**
	 * Constructor.
	 * @param string $scenario scenario name. See {@link CModel::scenario} for more details about this parameter.
	 */
	public function __construct($scenario='insert')
	{
		if($scenario===null) // internally used by populateRecord() and model()
			return;

		$this->setScenario($scenario);
		$this->setIsNewRecord(true);
		//$this->_attributes=$this->getMetaData()->attributeDefaults;

		$this->init();

		//$this->attachBehaviors($this->behaviors());
		$this->afterConstruct();
	}

	/**
	 * Initializes this model.
	 * This method is invoked when an AR instance is newly created and has
	 * its {@link scenario} set.
	 * You may override this method to provide code that is needed to initialize the model (e.g. setting
	 * initial property values.)
	 */
	public function init()
	{
	}

	/**
	 * Sets the parameters about query caching.
	 */
	public function cache($duration, $dependency=null, $queryCount=1)
	{
		$this->getDbConnection()->cache($duration, $dependency, $queryCount);
		return $this;
	}

	/**
	 * PHP sleep magic method.
	 * This method ensures that the model meta data reference is set to null.
	 * @return array
	 */
	public function __sleep()
	{
		$this->_md=null;
		return array_keys((array)$this);
	}

        
        /**
	 * PHP getter magic method.
	 */
	public function __get($name)
	{
		if(isset($this->_attributes[$name]))
			return $this->_attributes[$name];
		elseif(isset($this->getMetaData()->columns[$name]))
			return null;
		elseif(isset($this->_related[$name]))
			return $this->_related[$name];
		elseif(isset($this->getMetaData()->relations[$name]))
			return $this->getRelated($name);
		else
			return parent::__get($name);
	}

	/**
	 * PHP setter magic method.
	 */
	public function __set($name,$value)
	{
		if($this->setAttribute($name,$value)===false)
		{
			if(isset($this->getMetaData()->relations[$name]))
				$this->_related[$name]=$value;
			else
				parent::__set($name,$value);
		}
	}

	/**
	 * Checks if a property value is null.
	 */
	public function __isset($name)
	{
		if(isset($this->_attributes[$name]))
			return true;
		elseif(isset($this->getMetaData()->columns[$name]))
			return false;
		elseif(isset($this->_related[$name]))
			return true;
		elseif(isset($this->getMetaData()->relations[$name]))
			return $this->getRelated($name)!==null;
		else
			return parent::__isset($name);
	}

	/**
	 * Sets a component property to be null.
	 */
	public function __unset($name)
	{
		if(isset($this->getMetaData()->columns[$name]))
			unset($this->_attributes[$name]);
		elseif(isset($this->getMetaData()->relations[$name]))
			unset($this->_related[$name]);
		else
			parent::__unset($name);
	}

	/**
	 * Calls the named method which is not a class method.
	 */
	public function __call($name,$parameters)
	{
		if(isset($this->getMetaData()->relations[$name]))
		{
			if(empty($parameters))
				return $this->getRelated($name,false);
			else
				return $this->getRelated($name,false,$parameters[0]);
		}

		$scopes=$this->scopes();
		if(isset($scopes[$name]))
		{
			$this->getDbCriteria()->mergeWith($scopes[$name]);
			return $this;
		}

		return parent::__call($name,$parameters);
	}
        
        /**
	 * Returns the list of all attribute names of the model.
	 */
	public function attributeNames()
	{
		return array_keys($this->getMetaData()->columns);
	}
        
        /**
	 * Returns the text label for the specified attribute.
	 */
	public function getAttributeLabel($attribute)
	{
		$labels=$this->attributeLabels();
		if(isset($labels[$attribute]))
			return $labels[$attribute];
		elseif(strpos($attribute,'.')!==false)
		{
			$segs=explode('.',$attribute);
			$name=array_pop($segs);
			$model=$this;
			foreach($segs as $seg)
			{
				$relations=$model->getMetaData()->relations;
				if(isset($relations[$seg]))
					$model=CActiveRecord::model($relations[$seg]->className);
				else
					break;
			}
			return $model->getAttributeLabel($name);
		}
		else
			return $this->generateAttributeLabel($attribute);
	}

	/**
	 * Returns the database connection used by active record.
	 * By default, the "db" application component is used as the database connection.
	 * You may override this method if you want to use a different database connection.
	 * @return CDbConnection the database connection used by active record.
	 */
	public function getDbConnection()
	{
		if(self::$db!==null)
			return self::$db;
		else
		{
			self::$db = \init::app() -> getDBConnector();
			if(is_object(self::$db))
				return self::$db;
			else
				throw new \CException(\init::t('yii','Active Record requires a "db" CDbConnection application component.'));
		}
	}

        /**
	 * Checks whether this AR has the named attribute
	 * @param string $name attribute name
	 * @return boolean whether this AR has the named attribute (table column).
	 */
	public function hasAttribute($name)
	{
		return isset($this->getMetaData()->columns[$name]);
	}

	/**
	 * Returns the named attribute value.
	 */
	public function getAttribute($name)
	{
		if(property_exists($this,$name))
			return $this->$name;
		elseif(isset($this->_attributes[$name]))
			return $this->_attributes[$name];
	}

	/**
	 * Sets the named attribute value.
	 */
	public function setAttribute($name,$value)
	{
		if(property_exists($this,$name))
			$this->$name=$value;
		elseif(isset($this->getMetaData()->columns[$name]))
			$this->_attributes[$name]=$value;
		else
			return false;
		return true;
	}

        
        /**
	 * Returns all column attribute values.
	 */
	public function getAttributes($names=true)
	{
		$attributes=$this->_attributes;
		foreach($this->getMetaData()->columns as $name=>$column)
		{
			if(property_exists($this,$name))
				$attributes[$name]=$this->$name;
			elseif($names===true && !isset($attributes[$name]))
				$attributes[$name]=null;
		}
		if(is_array($names))
		{
			$attrs=array();
			foreach($names as $name)
			{
				if(property_exists($this,$name))
					$attrs[$name]=$this->$name;
				else
					$attrs[$name]=isset($attributes[$name])?$attributes[$name]:null;
			}
			return $attrs;
		}
		else
			return $attributes;
	}

	/**
	 * Saves the current record.
	 *
	 */
	public function save( $runValidation=true, $attributes=null ) {
            if(!empty($this->_table_name) and $runValidation ) {
               if(isset($attributes[ $this->_pk ]) and !empty($attributes[ $this->_pk ])) $this->setIsNewRecord(false); // go update
               return $this->getIsNewRecord() ? $this->insert($attributes) : $this->update($attributes);
            }
            
	}

        /**
	 * Inserts a row into the table based on this active record attributes.
	 */
	public function insert($attributes=null) {
                $_coonected = (self::$db === null) ? $this->getDbConnection(): self::$db;
            
		if(!$this->getIsNewRecord())
			throw new CHttpException(404, \init::t('init','The active record cannot be inserted to database because it is not new.'));
             
                // insert
                if(!isset($attributes[ $this->_pk ]) or empty($attributes[ $this->_pk ])) {
                    $_insert = $_coonected -> insert($this->_table_name, array('target' => 'main')) 
                            -> fields($attributes)
                            -> execute(); 
                    
                    if(!$_insert)
                        throw new CHttpException(404,\init::t('init','Fatal error dont insert!'));
                    
                    return true;
                }
                
		return false;
	}

	/**
	 * Updates the row represented by this active record.
	 */
	public function update($attributes=null) {
                $_coonected = (self::$db === null) ? $this->getDbConnection(): self::$db;
                
                if($_id = (int)$attributes[ $this->_pk ]) {
                    unset( $attributes[ $this->_pk ] );
                    
                    $_update = $_coonected -> update($this->_table_name, array('target' => 'main')) 
                            -> fields($attributes)
                            -> condition($this->_pk, $_id, '=') -> execute(); 
                  if(!$_update) 
                      throw new CHttpException(404,\init::t('init','Fatal error dont update!'));

                  return true;
                } else {
                    return false;
                }
                
	}

	/**
	 * Saves a selected list of attributes.
	 */
	public function saveAttributes($attributes)
	{
		if(!$this->getIsNewRecord())
		{
			\init::trace(get_class($this).'.saveAttributes()','system.db.ar.CActiveRecord');
			$values=array();
			foreach($attributes as $name=>$value)
			{
				if(is_integer($name))
					$values[$value]=$this->$value;
				else
					$values[$name]=$this->$name=$value;
			}
			if($this->_pk===null)
				$this->_pk=$this->getPrimaryKey();
			if($this->updateByPk($this->getOldPrimaryKey(),$values)>0)
			{
				$this->_pk=$this->getPrimaryKey();
				return true;
			}
			else
				return false;
		}
		else
			throw new CDbException(\init::t('yii','The active record cannot be updated because it is new.'));
	}

        /**
	 * Deletes the row corresponding to this active record.
	 * @return boolean whether the deletion is successful.
	 * @throws CException if the record is new
	 */
	public function delete($attributes = null) {
            $_coonected = (self::$db === null) ? $this->getDbConnection(): self::$db;
                            
            if($_id = (int)$attributes[ $this->_pk ]) {
                $_delete = $_coonected -> delete($this->_table_name, array('target' => 'main')) 
                                       -> condition($this->_pk, $_id, '=') -> execute();
                if(!$_delete) 
                      throw new CHttpException(404,\init::t('init','Fatal error dont delete!'));
                
                return true;
            }
            else
                return false;
	}

	/**
	 * Repopulates this active record with the latest data.
	 * @return boolean whether the row still exists in the database. If true, the latest data will be populated to this active record.
	 */
	public function refresh()
	{
		\init::trace(get_class($this).'.refresh()','system.db.ar.CActiveRecord');
		if(($record=$this->findByPk($this->getPrimaryKey()))!==null)
		{
			$this->_attributes=array();
			$this->_related=array();
			foreach($this->getMetaData()->columns as $name=>$column)
			{
				if(property_exists($this,$name))
					$this->$name=$record->$name;
				else
					$this->_attributes[$name]=$record->$name;
			}
			return true;
		}
		else
			return false;
	}
        
        /**
	 * Returns if the current record is new.
	 */
	public function getIsNewRecord()
	{
		return $this->_new;
	}

	/**
	 * Sets if the record is new.
	 */
	public function setIsNewRecord($value)
	{
		$this->_new=$value;
	}

}