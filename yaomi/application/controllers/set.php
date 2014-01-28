<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Set extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('inventory_model','inventory');
        $this->load->model('set_model','set');
    }

    public function _remap($code) {

		if($code === 'index' || $code === "liste") {
			$this->liste();
		}
		else {
			$this->_cards_set($code);
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
			//$data['first'] = $first;
			$set_content['classes'] = 'set-group';
			//$first = FALSE;
			foreach($block['sets'] as $set) {
				//var_dump($set);
				$data['content'] .= $this->layout->load_view('set',$set);
			}
			//$cards_in_set = count($set['instances']);
			//$collection_intro['cards_owned'] += $cards_in_set; 
			

			//var_dump($data);
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

		// var_dump($sets_list);
		// die();

		// $data = array();
		// $data['content'] = '' ;
		// foreach($sets_list as $set) {
		// 	//$set->image = $this->set->get_set_symbol($set->code);
		// 	$data['content'] .= $this->load->view('set',$set,TRUE);
		// }
	
		// $data_output = array();
		// $data_output['content'] = $this->load->view('sets_list', $data, TRUE);
		// $data_output['page_title'] = 'Liste des extensions';
		// $this->layout->output_view($data_output);
	}


	public function _cards_set($set_code) {

		$user =  $this->session->userdata('user_id');

		$set_info = $this->set->get_set($set_code, 'code');

		$intro['title']  = $set_info->name;
		$intro['title'] .= (isset($set_info->name_fr) && $set_info->name_fr !== '') ? ' (' . $set_info->name_fr . ')' : '' ;
		$intro['open'] = TRUE;
		$intro['classes'] = 'intro';

		$set_cards_list = $this->inventory->get_set_cards($user, $set_code);

		$cards_view = '';
		if($set_cards_list === FALSE) {
			$intro['content'] = 'Pas de résultats';
		}
		else {
			$intro['content'] = ''; // prez rapide de l'extension
			$cards_view = '';
			$data = '';
			foreach($set_cards_list as $set) {	
				$data['content'] = '' ;
				$card_content['classes'] = 'card-group';
				$card_content['title'] = 'Cartes';
				foreach($set['instances'] as $card) {
					$card->display_name = TRUE;
					$card->landscape = ((int)$card->is_landscape === 1);
					$data['content'] .= $this->layout->load_view('card', $card);
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