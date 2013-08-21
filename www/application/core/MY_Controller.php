<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        ini_set('display_errors', 1);

        $page = $this->uri->rsegment(1);
        $sub_page = $this->uri->rsegment(2);

        //is the application initialized?
        // $is_installed = $this->config->item('install');
        // if(!$is_installed && ($page !== 'install')) {
        //     redirect('install');
        //     exit();
        // }

        // is the user logged?
        $is_logged = $this->session->userdata('is_logged');

        // is the user on a public page?
        // if "no" and "no", we redirect them on the login page
        $public_pages = array('login', 'about', 'credits', 'install');
 		if(!$is_logged && !in_array($page, $public_pages)) {
			redirect('login');
            exit;
		}
    
        // url history
        if($this->session->userdata('current_url') !== current_url()) {
            $prev_url = $this->session->userdata('current_url');
            $current_url = current_url();
        }
        else {
            $prev_url = $this->session->userdata('prev_url');
            $current_url = $this->session->userdata('current_url');
        }

        // building of session
        $this->session->set_userdata(
            array(
                'page' => $page,
                'sub_page' => $sub_page,
                'current_url' => $current_url,
                'prev_url' => $prev_url
            )
        );
    }
}