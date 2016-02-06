<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        //ini_set('display_errors', 1);

        $page = $this->uri->rsegment(1);
        $sub_page = $this->uri->rsegment(2);

        // load custom config file
        //$this->load->config('yaomi_config');

       // var_dump( $this->config->item('is_installed') );

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
        //$public_pages = array('login', 'about', 'credits', 'install', 'register');

        // is the user on a private page?
        // if "no" and "yes", we redirect them on the login page
        $private_pages = array('settings');
 		if(!$is_logged && in_array($page, $private_pages)) {
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


        $this->is_logged = $is_logged;
        $this->user = $this->session->userdata('user_id');

        if(!isset($this->body_class)) {
            $this->body_class = array();
        }
    }

    function _display_error($message = "") {
        $data['title'] = 'Oups';
        $data['content'] = $this->layout->load_view('utils/error', array('content' => $message));
        $data_output['content'] = $this->layout->load_view('utils/group', $data);
        $this->layout->output_view($data_output);
    }

    function _display_success($message = "") {
        $data['title'] = 'Woké';
        $data['content'] = $this->layout->load_view('utils/success', array('content' => $message));
        $data_output['content'] = $this->layout->load_view('utils/group', $data);
        $this->layout->output_view($data_output);
    }


}