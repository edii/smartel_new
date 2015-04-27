<?php  
/**
 *
 * An open source application development framework for PHP 5.4 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * DataBase
 * CREATE TABLE IF NOT EXISTS  `sessions` (
    session_id varchar(40) DEFAULT '0' NOT NULL,
    ip_address varchar(45) DEFAULT '0' NOT NULL,
    user_agent varchar(120) NOT NULL,
    last_activity int(10) unsigned DEFAULT 0 NOT NULL,
    user_data text NOT NULL,
    PRIMARY KEY (session_id),
    KEY `last_activity_idx` (`last_activity`)
   );
 * 
 */

/**
 * Session Class
 */
class CSession {

	
	private $sess_use_database = TRUE; /* true or false */
	private $sess_table_name    = 'sessions';
	private $sess_expiration    = 7200;
	private $sess_expire_on_close   = FALSE;
	private $sess_match_ip  = FALSE;
	private $sess_match_useragent   = TRUE;
        private $sess_cookie_name   = 'csession';
	private $cookie_prefix  = '';
	private $cookie_path    = '/';
	private $cookie_domain  = '';
	private $cookie_secure  = FALSE;
	private $sess_time_to_update    = 30000;
	private $encryption_key = '';
	private $flashdata_key  = 'flash';
	private $time_reference = 'time';
	private $gc_probability = 5;
	private $userdata = array();
	private $_session;
	private $now;
        
        // db params
        private static $db;
        private $_query;
        
        // (true | false) .. default cookie ( false )
        private $_bool_session = true;
        private $_session_path = false; // realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session')


        /**
	 * Session Constructor
	 *
	 * The constructor runs the session routines automatically
	 * whenever the class is instantiated.
	 */
	public function __construct($params = array()) {
                if($this->_bool_session) {
                    if ( $this->is_session_started() === FALSE ) {
                        ini_set('session.save_path', $this->_session_path);
                        session_start();
                    }    
                }
            
		// log_message('debug', "Session Class Initialized");

		// Set the super object to a local variable for use throughout the class
		$this->_session = \init::app();

                /*    
		// Set all the session preferences, which can either be set
		// manually via the $params array above or via the config file
		foreach ( ['sess_encrypt_cookie', 
                            'sess_use_database', 
                            'sess_table_name', 
                            'sess_expiration', 
                            'sess_expire_on_close', 
                            'sess_match_ip', 
                            'sess_match_useragent', 
                            'sess_cookie_name', 
                            'cookie_path', 
                            'cookie_domain', 
                            'cookie_secure', 
                            'sess_time_to_update', 
                            'time_reference', 
                            'cookie_prefix', 
                            'encryption_key'] as $key) {
			$this->$key = (isset($params[$key])) ? $params[$key] : ''; //$this->_session->config->item($key)
		}
                */
                
		if ($this->encryption_key == '') {
			// show_error('In order to use the Session class you are required to set an encryption key in your config file.');
		}

		

		// Are we using a database?  If so, load it
		if ($this->sess_use_database === TRUE AND $this->sess_table_name != '') {
			self::$db  = \init::app() -> getDBConnector();
                        $this->_query = self::$db -> select('sessions', 's', array('target' => 'main'));
		}

		// Set the "now" time.  Can either be GMT or server time, based on the
		// config prefs.  We use this to set the "last activity" time
		$this->now = $this->_get_time();

		// Set the session length. If the session expiration is
		// set to zero we'll set the expiration two years from now.
		if ($this->sess_expiration == 0) {
			$this->sess_expiration = (60*60*24*365*2);
		}

		// Set the cookie name
		$this->sess_cookie_name = $this->cookie_prefix.$this->sess_cookie_name;
                //var_dump( $this->sess_cookie_name ); die('stop_stop');
                
		// Run the Session routine. If a session doesn't exist we'll
		// create a new one.  If it does, we'll update it.
		if ( ! $this->sess_read()) {
			$this->sess_create();
		} else {
			$this->sess_update();
                        
                        
		}

		// Delete 'old' flashdata (from last request)
		$this->_flashdata_sweep();

		// Mark all new flashdata as old (data will be deleted before next request)
		$this->_flashdata_mark();

		// Delete expired sessions if necessary
		$this->_sess_gc();

		// log_message('debug', "Session routines successfully run");
	}

