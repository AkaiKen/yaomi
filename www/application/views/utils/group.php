<?php 
	$block_classes = ' ';
	if(isset($classes)) {
		$block_classes .= $classes;
	}
	if(isset($foldable)) {
		$block_classes .= ' foldable';
		if(!isset($open) || $open !== FALSE) {
			$block_classes .= ' open';
		}
	}
?>
<div class="<?php echo $block_classes ; ?> block">
	<div class="group-name block-title"><?php echo $title ; ?></div>
	<?php if(isset($content) && $content !== '') : ?>
	<div class="group-content block-content">
		<?php echo $content ; ?>
	</div>
	<?php endif ; ?>
</div>