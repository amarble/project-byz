<?php 
/**
 * index.php index view file for categoriesController
 * 
 * $this references categoriesController
 * $models references an array of categories models
 * 
 */


$this->title = "Test Project | Categories";
?>
<h3>Category listing</h3>
<?php $this->renderPartial('table', array('data' => $models)); ?>
<p>
  <a href="categories/create" class="btn btn-success pull-right">New Category</a>
</p>
<div id="deleteBox" class="modal hide fade" tabindex="-1" role="dialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3 id="deleteBoxLabel">Confirm deletion</h3>
  </div>
  <div class="modal-body">
    <p>Are you sure you want to delete the category <span id="deleteTarget"></span>?</p>
    <p>Please note that deleting a category will also delete all products within the category!</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal">Cancel</button>
    <a class="btn btn-danger btnConfirmDelete" href="#">Delete</a>
  </div>
</div>