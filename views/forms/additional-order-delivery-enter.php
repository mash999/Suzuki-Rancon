<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>


<div class="topbar">
    <div class="breadcrumbs">
        <a href="#"><i class="fa fa-check-square-o"></i>Order Delivery</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="../display/additional-order-delivery.php">Additional Parts Delivery</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#">Additional Parts Delivery Form</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="form-title page-title">
		<span class="title">Additional Parts Delivery Form</span>
	</h1>	




	<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="actual-do-date">Actual Do Date <strong>*</strong></label>
			<input type="text" id="actual-do-date" class="form-control date-picker" name="actual-do-date" placeholder="Actual Do Date" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="delivery-date">Delivery Date <strong>*</strong></label>
			<input type="text" id="delivery-date" class="form-control date-picker" name="delivery-date" placeholder="Delivery Date" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="site">Site <strong>*</strong></label>
			<input type="text" id="site" class="form-control" name="site" placeholder="Site" value="" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="reference-do-no">Reference Do No <strong>*</strong></label>
			<input type="text" id="reference-do-no" class="form-control" name="reference-do-no" placeholder="Reference Do No">
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="reference-co-no">Reference Co No <strong>*</strong></label>
			<input type="text" id="reference-co-no" class="form-control" name="reference-co-no" placeholder="Reference Co No">
		</div> <!-- /form-input -->



		
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="customer-code">Customer code <strong>*</strong></label>
			<select name="customer-code" id="customer-code" class="form-control" required>
				<option value="">Select Customer Code</option>
				<?php 
				$c_codes = fetch_functions\get_rows('customers');
				foreach ($c_codes as $code) {
					echo "<option value='$code->CUSTOMER_ID'>$code->CUSTOMER_ID</option>";
				}
				?>
			</select>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input input-searchable">
			<label for="customer-name">Customer Name</label>
			<input type="text" id="customer-name" class="form-control" placeholder="Customer Name">
			<ul class="search-result">
				
			</ul> <!-- /search-result -->
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="customer-address">Customer Address</label>
			<input type="text" id="customer-address" class="form-control" placeholder="Choose Customer Code or Name" readonly>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="customer-contact">Customer Contact</label>
			<input type="text" id="customer-contact" class="form-control" placeholder="Select Customer Code" readonly>
		</div> <!-- /form-input -->



		
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="sales-channel">Sales Channel <strong>*</strong></label>
			<select name="sales-channel" id="sales-channel" class="form-control" required>
				<option value="">Select Sales Channel</option>
				<option value="Dealer">Dealer</option>
				<option value="Corporate">Corporate</option>
				<option value="Retail">Retail</option>
				<option value="Others">Others</option>
			</select>
		</div> <!-- /form-input -->




		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive multiple-rows">
			<table>
				<thead>
					<tr class="header-row">
						<th class="add add-type"><i class="fa fa-plus add-row"></i></th>
						<th>Part Number</th>
						<th>Part Name</th>
						<th>Model</th>
						<th>Color Code</th>
						<th>Color Name</th>
						<th>Quantity</th>
						<th>Unit</th>
						<th>Remarks</th>
					</tr>
				</thead>

				<tbody>
					<?php for($i=0; $i<5; $i++) { ?>
					<tr>
						<td class="delete"><i class="fa fa-trash trash-it"></i></td>
						<td><input type="text" name="part-number[]" placeholder="Part Number" value=""></td>
						<td><input type="text" name="part-name[]" placeholder="Part Name" value=""></td>
						<td><input type="text" name="model[]" placeholder="Model" value=""></td>
						<td><input type="text" name="color-code[]" placeholder="Color Code" value=""></td>
						<td><input type="text" name="color-name[]" placeholder="Color Name" value=""></td>
						<td><input type="text" name="quantity[]" placeholder="Quantity" value=""></td>
						<td>
							<select name="unit[]">
								<option value="">Select Unit</option>
								<option value="KG">KG</option>
								<option value="Litter">Litter</option>
								<option value="Pair">Pair</option>
								<option value="Pieces">Pieces</option>
								<option value="Unit">Unit</option>
							</select>
						</td>
						<td><input type="text" name="remarks[]" placeholder="Remarks" value=""></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div> <!-- /form-input -->




		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-input">
			<label for="excel-file" class="btn btn-primary">Upload Excel</label>
			<input type="file" id="excel-file" name="file" class="hidden">
			<button type="submit" class="btn btn-primary" name="save-additional-delivery">Save</button>
			<a href="../display/additional-order-delivery.php" class="btn btn-primary">Cancel</a>
			<br><br>
			<p id="file-name"></p>
		</div> <!-- /form-input -->
	</form>

</div> <!-- /content -->

</body>
</html>