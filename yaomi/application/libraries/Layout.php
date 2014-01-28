<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Extremly Simple Layout Class
 *
 * Abstract the views loading
 *
 * Howto :
 * - load a partial view (header, footer, some block) with load_view, which returns the view in a variable
 * - output the final view with output_view (basically for layout views, but can be used for others containers)
 *
 * @package			CodeIgniter
 * @subpackage		Libraries
 * @category		Libraries
 * @author			Lara Dufour pour Asdoria
 * @license
 * @link
 */
class Layout
{

	function load_view($view_name, $data = ''){

		$CI =& get_instance();

		// est-on en système de modules ? (avec Module Extensions par exemple)
		$module = "";
		if (method_exists( $CI->router, 'fetch_module' )){
			$module = $CI->router->fetch_module();

			if(file_exists(APPPATH. 'modules/' . $module . '/views/' . $view_name .'.php')){
				return $CI->load->view($view_name, $data, TRUE);
			}
			elseif(file_exists(APPPATH.'views/' . $view_name .'.php')){
				return $CI->load->view($view_name, $data, TRUE);
			}
			else {
				return (isset($CI->config->config['debug_view']) && $CI->config->config['debug_view'] === TRUE) 
				? "The view '" . $view_name . "' is missing."  
				: '';
			}
		}

		// pas de système de modules
		if(file_exists(APPPATH.'views/' . $view_name .'.php')){
			return $CI->load->view($view_name, $data, TRUE);
		}
		else {
			return (isset($CI->config->config['debug_view']) && $CI->config->config['debug_view'] === TRUE)
			? "The view '" . $view_name . "' is missing."  
			: '';
		}

		// OLD
		//return (file_exists(APPPATH.'views/' . $view_name .'.php')) ? $CI->load->view($view_name, $data, TRUE) : '';
	}

	function output_view( $content = array(), $layout_view = 'layout'){
		$CI =& get_instance();
		$CI->load->view($layout_view, $content);
	}
}

/** END of file Layout.php **/
