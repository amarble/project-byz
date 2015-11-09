<?php
$this->title = 'Test Project | Products | Create';
?>
<h3>Create New Product</h3>
<form class="form-horizontal" method="POST">
  <div class="control-group">
    <label class="control-label" for="model[name]">Name</label>
    <div class="controls">
      <input type="text" id="model[name]" name="model[name]" placeholder="Name">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="model[description]">Description</label>
    <div class="controls">
      <textarea id="model[description]" name="model[description]"></textarea>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="model[price]">Price</label>
    <div class="controls">
      <div class="input-prepend input-append">
        <span class="add-on">$</span>
        <input class="span2" type="text" id="model[price]" name="model[price]">
        <span class="add-on">.00</span>
      </div>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="model[category]">Category</label>
    <div class="controls">
      <select id="model[category]" name="model[category]">
        <?php foreach ($categories as $category):?>
        <option value="<?php echo $category->id?>"><?php echo $category->name?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="control-group">
    <button type="submit" class="btn btn-primary">Create</button>
  </div>
</form>