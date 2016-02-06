<section 
	class="item card simple"
	data-rarity='<?php echo $rarity; ?>'
	data-color='<?php echo $color; ?>'
>
<span class="colors">
<?php 
$nb_colors = strlen($color);
if($nb_colors > 1) :
	for($i = 0; $i < $nb_colors; $i++) : 
		if(in_array($color[$i], get_classic_colors(TRUE))) :
?>
	<img src="assets/img/<?php echo $color[$i] ?>.png" />	
	<?php 
		endif;
	endfor;
else : ?>
	<img src="assets/img/<?php echo $color ; ?>.png" />
<?php
endif;
?>
</span>
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
	<div class="form-group">
		<label class="qty-label" for="<?php echo $id ; ?>[total]">Quantité possédée</label>
		<div class="quick-input custom-radio-group">
			<?php for($i = 0; $i <= 4 ; $i++) : ?>
				<input 
					class="qty qty-quick-total"
					type="radio" 
					id="<?php echo $id ; ?>[<?php echo $i ; ?>]" 
					value="<?php echo $i ; ?>" 
					name="<?php echo $id ; ?>[quick]" 
					<?php if(isset($qty) && (int)$qty === (int)$i) : ?>checked="checked"<?php endif; ?>
				/>
				<label for="<?php echo $id ; ?>[<?php echo $i ; ?>]"><?php echo $i ; ?></label>
			<?php endfor; ?>
		</div>
		<div class="qty-number">
			<input 
				class="qty qty-total" 
				type="number" 
				value="<?php echo (isset($qty)) ? $qty : 0 ; ?>" 
				id="<?php echo $id ; ?>[total]" 
				name="<?php echo $id ; ?>[total]"
				data-qty="total"
			/>
		</div>
	</div>
</section>