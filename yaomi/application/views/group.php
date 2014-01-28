<div class="<?php if(isset($classes)) echo $classes ; ?> block">
	<div class="group-name block-title"><?php echo $title ; ?></div>
	<?php if(isset($content) && $content !== '') : ?>
	<div class="group-content block-content">
		<?php echo $content ; ?>
	</div>
	<?php endif ; ?>
</div>