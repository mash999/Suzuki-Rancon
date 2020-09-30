<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;

if(isset($_GET['ref'])){
	$ref = htmlspecialchars($_GET['ref']);
	if($_SESSION['rancon_access_level'] <= 1){
		echo "<script>location.href='$base_url/views/display/purchase-requisitions-details.php?ref=$ref';</script>";
		die();
	}
	$purchase_entry =  fetch_functions\get_row('purchase_requisitions','KEY_ID', $ref)[0];
	$purchase_parts =  fetch_functions\get_row('purchase_requisitions_parts','ENTRY_REFERENCE_ID', $ref);
}
else{
	echo "<h1 class='custom-data'>No data exists with this id. Please choose from valid id numbers</h1>";
	die();
}
?>


<div class="topbar">
    <div class="breadcrumbs">
        <a href="../display/purchase-requisitions.php"><i class="fa fa-money"></i>Purchase Requisitions</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#">Purchase Requisitions Edit Form</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="form-title page-title">
		<span class="title">Purchase Requisitions Edit Form</span>
	</h1>	




	<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<div class="entity-holder" data-entity="purchase_requisitions" data-getin="KEY_ID">
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="date">Date <strong>*</strong></label>
				<input type="text" id="date" class="form-control edit-bulk-inputs date-picker" placeholder="Date" data-in="REQUISITION_DATE" data-get="<?php echo $ref;?>" value="<?php echo date('d-m-Y',strtotime($purchase_entry->REQUISITION_DATE));?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="site">Site <strong>*</strong></label>
				<input type="text" id="site" class="form-control edit-bulk-inputs" placeholder="Site" data-in="SITE" data-get="<?php echo $ref;?>" value="<?php echo $purchase_entry->SITE;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="requester-name">Requester Name <strong>*</strong></label>
				<input type="text" id="requester-name" class="form-control edit-bulk-inputs" placeholder="Requester Name" data-in="REQUESTER_NAME" data-get="<?php echo $ref;?>" value="<?php echo $purchase_entry->REQUESTER_NAME;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="requester-designation">Requester Designation <strong>*</strong></label>
				<input type="text" id="requester-designation" class="form-control edit-bulk-inputs" placeholder="Requester Designation" data-in="REQUESTER_DESIGNATION" data-get="<?php echo $ref;?>" value="<?php echo $purchase_entry->REQUESTER_DESIGNATION;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="requester-department">Requester Department <strong>*</strong></label>
				<input type="text" id="requester-department" class="form-control edit-bulk-inputs" placeholder="Requester Department" data-in="REQUESTER_DEPARTMENT" data-get="<?php echo $ref;?>" value="<?php echo $purchase_entry->REQUESTER_DEPARTMENT;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="approved-by">Approved By <strong>*</strong></label>
				<select id="approved-by" class="form-control edit-bulk-inputs" data-in="APPROVED_BY" data-get="<?php echo $ref;?>" required>
					<option value="">Approved By</option>
					<?php 
					if($purchase_entry->APPROVED_BY == 'Mr.Anowar')	echo "<option value='Mr.Anowar' selected>Mr.Anowar</option>";
					else echo "<option value='Mr.Anowar'>Mr.Anowar</option>";
					if($purchase_entry->APPROVED_BY == 'Mr.Fahim')	echo "<option value='Mr.Fahim' selected>Mr.Fahim</option>";
					else echo "<option value='Mr.Fahim'>Mr.Fahim</option>";
					?>
				</select>
			</div> <!-- /form-input -->



			
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="suppliers-code">Suppliers code <strong>*</strong></label>
				<select id="supplier-code" class="form-control edit-bulk-inputs" data-in="SUPPLIER_CODE" data-get="<?php echo $ref;?>" required>
					<option value="">Select Supplier Code</option>
					<?php 
					$s_codes = fetch_functions\get_rows('suppliers');
					foreach ($s_codes as $code) {
						if($code->SUPPLIER_CODE == $purchase_entry->SUPPLIER_CODE){
							$supplier_name = $code->SUPPLIER_NAME;
							$supplier_address = $con->SUPPLIER_ADDRESS;
							echo "<option value='$code->SUPPLIER_CODE' selected>$code->SUPPLIER_CODE</option>";
						}
						else{
							echo "<option value='$code->SUPPLIER_CODE'>$code->SUPPLIER_CODE</option>";
						}
					}
					?>
				</select>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input input-searchable">
				<label for="supplier-name">Supplier Name</label>
				<input type="text" id="supplier-name" class="form-control" placeholder="Supplier Name" value="<?php echo $supplier_name;?>">
				<ul class="search-result"></ul>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="supplier-address">Supplier Address</label>
				<input type="text" id="supplier-address" class="form-control" placeholder="Choose Supplier Code or Name" value="<?php echo $supplier_address;?>" readonly>
			</div> <!-- /form-input -->
		</div> <!-- /entity-holder -->




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
						<th>Remarks</th>
					</tr>
				</thead>

				<tbody class="entity-holder" data-entity="purchase_requisitions_parts" data-getin="KEY_ID">
					<?php foreach ($purchase_parts as $part) { ?>
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
							<input type="text" class="edit-bulk-inputs" placeholder="Remarks" data-in="REMARKS" value="<?php echo $part->REMARKS;?>" data-get="<?php echo $part->KEY_ID;?>">
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div> <!-- /form-input -->




		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-input">
			<button type="button" class="btn btn-primary update-records">Update</button>
			<?php echo "<span class='return-page hidden'>$base_url/views/display/purchase-requisitions-details.php?ref=$ref</span>";?>
			<a href="../display/purchase-requisitions-details.php?ref=<?php echo $ref;?>" class="btn btn-primary">Cancel</a>
		</div> <!-- /form-input -->
	</form>

</div> <!-- /content -->

</body>
</html>