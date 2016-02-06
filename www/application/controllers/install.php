<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Install extends MY_Controller {

	public function __construct(){
        parent::__construct();

        $this->lang->load('install');

        $this->load->model('settings_model', 'settings_m');
    }

	public function index() {

		// we are to create the first user
	
        $this->load->helper(array('form')); 
		$this->load->library('form_validation');

		$this->form_validation->set_rules('login', lang('install.login'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('pwd', lang('install.password'), 'trim|required|matches[pwd_confirm]');
		$this->form_validation->set_rules('pwd_confirm', lang('install.password_confirmation'), 'trim|required');

		if ($this->form_validation->run() === FALSE) {

			$install = array();
        	$install['title'] = lang('install.title');
   			$install['content'] = $this->layout->load_view('install/install_form');
   			$data_output['content'] = $this->layout->load_view('utils/group', $install);
			$this->layout->output_view($data_output);

		}
		else {
			$login = $this->input->post('login', TRUE);
			$password = $this->input->post('pwd', TRUE);
			$new_user = $this->settings_m->create_user($login, $password, '', '', '', TRUE);

			if($new_user !== FALSE) {
				$this->session->set_userdata('user_id', $new_user);
    			$this->session->set_userdata('is_logged', TRUE);
    			$this->config->set_item('install',TRUE);
    			redirect('home');
			}

			//var_dump($new_user);

		}  	

	}

}