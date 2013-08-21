<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends CI_Model {

	function create_user($login, $password, $email = '', $app_lang = '', $cards_lang = '', $is_admin = FALSE) {

		// first: is this user already existing? if yes, we stop here and 
		$this->db->select('id')->from('auth_users')->where('login', $login);
		$exec = $this->db->get();

		if($exec->num_rows() > 0) {
			return FALSE;
		}
		
		// encrypted password = sha1(pwd.salt)
    	// the salt is in config

		$salt = $this->config->item('salt');
    	$encrypted_pwd = sha1($password.$salt);

    	$new_user = array(
		   'login' => $login,
		   'password' => $encrypted_pwd,
		   'email' => $email,
		   'app_lang' => $app_lang,
		   'cards_lang' => $cards_lang,
		   'is_admin' => $is_admin
		);

		$this->db->insert('auth_users', $new_user); 

		$new_user_id = $this->db->insert_id();

		return $new_user_id;
			
	}

}