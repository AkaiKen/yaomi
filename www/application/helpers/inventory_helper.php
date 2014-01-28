<?php 

function get_filter_string($filter_type, $filter_term) {
	
	$string = '';

	switch($filter_type) {
		case 'rarity': 
			$string = 'AND rarity = ' . $filter_term ;
			break;
		case 'color':
			if(strotoupper($filter_term) === 'N') {
				$string = 'AND color NOT IN (W, U, B, R, G)';
			}
			else {
				$string = 'AND color = ';
			}
			break;
		default:
			break;
	}

	return $string;

}