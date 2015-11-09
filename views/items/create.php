<?php
$this->title = 'Test Project | Products | Create';
?>
<h3>New Inventory Item</h3>
<form class="form-horizontal" method="POST">
  <div class="form-group">
    <label class="control-label col-sm-2" for="model[name]">Name</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="model[name]" name="model[name]" placeholder="Name">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="model[purchacePrice]">Purchase Price</label>
    <div class="col-sm-4">
      <div class="input-group">
        <span class="input-group-addon">$</span>
        <input class="col-sm-2 form-control" type="text" id="model[purchacePrice]" name="model[purchasePrice]">
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="model[purcahseDate]">Purchase Date</label>
    <div class="col-sm-4">
      <input type="text" class="form-control datepicker" id="model[purchaseDate]" name="model[purchaseDate]">
    </div>
  </div>
	<div class="form-group">
    <label class="control-label col-sm-2" for="model[vendor]">Purchased From</label>
    <div class="col-sm-4">
      <select class="form-control select2-user-input" id="model[vendor]" name="model[vendor]">
			<?php foreach ($vendors as $vendor):?>
				<option value="<?php echo $vendor->id?>"><?php echo $vendor->name?></option>
			<?php endforeach;?>
			</select>
    </div>
  </div>
	<div class="form-group">
    <label class="control-label col-sm-2" for="model[tags][]">Tags</label>
    <div class="col-sm-4">
      <select class="form-control select2-tags" id="model[tags][]" name="model[tags][]" multiple>
			<?php foreach ($tags as $tag):?>
				<option value="<?php echo $tag->id?>"><?php echo $tag->name?></option>
			<?php endforeach;?>
	  </select>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="model[status]">Status</label>
    <div class="col-sm-4">
      <select id="model[status]" class="form-control" name="model[status]">
        <?php foreach ($statuses as $status):?>
        <option value="<?php echo $status->id?>"><?php echo $status->name?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="model[comments]">Comments</label>
    <div class="col-sm-4">
      <textarea id="model[comments]" class="form-control" name="model[comments]"><?php echo $model->comments?></textarea>
    </div>
  </div>
	<div class="form-group">
		<input type="hidden" name="model[hold]" value="0">
    <div class="col-sm-offset-2 col-sm-4">
      <div class="checkbox">
        <label>
          <input type="checkbox" id="model[hold]" name="model[hold]" value="1"> Hold this item
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
		<div class="col-sm-offset-2 col-sm-4">
			<button type="submit" class="btn btn-primary">Create</button>
		</div>
	</div>
</form>