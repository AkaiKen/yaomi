<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Card_model extends CI_Model {

	function get_card($card_identifier, $field_in = 'id', $field_out = '') {

		$several_out = FALSE;
		if(is_array($field_out)) {
			$several_out = TRUE;
			$field_out = implode(',', $field_out);
		}

		$this->db->select($field_out)
			->from('mdm_cards')
			->where('mdm_cards.' . $field_in, $card_identifier);

		$exec = $this->db->get();

		if($exec->num_rows() > 0) {
			if($field_out !== '' || !$several_out) {
				return $exec->row()->$field_out;
			}
			return $exec->row();
		}
		return FALSE;
	}

	function get_card_sets($card_id) {
		$this->db->select('fk_set')
			->from('mdm_cards_x_sets')
			->where('fk_card', $card_id);

		$exec = $this->db->get();

		if($exec->num_rows() > 0) {
			$result = $exec->result();
			$sets = array();
			foreach($result as $set) {
				$sets[] = $set->fk_set;
			}
			return $sets;
		}
		return FALSE;
	}

	function get_card_variation($card_id, $set_code) {

		$this->db->select('variation')
			->from('mdm_cards_x_sets')
			->join('mdm_sets', 'mdm_cards_x_sets.fk_set = mdm_sets.id')
			->where('fk_card', $card_id)
			->where('mdm_sets.code', $set_code);

		$exec = $this->db->get();

		if($exec->num_rows() > 0 && $exec->row()->variation !== NULL) {
			return $exec->row()->variation;
		}
		return FALSE;

	}

	function count_existing_cards() {

		return $this->db->count_all('mdm_cards');

	}
}