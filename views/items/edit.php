<?php
$this->title = 'Test Project | Intentory Items | Edit';
?>
<h3>Edit Inventory Item</h3>
<form class="form-horizontal" method="POST">
	<input type="hidden" name="model[id]" value="<?php echo $model->id?>">
  <div class="form-group">
    <label class="control-label col-sm-2" for="model[name]">Name</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="model[name]" name="model[name]" placeholder="Name" value="<?php echo $model->name; ?>">
    </div>
  </div>
	<div class="form-group">
    <label class="control-label col-sm-2" for="model[status]">Status</label>
    <div class="col-sm-4">
      <select id="model[status]" class="form-control" name="model[status]">
        <?php foreach ($statuses as $status):?>
        <option value="<?php echo $status->id?>" <?php echo $status->id == $model->status ? 'selected' : ''?>><?php echo $status->name?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
	<div class="form-group">
    <label class="control-label col-sm-2" for="model[quantity]">Quantity</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="model[quantity]" name="model[quantity]" placeholder="Name" value="<?php echo $model->quantity; ?>">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="model[purchacePrice]">Purchase Price</label>
    <div class="col-sm-4">
      <div class="input-group">
        <span class="input-group-addon">$</span>
        <input class="col-sm-2 form-control" type="text" id="model[purchacePrice]" name="model[purchasePrice]" value="<?php echo $model->purchasePrice; ?>">
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="model[purcahseDate]">Purchase Date</label>
    <div class="col-sm-4">
      <input type="text" class="form-control datepicker" id="model[purchaseDate]" name="model[purchaseDate]" value="<?php echo date('m-d-Y', strtotime($model->purchaseDate)); ?>">
    </div>
  </div>
	<div class="form-group">
    <label class="control-label col-sm-2" for="model[vendor]">Purchased From</label>
    <div class="col-sm-4">
      <select class="form-control select2-user-input" id="model[vendor]" name="model[vendor]">
			<?php foreach ($vendors as $vendor):?>
				<option value="<?php echo $vendor->id?>" <?php echo $vendor->id == $model->vendor ? 'selected' : ''?>><?php echo $vendor->name?></option>
			<?php endforeach;?>
			</select>
    </div>
  </div>
	<div class="form-group">
    <label class="control-label col-sm-2" for="model[salePrice]">Listed Price</label>
    <div class="col-sm-4">
      <div class="input-group">
        <span class="input-group-addon">$</span>
        <input class="col-sm-2 form-control" type="text" id="model[salePrice]" name="model[salePrice]" value="<?php echo $model->salePrice; ?>">
      </div>
    </div>
  </div>
	<div class="form-group">
    <label class="control-label col-sm-2" for="model[saleMethod]">Listed At</label>
    <div class="col-sm-4">
      <select class="form-control select2-user-input" id="model[saleMethod]" name="model[saleMethod]">
				<option value=""></option>
			<?php foreach ($vendors as $vendor):?>
				<option value="<?php echo $vendor->id?>" <?php echo $vendor->id == $model->saleMethod ? 'selected' : ''?>><?php echo $vendor->name?></option>
			<?php endforeach;?>
			</select>
    </div>
  </div>
	<div class="form-group">
    <label class="control-label col-sm-2" for="model[saleDate]">Listing Date</label>
    <div class="col-sm-4">
      <input type="text" class="form-control datepicker" id="model[saleDate]" name="model[saleDate]" value="<?php echo $model->saleDate ? date('m-d-Y', strtotime($model->saleDate)) : ''; ?>">
    </div>
  </div>
	<div class="form-group">
    <label class="control-label col-sm-2" for="model[soldPrice]">Sold Price</label>
    <div class="col-sm-4">
      <div class="input-group">
        <span class="input-group-addon">$</span>
        <input class="col-sm-2 form-control" type="text" id="model[soldPrice]" name="model[soldPrice]" value="<?php echo $model->soldPrice; ?>">
      </div>
    </div>
  </div>
	<div class="form-group">
    <label class="control-label col-sm-2" for="model[soldDate]">Sold Date</label>
    <div class="col-sm-4">
      <input type="text" class="form-control datepicker" id="model[soldDate]" name="model[soldDate]" value="<?php echo $model->soldDate ? date('m-d-Y', strtotime($model->soldDate)) : ''; ?>">
    </div>
  </div>
	<div class="form-group">
    <label class="control-label col-sm-2" for="model[tags][]">Tags</label>
    <div class="col-sm-4">
      <select class="form-control select2-tags" id="model[tags][]" name="model[tags][]" multiple>
			<?php foreach ($tags as $tag):?>
				<option value="<?php echo $tag->id?>" <?php echo in_array($tag->id, $model->tags) ? 'selected' : '' ?>><?php echo $tag->name?></option>
			<?php endforeach;?>
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
          <input type="checkbox" id="model[hold]" name="model[hold]" value="1" <?php echo $model->hold ? 'checked' : '' ;?>> Hold this item
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
		<div class="col-sm-offset-2 col-sm-4">
			<button type="submit" class="btn btn-primary">Save Changes</button>
			<a href="<?php echo HTMLROOT?>items/split/<?php echo $model->id?>" class="btn btn-default" <?php echo $model->quantity > 1 ? '' : 'disabled';?>>Split</a>
		</div>
	</div>
</form>
