<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update extends MY_Controller {

	function index() {
		echo 'coucou';
	}

	function manual() {

		//phpinfo();

		var_dump($_FILES);

		ini_set('display_errors', 1);

	    ini_set('upload_max_filesize', 20000000000);                               
	    ini_set('post_max_size', 200000000000);                               
	    ini_set('max_input_time', 3000);                                
	    ini_set('max_execution_time', 3000);
	    ini_set("memory_limit", 2000000000000);

		$config['upload_path'] = './tmp/';
		$config['allowed_types'] = '*';
		//$config['max_size'] = '0';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('update_csv')) {
			$data = array('error' => $this->upload->display_errors());
		}
		else {
			$upload_data = $this->upload->data();
			$data = array('upload_data' => $upload_data);
			$this->_insert_new($upload_data);
		}

		$form = $this->load->view('update_form', $data, TRUE);

    	$this->load->view('layout', array('content' => $form));

	}

	function _insert_new($upload_data) {

		$this->load->model('card_model');
		$this->load->model('set_model');
		$this->load->model('update_model');

		$full_path = $upload_data['full_path'];

		$fp = fopen($full_path, "r");

		if($fp) {

			while(!feof($fp)) {
				$line = fgets($fp); 
				if($line) {

					echo '<hr />';
					//echo $line;

					var_dump($line);

					$xpld = explode('|',$line);

					$new_card['name'] = trim($xpld[0]);
					$new_card['set_name'] = $xpld[1];
					$new_card['set_code'] = $xpld[2];
					$new_card['set_id'] = $this->set_model->get_set($new_card['set_code'], 'code', 'id') ; // calculated
					$new_card['card_type'] = $xpld[3];
					$new_card['power'] = $xpld[4];
					$new_card['toughness'] = $xpld[5];
					$new_card['loyalty'] = $xpld[6];
					$new_card['mana_cost'] = $xpld[7];
					$new_card['converted_mana_cost'] = $xpld[8];
					$new_card['artist'] = $xpld[9];
					$new_card['flavor_text'] = $xpld[10];
					$new_card['color'] = $xpld[11];
					$new_card['generated_mana'] = $xpld[12];
					$new_card['card_number'] = $xpld[13];
					$new_card['rarity'] = $xpld[14];
					$new_card['rulings'] = $xpld[15];
					$new_card['variation'] = $xpld[16];
					$new_card['ability'] = $xpld[17];
					$new_card['watermark'] = $xpld[18];
					$new_card['name_fr'] = $xpld[19];

					// 1) la carte proprement dite

					// on vérifie si on n'a pas déjà une ligne avec le même nom
					$card_id = $this->card_model->get_card($new_card['name'], 'name', 'id');

					var_dump($card_id);

					// si on a déjà une...
					if($card_id !== FALSE){
						// on vérifie s'il existe une variation dans l'extension donnée
		 				$variation = $this->card_model->get_card_variation($card_id, $new_card['set_code']);
						// si elle existe...
						var_dump($variation);

						if($variation !== FALSE) {
							// ...si elle est différente, on insère...
							if($variation !== $new_card['variation']) {
								$card_id = $this->update_model->insert_card($new_card); 
								// /!\ ici card_id change ! cas particulier des variations, qui sont deux exemplaires
								// d'une même carte avec des illustrations différentes
							}
							else {
								// ... sinon on update
								$this->update_model->update_card($card_id, $new_card);
							}
						}
						else {
							// ... sinon on vérifie capacité, rulings, et nom français
							$ability = $this->card_model->get_card($card_id, 'id', 'ability');
							$rulings = $this->card_model->get_card($card_id, 'id', 'rulings');
							$name_fr = $this->card_model->get_card($card_id, 'id', 'name_fr');

							if($ability !== $new_card['ability'] 
								|| $rulings !== $new_card['rulings'] 
								|| ($new_card['name_fr'] !== '' 
									&& $new_card['name_fr'] !== NULL 
									&& $name_fr !== $new_card['name_fr'])) {

								// si on a des différences, on update...
								$this->update_model->update_card($card_id, $new_card);
							}
							else {
								var_dump('on ne fait rien');
							}
							// ...sinon on ne fait rien
						}
					}
					else {
						// ... sinon, on insère
						$card_id = $this->update_model->insert_card($new_card);
					}

					// 2) la carte dans l'extension donnée

					if($new_card['set_id'] === FALSE) {
						return FALSE;
					}

					$sets = $this->card_model->get_card_sets($card_id);
					if($sets !== FALSE) {
						if(in_array($new_card['set_id'],$sets)) {
							// on update la ligne card x set
							
							$this->update_model->update_card_set($card_id, $new_card['set_id'], $new_card);
						}
						else {
							// on insère dans card x set
							$this->update_model->insert_card_set($card_id, $new_card['set_id'], $new_card);
						}
					}
					else {
						// on insère dans card x set
						$this->update_model->insert_card_set($card_id, $new_card['set_id'], $new_card);
					}
				
				}

			} // end while

			fclose($fp); // on ferme le fichier

			unlink($full_path); // et on supprime le fichier

		}

	}

}