<div class="home-wrapper">
	<?php if(!$this->is_logged) : ?>
	<div class="block">
		<div class="block-title">Yaomi bonjour !</div>
		<div class="block-content">
			<p>Yet An Otter Magic Inventory (yaomi) est un site web de gestion d'inventaire de cartes Magic.</p>
			<p>Il vous permet de gérer votre collection de cartes en listant les cartes existantes et les extensions 
				dans lesquelles elles apparaissent, ainsi que le nombre que vous possédez de chacune.</p>
		</div>
	</div>
	<?php endif ?>
	<div class="block">
		<div class="block-title">Un extrait au hasard ?</div>
		<div class="block-content">
			<?php echo $random_card ; ?>
		</div>
	</div>
	<div class="block">
		<div class="block-title">Extensions</div>
		<div class="block-content">
			<ul>
				<li class="all"><a href="<?php echo site_url('set')?>">Liste des extensions</a></li>
			</ul>
		</div>
	</div>
	<?php if($this->is_logged) : ?>
	<div class="block">
		<div class="block-title">Collection</div>
		<div class="block-content">
			<ul>
				<li><a href="<?php echo site_url('collection')?>">Ma collection</a></li>
				<li><a title="Bientôt">Cartes dans un deck</a></li>
				<li><a title="Bientôt">Cartes à l'échange</a></li>
				<li><a title="Bientôt">Cartes recherchées</a></li>
			</ul>
			
		</div>
	</div>
	<?php endif ; ?>
</div>