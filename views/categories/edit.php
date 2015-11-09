<?php
/**
 * edit.php edit view file for categoriesController
 * 
 * $this references categoriesController
 * $model references the model being edited
 * 
 */

$this->title = 'Test Project | Categories | Edit';
?>
<h3>Edit Category</h3>
<form class="form-horizontal" method="POST">
  <input type="hidden" value="<?php echo $model->id?>" id="model[id]" name="model[id]">
  <div class="control-group">
    <label class="control-label" for="model[name]">Name</label>
    <div class="controls">
      <input type="text" id="model[name]" name="model[name]" value="<?php echo $model->name?>">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="model[description]">Description</label>
    <div class="controls">
      <textarea id="model[description]" name="model[description]"><?php echo $model->description?></textarea>
    </div>
  </div>
  <div class="control-group">
    <button type="submit" class="btn btn-primary">Edit</button>
  </div>
</form>