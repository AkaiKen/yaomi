<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends MY_Controller {

	public function __construct(){
        parent::__construct();
    }

    function index() {
        
        $data_output = array();

        $about = array();
        $about['title'] = "À propos";
        $about['content'] = $this->layout->load_view('about');

        $this->load->helper(array('form')); 
        $this->load->library('form_validation');
        $data_output['login_form'] = $this->layout->load_view('login_form');

    	$data_output['content'] = $this->layout->load_view('group', $about);

		$this->layout->output_view($data_output) ;
    }


}