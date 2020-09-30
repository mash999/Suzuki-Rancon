<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>


<div class="topbar">
    <div class="breadcrumbs">
        <a href="#"><i class="fa fa-chain-broken"></i>Claims</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="../display/ckd-claim.php"><span>Claims for CKD</span></a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#"><span>Claims for CKD Form</span></a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="form-title page-title">
		<span class="title">Claims for CKD Form</span>
	</h1>	




	<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="date">Claim Issue Date <strong>*</strong></label>
			<input type="text" id="date" class="form-control date-picker" name="claim-issue-date" placeholder="Claim Issue Date" value="" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="site">Site <strong>*</strong></label>
			<input type="text" id="site" class="form-control" name="site" placeholder="Site" value="" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="created-by">Created By <strong>*</strong></label>
			<input type="text" id="created-by" class="form-control" name="created-by" placeholder="Created By" value="" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="approved-by">Approved By <strong>*</strong></label>
			<select name="approved-by" id="approved-by" class="form-control" required>
				<option value="Mr.Anowar">Mr.Anowar</option>
				<option value="Mr.Fahim">Mr.Fahim</option>
			</select>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="claim-reference-no">Claim Reference Number <strong>*</strong></label>
			<input type="text" id="claim-reference-no" class="form-control" name="claim-reference-no" placeholder="Claim Reference Number" value="" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="apd-no">APD Number <strong>*</strong></label>
			<input type="text" id="apd-no" class="form-control" name="apd-no" placeholder="APD Number" value="" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="ppd-no">PPD Number</label>
			<input type="text" id="ppd-no" class="form-control" name="ppd-no" placeholder="PPD Number" value="">
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
			<label for="model">Model <strong>*</strong></label>
			<input type="text" id="model" class="form-control" name="model" placeholder="Model" value="" required>
		</div> <!-- /form-input -->



		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="shipping-mode">Shipping Mode <strong>*</strong></label>
			<select name="shipping-mode" id="shipping-mode" class="form-control" required>
				<option value="Air">Air</option>
				<option value="Others">Others</option>
			</select>
		</div> <!-- /form-input -->



		<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 form-input">
			<label for="month">Month <strong>*</strong></label>
			<select name="month" id="month" class="form-control" required>
				<?php $months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
				foreach ($months as $m) {
					if(strtolower($m) == strtolower(date("M"))) echo "<option value = '$m' selected>$m</option>";
					else echo "<option value = '$m'>$m</option>";
				}
				?>
			</select>
		</div> <!-- /form-input -->



		<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 form-input">
			<label for="year">Year <strong>*</strong></label>
			<select name="year" id="year" class="form-control" required>
				<?php 
				for($i = date("Y") - 50; $i < date("Y") + 50; $i++){
					if($i == date("Y"))	echo "<option value='$i' selected>$i</option>";
					else echo "<option value='$i'>$i</option>";
				}
				?>
			</select>
		</div> <!-- /form-input -->




		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive multiple-rows">
			<table>
				<thead>
					<tr class="header-row">
						<th class="add add-type"><i class="fa fa-plus add-row"></i></th>
						<th>Part Number</th>
						<th>Part Name</th>
						<th>Color Code</th>
						<th>Color Name</th>
						<th>Quantity</th>
						<th>Unit</th>
						<th>Box Number</th>
						<th>Case Number</th>
						<th>Reference Number</th>
						<th>Lot Number</th>
						<th>Claim Type</th>
						<th>Claim Code</th>
						<th>Action Code</th>
						<th>Process Code</th>
						<th>Details of Defect</th>
						<th>Defect Finding Way</th>
						<th>Picture</th>
						<th>Remarks</th>
					</tr>
				</thead>

				<tbody>
					<?php for($i=0; $i<5; $i++) { ?>
					<tr>
						<td class="delete"><i class="fa fa-trash trash-it"></i></td>
						<td><input type="text" name="part-number[]" placeholder="Part Number" value=""></td>
						<td><input type="text" name="part-name[]" placeholder="Part Name" value=""></td>
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
						<td><input type="text" name="box-number[]" placeholder="Box Number" value=""></td>
						<td><input type="text" name="case-number[]" placeholder="Case Number" value=""></td>
						<td><input type="text" name="reference-number[]" placeholder="Reference Number" value=""></td>
						<td><input type="text" name="lot-number[]" placeholder="Lot Number" value=""></td>
						<td><input type="text" name="claim-type[]" placeholder="Claim Type" value=""></td>
						<td><input type="text" name="claim-code[]" placeholder="Claim Code" value=""></td>
						<td><input type="text" name="action-code[]" placeholder="Action Code" value=""></td>
						<td><input type="text" name="process-code[]" placeholder="Process Code" value=""></td>
						<td><input type="text" name="details-of-defect[]" placeholder="Details of Defect" value=""></td>
						<td><input type="text" name="defect-finding-way[]" placeholder="Defect Finding Way" value=""></td>
						<td><input type="file" name="picture[]"></td>
						<td><input type="text" name="remarks[]" placeholder="Remarks" value=""></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div> <!-- /form-input -->




		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-input">
			<input type="hidden" name="type" value="ckd-claim">
			<label for="excel-file" class="btn btn-primary">Upload Excel</label>
			<input type="file" id="excel-file" name="file" class="hidden">
			<button type="submit" class="btn btn-primary" name="save-claims">Save</button>
			<a href="../display/ckd-claim.php" class="btn btn-primary">Cancel</a>
			<br><br>
			<p id="file-name"></p>
		</div> <!-- /form-input -->
	</form>

</div> <!-- /content -->

</body>
</html>