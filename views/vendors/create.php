<?php
/**
 * create.php index view file for categoriesController
 * 
 * $this references categoriesController
 * 
 */

$this->title = 'Test Project | Vendor | Create';
?>
<h3>Create New Vendor</h3>
<form class="form-horizontal" method="POST">
  <div class="form-group">
    <label class="control-label col-sm-2" for="model[name]">Name</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="model[name]" name="model[name]" placeholder="Name" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="model[comments]">Comments</label>
    <div class="col-sm-4">
      <textarea class="form-control" id="model[comments]" name="model[comments]"></textarea>
    </div>
  </div>
  <div class="form-group">
		<div class="col-sm-offset-2 col-sm-4">
			<button type="submit" class="btn btn-primary">Create</button>
		</div>
	</div>
</form>