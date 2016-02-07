<?php 

function get_classic_colors($with_colorless = FALSE) {
	$colors = array('W', 'U', 'B', 'R', 'G');
	if($with_colorless) {
		$colors[] = 'C';
	}
	return $colors;
}

function get_filter_string($filter_type, $filter_term) {
	
	$string = '';

	switch($filter_type) {
		case 'rarity': 
			$string = 'AND rarity = ' . $filter_term ;
			break;
		case 'color':
			if(strotoupper($filter_term) === 'N') {
				//$string = 'AND color NOT IN (W, U, B, R, G)';
				$string = 'AND color NOT IN (' . implode(',', get_classic_colors()) . ')';
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

function reajust_card_color($card) {

	$classic_colors = get_classic_colors(TRUE);

	// if the card is a classic, colorless artifact, we add 'C' for colorless
	if($card->color === 'A') {
		$card->color .= 'C';
	}

	// if the card is a (classic) land (Dryad Arbor, you're not concerned), we add 'C' for colorless
	if($card->color === 'L') {
		$card->color .= 'C';
	}

	// if the card colors don't contain WUBRG, nor C, we add 'O' for others (planes, schemes, raccoons...)	
	$found = 0;
	foreach($classic_colors as $color) {
		if(strpos($card->color, $color) !== FALSE){
			$found = 1;
			break 1;
		}
	}
	if($found === 0) {
		$card->color .= 'O';
	}
	

}

function _file_exists($file_full_path) {

	// does the path contains a url host?
	$is_remote = (!empty(parse_url($file_full_path, PHP_URL_HOST)));

	if($is_remote) {
		$headers = get_headers($file_full_path);
		return ($headers[0] === 'HTTP/1.1 200 OK');
	}
	else {
		return (file_exists($file_full_path));
	}
	return FALSE;

}
