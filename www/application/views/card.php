<section 
	class="item card sub-block <?php if(isset($landscape) && $landscape === TRUE) { echo 'double' ; } ?>"
	data-rarity='<?php echo $rarity; ?>'
	data-color='<?php echo $color; ?>'
>
	<?php if(isset($display_name) && $display_name === TRUE) : ?>
	<div class="card-name">
		<span class="original-name name" lang="en" title="<?php echo (isset($name) && $name !== '') ? $name : '' ;?>">
		<?php echo (isset($name) && $name !== '') ? $name : '' ;?>
		</span>
		<?php $displayed_name_fr = (isset($name_fr) && $name_fr !== '') ? $name_fr : FALSE; ?>
		<span 
			class="localized-name name <?php if(!$displayed_name_fr) echo 'empty' ; ?>" 
			lang="fr" 
			title="<?php echo ($displayed_name_fr) ? $name_fr : '' ;?>">
			<?php echo ($displayed_name_fr) ? $name_fr : '&nbsp;' ; ?>
		</span>
	</div>
	<?php endif ; ?>
	<?php if(isset($display_name) && $display_name === TRUE && isset($display_set_name) && $display_set_name === TRUE) : ?>
	<hr />
	<?php endif ; ?>
	<?php if(isset($display_set_name) && $display_set_name === TRUE) : ?>
	<div class="set-name">
		<span class="original-name name" lang="en" 
			title="<?php echo (isset($set_name) && $set_name !== '') ? htmlentities($set_name) : '' ;?>">
		<?php echo (isset($set_name) && $set_name !== '') ? $set_name : '' ;?>
		</span>
		<span 
			class="localized-name name" lang="fr" 
			title="<?php echo (isset($set_name_fr) && $set_name_fr !== '') ? htmlentities($set_name_fr) : '' ;?>">
			<?php 
			if(isset($set_name_fr) && $set_name_fr !== '') {
				echo $set_name_fr ; 
			} 
			else {
				echo '&nbsp;';
			} 
			?>
		</span>
	</div>
	<?php endif ; ?>
	<?php 
		$sizes = (!isset($landscape) || $landscape === FALSE) ? 'height="285" width="199"' : 'height="199" width="285"';
	?>
	<img class="item-media card-picture" src="<?php echo $image ; ?>" alt="" <?php echo $sizes ; ?> />
	
	<?php if(!isset($display_qty) || $display_qty !== FALSE) : ?>
	<div class="form-group">
		<label class="qty-label" for="<?php echo $id ; ?>[total]">Quantité possédée</label>
		<div class="qty-number">
			<?php if(isset($read_only) && $read_only) : ?>
			<span class="qty qty-total"><?php echo (isset($qty)) ? $qty : 0 ; ?></span>
			<?php else : ?>
			<input 
				class="qty qty-total" 
				type="number" 
				value="<?php echo (isset($qty)) ? $qty : 0 ; ?>" 
				id="<?php echo $id ; ?>[total]" 
				name="<?php echo $id ; ?>[total]"
				data-qty="total"
			/>
		<?php endif; ?>
		</div>
	</div>
	<?php if(!isset($display_deck_qty) || $display_deck_qty !== FALSE) : ?>
	<div class="form-group">
		<label class="qty-label " for="<?php echo $id ; ?>[deck]">dont dans un deck :</label>
		<div class="qty-number">
			<input 
				class="qty qty-deck" 
				type="number" 
				value="<?php echo (isset($deck_qty)) ? $deck_qty : 0 ; ?>" 
				id="<?php echo $id ; ?>[deck]" 
				name="<?php echo $id ; ?>[deck]" 
				data-qty="deck"
			/>
		</div>
	</div>
	<?php endif ; ?>
	<?php endif ; ?>
	<div class="card-number"><?php echo sprintf(lang('specific.card_number'), $card_number) ; ?></div>
</section>