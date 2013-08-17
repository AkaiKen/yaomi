<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_model extends CI_Model {

	function insert_card($card_data) {
		echo 'on insère dans cards';
		//var_dump($card_data);

		$data = array(
			'name' => $card_data['name'],
			'name_fr' => $card_data['name_fr'],
			'card_type' => $card_data['card_type'],
			'power' => $card_data['power'],
			'toughness' => $card_data['toughness'],
			'loyalty' => $card_data['loyalty'],
			'mana_cost' => $card_data['mana_cost'],
			'converted_mana_cost' => $card_data['converted_mana_cost'],
			'ability' => $card_data['ability'],
			'color' => $card_data['color'],
			'generated_mana' => $card_data['generated_mana'],
			'rulings' => $card_data['rulings']
		);
		$this->db->insert('mdm_cards', $data);

		return $this->db->insert_id();
	}

	function update_card($card_id, $card_data) {

		echo 'on update cards';
		//var_dump($card_data);

		$this->db->where('id', $card_id);
		$data_card = array(
			'name' => $card_data['name'],
			'name_fr' => $card_data['name_fr'],
			'card_type' => $card_data['card_type'],
			'power' => $card_data['power'],
			'toughness' => $card_data['toughness'],
			'loyalty' => $card_data['loyalty'],
			'mana_cost' => $card_data['mana_cost'],
			'converted_mana_cost' => $card_data['converted_mana_cost'],
			'ability' => $card_data['ability'],
			'color' => $card_data['color'],
			'generated_mana' => $card_data['generated_mana'],
			'rulings' => $card_data['rulings']
		);
		$this->db->set($data_card);
		$this->db->update('mdm_cards');

		
	}

	function insert_card_set($card_id, $set_id, $card_data) {
		echo 'on insère in cards_x_sets';
		//var_dump($card_data);

		$data = array(
			'fk_card' => $card_id,
			'fk_set' => $set_id,
			'card_number' => $card_data['card_number'],
			'rarity' => $card_data['rarity'],
			'variation' => ($card_data['variation'] !== '') ? $card_data['variation'] : NULL,
			'watermark' => $card_data['watermark'],
			'artist' => $card_data['artist'],
			'flavor_text' => $card_data['flavor_text']
		);
		$this->db->insert('mdm_cards_x_sets', $data);

	}

	function update_card_set($card_id, $set_id, $card_data) {

		echo 'on update in cards_x_sets';

		$this->db->where('fk_card', $card_id);
		$this->db->where('fk_set', $set_id);
		$data = array(
			'fk_card' => $card_id,
			'fk_set' => $set_id,
			'card_number' => $card_data['card_number'],
			'rarity' => $card_data['rarity'],
			'variation' => ($card_data['variation'] !== '') ? $card_data['variation'] : NULL,
			'watermark' => $card_data['watermark'],
			'artist' => $card_data['artist'],
			'flavor_text' => $card_data['flavor_text']
		);
		$this->db->set($data);
		$this->db->update('mdm_cards_x_sets');
		
	}


}