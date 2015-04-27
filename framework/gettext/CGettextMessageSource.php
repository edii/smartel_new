<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

class CGettextMessageSource extends \CComponent
{
        // extends \CMessageSource
    
        /**
	 * @event MissingTranslationEvent an event that is triggered when a message translation is not found.
	 */
	const EVENT_MISSING_TRANSLATION = 'missingTranslation';

	/**
	 * @var boolean whether to force message translation when the source and target languages are the same.
	 * Defaults to false, meaning translation is only performed when source and target languages are different.
	 */
	public $forceTranslation = false;
	/**
	 * @var string the language that the original messages are in. If not set, it will use the value of
	 * [[\yii\base\Application::sourceLanguage]].
	 */
	public $sourceLanguage;

	private $_messages = array();

    
	const MO_FILE_EXT = '.mo';
	const PO_FILE_EXT = '.po';

	/**
	 * @var string
	 */
	public $basePath = '/data/locale';
	/**
	 * @var string
	 */
	public $catalog = 'base';
	/**
	 * @var boolean
	 */
	public $useMoFile = true; // true or false
	/**
	 * @var boolean
	 */
	public $useBigEndian = false;

        /**
	 * Initializes this component.
	 */
	public function init()
	{
		parent::init();
		if ($this->sourceLanguage === null) {
			$this->sourceLanguage = \init::app()->sourceLanguage;
		}
	}
        
	/**
	 * Loads the message translation for the specified language and category.
	 * Child classes should override this method to return the message translations of
	 * the specified language and category.
	 * @param string $category the message category
	 * @param string $language the target language
	 * @return array the loaded messages. The keys are original messages, and the values
	 * are translated messages.
	 */
	protected function loadMessages($category, $language)
	{
		$messageFile = PATH.$this->basePath . '/' . $language . '/' . $this->catalog;
		if ($this->useMoFile) {
			$messageFile .= static::MO_FILE_EXT;
		} else {
			$messageFile .= static::PO_FILE_EXT;
		}

               
                
		if (is_file($messageFile)) {
			if ($this->useMoFile) {
				$gettextFile = new CGettextMoFile(array('useBigEndian' => $this->useBigEndian));
			} else {
				$gettextFile = new CGettextPoFile();
			}
                        
			$messages = $gettextFile->load($messageFile, $category);
                        
			if (!is_array($messages)) {
				$messages = array();
			}
			return $messages;
		} else {
			\init::trace("The message file for category '$category' does not exist: $messageFile", __METHOD__);
			return array();
		}
	}
        
        public function translate($category, $message, $language)
	{
                
            
		if ($this->forceTranslation || $language !== $this->sourceLanguage) {
			return $this->translateMessage($category, $message, $language);
		} else {
			return $message;
		}
	}

	/**
	 * Translates the specified message.
	 * If the message is not found, a [[missingTranslation]] event will be triggered
	 * and the original message will be returned.
	 * @param string $category the category that the message belongs to
	 * @param string $message the message to be translated
	 * @param string $language the target language
	 * @return string the translated message
	 */
	protected function translateMessage( $category, $message, $language )
	{
                $key = $language. '/' . $category;
                if (!isset($this->_messages[$key])) {
                    $this->_messages[$key] = $this->loadMessages($category, $language);
                }
            
                if (isset($this->_messages[$key][$category]) && $this->_messages[$key][$category] !== '') {
                        return $this->_messages[$key][$category];
                } elseif ($this->hasEventHandler('missingTranslation')) {
			$event = new CMissingTranslationEvent(array(
				'category' => $category,
				'message' => $message,
				'language' => $language,
			));
			$this->trigger(self::EVENT_MISSING_TRANSLATION, $event);
			return $this->_messages[$key] = $event->message;
		} else {
			return $message;
		}
                
                
                
//                elseif ($this->hasEventHandlers('missingTranslation')) {
//			$event = new MissingTranslationEvent(array(
//				'category' => $category,
//				'message' => $message,
//				'language' => $language,
//			));
//			$this->trigger(self::EVENT_MISSING_TRANSLATION, $event);
//			return $this->_messages[$key] = $event->message;
//		} else {
//			return $message;
//		}
                
            
//		$key = $language . '/' . $category;
//		if (!isset($this->_messages[$key])) {
//			$this->_messages[$key] = $this->loadMessages($category, $language);
//		}
//                
//                var_dump( $this->_messages ); die('stop');
//                
//		if (isset($this->_messages[$key][$message]) && $this->_messages[$key][$message] !== '') {
//			return $this->_messages[$key][$message];
//		} elseif ($this->hasEventHandlers('missingTranslation')) {
//			$event = new MissingTranslationEvent(array(
//				'category' => $category,
//				'message' => $message,
//				'language' => $language,
//			));
//			$this->trigger(self::EVENT_MISSING_TRANSLATION, $event);
//			return $this->_messages[$key] = $event->message;
//		} else {
//			return $message;
//		}
	}
        
}
