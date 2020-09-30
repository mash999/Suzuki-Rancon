<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;

if(isset($_GET['ref'])){
	$ref = htmlspecialchars($_GET['ref']);
	if($_SESSION['rancon_access_level'] <= 1){
		echo "<script>location.href='$base_url/views/display/ckd-claim-details.php?ref=$ref';</script>";
		die();
	}
	$claims_entry =  fetch_functions\get_row('claims','KEY_ID', $ref)[0];
	$claims_parts =  fetch_functions\get_row('claims_parts','ENTRY_REFERENCE_ID', $ref);
}
else{
	echo "<h1 class='custom-data'>No data exists with this id. Please choose from valid id numbers</h1>";
	die();
}
?>


<div class="topbar">
    <div class="breadcrumbs">
        <a href="#"><i class="fa fa-chain-broken"></i>Claims</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="../display/ckd-claim.php"><span>Claims for CKD</span></a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#"><span>Claims for CKD Edit</span></a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="form-title page-title">
		<span class="title">Claims for CKD Edit</span>
	</h1>	




	<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<div class="entity-holder" data-entity="claims" data-getin="KEY_ID">
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="date">Claim Issue Date <strong>*</strong></label>
				<input type="text" id="date" class="form-control edit-bulk-inputs date-picker" placeholder="Claim Issue Date" data-in="CLAIM_ISSUE_DATE" data-get="<?php echo $ref;?>" value="<?php echo date('d-m-Y',strtotime($claims_entry->CLAIM_ISSUE_DATE));?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="site">Site <strong>*</strong></label>
				<input type="text" id="site" class="form-control edit-bulk-inputs" placeholder="Site" data-in="SITE" data-get="<?php echo $ref;?>" value="<?php echo $claims_entry->SITE;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="created-by">Created By <strong>*</strong></label>
				<input type="text" id="site" class="form-control edit-bulk-inputs" placeholder="Created By" data-in="CREATED_BY " data-get="<?php echo $ref;?>" value="<?php echo $claims_entry->CREATED_BY ;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="approved-by">Approved By <strong>*</strong></label>
				<select id="approved-by" class="form-control edit-bulk-inputs" data-in="APPROVED_BY" data-get="<?php echo $ref;?>" required>
					<?php 
					if($claims_entry->APPROVED_BY == "Mr.Anowar") echo "<option value='Mr.Anowar' selected>Mr.Anowar</option>";
					else  echo "<option value='Mr.Anowar'>Mr.Anowar</option>";
					if($claims_entry->APPROVED_BY == "Mr.Fahim") echo "<option value='Mr.Fahim' selected>Mr.Fahim</option>";
					else  echo "<option value='Mr.Fahim'>Mr.Fahim</option>";
					?>
				</select>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="claim-reference-no">Claim Reference Number <strong>*</strong></label>
				<input type="text" id="claim-reference-no" class="form-control edit-bulk-inputs" placeholder="Claim Reference Number" data-in="CLAIM_REFERENCE_NUMBER " data-get="<?php echo $ref;?>" value="<?php echo $claims_entry->CLAIM_REFERENCE_NUMBER ;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="apd-no">APD Number <strong>*</strong></label>
				<input type="text" id="apd-no" class="form-control edit-bulk-inputs" placeholder="APD Number" data-in="APD_NUMBER" data-get="<?php echo $ref;?>" value="<?php echo $claims_entry->APD_NUMBER;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="ppd-no">PPD Number</label>
				<input type="text" id="ppd-no" class="form-control edit-bulk-inputs" placeholder="PPD Number" data-in="PPD_NUMBER" data-get="<?php echo $ref;?>" value="<?php echo $claims_entry->PPD_NUMBER;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="invoice-no">Invoice Number <strong>*</strong></label>
				<input type="text" id="invoice-no" class="form-control edit-bulk-inputs" placeholder="Invoice Number" data-in="INVOICE_NUMBER" data-get="<?php echo $ref;?>" value="<?php echo $claims_entry->INVOICE_NUMBER;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="lc-no">LC Number <strong>*</strong></label>
				<input type="text" id="lc-no" class="form-control edit-bulk-inputs" placeholder="LC Number" data-in="LC_NUMBER" data-get="<?php echo $ref;?>" value="<?php echo $claims_entry->LC_NUMBER;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="model">Model <strong>*</strong></label>
				<input type="text" id="model" class="form-control edit-bulk-inputs" placeholder="Model" data-in="MODEL" data-get="<?php echo $ref;?>" value="<?php echo $claims_entry->MODEL;?>" required>
			</div> <!-- /form-input -->



			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="shipping-mode">Shipping Mode <strong>*</strong></label>
				<select id="shipping-mode" class="form-control edit-bulk-inputs" data-in="SHIPPING_MODE" data-get="<?php echo $ref;?>" required>
					<?php 
					if($claims_entry->APPROVED_BY == "Air") echo "<option value='Air' selected>Air</option>";
					else  echo "<option value='Air'>Air</option>";
					if($claims_entry->APPROVED_BY == "Others") echo "<option value='Others' selected>Others</option>";
					else  echo "<option value='Others'>Others</option>";
					?>
				</select>
			</div> <!-- /form-input -->



			<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 form-input">
				<label for="month">Month <strong>*</strong></label>
				<select name="month" id="month" class="form-control edit-bulk-inputs" data-in="MONTH" data-get="<?php echo $ref;?>" required>
					<?php $months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
					foreach ($months as $m) {
						if(strtolower($m) == $claims_entry->MONTH) echo "<option value = '$m' selected>$m</option>";
						else echo "<option value = '$m'>$m</option>";
					}
					?>
				</select>
			</div> <!-- /form-input -->



			<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 form-input">
				<label for="year">Year <strong>*</strong></label>
				<select name="year" id="year" class="form-control edit-bulk-inputs" data-in="YEAR" data-get="<?php echo $ref;?>" required>
					<?php 
					for($i = date("Y") - 50; $i < date("Y") + 50; $i++){
						if($i == $claims_entry->YEAR)	echo "<option value='$i' selected>$i</option>";
						else echo "<option value='$i'>$i</option>";
					}
					?>
				</select>
			</div> <!-- /form-input -->
		</div> <!-- /entity-holder -->




		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive multiple-rows">
			<table>
				<thead>
					<tr class="header-row">
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
						<th>Remarks</th>
					</tr>
				</thead>

				<tbody class="entity-holder" data-entity="claims_parts" data-getin="KEY_ID">
					<?php foreach ($claims_parts as $part) { ?>
					<tr>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Part Number" data-in="PART_NUMBER" value="<?php echo $part->PART_NUMBER;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Part Name" data-in="PART_NAME" value="<?php echo $part->PART_NAME;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Color Code" data-in="COLOR_CODE" value="<?php echo $part->COLOR_CODE;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Color Name" data-in="COLOR_NAME" value="<?php echo $part->COLOR_NAME;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Quantity" data-in="QUANTITY" value="<?php echo $part->QUANTITY;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
						<td>
							<select class="select-input edit-bulk-inputs" data-in="UNIT" data-value="<?php echo $part->UNIT;?>" data-get="<?php echo $part->KEY_ID;?>">
								<option value="">Select Unit</option>
								<option value="KG">KG</option>
								<option value="Litter">Litter</option>
								<option value="Pair">Pair</option>
								<option value="Pieces">Pieces</option>
								<option value="Unit">Unit</option>
							</select>
						</td>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Box Number" data-in="BOX_NUMBER" value="<?php echo $part->BOX_NUMBER;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Case Number" data-in="CASE_NUMBER" value="<?php echo $part->CASE_NUMBER;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Reference Number" data-in="REFERENCE_NUMBER" value="<?php echo $part->REFERENCE_NUMBER;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Lot Number" data-in="LOT_NUMBER" value="<?php echo $part->LOT_NUMBER;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Claim Type" data-in="CLAIM_TYPE" value="<?php echo $part->CLAIM_TYPE;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Claim Code" data-in="CLAIM_CODE" value="<?php echo $part->CLAIM_CODE;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Action Code" data-in="ACTION_CODE" value="<?php echo $part->ACTION_CODE;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Process Code" data-in="PROCESS_NUMBER" value="<?php echo $part->PROCESS_CODE;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Details of Defect" data-in="DETAILS_OF_DEFECT" value="<?php echo $part->DETAILS_OF_DEFECT;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Defect Finding Way" data-in="DEFECT_FINDING_WAY" value="<?php echo $part->DEFECT_FINDING_WAY;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Remarks" data-in="REMARKS" value="<?php echo $part->REMARKS;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div> <!-- /form-input -->




		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-input">
			<button type="button" class="btn btn-primary update-records">Update</button>
			<?php echo "<span class='return-page hidden'>$base_url/views/display/ckd-claim-details.php?ref=$ref</span>";?>
			<a href="../display/ckd-claim-details.php?ref=<?php echo $ref;?>" class="btn btn-primary">Cancel</a>
		</div> <!-- /form-input -->
	</form>

</div> <!-- /content -->

</body>
</html>