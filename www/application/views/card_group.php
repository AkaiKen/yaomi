<form action="<?php echo site_url('card/update') ; ?>" method="post" class="" 
	data-success="Votre collection a bien été mise à jour"
	data-error="Il y a eu une erreur..."
>
	<div class="cards">
		<?php echo $content ; ?>
	</div>
	<div class="submit">
		<input class="submit-input" type="submit" value="Envoyer" />
	</div>
</form>
