<ul class="settings-action-menu">
	<?php if($is_admin) : ?>
	<li class="settings-action-elt"><a class="" href="<?php echo site_url('settings/create_user') ; ?>">Créer un utilisateur</a></li>
	<li class="settings-action-elt"><a class="" href="<?php echo site_url('settings/validate_users') ; ?>">Valider des utilisateurs</a></li>
	<li class="settings-action-elt"><a class="" href="<?php echo site_url('settings/translate_cards') ; ?>">Traduire des cartes</a></li>
	<?php endif ; ?>
</ul>