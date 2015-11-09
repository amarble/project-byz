<?php
$this->title = 'Test Project | Intentory Items | Split';
?>
<h3>Split Inventory Item</h3>
<div class="row">
	<div class="col-sm-12">
		<p>Split an inventory item batch into two batches by adjusting the quantity slider</p>
	</div>
</div>
<form method="POST">
	<input type="hidden" name="model[id]" value="<?php echo $model->id; ?>">
	<div class="row" style="margin : 20px 0;">
		<div class="col-sm-2 col-sm-offset-2 text-right">
			Quantity Remaining : <span id="hold-quantity"><?php echo $model->quantity; ?></span>
		</div>
		<div class="col-sm-4 text-center">
			<input type="text" name="split" id="split" class="sliderbox" value="0" />
		</div>
		<div class="col-sm-2">
			Quantity To Split : <span id="split-quantity">0</span>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-offset-4 col-sm-4 text-center">
			<button type="submit" class="btn btn-primary">Split Group</button>
		</div>
	</div>
</form>
<script>
	$('.sliderbox').bootstrapSlider({
		max : <?php echo $model->quantity - 1; ?>,
		value: 0
	});
	$('.sliderbox').on('slide',  function(e) {
			var quantity = <?php echo $model->quantity;?>;
			$('#hold-quantity').text(quantity - e.value);
			$('#split-quantity').text(e.value);
	});
</script>