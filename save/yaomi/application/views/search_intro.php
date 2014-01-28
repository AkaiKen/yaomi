<?php 

echo 'Terme recherché : "' . $search_term . '"';
echo '<hr />';
if($number > 1) {
echo $number . ' cartes trouvées';
	
}
else {
	echo $number . ' carte trouvée';

}
?>
<hr />
<button role="button" type="button" class="toggle" id="fold-card-groups">Enrouler tout</button>
<button role="button" type="button" class="toggle" id="unfold-card-groups">Dérouler tout</button>
