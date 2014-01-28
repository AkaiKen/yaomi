<form action="<?php echo site_url('update/manual') ; ?>" method="post" enctype="multipart/form-data" >
	<?php if(isset($error)) echo $error ; ?>
	<input type="file" name="update_csv"  />
	<input type="submit" />
	<?php if(isset($upload_data)) var_dump($upload_data) ; ?>
</form>