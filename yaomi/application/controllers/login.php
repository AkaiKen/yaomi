<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('login_model', 'login_m');

        ini_set('display_errors', 1);
    }

    public function index() {

    	if(is_logged()) {
    		$this->_go_inside();
    	}
    	else {
            $this->load->helper(array('form')); 
    		$this->load->library('form_validation');
    		$this->form_validation->set_rules('login', 'Identifiant', 'required');
			$this->form_validation->set_rules('pwd', 'Mot de passe', 'required');
			if ($this->form_validation->run() === FALSE) {
                $data_output = array();
                $data_output['login_form'] = $this->layout->load_view('login_form');
                $data_output['page_title'] = 'Connexion';
                $this->session->unset_userdata('login_errors');
                $this->layout->output_view($data_output);
			}
			else {
				$login = $this->input->post('login', TRUE);
				$pwd = $this->input->post('pwd', TRUE);
				$this->_authenticate($login, $pwd);
			}
    	}

    }

    private function _authenticate($login, $pwd) {

        $is_authenticated = $this->login_m->authenticate($login, $pwd);

        if($is_authenticated) {
            $this->_go_inside();
        }
        else {
            $this->session->set_userdata('login_errors', lang('login.bad_credentials'));
            redirect('/');
            exit();
        }
    	
    }

    private function _go_inside($to = '') {
    	redirect($to);
    	exit();
    }

    public function logout() {
        $this->session->sess_destroy();
    	redirect('/');
    }

}