        function is_session_started() {
            if ( php_sapi_name() !== 'cli' ) {
                if ( version_compare(phpversion(), '5.4.0', '>=') ) {
                    return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
                } else {
                    return session_id() === '' ? FALSE : TRUE;
                }
            }
            return FALSE;
        }
        
        public static function init() {
            
        }

        
        public function setSession() {
            $argv = func_get_args();
            //var_dump( $argv ); die('session');
            if(is_array($argv) and count($argv) > 0) {
		
                foreach ((array)$argv as $key => $val) {
                        $this->userdata[$key] = $val;
                }
		

                $this->sess_write(); 
               return $this;
            }
        }

        // --------------------------------------------------------------------

        protected function getCokkiesName( $name ) {
             
            
            if(!empty($name) and !$this->_bool_session) {
                $_cokkies = array();
                foreach($_COOKIE as $key => $value) {
                    if($name == $key)
                        $_cokkies[$key] = $value;
                }
                
                if(is_array($_cokkies) and count($_cokkies) > 0)
                    return $_cokkies;
                
            } elseif(!empty($name) and $this->_bool_session) {
                $_session = array();
                foreach($_SESSION as $key => $value) {
                    if($name == $key)
                        $_session[$key] = $value;
                }
                
                if(is_array($_session) and count($_session) > 0)
                    return $_session;
            } else {
                return false;
            }
        }
        
