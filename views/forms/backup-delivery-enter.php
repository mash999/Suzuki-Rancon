<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>


<div class="topbar">
    <div class="breadcrumbs">
        <a href="#"><i class="fa fa-check-square-o"></i>Order Delivery</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="../display/backup-order-delivery.php">Backup Parts Delivery</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#">Backup Parts Delivery Form</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="form-title page-title">
		<span class="title">Backup Parts Delivery Form</span>
	</h1>	




	<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="date">Date <strong>*</strong></label>
			<input type="text" id="date" class="form-control date-picker" name="date" placeholder="Date" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="site">Site <strong>*</strong></label>
			<input type="text" id="site" class="form-control" name="site" placeholder="Site" value="" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="requester-name">Requester Name <strong>*</strong></label>
			<input type="text" id="requester-name" class="form-control" name="requester-name" placeholder="Requester Name">
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="requester-designation">Requester Designation <strong>*</strong></label>
			<input type="text" id="requester-designation" class="form-control" name="requester-designation" placeholder="Requester Designation">
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="requester-department">Requester Department <strong>*</strong></label>
			<input type="text" id="requester-department" class="form-control" name="requester-department" placeholder="Requester Department">
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="requisition-number">Requisition Number <strong>*</strong></label>
			<input type="text" id="requisition-number" class="form-control" name="requisition-number" placeholder="Requisition Number" value="" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="reference-number">Reference Number <strong>*</strong></label>
			<input type="text" id="reference-number" class="form-control" name="reference-number" placeholder="Reference Number" value="" required>
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
			<button type="submit" class="btn btn-primary" name="save-backup-delivery">Save</button>
			<a href="../display/backup-order-delivery.php" class="btn btn-primary">Cancel</a>
			<br><br>
			<p id="file-name"></p>
		</div> <!-- /form-input -->
	</form>

</div> <!-- /content -->

</body>
</html>