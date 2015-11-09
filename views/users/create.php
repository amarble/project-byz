<?php
/**
 * create.php index view file for categoriesController
 * 
 * $this references categoriesController
 * 
 */

$this->title = 'Test Project | Account | Create';
?>
<h3>Create Account</h3>
<form class="form-horizontal" method="POST">
  <div class="form-group">
    <label class="control-label col-sm-2" for="model[username]">User Name</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="model[username]" name="model[username]" value="<?php echo $model->username; ?>" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="model[email]">Email</label>
    <div class="col-sm-4">
      <input type="email" class="form-control" id="model[email]" name="model[email]" value="<?php echo $model->email; ?>" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="model[password]">Password</label>
    <div class="col-sm-4">
      <input type="password" class="form-control" id="model[password]" name="model[password]" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="model[repeatPassword]">Repeat password</label>
    <div class="col-sm-4">
      <input type="password" class="form-control" id="model[repeatePassword]" name="model[repeatPassword]" required>
    </div>
  </div>
  <div class="form-group">
		<div class="col-sm-offset-2 col-sm-4">
			<button type="submit" class="btn btn-primary">Create Account</button>
		</div>
	</div>
</form>