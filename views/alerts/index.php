<?php 
/**
 * index.php index view file for alertsController
 * 
 * $this references alertsController
 * $models references an array of alerts models
 * 
 */


$this->title = "Test Project | Alerts";
?>
<h3>Tags listing</h3>
<?php $this->renderPartial('table', array('data' => $models)); ?>
<p>
  <a href="alerts/create" class="btn btn-success pull-right">New alert</a>
</p>
<div id="deleteBox" class="modal hide fade" tabindex="-1" role="dialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">�</button>
    <h3 id="deleteBoxLabel">Confirm deletion</h3>
  </div>
  <div class="modal-body">
    <p>Are you sure you want to delete the tag <span id="deleteTarget"></span>?</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal">Cancel</button>
    <a class="btn btn-danger btnConfirmDelete" href="#">Delete</a>
  </div>
</div>