<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Card extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('inventory_model','inventory');
        $this->load->model('card_model','card');

        $this->load->helper('inventory');
    }

    public function _remap($query, $params = array()) {


    		switch($query) {
    			case 'index':
    				$this->index();
    				break;
    			case 'search':
    				$args = isset($params[0]) ? $params[0] : '';
    				$this->search($args);
    				break;
    			case 'update':
    				$this->update();
    				break;
    			default:
					$this->_card_details($query);
					break;
    		}		

	}

	public function index() {
		// what to do?
	}

	public function search($search_term = '') {

		if(trim($search_term) === '' ) {
			if($this->input->get('term',TRUE)) {
				$search_term = urldecode($this->input->get('term', TRUE));
			}
			else {
				redirect('/');
			}
			// trouver mieux !
		}
		else {
			$search_term = urldecode($search_term);
		}

		if(strlen($search_term) < 3) {
			$no_search = 1;
		}

		$intro['title']  = 'Résultats de la recherche';
		$intro['open'] = TRUE;
		$intro['classes'] = 'intro';

		$search_intro['search_term'] = $search_term;
		
		$cards_view = '';
		if(isset($no_search) && $no_search == 1) {
			$intro['content']  = 'Terme trop vague';
			$cards_view = '';
		}
		else {
			
			$user =  $this->session->userdata('user_id');

			$cards = $this->inventory->get_cards($user,'search', $search_term);
			if($cards === FALSE) {
				$intro['content'] = 'pas de résultats';
			}
			else {
				$cards_view = '';
				$first = TRUE; // pour ouvrir la première carte par défaut
				$search_intro['number'] = count($cards);
				foreach($cards as $card) {
					$card_content['title'] = $card['name'];
					$card_content['title'] .= (isset($card['name_fr']) && $card['name_fr'] !== '') ? ' ('.$card['name_fr'].')' : '' ;
					$card_content['foldable'] = TRUE;
					$card_content['open'] = $first;
					$first = FALSE;
					$card_content['classes'] = 'card-group';
					$data['content'] = '' ;
					foreach($card['instances'] as $card_instance) {
						$card_instance->display_set_name = TRUE;
						if((int)$card_instance->is_landscape === 1){
							$card_instance->landscape = TRUE;
						}
						else {
							$card_instance->landscape = FALSE;
						}
						$data['content'] .= $this->layout->load_view('card',$card_instance);
					}
					$card_content['content'] = $this->layout->load_view('card_group',$data);
					$cards_view .= $this->layout->load_view('utils/group', $card_content);
				}

				$intro['content'] = $this->layout->load_view('search_intro', $search_intro);
				$intro['content'] .= $this->layout->load_view('utils/fold_buttons');
				$intro['content'] .= $this->layout->load_view('filter/rarities_filter');
				$intro['content'] .= $this->layout->load_view('filter/colors_filter');
			}
		}

		$intro_view = $this->layout->load_view('utils/group_foldable', $intro);

		$data_output = array();
		$data_output['content'] = $intro_view . $cards_view;
		$data_output['page_title'] = 'Résultats de recherche';

		$this->layout->output_view($data_output);

	}

	public function _card_details($query) {

		$data_output = array();
		$data_output['content'] = $query;
		$data_output['page_title'] = 'Résultats de recherche';

		$this->layout->output_view($data_output);

	}

	public function update() {

		$is_ajax_request = $this->input->is_ajax_request();

		// form method is set to POST because of the amount of transmitted data
		if(!$is_ajax_request) {
			$cards = $this->input->post(NULL, TRUE);
		}
		//... but if js is activated, form is hijacked and data are send in GET (one http request less than POST)
		else {
			$cards = $this->input->get(NULL, TRUE);
		}

		foreach($cards AS $card_id => $qty){
			$total_qty = $qty['total'];
			if(isset($qty['quick']) && ($qty['total'] === '' || $qty['total'] <= 4)) {
				$total_qty = $qty['quick'];
			}
			$deck_qty = NULL;
			if(isset($qty['deck'])){
				$deck_qty = $qty['deck'];
			}
			$user_id = $this->session->userdata('user_id');
			$this->inventory->update_qty($card_id, $total_qty, $deck_qty, $user_id);
		}

		if(!$is_ajax_request) {
			$referrer = $this->session->userdata('prev_url') ;
			redirect($referrer);
			exit;			
		}
		else {
			return;
		}

	}


}
