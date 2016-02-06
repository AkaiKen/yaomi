<form action="<?php echo site_url('register') ; ?>" method="post" class="classic-form">
	<div class="form-group">
		<label for="login"><?php echo lang('register.login') ; ?></label>
		<input type="text" id="login" name="login" value="<?php echo set_value('login')?>" required />
		<?php echo form_error('login') ?>
	</div>
	<div class="form-group">
		<label for="pwd"><?php echo lang('register.password') ; ?></label>
		<input type="password" id="pwd" name="pwd" value="" required />
		<?php echo form_error('pwd') ?>
	</div>
	<div class="form-group">
		<label for="pwd_confirm"><?php echo lang('register.password_confirmation') ; ?></label>
		<input type="password" id="pwd_confirm" name="pwd_confirm" value="" required />
		<?php echo form_error('pwd_confirm') ?>
	</div>
	<div class="form-group">
		<label for="email"><?php echo lang('register.email') ; ?></label>
		<input type="text" id="email" name="email" value="<?php echo set_value('email')?>" required />
		<?php echo form_error('email') ?>
	</div>
	<div class="form-group">
		<label for="name"><?php echo lang('register.name') ; ?></label>
		<input type="text" id="name" name="name" value="<?php echo set_value('name')?>" />
		<?php echo form_error('name') ?>
	</div>
	<div class="form-group">
		<button type="submit" aria-label="<?php echo lang('register.label_button') ; ?>" 
			class="settings-submit"><span><?php echo lang('register.label_button') ; ?></span></button>
	</div>
</form>