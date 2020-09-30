<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;

if(isset($_GET['ref'])){
	$ref = htmlspecialchars($_GET['ref']);
	if($_SESSION['rancon_access_level'] <= 1){
		echo "<script>location.href='$base_url/views/display/additional-parts-details.php?ref=$ref';</script>";
		die();
	}
	$additional_entry =  fetch_functions\get_row('entries','KEY_ID', $ref)[0];
	$additional_parts =  fetch_functions\get_row('parts','ENTRY_REFERENCE_ID', $ref);
}
else{
	echo "<h1 class='custom-data'>No data exists with this id. Please choose from valid id numbers</h1>";
	die();
}
?>


<div class="topbar">
    <div class="breadcrumbs">
        <i class="fa fa-bars visible-xs menu-bar" aria-hidden="true"></i>
        <a href="#"><i class="fa fa-pencil-square-o"></i>Enter</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="../display/additional-parts.php">Additional Parts Enter</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#">Additional Parts Edit Form</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="form-title page-title">
		<span class="title">Additional Parts Edit Form</span>
	</h1>	




	<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<div class="entity-holder" data-entity="entries" data-getin="KEY_ID">
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="requisition-number">Purchase Requisition Number</label>
				<select name="requisition-number" class="form-control edit-bulk-inputs" data-in="REQUISITION_NUMBER" data-get="<?php echo $ref;?>">
					<option value="">Select Requisition Number</option>
					<?php 
					$req_numbers = fetch_functions\get_rows('purchase_requisitions');
					foreach ($req_numbers as $r) {
						if($additional_entry->REQUISITION_NUMBER == $r->KEY_ID)
							echo "<option value='$r->KEY_ID' selected>$r->KEY_ID</option>";
						else
							echo "<option value='$r->KEY_ID'>$r->KEY_ID</option>";
					}
					?>
				</select>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="date">Date <strong>*</strong></label>
				<input type="text" id="date" class="form-control edit-bulk-inputs date-picker" placeholder="Date" data-in="ENTRY_DATE" data-get="<?php echo $ref;?>" value="<?php echo date('d-m-Y',strtotime($additional_entry->ENTRY_DATE));?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="site">Site <strong>*</strong></label>
				<input type="text" id="site" class="form-control edit-bulk-inputs" placeholder="Site" data-in="SITE" data-get="<?php echo $ref;?>" value="<?php echo $additional_entry->SITE;?>" required>
			</div> <!-- /form-input -->



			
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="suppliers-code">Suppliers code <strong>*</strong></label>
				<select id="supplier-code" class="form-control edit-bulk-inputs" data-in="SUPPLIER_CODE" data-get="<?php echo $ref;?>" required>
					<option value="">Select Supplier Code</option>
					<?php 
					$s_codes = fetch_functions\get_rows('suppliers');
					foreach ($s_codes as $code) {
						if($code->SUPPLIER_CODE == $additional_entry->SUPPLIER_CODE){
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



			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="challan-no">Supplier Challan Number <strong>*</strong></label>
				<input type="text" id="challan-no" class="form-control edit-bulk-inputs" placeholder="Supplier Challan Number" data-in="SUPPLIER_CHALLAN_NUMBER" data-get="<?php echo $ref;?>" value="<?php echo $additional_entry->SUPPLIER_CHALLAN_NUMBER;?>" required>
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

				<tbody class="entity-holder" data-entity="parts" data-getin="KEY_ID">
					<?php foreach ($additional_parts as $part) { ?>
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
			<?php echo "<span class='return-page hidden'>$base_url/views/display/additional-parts-details.php?ref=$ref</span>";?>
			<a href="../display/additional-parts-details.php?ref=<?php echo $ref;?>" class="btn btn-primary">Cancel</a>
		</div> <!-- /form-input -->
	</form>

</div> <!-- /content -->

</body>
</html>	