<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MY_Controller {

	public function __construct(){
        parent::__construct();

        $this->lang->load('settings');

        $this->load->model('settings_model', 'settings_m');

       
    }

    public function index() {
    	$this->list_actions();
    }

    public function list_actions() {
    	$actions = array();
    	$actions['title'] = lang('actions.title');
		$actions['content'] = $this->layout->load_view('settings/actions_list', array('is_admin' => $this->session->userdata('is_admin')));
		$data_output['content'] = $this->layout->load_view('utils/group', $actions);
		$this->layout->output_view($data_output);
    }

    public function create_user() {

        $this->load->helper(array('form')); 
		$this->load->library('form_validation');

		$this->form_validation->set_rules('login', lang('settings.create_user.login'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('pwd', lang('settings.create_user.password'), 'trim|required|matches[pwd_confirm]');
		$this->form_validation->set_rules('pwd_confirm', lang('settings.create_user.password_confirmation'), 'trim|required');

		$create_user = array();
        $create_user['title'] = lang('settings.create_user.title');

		if ($this->form_validation->run() === FALSE) {

   			$create_user['content'] = $this->layout->load_view('settings/create_user_form');
   			$data_output['content'] = $this->layout->load_view('utils/group', $create_user);
			$this->layout->output_view($data_output);

		}
		else {
			$login = $this->input->post('login', TRUE);
			$password = $this->input->post('pwd', TRUE);
			$name = $this->input->post('name', TRUE);
			$is_admin = ($this->input->post('is_admin', TRUE) === '1');

			$new_user = $this->settings_m->create_user($login, $password, $name, '', '', '', $is_admin);

			if($new_user !== FALSE) {
	   			$create_user['content'] = $this->layout->load_view('settings/create_user_result', array(
	   				'name' => $name,
	   				'login' => $login
	   			));
	   			$data_output['content'] = $this->layout->load_view('utils/group', $create_user);
				$this->layout->output_view($data_output);
			}
			else {
				$create_user['content'] = $this->layout->load_view('utils/error');
	   			$data_output['content'] = $this->layout->load_view('utils/group', $create_user);
				$this->layout->output_view($data_output);
			}

		}  	

    }
}