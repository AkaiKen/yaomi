<form action="<?php echo site_url('settings/create_user') ; ?>" method="post">
	<div class="form-group">
		<label for="login"><?php echo lang('settings.create_user.login') ; ?></label>
		<input type="text" id="login" name="login" value="<?php echo set_value('login')?>" />
		<?php echo form_error('login') ?>
	</div>
	<div class="form-group">
		<label for="pwd"><?php echo lang('settings.create_user.password') ; ?></label>
		<input type="password" id="pwd" name="pwd" value="" />
		<?php echo form_error('pwd') ?>
	</div>
	<div class="form-group">
		<label for="pwd_confirm"><?php echo lang('settings.create_user.password_confirmation') ; ?></label>
		<input type="password" id="pwd_confirm" name="pwd_confirm" value="" />
		<?php echo form_error('pwd_confirm') ?>
	</div>
	<div class="form-group">
		<label for="name"><?php echo lang('settings.create_user.name') ; ?></label>
		<input type="text" id="name" name="name" value="<?php echo set_value('name')?>" />
		<?php echo form_error('name') ?>
	</div>
	<div class="form-group">
		<label for="is_admin"><?php echo lang('settings.create_user.is_admin') ; ?></label>
		<input type="checkbox" id="is_admin" name="is_admin" value="1" />
		<?php echo form_error('is_admin') ?>
	</div>
	<button type="submit" aria-label="<?php echo lang('settings.create_user.label_button') ; ?>" 
		class="settings-submit"><span><?php echo lang('settings.create_user.label_button') ; ?></span></button>
</form>