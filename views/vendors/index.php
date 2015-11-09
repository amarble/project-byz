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
<h3>Vendors listing</h3>
<?php $this->renderPartial('table', array('data' => $models)); ?>
<p>
  <a href="vendors/create" class="btn btn-success pull-right">New Vendor</a>
</p>