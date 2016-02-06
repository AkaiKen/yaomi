<div class="login-wrapper">
	<div class="login-form">
		<form action="<?php echo site_url('login') ; ?>" method="post">
			<span class="form-group">
				<label for="login">Identifiant</label>
				<input type="text" id="login" name="login" value="<?php echo set_value('login')?>" />
			</span>
			<span class="form-group">
				<label for="pwd">Mot de passe</label>
				<input type="password" id="pwd" name="pwd" value="" />
			</span>
			<button type="submit" aria-label="<?php echo lang('login.label_button') ; ?>" 
				class="login-submit"><span><?php echo lang('login.label_button') ; ?></span></button>
			<!-- <a href="<?php echo site_url('register') ; ?>" class="button-alt-style register-link">S'inscrire</a> -->
		</form>
	</div>
	<?php if(validation_errors() !== '' || $this->session->userdata('login_errors')) : ?>
	<div class="login-form-errors">
		<?php if($this->session->userdata('login_errors')) echo $this->session->userdata('login_errors') ; ?>
		<?php if(validation_errors() !== '') echo validation_errors(); ?>
	</div>
	<?php endif ; ?>
</div>