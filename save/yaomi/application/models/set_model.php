<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Set_model extends CI_Model {

	function get_set($set_identifier, $field_in = "id", $field_out = '') {

		$this->db->select($field_out)
			->from('mdm_sets')
			->where('mdm_sets.' . $field_in ,$set_identifier);

		$exec = $this->db->get();

	//	echo $this->db->last_query();

		if($exec->num_rows() > 0) {
			if($field_out !== '') {
				return $exec->row()->$field_out;
			}
			return $exec->row();
		}
		return FALSE;
	}

	function get_all_sets() {

		$this->db->select('name, name_fr, code')
			->from('mdm_sets');

		$exec = $this->db->get();

		if($exec->num_rows() > 0) {
			return $exec->result();
		}
		return FALSE;

	}

	function get_set_cards($set_code) {
		
		$this->db->select('mdm_cards.name, mdm_cards.name_fr, mdm_cards_x_sets.card_number')
			->from('mdm_cards')
			->join('mdm_cards_x_sets', 'mdm_cards_x_sets.fk_card = mdm_cards.id')
			->join('mdm_sets', 'mdm_sets.id = mdm_cards_x_sets.fk_set')
			->where('mdm_sets.code', $set_code);

		$exec = $this->db->get();

		if($exec->num_rows() > 0) {
			return $exec->result();
		}
		return FALSE;

	}


}