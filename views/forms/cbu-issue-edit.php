<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;

if(isset($_GET['ref'])){
	$ref = htmlspecialchars($_GET['ref']);
	if($_SESSION['rancon_access_level'] <= 1){
		echo "<script>location.href='$base_url/views/display/cbu-issue-details.php?ref=$ref';</script>";
		die();
	}
	$cbu_issue =  fetch_functions\get_row('issues','KEY_ID', $ref)[0];
	$cbu_parts =  fetch_functions\get_row('issue_records','ENTRY_REFERENCE_ID', $ref);
}
else{
	echo "<h1 class='custom-data'>No data exists with this id. Please choose from valid id numbers</h1>";
	die();
}
?>


<div class="topbar">
    <div class="breadcrumbs">
        <span><i class="fa fa-list-ul"></i>Issues</span>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="../display/cbu-issue.php"><span>CBU Issue to Assembly</span></a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#"><span>CBU Issue to Assembly Edit</span></a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="form-title page-title">
		<span class="title">CBU Issue to Assembly Edit</span>
	</h1>	




	<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<div class="entity-holder" data-entity="issues" data-getin="KEY_ID">
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="requisition-number">Requisition Number</label>
				<input type="text" id="requisition-number" class="form-control edit-bulk-inputs" placeholder="Requisition Number" data-in="KEY_ID" data-get="<?php echo $ref;?>" value="<?php echo $cbu_issue->KEY_ID;?>" readonly>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="date">Date <strong>*</strong></label>
				<input type="text" id="date" class="form-control edit-bulk-inputs date-picker" placeholder="Date" data-in="ENTRY_DATE" data-get="<?php echo $ref;?>" value="<?php echo date('d-m-Y',strtotime($cbu_issue->ENTRY_DATE));?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="site">Site <strong>*</strong></label>
				<input type="text" id="site" class="form-control edit-bulk-inputs" placeholder="Site" data-in="SITE" data-get="<?php echo $ref;?>" value="<?php echo $cbu_issue->SITE;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="name">Requester Name <strong>*</strong></label>
				<input type="text" id="name" class="form-control edit-bulk-inputs" placeholder="Requester Name" data-in="NAME" data-get="<?php echo $ref;?>" value="<?php echo $cbu_issue->NAME;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="designation">Requester Designation <strong>*</strong></label>
				<input type="text" id="designation" class="form-control edit-bulk-inputs" placeholder="Requester Designation" data-in="DESIGNATION" data-get="<?php echo $ref;?>" value="<?php echo $cbu_issue->DESIGNATION;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="department">Requester Department <strong>*</strong></label>
				<input type="text" id="department" class="form-control edit-bulk-inputs" placeholder="Requester Department" data-in="DEPARTMENT" data-get="<?php echo $ref;?>" value="<?php echo $cbu_issue->DEPARTMENT;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="invoice-no">Invoice Number <strong>*</strong></label>
				<input type="text" id="invoice-no" class="form-control edit-bulk-inputs" placeholder="Invoice Number" data-in="INVOICE_NUMBER" data-get="<?php echo $ref;?>" value="<?php echo $cbu_issue->INVOICE_NUMBER;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="lc-no">LC Number <strong>*</strong></label>
				<input type="text" id="lc-no" class="form-control edit-bulk-inputs" placeholder="LC Number" data-in="LC_NUMBER" data-get="<?php echo $ref;?>" value="<?php echo $cbu_issue->LC_NUMBER;?>" required>
			</div> <!-- /form-input -->



			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="lot-no">Lot Number</label>
				<input type="text" id="lot-no" class="form-control edit-bulk-inputs" placeholder="Lot Number" data-in="LOT_NUMBER" data-get="<?php echo $ref;?>" value="<?php echo $cbu_issue->LOT_NUMBER;?>">
			</div> <!-- /form-input -->
		</div> <!-- entity-holder -->



		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive multiple-rows">
			<table>
				<thead>
					<tr class="header-row">
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

				<tbody class="entity-holder" data-entity="issue_records" data-getin="KEY_ID">
					<?php foreach ($cbu_parts as $part) { ?>
					<tr>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Part Number" data-in="PART_NUMBER" value="<?php echo $part->PART_NUMBER;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Part Name" data-in="PART_NAME" value="<?php echo $part->PART_NAME;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Model" data-in="MODEL" value="<?php echo $part->MODEL;?>" data-get="<?php echo $part->KEY_ID;?>">
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
							<input type="text" class="edit-bulk-inputs" placeholder="Frame Number" data-in="FRAME_NUMBER" value="<?php echo $part->FRAME_NUMBER;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
						<td>
							<input type="text" class="edit-bulk-inputs" placeholder="Engine Number" data-in="ENGINE_NUMBER" value="<?php echo $part->ENGINE_NUMBER;?>" data-get="<?php echo $part->KEY_ID;?>">
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
			<?php echo "<span class='return-page hidden'>$base_url/views/display/cbu-issue-details.php?ref=$ref</span>";?>
			<a href="../display/cbu-issue-details.php?ref=<?php echo $ref;?>" class="btn btn-primary">Cancel</a>
		</div> <!-- /form-input -->
	</form>

</div> <!-- /content -->

</body>
</html>