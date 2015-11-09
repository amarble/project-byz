<?php 
/**
 * index.php index view file for productsController
 * 
 * $this references productsController
 * $models references an array of products models
 * 
 */


$this->title = "Test Project | Products";
?>
<h3>Products listing</h3>
<?php $this->renderPartial('table', array('data' => $models, 'categories' => $categories, 'pagination' => $pagination)); ?>
<p>
  <a href="<?php echo HTMLROOT?>products/create" class="btn btn-success pull-right">New Product</a>
</p>
<form>
  <input type="hidden" name="sortAttribute" id="sortAttribute" value="<?php echo $pagination['sortAttribute']?>" />
  <input type="hidden" name="sortDirection" id="sortDirection" value="<?php echo $pagination['sortDirection']?>" />
  <input type="hidden" name="paginationPageNumber" id="paginationPageNumber" value="<?php echo $pagination['limit'][0]?>" />
  <input type="hidden" name="paginationPerPage" id="paginationPerPage" value="<?php echo $pagination['limit'][1]?>" />
  <input type="hidden" name="filter" id="filter" value="<?php echo $pagination['filter']?>" />
</form>
<div id="deleteBox" class="modal hide fade" tabindex="-1" role="dialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3 id="deleteBoxLabel">Confirm deletion</h3>
  </div>
  <div class="modal-body">
    <p>Are you sure you want to delete the item <span id="deleteTarget"></span>?</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal">Cancel</button>
    <a class="btn btn-danger btnConfirmDelete" href="#">Delete</a>
  </div>
</div>