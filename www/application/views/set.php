<li class="set sub-block">
	<a class="set-link" href="<?php echo site_url('set/' . $code ) ;?>">
		<?php if(isset($image)) : ?>
		<img class="item-media set-picture" src="<?php echo $image ; ?>" alt="" width="24" height="24" />
		<img class="item-media set-picture" src="<?php echo $image ; ?>" alt="" width="24" height="24" />
		<?php endif ; ?>
		<span class="set-name" title="<?php echo htmlentities($name) ; ?>">
			<?php echo $name ; ?>
		</span>
	</a>
</li>