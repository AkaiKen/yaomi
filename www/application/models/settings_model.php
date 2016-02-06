<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends CI_Model {

	function create_user($login, $password, $name = '', $email = '', $app_lang = '', $cards_lang = '', $is_admin = FALSE, $is_active = FALSE) {
		
		// encrypted password = sha1(pwd.salt)
    	// the salt is in config

		$salt = $this->config->item('salt');
    	$encrypted_pwd = sha1($password.$salt);

    	$new_user = array(
		   'login' => $login,
		   'password' => $encrypted_pwd,
		   'name' => $name,
		   'email' => $email,
		   'app_lang' => $app_lang,
		   'cards_lang' => $cards_lang,
		   'is_admin' => $is_admin,
		   'is_active' => $is_active
		);

		$this->db->insert('auth_users', $new_user); 

		$new_user_id = $this->db->insert_id();

		return $new_user_id;
			
	}

	function validate_user($user_id) {




	}

	function list_non_translated_cards() {

		$this->db->select('id, name')
			->from("mdm_cards")
			->where('name_fr', '')
			->order_by('name');

		$exec = $this->db->get();

		// if($exec->num_rows() > 0) {
		if(count($exec->result()) > 0) {
			return $exec->result();
		}
		return FALSE;


	}

	function set_card_as_non_translated($id_card) {
		//$data = array("name_fr", NULL);
		$this->db->set('name_fr', NULL);
		$this->db->where('id', $id_card);
		$this->db->update('mdm_cards');
	}
}