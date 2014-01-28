<?php if(empty($cards)) : ?>
<p>Aucune carte à traduire.</p>
<?php else : ?>
<form action="<?php echo site_url('settings/translate_cards') ; ?>" method="post" class="classic-form">
<?php foreach($cards as $card) : ?>
	<div class="form-group">
		<label for="card[<?php echo $card->id ; ?>]" ><?php echo $card->name ;  ?></label>
		<input type="text" name="card[<?php echo $card->id ; ?>]" id="card[<?php echo $card->id ; ?>]" />
		<input type="checkbox" name="card-nt[<?php echo $card->id ; ?>]" id="card-nt[<?php echo $card->id ; ?>]" value="1" />
		<label for="card-nt[<?php echo $card->id ; ?>]" >N'existe pas en français</label>
		<input type="submit" value="Traduire" />
		<?php echo form_error('card-' . $card->id) ?>
	</div>
<?php endforeach; ?>
</form>
<?php endif ; ?>
