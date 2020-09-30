<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;

if(isset($_GET['ref'])){
	$ref = htmlspecialchars($_GET['ref']);
	if($_SESSION['rancon_access_level'] <= 1){
		echo "<script>location.href='$base_url/views/display/return-order-details.php?ref=$ref';</script>";
		die();
	}
	$return_entry =  fetch_functions\get_row('return_order','KEY_ID', $ref)[0];
	$return_parts =  fetch_functions\get_row('returned_parts','ENTRY_REFERENCE_ID', $ref);
}
else{
	echo "<h1 class='custom-data'>No data exists with this id. Please choose from valid id numbers</h1>";
	die();
}
?>


<div class="topbar">
    <div class="breadcrumbs">
        <i class="fa fa-bars visible-xs menu-bar" aria-hidden="true"></i>
        <a href="../display/return-order.php"><i class="fa fa-refresh"></i>Return in Order</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#">Return in Order Edit Form</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="form-title page-title">
		<span class="title">Return in Order Edit Form</span>
	</h1>	




	<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<div class="entity-holder" data-entity="return_order" data-getin="KEY_ID">
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="delivery-challan-number">Delivery Challan Number <strong>*</strong></label>
				<select name="delivery-challan-number" id="delivery-challan-number" class="form-control" required>
					<option value="">Delivery Number</option>
					<?php 
					$delivery_number = fetch_functions\get_rows('delivery');
					foreach ($delivery_number as $d_number) {
						if($d_number->DELIVERY_CHALLAN_NUMBER == $return_entry->DELIVERY_CHALLAN_NUMBER){
							echo "<option value='$d_number->KEY_ID' selected>$d_number->KEY_ID</option>";
						}
						else{
							echo "<option value='$d_number->KEY_ID'>$d_number->KEY_ID</option>";
						}
					}
					?>
				</select>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="date">Date <strong>*</strong></label>
				<input type="text" id="date" class="form-control edit-bulk-inputs date-picker" placeholder="Date" data-in="RETURN_DATE" data-get="<?php echo $ref;?>" value="<?php echo date('d-m-Y',strtotime($return_entry->RETURN_DATE));?>" required>
			</div> <!-- /form-input -->



			
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="sales-channel">Sales Channel <strong>*</strong></label>
				<select name="sales-channel" id="sales-channel" class="form-control edit-bulk-inputs" data-in="SALES_CHANNEL" data-get="<?php echo $ref;?>" required>
					<option value="">Select Sales Channel</option>
					<?php 
					if($return_entry->SALES_CHANNEL == "Dealer") echo "<option value='Dealer' selected>Dealer</option>";
					else echo "<option value='Dealer'>Dealer</option>";
					if($return_entry->SALES_CHANNEL == "Corporate") echo "<option value='Corporate' selected>Corporate</option>";
					else echo "<option value='Corporate'>Corporate</option>";
					if($return_entry->SALES_CHANNEL == "Retail") echo "<option value='Retail' selected>Retail</option>";
					else echo "<option value='Retail'>Retail</option>";
					if($return_entry->SALES_CHANNEL == "Others") echo "<option value='Others' selected>Others</option>";
					else echo "<option value='Others'>Others</option>";
					?>
				</select>
			</div> <!-- /form-input -->



			
			<!-- <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="returned-to">Returned To <strong>*</strong></label>
				<select name="returned-to" id="returned-to" class="form-control" required>
					<option value="Supplier">Supplier</option>
					<option value="Customer">Customer</option>
				</select>
			</div> --> <!-- /form-input -->



			
	<!-- 		<div id="return-to-supplier">
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
					<label for="suppliers-code">Suppliers code <strong>*</strong></label>
					<select name="supplier-code" id="supplier-code" class="form-control" required>
						<option value="">Select Supplier Code</option>
						<?php 
						// $s_codes = fetch_functions\get_rows('suppliers');
						// foreach ($s_codes as $code) {
						// 	echo "<option value='$code->SUPPLIER_CODE'>$code->SUPPLIER_CODE</option>";
						// }
						?>
					</select>
				</div> 




				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input input-searchable">
					<label for="supplier-name">Supplier Name</label>
					<input type="text" id="supplier-name" class="form-control" placeholder="Supplier Name">
					<ul class="search-result">
						
					</ul> 
				</div> 




				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
					<label for="supplier-address">Supplier Address</label>
					<input type="text" id="supplier-address" class="form-control" placeholder="Choose Supplier Code or Name" readonly>
				</div> 




				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
					<label for="supplier-contact">Supplier Contact</label>
					<input type="text" id="supplier-contact" class="form-control" placeholder="Choose Supplier Code or Name" readonly>
				</div> 
			</div>  -->



			
			<div id="return-to-customer">		
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
					<label for="customer-code">Customer code <strong>*</strong></label>
					<select id="customer-code" class="form-control edit-bulk-inputs" data-in="CUSTOMER_CODE" data-get="<?php echo $ref;?>" required>
						<option value="">Select Customer Code</option>
						<?php 
						$c_codes = fetch_functions\get_rows('customers');
						foreach ($c_codes as $code) {
							if($code->CUSTOMER_ID == $return_entry->CUSTOMER_CODE){
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
			</div> <!-- /return-to-customer -->
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
						<th>Return Reason</th>
						<th>Remarks</th>
					</tr>
				</thead>

				<tbody id="challan-parts" class="entity-holder" data-entity="returned_parts" data-getin="KEY_ID">
					<?php foreach ($return_parts as $part) { ?>
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
							<input type="text" class="edit-bulk-inputs" placeholder="Return Reason" data-in="RETURN_REASON" value="<?php echo $part->RETURN_REASON;?>" data-get="<?php echo $part->KEY_ID;?>">
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
			<?php echo "<span class='return-page hidden'>$base_url/views/display/return-order-details.php?ref=$ref</span>";?>
			<a href="../display/return-order-details.php?ref=<?php echo $ref;?>" class="btn btn-primary">Cancel</a>
		</div> <!-- /form-input -->
	</form>

</div> <!-- /content -->

</body>
</html>	