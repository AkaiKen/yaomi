<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Collection extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('inventory_model','inventory');
        $this->load->model('set_model','set');
        $this->load->model('card_model','card');
    }

	public function index() {
		$this->liste();
	}

	/**
	* Cards are sorted by set
	*
	*/
	public function liste() {

		$intro = array();

		$intro['title']  = 'Collection';
		$intro['open'] = TRUE;
		$intro['classes'] = 'intro';

		$user =  $this->session->userdata('user_id');

		$cards = $this->inventory->get_my_cards($user);

		$cards_view = '';

		if($cards === FALSE) {
			$intro['content'] = "Vous n'avez encore aucune carte."; 
			// mettre lien pour remplir, ou bien une copie du moteur de recherche
		}
		else {

			$collection_intro['cards_owned'] = 0;
			$collection_intro['sets_cards_owned'] = count($cards);
			$collection_intro['existing_cards'] = $this->card->count_existing_cards();

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
					$data['content'] .= $this->layout->load_view('card',$cards);
				}
				$cards_in_set = count($set['instances']);
				$collection_intro['cards_owned'] += $cards_in_set; 

				$card_content['content'] = $this->layout->load_view('card_group',$data);
				$cards_view .= $this->layout->load_view('group_foldable', $card_content);
			}

			$intro['content'] = $this->layout->load_view('collection_intro', $collection_intro); 
			// lister nombres de cartes, nombre de cartes différentes, combien il en manque...

		}


		$intro_view = $this->layout->load_view('group_foldable', $intro);

		$data_output = array();
		$data_output['content'] = $intro_view . $cards_view;

		$this->layout->output_view($data_output);
	
	}

}