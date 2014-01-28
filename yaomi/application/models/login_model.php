<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model {

	function authenticate($login, $password) {

		// encrypted password = sha1(pwd.salt)
    	// the salt is in config

		$salt = $this->config->item('salt');
    	$encrypted_pwd = sha1($password.$salt);

    	$this->db->select('id, name')
    		->from('auth_users')
    		->where(array('login' => $login, 'password' => $encrypted_pwd));

    	$exec = $this->db->get();

    	if($exec->num_rows() > 0) {
    		// yes!
    		$user = $exec->row();
            $this->session->set_userdata('user_id', $user->id);
    		$this->session->set_userdata('user_name', $user->name);
    		$this->session->set_userdata('is_logged', TRUE);
    		return TRUE;
    	} 
    	else {
    		return FALSE;
    	}
	}




}