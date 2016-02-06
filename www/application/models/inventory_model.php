<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_model extends CI_Model {

	function get_cards($user, $type = 'search', $term = '', $filter_type = '', $filter_term = '', $page = 1) {

		try {

			$group = '';
			$where = '';

			$where_filter = '';
			if($filter_type !== '') {
				$where_filter = get_filter_string($filter_type, $filter_term);
			}

			$limit = 0;
			$order_by = 'mdm_cards.name ASC';

			switch($type) {
				case 'search':
				default:
					$terms = explode(' ', $term);
					$term_count = sizeof($terms);
					$term_string = '';
					$term_string_fr = '' ;
					for($i = 0; $i <= ($term_count - 1); $i++) {

						if(strpos($terms[$i], 'ae') !== FALSE){
							$term_alt = str_ireplace('ae', 'æ', $terms[$i]);
							$term_string .= '(mdm_cards.name LIKE "%' . $terms[$i] . '%" OR mdm_cards.name LIKE "%' . $term_alt . '%")';
							$term_string_fr .= '(mdm_cards.name_fr LIKE "%' . $terms[$i] . '%" OR mdm_cards.name_fr LIKE "%' . $term_alt . '%")';
						}
						elseif(strpos($terms[$i], 'oe') !== FALSE){
							$term_alt = str_ireplace('oe', 'œ', $terms[$i]);
							$term_string .= '(mdm_cards.name LIKE "%' . $terms[$i] . '%" OR mdm_cards.name LIKE "%' . $term_alt . '%")';
							$term_string_fr .= '(mdm_cards.name_fr LIKE "%' . $terms[$i] . '%" OR mdm_cards.name_fr LIKE "%' . $term_alt . '%")';
						}
						else {
							$term_string .= '(mdm_cards.name LIKE "%' . $terms[$i] . '%" )';
							$term_string_fr .= '(mdm_cards.name_fr LIKE "%' . $terms[$i] . '%" )';
						}
						
						if($i < ($term_count - 1)) {
							$term_string .= ' AND ';
							$term_string_fr .= ' AND ';
						}
					}

					$where = '(' . $term_string . ') OR (' . $term_string_fr . ')';
					$group = 'card';
					break;
				case 'set':
					$where = "mdm_sets.code = '"  . $term . "'" ;
					$group = 'set'; // @TODO ne pas grouper
					$order_by = 'CAST(mdm_cards_x_sets.card_number AS INTEGER)';
					break;
				case 'collection':
					$where = "qty <> 0";
					$group = 'set';
					$order_by = "set_code, CAST(mdm_cards_x_sets.card_number AS INTEGER)";
					break;
				case 'random':
					if($user) {
						$where = "qty <> 0";
					}
					$order_by = 'RANDOM()';
					$limit = 1;
					$group = 'card';
			}

			$what = array(
				'mdm_cards_x_sets.id', 
				'mdm_cards.id AS card_id', 
				'mdm_cards.name', 
				'mdm_cards.name_fr', 
				'mdm_sets.name AS set_name', 
				'mdm_sets.name_fr AS set_name_fr', 
				'mdm_sets.code AS set_code', 
				'mdm_cards_x_sets.card_number', 
				'mdm_cards_x_sets.card_internal_id', 
				'mdm_cards.is_landscape', 
				'rarity', 
				'color'
			);

			if($user) {
				$what[] = 'qty';
				$what[] = 'deck_qty';
			}
			
			$this->db->select($what)
				->from('mdm_cards')
				->join('mdm_cards_x_sets', 'mdm_cards.id = mdm_cards_x_sets.fk_card','left')
				->join('mdm_sets', 'mdm_sets.id = mdm_cards_x_sets.fk_set', 'left');

			if($user) {
				$this->db->join('app_user_collection', 
					'mdm_cards_x_sets.id = app_user_collection.fk_card_instance AND fk_user = ' . $user, 'left');
			}

			if($where !== '') {			
				$this->db->where($where, NULL, FALSE);
			}

			$this->db->order_by($order_by);

			if($limit > 0) {
				$this->db->limit($limit);
			}

			$query_cards = $this->db->get();

			// echo $this->db->last_query();
			//die();

			// if($query_cards->num_rows() > 0){
			if(count($query_cards->result()) > 0) {

				$result = $query_cards->result();

				if($group !== '') {
					switch($group) {
						// we're grouping by card name (e.g.: search result)
						case 'card':
							$cards = array();
							foreach($result as $card) {
								
								if(!isset($cards[$card->card_id])) {
									//reajust_card_color($card);
									$cards[$card->card_id] = array();
									$cards[$card->card_id]['name'] = $card->name;
									$cards[$card->card_id]['name_fr'] = $card->name_fr;
									$cards[$card->card_id]['instances'] = array();
								}
								$cards[$card->card_id]['instances'][$card->id] = $card;
								$image = $this->get_card_image($card->set_code, $card->card_internal_id);


								if(!_file_exists($image)) {
									$image = $this->get_card_image_legacy($card->set_code, $card->card_number);
								}
								$cards[$card->card_id]['instances'][$card->id]->image = $image;
							// var_dump($card);
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

								reajust_card_color($card);

								$cards[$card->set_code]['instances'][$card->id] = $card;
								$image = $this->get_card_image($card->set_code, $card->card_internal_id);
								if(!_file_exists($image)) {
									$image = $this->get_card_image_legacy($card->set_code, $card->card_number);
								}
								$cards[$card->set_code]['instances'][$card->id]->image = $image;

							//	var_dump($card);
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
	
	function get_set_cards($user, $set_code) {
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

		if(count($exec->result()) > 0) {

			$this->db->where($where);

			if((int)$total_qty === 0 && (int)$deck_qty === 0) {
				// delete the record, don't want to bulk the database
				$this->db->delete($table);
			}
			else {
				// update the record
				$what = array(
					'qty' => $total_qty
				);
				if($deck_qty !== NULL) {
					$what['deck_qty'] = $deck_qty ;
				}
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

	function get_card_image($set_code, $card_internal_id) {

		$image_name = $card_internal_id . '.jpg';

		$lang = 'english'; // à mettre en variable

		$pictures_location = $this->config->item('pictures_location');
		$image_full_path = $pictures_location . '/'. $lang . '/' . $set_code . '/' . $image_name;

		return $image_full_path;

	}

	function get_card_image_legacy($set_code, $card_number) {

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

		return $image_full_path;
	}




}
