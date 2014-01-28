<?php 
	$term_id = (isset($id)) ? 'term_' . $id : 'term';
?>

<form method="get" action="<?php echo site_url('card/search'); ?>">
  <label for="<?php echo $term_id ; ?>" class="search-label">Rechercher : </label>
  <input name="<?php echo $term_id ; ?>" id="<?php echo $term_id ; ?>" type="text" placeholder="" />
</form>