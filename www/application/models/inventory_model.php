<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_model extends CI_Model {

	function get_cards($user, $type = 'search', $term = '') {

		try {

			$group = '';
			$where = '';
			$limit = '';
			$order_by = 'mdm_cards.name ASC';
			switch($type) {
				case 'search':
				default:
					$where = "(mdm_cards.name LIKE '%". $term . "%' OR mdm_cards.name_fr LIKE '%" . $term  . "%')";
					$group = 'card';
					break;
				case 'set':
					$where = "mdm_sets.code = '"  . $term . "'" ;
					$group = 'set'; // @TODO ne pas grouper
					break;
				case 'collection':
					$where = "qty <> 0";
					$group = 'set';
					break;
				case 'random':
					$where = "qty <> 0";
					$order_by = 'RAND()';
					$limit = 1;
					$group = 'card';
			}

			
			$this->db->select('mdm_cards_x_sets.id, mdm_cards.id AS card_id, mdm_cards.name, mdm_cards.name_fr, 
				mdm_sets.name AS set_name, mdm_sets.name_fr AS set_name_fr, mdm_sets.code AS set_code, 
				mdm_cards_x_sets.card_number, mdm_cards.is_landscape, qty, deck_qty')
				->from('mdm_cards')
				->join('mdm_cards_x_sets', 'mdm_cards.id = mdm_cards_x_sets.fk_card','left')
				->join('mdm_sets', 'mdm_sets.id = mdm_cards_x_sets.fk_set', 'left')
				->join('app_user_collection', 'mdm_cards_x_sets.id = app_user_collection.fk_card_instance', 'left')
				->where($where)
				->where('fk_user', $user);

			$this->db->order_by($order_by);

			if($limit !== '') {
				$this->db->limit($limit);
			}

			$query_cards = $this->db->get();

			if($query_cards->num_rows() > 0){

				$result = $query_cards->result();

				if($group !== '') {
					switch($group) {
						// we're grouping by card name (e.g.: search result)
						case 'card':
							$cards = array();
							foreach($result as $card) {
								if(!isset($cards[$card->card_id])) {
									$cards[$card->card_id] = array();
									$cards[$card->card_id]['name'] = $card->name;
									$cards[$card->card_id]['name_fr'] = $card->name_fr;
									$cards[$card->card_id]['instances'] = array();
								}
								$cards[$card->card_id]['instances'][$card->id] = $card;
								$cards[$card->card_id]['instances'][$card->id]->image = $this->get_card_image($card->set_code, $card->card_number);
							}
							break;
						// we're grouping by set (e.g.: collection)
						case 'set':
							$cards = array();
							foreach($result as $card) {
								if(!isset($cards[$card->set_code])) {
									$cards[$card->set_code] = array();
									$cards[$card->set_code]['name'] = $card->set_name;
									$cards[$card->set_code]['name_fr'] = $card->set_name_fr;
									$cards[$card->set_code]['instances'] = array();
								}
								$cards[$card->set_code]['instances'][$card->id] = $card;
								$cards[$card->set_code]['instances'][$card->id]->image = $this->get_card_image($card->set_code, $card->card_number);
							}
							break;
					}
				}
				else {

					return $result;
				}

				return $cards;	
				
			}
			return FALSE;

		}
		catch(Exception $e) {

		}

	}

	// ALIAS //
	function get_set_cards($user,$set_code) {
		return $this->get_cards($user, 'set', $set_code);
	}

	function get_my_cards($user) {
		return $this->get_cards($user, 'collection');
	}

	function get_random_card($user) {
		return $this->get_cards($user, 'random');
	}

	// END ALIAS //


	function update_qty($card_id, $total_qty, $deck_qty, $user_id) {

		$table = 'app_user_collection';
		$where = array('fk_user' => $user_id, 'fk_card_instance' => $card_id);

		$this->db->select('id')
			->from($table)
			->where($where);

		$exec = $this->db->get();

		if($exec->num_rows() > 0) {

			$this->db->where($where);

			if((int)$total_qty === 0 && (int)$deck_qty === 0) {
				// delete the record, don't want to bulk the database
				$this->db->delete($table);
			}
			else {
				// update the record
				$what = array(
					'qty' => $total_qty,
					'deck_qty' => $deck_qty
				);
				$this->db->update($table, $what);
			}			
		}
		else {

			if((int)$total_qty === 0 && (int)$deck_qty === 0) {
				// don't insert this data
			}
			else {
				// insert a new record
				$what = array(
					'fk_user' => $user_id, 
					'fk_card_instance' => $card_id,
					'qty' => $total_qty,
					'deck_qty' => $deck_qty
				);
				$this->db->insert($table, $what);
			}
			
		}

	}

	function get_card_image($set_code, $card_number) {

		// we have to zerofill numbers
		if((int)$card_number < 100) {
			if((int)$card_number < 10) {
				$card_number = '0'.$card_number;
			}
			$card_number = '0'.$card_number;
		}
		$image_name = $card_number . '.jpg';

		$lang = 'english'; // à mettre en variable

		$pictures_location = $this->config->item('pictures_location');
		$image_full_path = $pictures_location . '/'. $lang . '/' . $set_code . '/' . $image_name;

		$is_remote = (strpos($pictures_location, '//')) ? TRUE : FALSE;

		// $image_full_path = ($this->_file_exists($image_full_path, $is_remote)) 
		// 	? $image_full_path 
		// 	: 'assets/img/back.jpg';

		return $image_full_path;
	}

	function get_set_symbol($set_code) {

		$symbols_location = $this->config->item('symbols_location');
		$image_full_path = $symbols_location . $set_code . '.png';

		$is_remote = (strpos($symbols_location, '//')) ? TRUE : FALSE;

		// $image_full_path = ($this->_file_exists($image_full_path, $is_remote)) 
		// 	? $image_full_path 
		// 	: NULL ;

		return $image_full_path;

	}

	function _file_exists($file_full_path, $is_remote) {

		if($is_remote) {
			$headers = get_headers($file_full_path);
			return ($headers[0] === 'HTTP/1.1 200 OK');
		}
		else {
			return (file_exists($file_full_path));
		}
		return FALSE;

	}


}