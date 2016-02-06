<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('inventory_model','inventory');
    }

	public function index() {

		// $user = NULL;
		// if($this->is_logged) {
		// 	$user = $this->session->userdata('user_id');
		// }

		$data_home = array();
		$data_home['search_form'] = $this->load->view('search_form', array('id' => 'home'), TRUE);

		$data_home['random_card'] = "";

		$random_card = $this->inventory->get_random_card($this->user);
		if($random_card){
			$card = current($random_card); // we want the (unique) card
			$card_instance = current($card['instances']); // we want the (unique too) instance
			$card_instance->display_set_name = TRUE;
			$card_instance->display_name = TRUE;
			$card_instance->display_qty = FALSE;
			$data_home['random_card'] = $this->load->view('card',$card_instance,TRUE);
		}

		//$home_view = $this->load->view('home', $data_home, TRUE);
		//$this->load->view('layout', array('content' => $home_view));

		$data_output = array();
		$data_output['content'] = $this->load->view('home', $data_home, TRUE);
		$data_output['page_title'] = 'Accueil';
		$this->layout->output_view($data_output);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */