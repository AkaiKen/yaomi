<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model {

	function authenticate($login, $password) {

		// encrypted password = sha1(pwd.salt)
    	// the salt is in config

		$salt = $this->config->item('salt');
    	$encrypted_pwd = sha1($password.$salt);

    	$this->db->select('id, name, is_admin')
    		->from('auth_users')
    		->where(array('login' => $login, 'password' => $encrypted_pwd, 'is_active' => 1));

    	$exec = $this->db->get();

    	//if($exec->num_rows() > 0) {
        if(count($exec->result()) > 0) {
    		// yes!
    		$user = $exec->row();
            $this->session->set_userdata('user_id', $user->id);
    		$this->session->set_userdata('user_name', $user->name);
            $this->session->set_userdata('is_logged', TRUE);
    		$this->session->set_userdata('is_admin', ($user->is_admin === '1'));
    		return TRUE;
    	} 
    	else {
    		return FALSE;
    	}
	}




}