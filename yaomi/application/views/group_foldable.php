<details class="<?php if(isset($classes)) echo $classes ; ?> block foldable" 
	<?php if(!isset($open) || $open !== FALSE) echo 'open' ; ?> 
>
	<summary class="group-name block-title"><?php echo $title  ; ?></summary>
	<?php if(isset($content) && $content !== '') : ?>
	<div class="group-content block-content ">
		<?php echo $content ; ?>
	</div>
	<?php endif ; ?>
</details>