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
<h3>Status listing</h3>
<?php $this->renderPartial('table', array('data' => $models)); ?>