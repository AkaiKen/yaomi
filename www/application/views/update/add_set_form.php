<form action="<?php echo site_url('update/add_set') ; ?>" method="post">
	<div class="form-group">
		<label for="name"><?php echo lang('update.add_set.name') ; ?></label>
		<input type="text" id="name" name="name" value="<?php echo set_value('name')?>" />
		<?php echo form_error('name') ?>
	</div>
	<div class="form-group">
		<label for="name_fr"><?php echo lang('update.add_set.name_fr') ; ?></label>
		<input type="text" id="name_fr" name="name_fr" value="<?php echo set_value('name_fr')?>" />
		<?php echo form_error('name_fr') ?>
	</div>
	<div class="form-group">
		<label for="code"><?php echo lang('update.add_set.code') ; ?></label>
		<input type="text" id="code" name="code" value="<?php echo set_value('code')?>" />
		<?php echo form_error('code') ?>
	</div>
	<div class="form-group">
		<label for="date"><?php echo lang('update.add_set.date') ; ?></label>
		<input type="date" id="date" name="date" value="<?php echo set_value('date')?>" />
		<?php echo form_error('date') ?>
	</div>
	<button type="submit" aria-label="<?php echo lang('update.add_set.label_button') ; ?>" 
		class="update-submit"><span><?php echo lang('update.add_set.label_button') ; ?></span></button>
</form>