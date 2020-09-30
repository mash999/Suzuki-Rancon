<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>


<div class="topbar">
    <div class="breadcrumbs">
        <span><i class="fa fa-list-ul"></i>Issues</span>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="../display/ckd-issue.php"><span>CKD Issue to Assembly</span></a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#"><span>CKD Issue to Assembly Form</span></a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="form-title page-title">
		<span class="title">CKD Issue to Assembly Form</span>
	</h1>	




	<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="requisition-number">Requisition Number</label>
			<?php 
			$stmt = $con->query("SELECT MAX(KEY_ID) AS MAX_ID FROM issues");
			$new_req_num = $stmt->fetch(\PDO::FETCH_OBJ);
			?>
			<input type="text" id="requisition-number" class="form-control" placeholder="Requisition Number" value ="<?php echo $new_req_num->MAX_ID + 1;?>" readonly>
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
			<label for="name">Requester Name <strong>*</strong></label>
			<input type="text" id="name" class="form-control" name="name" placeholder="Requester Name" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="designation">Requester Designation <strong>*</strong></label>
			<input type="text" id="designation" class="form-control" name="designation" placeholder="Requester Designation" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="department">Requester Department <strong>*</strong></label>
			<input type="text" id="department" class="form-control" name="department" placeholder="Requester Department" required>
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
						<th>Engine Number</th>
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
						<td><input type="text" name="frame-number[]" placeholder="Frame Number" value=""></td>
						<td><input type="text" name="engine-number[]" placeholder="Engine Number" value=""></td>
						<td><input type="text" name="remarks[]" placeholder="Remarks" value=""></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div> <!-- /form-input -->




		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-input">
			<input type="hidden" name="type" value="ckd-issue">
			<input type="hidden" name="received" value="0">
			<label for="excel-file" class="btn btn-primary">Upload Excel</label>
			<input type="file" id="excel-file" name="file" class="hidden">
			<button type="submit" class="btn btn-primary" name="save-issues">Save</button>
			<a href="../display/ckd-issue.php" class="btn btn-primary">Cancel</a>
			<br><br>
			<p id="file-name"></p>
		</div> <!-- /form-input -->
	</form>

</div> <!-- /content -->

</body>
</html>