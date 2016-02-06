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