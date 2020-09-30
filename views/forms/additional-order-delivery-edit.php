<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;

if(isset($_GET['ref'])){
	$ref = htmlspecialchars($_GET['ref']);
	if($_SESSION['rancon_access_level'] <= 1){
		echo "<script>location.href='$base_url/views/display/additional-order-delivery-details.php?ref=$ref';</script>";
		die();
	}
	$delivery_entry =  fetch_functions\get_row('additional_delivery','KEY_ID', $ref)[0];
	$stmt = $con->prepare("SELECT * FROM delivery_parts WHERE ENTRY_REFERENCE_ID = :ENTRY_REFERENCE_ID AND TYPE = :TYPE");
	$stmt->execute(array('ENTRY_REFERENCE_ID' => $ref, 'TYPE' => 'additional'));
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
        <a href="../display/additional-order-delivery.php">Additional Parts Delivery</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#">Additional Parts Delivery Edit</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="form-title page-title">
		<span class="title">Additional Parts Delivery Edit</span>
	</h1>	




	<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<div class="entity-holder" data-entity="additional_delivery" data-getin="KEY_ID">
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="actual-do-date">Actual Do Date <strong>*</strong></label>
				<input type="text" id="actual-do-date" class="form-control edit-bulk-inputs date-picker" placeholder="Actual Do Date" data-in="DO_DATE" data-get="<?php echo $ref;?>" value="<?php echo date('d-m-Y',strtotime($delivery_entry->DO_DATE));?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="delivery-date">Delivery Date <strong>*</strong></label>
				<input type="text" id="delivery-date" class="form-control edit-bulk-inputs date-picker" placeholder="Delivery Date" data-in="DELIVERY_DATE" data-get="<?php echo $ref;?>" value="<?php echo date('d-m-Y',strtotime($delivery_entry->DELIVERY_DATE));?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="site">Site <strong>*</strong></label>
				<input type="text" id="site" class="form-control edit-bulk-inputs" placeholder="Site" data-in="SITE" data-get="<?php echo $ref;?>" value="<?php echo $delivery_entry->SITE;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="reference-do-no">Reference Do No <strong>*</strong></label>
				<input type="text" id="reference-do-no" class="form-control edit-bulk-inputs" placeholder="Reference Do No" data-in="REFERENCE_DO_NUMBER" data-get="<?php echo $ref;?>" value="<?php echo $delivery_entry->REFERENCE_DO_NUMBER;?>" required>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="reference-co-no">Reference Co No <strong>*</strong></label>
				<input type="text" id="reference-co-no" class="form-control edit-bulk-inputs" placeholder="Reference Co No" data-in="REFERENCE_CO_NUMBER" data-get="<?php echo $ref;?>" value="<?php echo $delivery_entry->REFERENCE_CO_NUMBER;?>" required>
			</div> <!-- /form-input -->



			
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="customer-code">Customer code <strong>*</strong></label>
				<select id="customer-code" class="form-control edit-bulk-inputs" data-in="CUSTOMER_ID" data-get="<?php echo $ref;?>" required>
					<option value="">Select Customer Code</option>
					<?php 
					$c_codes = fetch_functions\get_rows('customers');
					foreach ($c_codes as $code) {
						if($code->CUSTOMER_ID == $delivery_entry->CUSTOMER_CODE){
							$customer_name = $code->CUSTOMER_NAME;
							$customer_address = $code->CUSTOMER_ADDRESS;
							$customer_contact = $code->CUSTOMER_PHONE_OFFICE;
							echo "<option value='$code->CUSTOMER_ID' selected>$code->CUSTOMER_ID</option>";
						}
						else{
							echo "<option value='$code->CUSTOMER_CODE'>$code->CUSTOMER_ID</option>";
						}
					}
					?>
				</select>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input input-searchable">
				<label for="customer-name">Customer Name</label>
				<input type="text" id="customer-name" class="form-control" placeholder="Customer Name" value="<?php echo $customer_name;?>">
				<ul class="search-result"></ul>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="customer-address">Customer Address</label>
				<input type="text" id="customer-address" class="form-control" placeholder="Choose Customer Code or Name" value="<?php echo $customer_address;?>" readonly>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="customer-contact">Customer Contact</label>
				<input type="text" id="customer-contact" class="form-control" placeholder="Choose Customer Code" value="<?php echo $customer_contact;?>" readonly>
			</div> <!-- /form-input -->



			
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="sales-channel">Sales Channel <strong>*</strong></label>
				<select name="sales-channel" id="sales-channel" class="form-control edit-bulk-inputs" data-in="SALES_CHANNEL" data-get="<?php echo $ref;?>" required>
					<option value="">Select Sales Channel</option>
					<?php 
					if($delivery_entry->SALES_CHANNEL == "Dealer") echo "<option value='Dealer' selected>Dealer</option>";
					else echo "<option value='Dealer'>Dealer</option>";
					if($delivery_entry->SALES_CHANNEL == "Corporate") echo "<option value='Corporate' selected>Corporate</option>";
					else echo "<option value='Corporate'>Corporate</option>";
					if($delivery_entry->SALES_CHANNEL == "Retail") echo "<option value='Retail' selected>Retail</option>";
					else echo "<option value='Retail'>Retail</option>";
					if($delivery_entry->SALES_CHANNEL == "Others") echo "<option value='Others' selected>Others</option>";
					else echo "<option value='Others'>Others</option>";
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
			<?php echo "<span class='return-page hidden'>$base_url/views/display/additional-order-delivery-details.php?ref=$ref</span>";?>
			<a href="../display/additional-order-delivery-details.php?ref=<?php echo $ref;?>" class="btn btn-primary">Cancel</a>
		</div> <!-- /form-input -->
	</form>

</div> <!-- /content -->

</body>
</html>