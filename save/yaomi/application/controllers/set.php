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
		foreach($sets_list as $set) {
			$set->image = $this->inventory->get_set_symbol($set->code);
			$data['content'] .= $this->load->view('set',$set,TRUE);
		}

		$sets_list_view = $this->load->view('sets_list', $data, TRUE);
	
		$this->load->view('layout', array('content' => $sets_list_view));
	}


	public function _cards_set($set_code) {

		$intro['title']  = 'Set';
		$intro['open'] = TRUE;
		$intro['classes'] = 'intro';

		$user =  $this->session->userdata('user_id');

		$set_cards_list = $this->inventory->get_set_cards($user, $set_code);

		//var_dump($set_cards_list);

		$cards_view = '';
		if($set_cards_list === FALSE) {
			$intro['content'] = 'pas de résultats';
		}
		else {
			$intro['content'] = ''; // prez rapide de l'extension
			$cards_view = '';
			$data = '';
			foreach($set_cards_list as $set) {	
				$intro['title'] = $set['name'];
				$intro['title'] .= (isset($set['name_fr']) && $set['name_fr'] !== '') ? ' ('.$set['name_fr'].')' : '' ;
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
			$cards_view = $this->layout->load_view('group', $card_content);

		}

		if($intro['content'] !== ''){
			$intro_view = $this->layout->load_view('group_foldable', $intro);
		}
		else {
			$intro_view = $this->layout->load_view('group', $intro);
		}

		$data_output['content'] = $intro_view . $cards_view;
		
		$this->layout->output_view($data_output);

	}



}