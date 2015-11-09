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
<h3>Inventory listing</h3>
<?php $this->renderPartial('table', array('data' => $models, 'statuses' => $statuses, 'vendors' => $vendors, 'tags' => $tags, 'pagination' => $pagination, 'headers' => $headers)); ?>
<p>
  <a href="<?php echo HTMLROOT?>items/create" class="btn btn-success pull-right">New Inventory Item</a>
</p>
<form>
  <input type="hidden" name="sortAttribute" id="sortAttribute" value="<?php echo $pagination['sortAttribute']?>" />
  <input type="hidden" name="sortDirection" id="sortDirection" value="<?php echo $pagination['sortDirection']?>" />
  <input type="hidden" name="paginationPageNumber" id="paginationPageNumber" value="<?php echo $pagination['limit'][0]?>" />
  <input type="hidden" name="paginationPerPage" id="paginationPerPage" value="<?php echo $pagination['limit'][1]?>" />
  <input type="hidden" name="filter" id="filter" value="<?php echo $pagination['filter']?>" />
	<input type="hidden" id="headers" value="<?php echo implode(',', $headers['show']);?>" />
</form>
<div class="modal fade" role="dialog" id="delete-modal">
  <div class="modal-dialog">
    <div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Confirm deletion</h3>
	  </div>
	  <div class="modal-body">
		<p>Are you sure you want to delete the item <span id="deleteTarget"></span>?</p>
	  </div>
	  <div class="modal-footer">
		<button class="btn" data-dismiss="modal">Cancel</button>
		<a class="btn btn-danger btnConfirmDelete" href="#">Delete</a>
	  </div>
	</div>
  </div>
</div>
<div class="modal fade" role="dialog" id="filter-modal">
  <div class="modal-dialog">
    <div class="modal-content">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Filters</h4>
	  </div>
	  <div class="modal-body">
	    <form class="form-horizontal">
		  <div class="form-group">
		    <label for="filter-statuses" class="col-sm-2 control-label">Statuses</label>
			<div class="col-sm-10">
			  <select class="form-control select2" id="filter-statuses" style="width: 100%" multiple>
			  <?php foreach ($statuses as $status):?>
				<option value="<?php echo $status->id?>"><?php echo $status->name?></option>
			  <?php endforeach;?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
		    <label for="filter-vendors" class="col-sm-2 control-label">Vendors</label>
			<div class="col-sm-10">
			  <select class="form-control select2" id="filter-vendors" style="width: 100%" multiple>
			  <?php foreach ($vendors as $vendor):?>
				<option value="<?php echo $vendor->id?>"><?php echo $vendor->name?></option>
			  <?php endforeach;?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
		    <label for="filter-tags" class="col-sm-2 control-label">Tags</label>
			<div class="col-sm-10">
			  <select class="form-control select2" id="filter-tags" style="width: 100%" multiple>
			  <?php foreach ($tags as $tag):?>
				<option value="<?php echo $tag->id?>"><?php echo $tag->name?></option>
			  <?php endforeach;?>
			  </select>
			</div>
		  </div>
		</form>
	  </div>
	  <div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	  </div>
	</div>
  </div>
</div>
<div class="modal fade" role="dialog" id="options-modal">
  <div class="modal-dialog">
    <div class="modal-content">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Options</h4>
	  </div>
	  <div class="modal-body">
	    <div class="row">
		  <div class="col-sm-6">
			Displayed Columns
		    <ul id="show-columns" class="column-sort list-group">
			  <?php foreach ($headers['show'] as $column): ?>
			  <li class="list-group-item" data-id="<?php echo $headers['headers'][$column]->id; ?>"><?php echo $headers['headers'][$column]->name; ?>
			  <?php endforeach; ?>
			</ul>
		  </div>
		  <div class="col-sm-6">
			Hidden Columns
		    <ul id="hide-columns" class="column-sort list-group">
			  <?php $hide = array_diff(array_keys($headers['headers']), $headers['show']); ?>
			  <?php foreach ($hide as $column): ?>
			  <li class="list-group-item" data-id="<?php echo $headers['headers'][$column]->id; ?>"><?php echo $headers['headers'][$column]->name; ?>
			  <?php endforeach; ?>
			</ul>
		  </div>
		</div>
	  </div>
	  <div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-primary" id="button-apply-options" data-dismiss="modal">Apply</button>
	  </div>
	</div>
  </div>
</div>
<script>
	$("#show-columns, #hide-columns").sortable({
		connectWith: ".column-sort"
	}).disableSelection();
</script>