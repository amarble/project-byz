<?php
/**
 * create.php index view file for categoriesController
 * 
 * $this references categoriesController
 * 
 */

$this->title = 'Test Project | Account | Login';
?>
<h3>Login</h3>
<form class="form-horizontal" method="POST">
  <div class="form-group">
    <label class="control-label col-sm-2" for="model[username]">User Name</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="model[username]" name="model[username]" value="<?php echo $model->username; ?>" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="model[password]">Password</label>
    <div class="col-sm-4">
      <input type="password" class="form-control" id="model[password]" name="model[password]" required>
    </div>
  </div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-4">
			<button type="submit" class="btn btn-primary">Login</button>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-4">
			<a href="<?php echo HTMLROOT?>users/create">Create new account</a>
		</div>
	</div>
</form>