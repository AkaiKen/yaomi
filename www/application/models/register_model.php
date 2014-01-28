<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register_model extends CI_Model {

	function ask_validation($user_id) {

		$this->db->select('email')
			->from('auth_users')
			->where('is_admin', 1);

		$query_admin = $this->db->get();

		if($query_admin->num_rows() > 0){

			$this->load->library('email');

			$result = $query_admin->result();
			
			foreach($result as $admin) {

				if($admin->email !== '') {
					$this->email->from('yaomi@lamecarlate.net', 'yaomi');
					$this->email->to($admin->email);

					$this->email->subject(lang('register.ask_validation.subject'));
					$this->email->message($this->layout->load_view('register/register_mail'));

					$this->email->send();

				//	echo $this->email->print_debugger();

				}

			}
			
		}
		
		return TRUE;

	}

}