<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;

if(isset($_GET['ref'])){
	$ref = htmlspecialchars($_GET['ref']);
	if($_SESSION['rancon_access_level'] <= 1){
		echo "<script>location.href='$base_url/views/display/backup-order-delivery-details.php?ref=$ref';</script>";
		die();
	}
	$delivery_entry =  fetch_functions\get_row('backup_delivery','KEY_ID', $ref)[0];
	$stmt = $con->prepare("SELECT * FROM delivery_parts WHERE ENTRY_REFERENCE_ID = :ENTRY_REFERENCE_ID AND TYPE = :TYPE");
	$stmt->execute(array('ENTRY_REFERENCE_ID' => $ref, 'TYPE' => 'backup'));
	$delivery_parts =  $stmt->fetchAll(\PDO::FETCH_OBJ);
}
else{
	echo "<h1 class='custom-data'>No data exists with this id. Please choose from valid id numbers</h1>";
	die();
}
?>


<div class="topbar">
    <div class="breadcrumbs">
        <a href="#"><i class="fa fa-check-square-o"></i>Order Delivery</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="../display/backup-order-delivery.php">Backup Parts Delivery</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#">Backup Parts Delivery Edit</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="form-title page-title">
		<span class="title">Backup Parts Delivery Edit</span>
	</h1>	




	<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<div class="entity-holder" data-entity="backup_delivery" data-getin="KEY_ID">
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="date">Date <strong>*</strong></label>
				<input type="text" id="date" class="form-control edit-bulk-inputs date-picker" placeholder="Date" data-in="DELIVERY_DATE" data-get="<?php echo $ref;?>" value="<?php echo date('d-m-Y',strtotime($delivery_entry->DELIVERY_DATE));?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="site">Site <strong>*</strong></label>
				<input type="text" id="site" class="form-control edit-bulk-inputs" placeholder="Site" data-in="SITE" data-get="<?php echo $ref;?>" value="<?php echo $delivery_entry->SITE;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="requester-name">Requester Name <strong>*</strong></label>
				<input type="text" id="requester-name" class="form-control edit-bulk-inputs" placeholder="Requester Name" data-in="REQUESTER_NAME" data-get="<?php echo $ref;?>" value="<?php echo $delivery_entry->REQUESTER_NAME;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="requester-designation">Requester Designation <strong>*</strong></label>
				<input type="text" id="requester-designation" class="form-control edit-bulk-inputs" placeholder="Requester Designation" data-in="REQUESTER_DESIGNATION" data-get="<?php echo $ref;?>" value="<?php echo $delivery_entry->REQUESTER_DESIGNATION;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="requester-department">Requester Department <strong>*</strong></label>
				<input type="text" id="requester-department" class="form-control edit-bulk-inputs" placeholder="Requester Department" data-in="REQUESTER_DEPARTMENT" data-get="<?php echo $ref;?>" value="<?php echo $delivery_entry->REQUESTER_DEPARTMENT;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="requisition-number">Requisition Number <strong>*</strong></label>
				<input type="text" id="requisition-number" class="form-control edit-bulk-inputs" placeholder="Requisition Number" data-in="REQUISITION_NUMBER" data-get="<?php echo $ref;?>" value="<?php echo $delivery_entry->REQUISITION_NUMBER;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="reference-number">Reference Number <strong>*</strong></label>
				<input type="text" id="reference-number" class="form-control edit-bulk-inputs" placeholder="Reference Number" data-in="REFERENCE_NUMBER" data-get="<?php echo $ref;?>" value="<?php echo $delivery_entry->REFERENCE_NUMBER;?>" required>
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

				<tbody class="entity-holder" data-entity="delivery_parts" data-getin="KEY_ID">
					<?php foreach ($delivery_parts as $part) { ?>
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
							<input type="text" class="edit-bulk-inputs" placeholder="Remarks" data-in="REMARKS" data-get="<?php echo $part->KEY_ID;?>" value="<?php echo $part->REMARKS;?>">
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div> <!-- /form-input -->




		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-input">
			<button type="button" class="btn btn-primary update-records">Update</button>
			<?php echo "<span class='return-page hidden'>$base_url/views/display/backup-order-delivery-details.php?ref=$ref</span>";?>
			<a href="../display/backup-order-delivery-details.php?ref=<?php echo $ref;?>" class="btn btn-primary">Cancel</a>
		</div> <!-- /form-input -->
	</form>

</div> <!-- /content -->

</body>
</html>