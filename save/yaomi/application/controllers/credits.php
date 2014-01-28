<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Credits extends MY_Controller {

	public function __construct(){
        parent::__construct();
    }

    function index() {

    	$credits = array();
    	$credits['title'] = 'Crédits et remerciements';
    	$credits['content'] = $this->layout->load_view('credits');

        $this->load->helper(array('form')); 
        $this->load->library('form_validation');
        $data_output['login_form'] = $this->layout->load_view('login_form');

        $data_output['content'] = $this->layout->load_view('group', $credits);

        $this->layout->output_view($data_output);
    }


}