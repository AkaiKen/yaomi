<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Set_model extends CI_Model {

	function get_set($set_identifier, $field_in = "id", $field_out = '') {

		$this->db->select($field_out)
			->from('mdm_sets')
			->where('mdm_sets.' . $field_in , $set_identifier);

		$exec = $this->db->get();

	//	echo $this->db->last_query();

		// if($exec->num_rows() > 0) {
		if(count($exec->result()) > 0) {
			if($field_out !== '') {
				return $exec->row()->$field_out;
			}
			return $exec->row();
		}
		return FALSE;
	}

	function get_all_sets($grouped = TRUE) {

		$what = array(
			'mdm_sets.name AS set_name', 
			'mdm_sets.name_fr AS set_name_fr', 
			'code AS set_code', 
			'mdm_blocks.id AS block_id',
			'mdm_blocks.name AS block_name', 
			'mdm_blocks.name_fr AS block_name_fr'
		);

		if(!$grouped) {
			$what = array(
				'mdm_sets.name AS set_name', 
				'mdm_sets.name_fr AS set_name_fr', 
				'code AS set_code', 
			);
		}

		$this->db->select($what)
			->from('mdm_sets')
			->join('mdm_blocks', 'mdm_blocks.id = mdm_sets.fk_block', 'left');
		//	->order_by('mdm_blocks.order', 'desc');

		$exec = $this->db->get();

		// if($exec->num_rows() > 0) {
		if(count($exec->result()) > 0) {
			if(!$grouped){
				return $exec->result();
			}
			$result = $exec->result();
			$blocks = array();
			foreach($result as $set) {
				if(!isset($blocks[$set->block_id])){
					$blocks[$set->block_id] = array();
					$blocks[$set->block_id]['name'] = $set->block_name;
					$blocks[$set->block_id]['name_fr'] = $set->block_name_fr;
					$blocks[$set->block_id]['sets'] = array();
				}
				$set->image = $this->get_set_symbol($set->set_code);
				$blocks[$set->block_id]['sets'][] = $set;
			}
			return $blocks;
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

		// if($exec->num_rows() > 0) {
		if(count($exec->result()) > 0) {
			return $exec->result();
		}
		return FALSE;

	}

	function get_set_symbol($set_code) {

		$symbols_location = $this->config->item('symbols_location');
		$image_full_path = $symbols_location . '/' . $set_code . '.png';

		$image_full_path = (_file_exists($image_full_path)) ? $image_full_path : NULL ;

		return $image_full_path;

	}


}
