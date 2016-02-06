<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Collection extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('inventory_model','inventory');
        $this->load->model('set_model','set');
        $this->load->model('card_model','card');

        $this->load->helper('inventory');
    }

	// public function index() {
	// 	$this->liste();
	// }

	public function _remap($code) {

		if($code === 'index') {
			$this->liste();
		}
		else {
			// code = user name
			$this->liste($code);
		}

	}

	/**
	* Cards are sorted by set
	*
	*/
	public function liste($user_name = FALSE) {

		// pagination
		$this->load->library('pagination');

		// $config['base_url'] = 'http://example.com/index.php/test/page/';
		// $config['total_rows'] = 200;
		// $config['per_page'] = 20;

		// $this->pagination->initialize($config);

		// echo $this->pagination->create_links();

		$intro = array();

		$intro['title']  = 'Collection';
		$intro['open'] = TRUE;
		$intro['classes'] = 'intro';

		if(!$user_name) {
			$user_id = $this->user;
		}
		else {
			$this->load->model('user_model');
			$user_id = $this->user_model->username_to_id($user_name);
		}

		$data_output = array();

		if(!$user_id) {
			set_status_header(404);
			$this->body_class[] = "error-404";
			$data_output['content'] = $this->layout->load_view('404');
		}
		else {

			$cards = $this->inventory->get_my_cards($user_id);

			$cards_view = '';

			if($cards === FALSE) {
				$intro['content'] = "Vous n'avez encore aucune carte."; 
				// mettre lien pour remplir, ou bien une copie du moteur de recherche
			}
			else {

				$collection_intro['cards_owned'] = 0;
				//$collection_intro['sets_cards_owned'] = count($cards);
				//$collection_intro['existing_cards'] = $this->card->count_existing_cards();

				$first = TRUE; // pour ouvrir le premier set par défaut
				foreach($cards as $set) {
					$card_content['title'] = $set['name'];
					$card_content['title'] .= (isset($set['name_fr']) && $set['name_fr'] !== '') ? ' ('.$set['name_fr'].')' : '' ;
					$data['content'] = '' ;
					$data['first'] = $first;
					$card_content['classes'] = 'card-group';
					$first = FALSE;
					foreach($set['instances'] as $cards) {
						$cards->display_name = TRUE;
						$cards->landscape = ((int)$cards->is_landscape === 1);
						if(!$this->is_logged || $user_id !== $this->user) {
							$cards->display_deck_qty = FALSE;
							$cards->read_only = TRUE;
						}
						$data['content'] .= $this->layout->load_view('card',$cards);
					}
					$cards_in_set = count($set['instances']);
					$collection_intro['cards_owned'] += $cards_in_set; 

					$card_content['content'] = $this->layout->load_view('card_group',$data);
					$card_content['foldable'] = TRUE;
					$card_content['open'] = TRUE;
					$cards_view .= $this->layout->load_view('utils/group', $card_content);
				}

			//	$intro['content'] = $this->layout->load_view('collection_intro', $collection_intro); 
				$intro['content'] = $this->layout->load_view('utils/fold_buttons'); 
				$intro['content'] .= $this->layout->load_view('filter/rarities_filter');
				//$intro['content'] .= $this->layout->load_view('filter/colors_filter');
				$intro['foldable'] = TRUE;
				// lister nombres de cartes, nombre de cartes différentes, combien il en manque...

			}

			$intro_view = $this->layout->load_view('utils/group', $intro);

			$data_output = array();
			$data_output['content'] = $intro_view . $cards_view;
			$data_output['page_title'] = 'Collection';
		}

		$this->layout->output_view($data_output);
	
	}



}