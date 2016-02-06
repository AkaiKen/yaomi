<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Lang extends CI_Lang {

	function __construct() {

        parent::__construct();

    }

    /**
	 * Fetch a single line of text from the language array
	 *
	 * @access	public
	 * @param	string	$line	the language line
	 * @return	string
	 */
	function line($line = '')
	{

		global $CFG;
 		$is_debug = isset($CFG->config['debug_lang']) ? $CFG->config['debug_lang'] : FALSE;

		//$is_debug = $this->config->item('debug');
		if($is_debug) {
			$value = ($line == '' OR ! isset($this->language[$line])) ? '['.$line.']' : $this->language[$line];
		}
		else {
			$value = ($line == '' OR ! isset($this->language[$line])) ? FALSE : $this->language[$line];
		}

		// Because killer robots like unicorns!
		if ($value === FALSE)
		{
			log_message('error', 'Could not find the language line "'.$line.'"');
		}

		return $value;
	}


}
