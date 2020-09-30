<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;

if(isset($_GET['ref'])){
	$ref = htmlspecialchars($_GET['ref']);
	if($_SESSION['rancon_access_level'] <= 1){
		echo "<script>location.href='$base_url/views/display/ckdbom-details.php?ref=$ref';</script>";
		die();
	}
	$ckdbom_entry =  fetch_functions\get_row('entries','KEY_ID', $ref)[0];
	$ckdbom_parts =  fetch_functions\get_row('parts','ENTRY_REFERENCE_ID', $ref);
}
else{
	echo "<h1 class='custom-data'>No data exists with this id. Please choose from valid id numbers</h1>";
	die();
}
?>


<div class="topbar">
    <div class="breadcrumbs">
        <span><i class="fa fa-pencil-square-o"></i>Enter</span>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="../display/ckdbom.php"><span>CKD By BOM Parts Enter</span></a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#"><span>CKD By BOM Parts Edit Form</span></a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="form-title page-title">
		<span class="title">CKD By BOM Parts Edit Form</span>
	</h1>	




	<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<div class="entity-holder" data-entity="entries" data-getin="KEY_ID">
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="requisition-number">Requisition Number</label>
				<input type="text" id="requisition-number" class="form-control edit-bulk-inputs" placeholder="Requisition Number" data-in="REQUISITION_NUMBER" data-get="<?php echo $ref;?>" value="<?php echo $ckdbom_entry->REQUISITION_NUMBER;?>">
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="date">Date <strong>*</strong></label>
				<input type="text" id="date" class="form-control edit-bulk-inputs date-picker" placeholder="Date" data-in="ENTRY_DATE" data-get="<?php echo $ref;?>" value="<?php echo date('d-m-Y',strtotime($ckdbom_entry->ENTRY_DATE));?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="site">Site <strong>*</strong></label>
				<input type="text" id="site" class="form-control" placeholder="Site" data-in="SITE" data-get="<?php echo $ref;?>" value="<?php echo $ckdbom_entry->SITE;?>" required>
			</div> <!-- /form-input -->



			
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="suppliers-code">Suppliers code <strong>*</strong></label>
				<select id="supplier-code" class="form-control edit-bulk-inputs" data-in="SUPPLIER_CODE" data-get="<?php echo $ref;?>" required>
					<option value="">Select Supplier Code</option>
					<?php 
					$s_codes = fetch_functions\get_rows('suppliers');
					foreach ($s_codes as $code) {
						if($code->SUPPLIER_CODE == $ckdbom_entry->SUPPLIER_CODE){
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
				<label for="invoice-no">Invoice Number <strong>*</strong></label>
				<input type="text" id="invoice-no" class="form-control edit-bulk-inputs" placeholder="Invoice Number" data-in="INVOICE_NUMBER" data-get="<?php echo $ref;?>" value="<?php echo $ckdbom_entry->INVOICE_NUMBER;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="lc-no">LC Number <strong>*</strong></label>
				<input type="text" id="lc-no" class="form-control edit-bulk-inputs" placeholder="LC Number" data-in="LC_NUMBER" data-get="<?php echo $ref;?>" value="<?php echo $ckdbom_entry->LC_NUMBER;?>" required>
			</div> <!-- /form-input -->



			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="lot-no">Lot Number</label>
				<input type="text" id="lot-no" class="form-control edit-bulk-inputs" placeholder="Lot Number" data-in="LOT_NUMBER" data-get="<?php echo $ref;?>" value="<?php echo $ckdbom_entry->LOT_NUMBER;?>">
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
						<th>Frame Number</th>
						<th>Engine Number</th>
						<th>Remarks</th>
					</tr>
				</thead>

				<tbody class="entity-holder" data-entity="parts" data-getin="KEY_ID">
					<?php foreach ($ckdbom_parts as $part) { ?>
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
			<?php echo "<span class='return-page hidden'>$base_url/views/display/ckdbom-details.php?ref=$ref</span>";?>
			<a href="../display/ckdbom-details.php?ref=<?php echo $ref;?>" class="btn btn-primary">Cancel</a>
		</div> <!-- /form-input -->
	</form>

</div> <!-- /content -->

</body>
</html>