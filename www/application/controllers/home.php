<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('inventory_model','inventory');
    }

	public function index() {

		$user = $this->session->userdata('user_id');

		$data_home = array();
		$data_home['search_form'] = $this->load->view('search_form', '', TRUE);

		$data_home['random_card'] = "";

		$random_card = $this->inventory->get_random_card($user);
		if($random_card){
			$card = array_shift($random_card); // we want the (unique) card
			$card_instance = array_shift($card['instances']); // we want the (unique too) instance
			$card_instance->display_set_name = TRUE;
			$card_instance->display_name = TRUE;
			$card_instance->display_qty = FALSE;
			$data_home['random_card'] = $this->load->view('card',$card_instance,TRUE);
		}

		$home_view = $this->load->view('home', $data_home, TRUE);
		$this->load->view('layout', array('content' => $home_view));
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */