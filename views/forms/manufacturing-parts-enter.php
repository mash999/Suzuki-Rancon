<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>


<div class="topbar">
    <div class="breadcrumbs">
        <span><i class="fa fa-pencil-square-o"></i>Enter</span>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="../display/manufacturing-parts.php"><span>Manufacturing Parts Enter</span></a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#"><span>Manufacturing Parts Enter Form</span></a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="form-title page-title">
		<span class="title">Manufacturing Parts Enter Form</span>
	</h1>	




	<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="requisition-number">Requisition Number</label>
			<input type="text" class="form-control" name="requisition-number" placeholder="Requisition Number">
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="date">Date <strong>*</strong></label>
			<input type="text" id="date" class="form-control date-picker" name="date" placeholder="Date" value="" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="site">Site <strong>*</strong></label>
			<input type="text" id="site" class="form-control" name="site" placeholder="Site" value="" required>
		</div> <!-- /form-input -->



		
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="suppliers-code">Suppliers code <strong>*</strong></label>
			<select name="supplier-code" id="supplier-code" class="form-control" required>
				<option value="">Select Supplier Code</option>
				<?php 
				$s_codes = fetch_functions\get_rows('suppliers');
				foreach ($s_codes as $code) {
					echo "<option value='$code->SUPPLIER_CODE'>$code->SUPPLIER_CODE</option>";
				}
				?>
			</select>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input input-searchable">
			<label for="supplier-name">Supplier Name</label>
			<input type="text" id="supplier-name" class="form-control" placeholder="Supplier Name">
			<ul class="search-result">
				
			</ul> <!-- /search-result -->
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="supplier-address">Supplier Address</label>
			<input type="text" id="supplier-address" class="form-control" placeholder="Choose Supplier Code or Name" readonly>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="invoice-no">Invoice Number <strong>*</strong></label>
			<input type="text" id="invoice-no" class="form-control" name="invoice-no" placeholder="Invoice Number" value="" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="lc-no">LC Number <strong>*</strong></label>
			<input type="text" id="lc-no" class="form-control" name="lc-no" placeholder="LC Number" value="" required>
		</div> <!-- /form-input -->



		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="lot-no">Lot Number</label>
			<input type="text" id="lot-no" class="form-control" name="lot-no" placeholder="Lot Number" value="">
		</div> <!-- /form-input -->



		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="ppd-no">PPD Number</label>
			<input type="text" id="ppd-no" class="form-control" name="ppd-no" placeholder="PPD Number" value="">
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
			<input type="hidden" name="type" value="manufacturing-parts">
			<label for="excel-file" class="btn btn-primary">Upload Excel</label>
			<input type="file" id="excel-file" name="file" class="hidden">
			<button type="submit" class="btn btn-primary" name="save-parts">Save</button>
			<a href="../display/manufacturing-parts.php" class="btn btn-primary">Cancel</a>
			<br><br>
			<p id="file-name"></p>
		</div> <!-- /form-input -->
	</form>

</div> <!-- /content -->

</body>
</html>