<form action="<?php echo site_url('install') ; ?>" method="post">
	<div class="form-group">
		<label for="login"><?php echo lang('install.login') ; ?></label>
		<input type="text" id="login" name="login" value="<?php echo set_value('login')?>" />
		<?php echo form_error('login') ?>
	</div>
	<div class="form-group">
		<label for="pwd"><?php echo lang('install.password') ; ?></label>
		<input type="password" id="pwd" name="pwd" value="" />
		<?php echo form_error('pwd') ?>
	</div>
	<div class="form-group">
		<label for="pwd_confirm"><?php echo lang('install.password_confirmation') ; ?></label>
		<input type="password" id="pwd_confirm" name="pwd_confirm" value="" />
		<?php echo form_error('pwd_confirm') ?>
	</div>
	<button type="submit" aria-label="<?php echo lang('install.label_button') ; ?>" 
		class="install-submit"><span><?php echo lang('install.label_button') ; ?></span></button>
</form>