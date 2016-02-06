<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Set extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('inventory_model','inventory');
        $this->load->model('set_model','set');

        $this->load->helper('inventory');

    }

    public function _remap($code, $sort) {

		if($code === 'index' || $code === "liste") {
			$this->liste();
		}
		else {
			$this->_cards_set($code, $sort);
		}

	}

	public function index() {

		$this->liste();

	}

	public function liste() {

		$sets_list = $this->set->get_all_sets();



		$data = array();
		$data['content'] = '' ;

		$sets_view = '';

		foreach($sets_list as $block) {
			$set_content = array();
			$set_content['title'] = $block['name'];
			$set_content['title'] .= (isset($block['name_fr']) && $block['name_fr'] !== '') ? ' ('.$block['name_fr'].')' : '' ;
			$data['content'] = '' ;
			$set_content['classes'] = 'set-group';
			foreach($block['sets'] as $set) {
				$data['content'] .= $this->layout->load_view('set',$set);
			}
			
			$set_content['foldable'] = TRUE;
			$set_content['open'] = TRUE;
			$set_content['content'] = $data['content'];
			$sets_view .= $this->layout->load_view('utils/group', $set_content);
		}

		//die();

		$data_output = array();
		$data_output['content'] = $sets_view;
		$data_output['page_title'] = 'Liste des extensions';
		$this->layout->output_view($data_output);

	}


	public function _cards_set($set_code, $sort = 'num', $quick_input = FALSE) {

		//$quick_input = TRUE;

		//$user =  $this->session->userdata('user_id');

		$set_info = $this->set->get_set($set_code, 'code');

		$intro['title']  = $set_info->name;
		$intro['title'] .= (isset($set_info->name_fr) && $set_info->name_fr !== '') ? ' (' . $set_info->name_fr . ')' : '' ;
		$intro['open'] = TRUE;
		$intro['classes'] = 'intro';

		$set_cards_list = $this->inventory->get_set_cards($this->user, $set_code, $sort);

		$cards_view = '';
		if($set_cards_list === FALSE) {
			$intro['content'] = 'Pas de résultats';
		}
		else {
			$intro['content'] = ''; // prez rapide de l'extension
			$intro['content'] .= $this->layout->load_view('filter/rarities_filter');
			$intro['content'] .= $this->layout->load_view('filter/colors_filter');
			$cards_view = '';
			$data = '';
			foreach($set_cards_list as $set_cards) {	
				$data['content'] = '' ;
				$card_content['classes'] = 'card-group';
				$card_content['title'] = 'Cartes';
				foreach($set_cards['instances'] as $card) {
					if(!$quick_input) {
						$card->display_name = TRUE;
						if(!$this->is_logged) {
							$card->display_deck_qty = FALSE;
							$card->display_qty = FALSE;
						}
						$card->landscape = ((int)$card->is_landscape === 1);
						$data['content'] .= $this->layout->load_view('card', $card);
					}
					else {
						$data['content'] .= $this->layout->load_view('card/card_simple',$card);
					}
					
				}
			}
			$card_content['content'] = $this->layout->load_view('card_group',$data);
			$cards_view = $this->layout->load_view('utils/group', $card_content);

		}

		if($intro['content'] !== ''){
			$intro['foldable'] = TRUE;
			$intro_view = $this->layout->load_view('utils/group', $intro);
		}
		else {
			$intro_view = $this->layout->load_view('utils/group', $intro);
		}

		$data_output = array();
		$data_output['content'] = $intro_view . $cards_view;
		$data_output['page_title'] = $intro['title'] . ' &mdash; Liste des cartes';
		
		$this->layout->output_view($data_output);

	}



}