	/**
	 * Fetch the current session data if it exists
	 *
	 * @access	public
	 * @return	bool
	 */
	function sess_read() {
                
		// Fetch the cookie
		$session = $this ->getCokkiesName( $this->sess_cookie_name );
                
                
                
                if(is_array($session)) $session = $session[$this->sess_cookie_name];
		// No cookie?  Goodbye cruel world!...
		if ($session === FALSE) {
			// log_message('debug', 'A session cookie was not found.');
			return FALSE;
		}
                
                

                // encryption was not used, so we need to check the md5 hash
                $hash	 = substr($session, strlen($session)-32); // get last 32 chars
                $session = substr($session, 0, strlen($session)-32);

                
                
                // Does the md5 hash match?  This is to prevent manipulation of session data in userspace
                if ($hash !==  md5($session.$this->encryption_key)) {
                        // log_message('error', 'The session cookie data did not match what was expected. This could be a possible hacking attempt.');
                        $this->sess_destroy();
                        return FALSE;
                }
		

		// Unserialize the session array
		$session = $this->_unserialize($session);

		// Is the session data we unserialized an array with the correct format?
		if ( ! is_array($session) OR 
                        ! isset($session['session_id']) OR 
                        ! isset($session['ip_address']) OR 
                        ! isset($session['user_agent']) OR 
                        ! isset($session['last_activity'])) {
			$this->sess_destroy();
			return FALSE;
		}

                
                
		// Is the session current?
		if (($session['last_activity'] + $this->sess_expiration) < $this->now) {
			$this->sess_destroy();
			return FALSE;
		}

		// Does the IP Match?
		if ($this->sess_match_ip == TRUE AND $session['ip_address'] != \init::app()->getRequest()->getUserHostAddress()) {
			$this->sess_destroy();
			return FALSE;
		}

		// Does the User Agent Match?
		if ($this->sess_match_useragent == TRUE AND trim($session['user_agent']) != trim(substr(\init::app()->getRequest()->getUserAgent(), 0, 120))) {
			$this->sess_destroy();
			return FALSE;
		}

		// Is there a corresponding session in the DB?
		if ($this->sess_use_database === TRUE) {
                        
                        
                        $this -> _query -> fields('s', array('session_id', 'ip_address', 'user_agent'));
                       
			$this->_query->condition('session_id', $session['session_id'], '=');

			if ($this->sess_match_ip == TRUE) {
				$this->_query->condition('ip_address', $session['ip_address'], '=');
			}

			if ($this->sess_match_useragent == TRUE) {
				$this->_query->condition('user_agent', $session['user_agent'], '=');
			}

			$query = $this->_query-> execute()->fetchAll(); //fetchAll(_) fetchAllAssoc('s.session_id', PDO::FETCH_ASSOC)

                        
                        
			// No result?  Kill it!
			if (count($query) == 0) {
				$this->sess_destroy();
				return FALSE;
			}

			// Is there custom data?  If so, add it to the main session array
			if (is_array($query) and count($query) > 0) {
				$custom_data = $this->_unserialize($query);

				if (is_array($custom_data)) {
					foreach ($custom_data as $key => $val) {
						$session[$key] = $val;
					}
				}
			}
		}

                
//                var_dump( $session );
//                echo "<hr /> session";
//                echo "<pre>";
//                var_dump( $_COOKIE );
//                echo "</pre>";
//                die('csession');
                
                
                //var_dump($session);
                // die('stop');
                
		// Session is valid!
		$this->userdata = $session;
		unset($session);

		return (isset($this->userdata) and !empty($this->userdata)) ? TRUE: FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Write the session data
	 *
	 * @access	public
	 * @return	void
	 */
	function sess_write()
	{
		// Are we saving custom data to the DB?  If not, all we do is update the cookie
		if ($this->sess_use_database === FALSE) {
			$this->_set_cookie();
			return;
		}
                 
		// set the custom userdata, the session data we will set in a second
		$custom_userdata = $this->userdata;
		$cookie_userdata = array();

		// Before continuing, we need to determine if there is any custom data to deal with.
		// Let's determine this by removing the default indexes to see if there's anything left in the array
		// and set the session data while we're at it
		/*
                foreach (['session_id','ip_address','user_agent','last_activity'] as $val) {
			unset($custom_userdata[$val]);
			$cookie_userdata[$val] = $this->userdata[$val];
		}
                */

		// Did we find any custom data?  If not, we turn the empty array into a string
		// since there's no reason to serialize and store an empty array in the DB
		if (count($custom_userdata) === 0) {
			$custom_userdata = '';
		} else {
			// Serialize the custom data array so we can store it
			$custom_userdata = $this->_serialize($custom_userdata);
		}

                if ($this->sess_use_database === TRUE) {
                    // Run the update query
                    //$this->_query->where('session_id', $this->userdata['session_id']);
                    //$this->_query->update($this->sess_table_name, array('last_activity' => $this->userdata['last_activity'], 'user_data' => $custom_userdata));
                    
                    
                    $_update = self::$db->update($this->sess_table_name) ->fields( array('last_activity' => $this->userdata['last_activity'], 'user_data' => $custom_userdata) );
                    $_update ->condition('session_id', $this->userdata['session_id'], '=') -> execute();
                }
		// Write the cookie.  Notice that we manually pass the cookie data array to the
		// _set_cookie() function. Normally that function will store $this->userdata, but
		// in this case that array contains custom data, which we do not want in the cookie.
               
                
		$this->_set_cookie($cookie_userdata);
	}

	// --------------------------------------------------------------------

	/**
	 * Create a new session
	 *
	 * @access	public
	 * @return	void
	 */
	function sess_create() {
		$sessid = '';
		while (strlen($sessid) < 32) {
			$sessid .= mt_rand(0, mt_getrandmax());
		}

		// To make the session ID even more secure we'll combine it with the user's IP
		$sessid .= \init::app()->getRequest()->getUserHostAddress();

                

		// Serialize the userdata for the cookie
                $cookie_data = '';
                if($this->userdata)  {
                    $cookie_data = $this->_serialize($this->userdata);
                    $cookie_data = $cookie_data.md5($cookie_data.$this->encryption_key);
                }
		
//                var_dump($this->userdata); die('all');
                
		$this->userdata = [
                                    'session_id'	=> md5(uniqid($sessid, TRUE)),
                                    'ip_address'	=> \init::app()->getRequest()->getUserHostAddress(),
                                    'user_agent'	=> substr(\init::app()->getRequest()->getUserAgent(), 0, 120),
                                    'last_activity'	=> $this->now,
                                    'user_data'		=> $cookie_data
                                    ];


		// Save the data to the DB if needed
		if ($this->sess_use_database === TRUE) {
                        self::$db->insert($this->sess_table_name) ->fields($this->userdata) ->execute();
			// $this->_query->query($this->_query->insert($this->sess_table_name, $this->userdata));
		}

		// Write the cookie
		$this->_set_cookie();
	}

	// --------------------------------------------------------------------

	/**
	 * Update an existing session
	 *
	 * @access	public
	 * @return	void
	 */
	function sess_update() {
               
                
                 // var_dump( ($this->userdata['last_activity'] + $this->sess_time_to_update), $this->now ); die('stop');
            
		// We only update the session every five minutes by default
		if (($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now) {
			return;
		}
                
		// Save the old session id so we know which record to
		// update in the database if we need it
		$old_sessid = $this->userdata['session_id'];
		$new_sessid = '';
		while (strlen($new_sessid) < 32) {
			$new_sessid .= mt_rand(0, mt_getrandmax());
		}

		// To make the session ID even more secure we'll combine it with the user's IP
		$new_sessid .= \init::app()->getRequest()->getUserHostAddress();

		// Turn it into a hash
		$new_sessid = md5(uniqid($new_sessid, TRUE));

		// Update the session data in the session data array
		$this->userdata['session_id'] = $new_sessid;
		$this->userdata['last_activity'] = $this->now;

		// _set_cookie() will handle this for us if we aren't using database sessions
		// by pushing all userdata to the cookie.
		$cookie_data = NULL;

                
                
		// Update the session ID and last_activity field in the DB if needed
		if ($this->sess_use_database === TRUE) {
			// set cookie explicitly to only have our session data
			/*$cookie_data = array();
                        
			foreach (['session_id','ip_address','user_agent','last_activity'] as $val) {
				$cookie_data[$val] = $this->userdata[$val];
			}
                        */
                        
                    
                        // var_dump( $cookie_data ); die('update');
                        
			// $this->_session->db->query($this->_session->db->update_string($this->sess_table_name, array('last_activity' => $this->now, 'session_id' => $new_sessid), array('session_id' => $old_sessid)));
		
                        $_update = self::$db->update($this->sess_table_name) ->fields( array('last_activity' => $this->now, 'session_id' => $new_sessid) );
                        $_update ->condition('session_id', $old_sessid, '=') -> execute();
                 }

                
                
		// Write the cookie
		$this->_set_cookie($cookie_data);
	}

	// --------------------------------------------------------------------

	/**
	 * Destroy the current session
	 *
	 * @access	public
	 * @return	void
	 */
	function sess_destroy() {
		// Kill the session DB row
		if ($this->sess_use_database === TRUE && isset($this->userdata['session_id'])) {
			//$this->_session->db->where('session_id', $this->userdata['session_id']);
			//$this->_session->db->delete($this->sess_table_name);
                        $_update = self::$db->delete($this->sess_table_name);
                        $_update ->condition('session_id', $this->userdata['session_id'], '=') -> execute();
                    
		}

                if($this-> _bool_session) {
                    if(isset($_SESSION[$this->sess_cookie_name]))
                        unset($_SESSION[$this->sess_cookie_name]);
                } else {
                
                    // Kill the cookie
                    setcookie(
                                            $this->sess_cookie_name,
                                            addslashes(serialize(array())),
                                            ($this->now - 31500000),
                                            $this->cookie_path,
                                            $this->cookie_domain,
                                            0
                                    );
                }
		// Kill session data
		$this->userdata = array();
                return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch a specific item from the session array
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	function userdata($item) {
		return ( ! isset($this->userdata[$item])) ? FALSE : $this->userdata[$item];
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch all session data
	 *
	 * @access	public
	 * @return	array
	 */
	function all_userdata() {                
                
		return $this->userdata;
	}

	// --------------------------------------------------------------------

	/**
	 * Add or change data in the "userdata" array
	 *
	 * @access	public
	 * @param	mixed
	 * @param	string
	 * @return	void
	 */
	function set_userdata($newdata = array(), $newval = '') {
		if (is_string($newdata)) {
			$newdata = [$newdata => $newval];
		}

		if (count($newdata) > 0) {
			foreach ($newdata as $key => $val) {
				$this->userdata[$key] = $val;
			}
		}

                 
                
		$this->sess_write();
                
                return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Delete a session variable from the "userdata" array
	 *
	 * @access	array
	 * @return	void
	 */
	function unset_userdata($newdata = array()) {
		if (is_string($newdata)) {
			$newdata = [$newdata => ''];
		}

		if (count($newdata) > 0) {
			foreach ($newdata as $key => $val) {
				unset($this->userdata[$key]);
			}
		}

		$this->sess_write();
                return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Add or change flashdata, only available
	 * until the next request
	 *
	 * @access	public
	 * @param	mixed
	 * @param	string
	 * @return	void
	 */
	function set_flashdata($newdata = array(), $newval = '') {
		if (is_string($newdata)) {
			$newdata = [$newdata => $newval];
		}

		if (count($newdata) > 0) {
			foreach ($newdata as $key => $val) {
				$flashdata_key = $this->flashdata_key.':new:'.$key;
				$this->set_userdata($flashdata_key, $val);
			}
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Keeps existing flashdata available to next request.
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */
	function keep_flashdata($key) {
		// 'old' flashdata gets removed.  Here we mark all
		// flashdata as 'new' to preserve it from _flashdata_sweep()
		// Note the function will return FALSE if the $key
		// provided cannot be found
		$old_flashdata_key = $this->flashdata_key.':old:'.$key;
		$value = $this->userdata($old_flashdata_key);

		$new_flashdata_key = $this->flashdata_key.':new:'.$key;
		$this->set_userdata($new_flashdata_key, $value);
	}

	// ------------------------------------------------------------------------

	/**
	 * Fetch a specific flashdata item from the session array
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	function flashdata($key) {
		$flashdata_key = $this->flashdata_key.':old:'.$key;
		return $this->userdata($flashdata_key);
	}

	// ------------------------------------------------------------------------

	/**
	 * Identifies flashdata as 'old' for removal
	 * when _flashdata_sweep() runs.
	 *
	 * @access	private
	 * @return	void
	 */
	function _flashdata_mark() {
		$userdata = $this->all_userdata();
		foreach ($userdata as $name => $value) {
			$parts = explode(':new:', $name);
			if (is_array($parts) && count($parts) === 2) {
				$new_name = $this->flashdata_key.':old:'.$parts[1];
				$this->set_userdata($new_name, $value);
				$this->unset_userdata($name);
			}
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Removes all flashdata marked as 'old'
	 *
	 * @access	private
	 * @return	void
	 */

	function _flashdata_sweep() {
		$userdata = $this->all_userdata();
		foreach ($userdata as $key => $value) {
			if (strpos($key, ':old:')) {
				$this->unset_userdata($key);
			}
		}

	}

	// --------------------------------------------------------------------

	/**
	 * Get the "now" time
	 *
	 * @access	private
	 * @return	string
	 */
	function _get_time() {
		if (strtolower($this->time_reference) == 'gmt') {
			$now = time();
			$time = mktime(gmdate("H", $now), gmdate("i", $now), gmdate("s", $now), gmdate("m", $now), gmdate("d", $now), gmdate("Y", $now));
		} else {
			$time = time();
		}
                
                // var_dump($time); die('stop');

		return $time;
	}

	// --------------------------------------------------------------------

	/**
	 * Write the session cookie
	 *
	 * @access	public
	 * @return	void
	 */
	function _set_cookie($cookie_data = NULL) {
                // var_dump($this->userdata); die('all');
            
		if (is_null($cookie_data) or !$cookie_data) {
			$cookie_data = $this->userdata;
		}

                
                 // var_dump( $cookie_data, $this->userdata ); // die('cookie');
                
		// Serialize the userdata for the cookie
		$cookie_data = $this->_serialize($cookie_data);
		$cookie_data = $cookie_data.md5($cookie_data.$this->encryption_key);
		$expire = ($this->sess_expire_on_close === TRUE) ? 0 : $this->sess_expiration + time();

                if($this->_bool_session) {
                    $_SESSION[ $this->sess_cookie_name ] = $cookie_data;
                } else {
                
                    // Set the cookie
                    setcookie(
                                            $this->sess_cookie_name,
                                            $cookie_data,
                                            $expire,
                                            $this->cookie_path,
                                            $this->cookie_domain,
                                            $this->cookie_secure
                                    );
                }
                                // var_dump($_COOKIE); die('stop');
                
	}

	// --------------------------------------------------------------------

	/**
	 * Serialize an array
	 *
	 * This function first converts any slashes found in the array to a temporary
	 * marker, so when it gets unserialized the slashes will be preserved
	 *
	 * @access	private
	 * @param	array
	 * @return	string
	 */
	function _serialize($data) {
		if (is_array($data)) {
			foreach ($data as $key => $val) {
				if (is_string($val)) {
					$data[$key] = str_replace('\\', '{{slash}}', $val);
				}
			}
		} else {
			if (is_string($data)) {
				$data = str_replace('\\', '{{slash}}', $data);
			}
		}

		return serialize($data);
	}

	// --------------------------------------------------------------------

	/**
	 * Unserialize
	 *
	 * This function unserializes a data string, then converts any
	 * temporary slash markers back to actual slashes
	 *
	 * @access	private
	 * @param	array
	 * @return	string
	 */
	function _unserialize($data) {
                //var_dump( unserialize($data) ); die('stop');
            
                if(is_string($data)) {
                    $data = unserialize(stripcslashes($data));
                } else {
                    return; // error!
                }
		

		if (is_array($data)) {
			foreach ($data as $key => $val) {
				if (is_string($val)) {
					$data[$key] = str_replace('{{slash}}', '\\', $val);
				}
			}

			return $data;
		}

		return (is_string($data)) ? str_replace('{{slash}}', '\\', $data) : $data;
	}

	// --------------------------------------------------------------------

	/**
	 * Garbage collection
	 *
	 * This deletes expired session rows from database
	 * if the probability percentage is met
	 *
	 * @access	public
	 * @return	void
	 */
	function _sess_gc() {
		if ($this->sess_use_database != TRUE) {
			return;
		}

		srand(time());
		if ((rand() % 100) < $this->gc_probability) {
			$expire = $this->now - $this->sess_expiration;

			//$this->_session->db->where("last_activity < {$expire}");
			self::$db->delete($this->sess_table_name) ->condition('last_activity', $expire, '<') -> execute();

			// log_message('debug', 'Session garbage collection performed.');
		}
	}
        
        
        public function _clearSession() {
            if(isset($_COOKIE[ $this->sess_cookie_name ])) {
              unset($_COOKIE[$this->sess_cookie_name]);
              setcookie($this->sess_cookie_name, '', time() - 3600); // empty value and old timestamp
              $this->userdata = false;
            }
            
            if(isset($_SESSION[ $this->sess_cookie_name ]) and $this->_bool_session) {
                unset($_SESSION[ $this->sess_cookie_name ]);
                $this->userdata = false;
            }
            
            return $this;
        }


}
// END Session Class