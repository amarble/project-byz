<?php
/**
 * create.php index view file for categoriesController
 * 
 * $this references categoriesController
 * 
 */

$this->title = 'Test Project | Category | Create';
?>
<h3>Create New category</h3>
<form class="form-horizontal" method="POST">
  <div class="control-group">
    <label class="control-label" for="model[name]">Name</label>
    <div class="controls">
      <input type="text" id="model[name]" name="model[name]" placeholder="Name" required>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="model[description]">Description</label>
    <div class="controls">
      <textarea id="model[description]" name="model[description]"></textarea>
    </div>
  </div>
  <div class="control-group">
    <button type="submit" class="btn btn-primary">Create</button>
  </div>
</form>