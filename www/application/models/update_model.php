<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_model extends CI_Model {

	function insert_card($card_data) {
		echo 'on insère dans cards';
		//var_dump($card_data);

		$data = array(
			'name' => $card_data['name'],
			'name_fr' => (isset($card_data['name_fr'])) ? $card_data['name_fr'] : NULL,
			'card_type' => (isset($card_data['card_type'])) ? $card_data['card_type'] : NULL,
			'power' => (isset($card_data['power'])) ? $card_data['power'] : NULL,
			'toughness' => (isset($card_data['toughness'])) ? $card_data['toughness'] : NULL,
			'loyalty' => (isset($card_data['loyalty'])) ? $card_data['loyalty'] : NULL,
			'mana_cost' => (isset($card_data['mana_cost'])) ? $card_data['mana_cost'] : NULL,
			'converted_mana_cost' => (isset($card_data['converted_mana_cost'])) ? $card_data['converted_mana_cost'] : NULL,
			'ability' => (isset($card_data['ability'])) ? $card_data['ability'] : NULL,
			'color' => (isset($card_data['color'])) ? $card_data['color'] : NULL,
			'generated_mana' => (isset($card_data['generated_mana'])) ? $card_data['generated_mana'] : NULL,
			'rulings' => (isset($card_data['rulings'])) ? $card_data['rulings'] : NULL,
		);
		$this->db->insert('mdm_cards', $data);

		return $this->db->insert_id();
	}

	function update_card($card_id, $card_data) {

		echo 'on update cards : ' . $card_id;
		//var_dump($card_data);
		
		$this->db->where('id', $card_id);
		$data = array(
			//'name' => $card_data['name'],
			//'name_fr' => $card_data['name_fr'],
			//'card_type' => $card_data['card_type'],
			//'power' => $card_data['power'],
			//'toughness' => $card_data['toughness'],
			//'loyalty' => $card_data['loyalty'],
			//'mana_cost' => $card_data['mana_cost'],
			//'converted_mana_cost' => $card_data['converted_mana_cost'],
			//'ability' => $card_data['ability'],
			//'color' => $card_data['color'],
			//'generated_mana' => $card_data['generated_mana'],
			//'rulings' => $card_data['rulings']
		);

		if(isset($card_data['name_fr']) && $card_data['name_fr'] !== NULL && $card_data['name_fr'] !== '') {
			$data['name_fr'] = $card_data['name_fr'];
		}
		if(isset($card_data['name']) && $card_data['name'] !== NULL && $card_data['name'] !== '') {
			$data['name'] = $card_data['name'];
		}
		if(isset($card_data['card_type']) && $card_data['card_type'] !== NULL && $card_data['card_type'] !== '') {
			$data['card_type'] = $card_data['card_type'];
		}
		if(isset($card_data['power']) && $card_data['power'] !== NULL && $card_data['power'] !== '') {
			$data['power'] = $card_data['power'];
		}
		if(isset($card_data['toughness']) && $card_data['toughness'] !== NULL && $card_data['toughness'] !== '') {
			$data['toughness'] = $card_data['toughness'];
		}
		if(isset($card_data['loyalty']) && $card_data['loyalty'] !== NULL && $card_data['loyalty'] !== '') {
			$data['loyalty'] = $card_data['loyalty'];
		}
		if(isset($card_data['mana_cost']) && $card_data['mana_cost'] !== NULL && $card_data['mana_cost'] !== '') {
			$data['mana_cost'] = $card_data['mana_cost'];
		}
		if(isset($card_data['converted_mana_cost']) && $card_data['converted_mana_cost'] !== NULL && $card_data['converted_mana_cost'] !== '') {
			$data['converted_mana_cost'] = $card_data['converted_mana_cost'];
		}
		if(isset($card_data['ability']) && $card_data['ability'] !== NULL && $card_data['ability'] !== '') {
			$data['ability'] = $card_data['ability'];
		}
		if(isset($card_data['color']) && $card_data['color'] !== NULL && $card_data['color'] !== '') {
			$data['color'] = $card_data['color'];
		}
		if(isset($card_data['generated_mana']) && $card_data['generated_mana'] !== NULL && $card_data['generated_mana'] !== '') {
			$data['generated_mana'] = $card_data['generated_mana'];
		}
		if(isset($card_data['rulings']) && $card_data['rulings'] !== NULL && $card_data['rulings'] !== '') {
			$data['rulings'] = $card_data['rulings'];
		}

		$this->db->set($data);
		$this->db->update('mdm_cards');

	}

	function insert_card_set($card_id, $set_id, $card_data) {
		echo 'on insère in cards_x_sets';
		//var_dump($card_data);

		$data = array(
			'fk_card' => $card_id,
			'fk_set' => $set_id,
			'card_number' => $card_data['card_number'],
			//'card_internal_id' => $card_data['card_internal_id'],
			'rarity' => (isset($card_data['rarity'])) ? $card_data['rarity'] : NULL,
			'variation' => (isset($card_data['variation']) && $card_data['variation'] !== '') ? $card_data['variation'] : NULL,
			'watermark' => (isset($card_data['watermark'])) ? $card_data['watermark'] : NULL,
			'artist' => (isset($card_data['artist'])) ? $card_data['artist'] : NULL,
			'flavor_text' => (isset($card_data['flavor_text'])) ? $card_data['flavor_text'] : NULL,
		);
		$this->db->insert('mdm_cards_x_sets', $data);

	}

	function update_card_set($card_id, $set_id, $card_data) {

		echo 'on update in cards_x_sets';

		$data = array();

		//var_dump($card_data);

		// if($card_data['fk_card'] !== NULL && $card_data['fk_card'] !== '') {
		// 	$data['fk_card'] = $card_data['fk_card'];
		// }
		// if($card_data['fk_set'] !== NULL && $card_data['fk_set'] !== '') {
		// 	$data['fk_set'] = $card_data['fk_set'];
		// }
		if(isset($card_data['card_number']) && $card_data['card_number'] !== NULL && $card_data['card_number'] !== '') {
			$data['card_number'] = $card_data['card_number'];
		}
		if($card_data['card_internal_id'] !== NULL && $card_data['card_internal_id'] !== '') {
			$data['card_internal_id'] = $card_data['card_internal_id'];
		}
		if(isset($card_data['rarity']) && $card_data['rarity'] !== NULL && $card_data['rarity'] !== '') {
			$data['rarity'] = $card_data['rarity'];
		}
		if(isset($card_data['variation']) && $card_data['variation'] !== NULL && $card_data['variation'] !== '') {
			$data['variation'] = $card_data['variation'];
		}
		if(isset($card_data['watermark']) && $card_data['watermark'] !== NULL && $card_data['watermark'] !== '') {
			$data['watermark'] = $card_data['watermark'];
		}
		if(isset($card_data['artist']) && $card_data['artist'] !== NULL && $card_data['artist'] !== '') {
			$data['artist'] = $card_data['artist'];
		}
		if(isset($card_data['flavor_text']) && $card_data['flavor_text'] !== NULL && $card_data['flavor_text'] !== '') {
			$data['flavor_text'] = $card_data['flavor_text'];
		}

		if(!empty($data)) {
			$this->db->where('fk_card', $card_id);
			$this->db->where('fk_set', $set_id);
			$this->db->set($data);
			$this->db->update('mdm_cards_x_sets');
		}

		
	}


}