<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends MY_Controller {

	public function __construct(){
        parent::__construct();

        $this->lang->load('register');

        $this->load->model('settings_model', 'settings_m');
        $this->load->model('register_model', 'register_m');
       
    }

    public function index() {
    		
    	$this->load->helper(array('form')); 
		$this->load->library('form_validation');

		$this->form_validation->set_error_delimiters('<span class="message-error">', '</span>');

		$this->form_validation->set_rules('login', lang('register.login'), 'trim|required|xss_clean|is_unique[auth_users.login]');
		$this->form_validation->set_message('is_unique', lang('register.login.unique'));
		$this->form_validation->set_rules('email', lang('register.email'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('pwd', lang('register.password'), 'trim|required|matches[pwd_confirm]');
		$this->form_validation->set_rules('pwd_confirm', lang('register.password_confirmation'), 'trim|required');

		$create_user = array();
        $create_user['title'] = lang('register.title');

		if ($this->form_validation->run() === FALSE) {
   			$create_user['content'] = $this->layout->load_view('register/register_form');
   			$data_output['content'] = $this->layout->load_view('utils/group', $create_user);
			$this->layout->output_view($data_output);
		}
		else {
			$login = $this->input->post('login', TRUE);
			$password = $this->input->post('pwd', TRUE);
			$name = $this->input->post('name', TRUE);
			$email = $this->input->post('email', TRUE);
			
			// we create the user, inactivated by default
			$new_user = $this->settings_m->create_user($login, $password, $name, $email);

			if($new_user !== FALSE) {

				// we send a mail to the admins...
				$validation_asked = $this->register_m->ask_validation($new_user);
				

				if($validation_asked) {
					// ...and display success
		   			$create_user['content'] = $this->layout->load_view('register/register_result');
		   			$data_output['content'] = $this->layout->load_view('utils/group', $create_user);
					$this->layout->output_view($data_output);
				}
				else {
					$this->_display_error('souci validation');
				}

				
			}
			else {
				$this->_display_error('already exists');
				// $create_user['content'] = $this->layout->load_view('utils/error');
	   // 			$data_output['content'] = $this->layout->load_view('utils/group', $create_user);
				// $this->layout->output_view($data_output);
			}

		}  	

    }

}