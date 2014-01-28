<div class="set sub-block">
	<a class="set-link" href="<?php echo site_url('set/' . $set_code ) ;?>">
		<?php if(isset($image)) : ?>
		<span><img class="item-media set-picture" src="<?php echo $image ; ?>" alt="" width="24" height="24" /></span>
		<?php endif ; ?>
		<span class="set-name" title="<?php echo htmlentities($set_name) ; ?>">
			<?php echo $set_name ; ?>
		</span>
		<?php if(isset($image)) : ?>
		<span><img class="item-media set-picture" src="<?php echo $image ; ?>" alt="" width="24" height="24" /></span>
		<?php endif ; ?>
	</a>
</div>