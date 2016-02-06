<ul class="update-action-menu">
	<?php if($is_admin) : ?>
	<li class="update-action-elt"><a class="" href="<?php echo site_url('update/add_set') ; ?>">Ajouter une extension</a></li>
	<li class="update-action-elt"><a class="" href="<?php echo site_url('update/manual') ; ?>">Ajouter des cartes</a></li>
	<?php endif ; ?>
</ul>