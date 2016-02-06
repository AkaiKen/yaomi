<?php 

echo 'Terme recherché : "' . $search_term . '" &mdash; ';

if($number > 1) {
	echo $number . ' cartes trouvées';
}
else {
	echo $number . ' carte trouvée';
}

?>
<hr />