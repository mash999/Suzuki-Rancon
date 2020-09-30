<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>


<div class="topbar">
    <div class="breadcrumbs">
        <span><i class="fa fa-shopping-basket"></i>Issues Received</span>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="../display/manufacturing-issue-received.php"><span>Manufacturing Parts Issues Received</span></a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#"><span>Manufacturing Parts Issues Received Form</span></a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="form-title page-title">
		<span class="title">Manufacturing Parts Issues Received Form</span>
	</h1>	




	<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="reference-number">Reference Number <strong>*</strong></label>
			<input type="text" class="form-control" name="reference-number" placeholder="Reference Number">
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label>Date <strong>*</strong></label>
			<?php $date = date("d-m-Y",time() + 6*60*60);?>
			<input type="text" class="form-control" name="date" placeholder="Date" value="<?php echo $date;?>" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label>Site <strong>*</strong></label>
			<input type="text" id="set-site" name="site" class="form-control" placeholder="Site" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label>Sender Name <strong>*</strong></label>
			<input type="text" id="set-name" name="name" class="form-control" placeholder="Sender Name" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label>Sender Designation <strong>*</strong></label>
			<input type="text" id="set-designation" name="designation" class="form-control" placeholder="Sender Designation" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label>Sender Department <strong>*</strong></label>
			<input type="text" id="set-department" name="department" class="form-control" placeholder="Sender Department" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label>Invoice Number <strong>*</strong></label>
			<input type="text" id="set-invoice-no" name="invoice-no" class="form-control" placeholder="Invoice Number" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label>LC Number <strong>*</strong></label>
			<input type="text" id="set-lc-no" name="lc-no" class="form-control" placeholder="LC Number" required>
		</div> <!-- /form-input -->



		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label>Lot Number</label>
			<input type="text" id="set-lot-no" name="lot-no" class="form-control" placeholder="Lot Number">
		</div> <!-- /form-input -->



		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label>PPD Number</label>
			<input type="text" id="set-ppd-no" name="ppd-no" class="form-control" placeholder="PPD Number">
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
						<th>Frame Number</th>
						<th>Swing Arm Number</th>
						<th>Remarks</th>
					</tr>
				</thead>

				<tbody id="set-body">
					<?php for ($i = 0; $i < 5 ; $i++) { ?>
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
						<td><input type="text" name="frame-number[]" placeholder="Frame Number" value=""></td>
						<td><input type="text" name="engine-number[]" placeholder="Swing Arm Number" value=""></td>
						<td><input type="text" name="remarks[]" placeholder="Remarks" value=""></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div> <!-- /form-input -->




		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-input">
			<input type="hidden" name="type" value="manufacturing-issue">
			<input type="hidden" name="received" value="1">
			<label for="excel-file" class="btn btn-primary">Upload Excel</label>
			<input type="file" id="excel-file" name="file" class="hidden">
			<button type="submit" class="btn btn-primary" name="save-issues">Save</button>
			<a href="../display/manufacturing-issue-received.php" class="btn btn-primary">Cancel</a>
			<br><br>
			<p id="file-name"></p>
		</div> <!-- /form-input -->
	</form>

</div> <!-- /content -->

</body>
</html>