<?php
/**
 * edit.php edit view file for categoriesController
 * 
 * $this references categoriesController
 * $model references the model being edited
 * 
 */

$this->title = 'Test Project | Status | Edit';
?>
<h3>Edit Status</h3>
<form class="form-horizontal" method="POST">
  <input type="hidden" value="<?php echo $model->id?>" id="model[id]" name="model[id]" required>
  <div class="form-group">
    <label class="control-label col-sm-2" for="model[name]">Name</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="model[name]" name="model[name]" value="<?php echo $model->name?>">
    </div>
  </div>
  <div class="form-group">
		<div class="col-sm-offset-2 col-sm-4">
			<button type="submit" class="btn btn-primary">Save Changes</button>
		</div>
	</div>
</form>