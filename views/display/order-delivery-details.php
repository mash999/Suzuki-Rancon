<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
if(isset($_GET['ref']))	{
	$ref = htmlspecialchars($_GET['ref']);
	$stmt = $con->prepare("SELECT * FROM delivery WHERE KEY_ID = :KEY_ID");
	$stmt->execute(array('KEY_ID' => $ref));
	$row = $stmt->fetch(\PDO::FETCH_OBJ);
	if(!$row){
		echo "<script>window.location.href = 'order-delivery.php';</script>";
	}
}
else echo "<script>window.location.href = 'order-delivery.php';</script>";
?>


<div class="topbar">
    <div class="breadcrumbs">
        <a href="#"><i class="fa fa-check-square-o"></i>Order Delivery</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="order-delivery.php">Delivery Challan</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#">Delivery Challan Details</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="page-title">
		<span class="title">Delivery Challan Details</span>
		<span class="btn btn-primary btn-sm pull-right content-modal-trigger">
			<i class="fa fa-plus"></i>&nbsp; New Record
		</span>
		<?php if($_SESSION['rancon_access_level'] > 1){ ?>  
		<a href="<?php echo $base_url;?>/views/forms/order-delivery-edit.php?ref=<?php echo $ref;?>" class="btn btn-primary btn-sm pull-right">
			<i class="fa fa-pencil"></i>&nbsp; Edit Records
		</a>
		<?php } ?>
		<label for="search-input"><i class="fa fa-search"></i></label>
		<input type="text" id="search-input" class="form-control" placeholder="Search Order">
	</h1>




	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 detail-info no-padding">
		<h3>
			Delivery Challan Number - <?php echo $ref;?></span>
			<?php if($_SESSION['rancon_access_level'] > 1){ ?>  
			<a href="#" class="btn btn-primary btn-sm pull-right delete-records" data-type="normal" data-main="delivery" data-section="delivery_parts" data-ref="<?php echo $ref;?>" style="margin-left: 5px;">Delete</a> 
			<span data-key="<?php echo $ref;?>" data-section="delivery" class="btn btn-primary btn-sm pull-right activate-edit" style="margin-left: 5px;">Edit</span>
			<?php } ?>

			<button class="export-file btn btn-primary btn-sm pull-right" data-action="gate-pass" data-entries="delivery" data-parts = "delivery_parts" data-key="<?php echo $row->KEY_ID;?>" data-file-name = "gate_pass" style="margin-left: 5px;">Gate Pass</button>
			<button class="export-file btn btn-primary btn-sm pull-right" data-action="print" data-entries="delivery" data-parts = "delivery_parts" data-key="<?php echo $row->KEY_ID;?>" data-file-name = "delivery_challan" style="margin-left: 5px;">Print</button>
			<button class="export-file btn btn-primary btn-sm pull-right" data-action="export-excel" data-entries="delivery" data-parts = "delivery_parts" data-key="<?php echo $row->KEY_ID;?>" data-file-name = "delivery_challan" style="margin-left: 5px;">Get Excel</button>
		</h3>
		



		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 table-responsive info-table no-padding-left">
			<table class="table table-bordered">
				<tr>
					<th>Actual DO Date</th>
					<td>
						<span class="cell-value"><?php echo date("d-m-Y", strtotime($row->DO_DATE));?></span>
						<input type="text" class="edit-basic-input date-picker">
					</td>
				</tr>
				<tr>
					<th>Delivery Date</th>
					<td>
						<span class="cell-value"><?php echo date("d-m-Y", strtotime($row->DELIVERY_DATE));?></span>
						<input type="text" class="edit-basic-input date-picker">
					</td>
				</tr>
				<tr>
					<th>Site</th>
					<td>
						<span class="cell-value"><?php echo $row->SITE;?></span>
						<input type="text" class="edit-basic-input">
					</td>
				</tr>
				<tr>
					<th>Reference DO Number</th>
					<td>
						<span class="cell-value"><?php echo $row->REFERENCE_DO_NUMBER;?></span>
						<input type="text" class="edit-basic-input">
					</td>
				</tr>
				<tr>
					<th>Reference CO Number</th>
					<td>
						<span class="cell-value"><?php echo $row->REFERENCE_CO_NUMBER;?></span>
						<input type="text" class="edit-basic-input">
					</td>
				</tr>
				<tr>
					<th>Transport Name</th>
					<td>
						<span class="cell-value"><?php echo $row->TRANSPORT_NAME;?></span>
						<input type="text" class="edit-basic-input">
					</td>
				</tr>
				<tr>
					<th>Truck Number</th>
					<td>
						<span class="cell-value"><?php echo $row->TRUCK_NUMBER;?></span>
						<input type="text" class="edit-basic-input">
					</td>
				</tr>
			</table>
		</div> <!-- /form-input -->



		
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 table-responsive info-table no-padding-right">
			<table class="table table-bordered">
				<tr>
					<th>Driver Name</th>
					<td>
						<span class="cell-value"><?php echo $row->DRIVER_NAME;?></span>
						<input type="text" class="edit-basic-input">
					</td>
				</tr>
				<tr>
					<th>Driver Mobile Number</th>
					<td>
						<span class="cell-value"><?php echo $row->DRIVER_MOBILE_NUMBER;?></span>
						<input type="text" class="edit-basic-input">
					</td>
				</tr>
				<tr>
					<th>Sales Channel</th>
					<td>
						<span class="cell-value"><?php echo $row->SALES_CHANNEL;?></span>
						<select class="edit-basic-input" id="sales-channel">
							<option value="">Sales Channel</option>
							<option value="Dealer">Dealer</option>
							<option value="Corporate">Corporate</option>
							<option value="Retail">Retail</option>
							<option value="Others">Others</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>Customer Code</th>
					<td id="td-customer-code">
						<span class="cell-value"><?php echo $row->CUSTOMER_CODE;?></span>
						<select class="edit-basic-input" id="customer-code">
							<option value="">Customer Code</option>
							<?php 
							$c_code = fetch_functions\get_rows('customers');
							foreach ($c_code as $c) {
								echo "<option value='$c->CUSTOMER_ID'>$c->CUSTOMER_ID</option>";
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<?php $customer = fetch_functions\get_row('customers', 'CUSTOMER_ID', $row->CUSTOMER_CODE)[0];?>
					<th>Customer Name</th>
					<td id="td-customer-name" class="input-searchable">
						<span class="cell-value"><?php echo $customer->CUSTOMER_NAME;?></span>
						<input type="text" id="customer-name" class="edit-basic-input">
						<ul class="search-result inline-search-result">
							
						</ul>
					</td>
				</tr>
				<tr>
					<th>Customer Address</th>
					<td id="td-customer-address"><?php echo $customer->CUSTOMER_ADDRESS;?></td>
				</tr>
				<tr>
					<th>Customer Contact</th>
					<td id="td-customer-contact"><?php echo $customer->CUSTOMER_PHONE_OFFICE;?></td>
				</tr>
			</table>
		</div> <!-- /form-input -->
	</div> <!-- /col-lg-12 -->


	
	
	<div class="table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<table class="table table-bordered table-striped searchable-table editable-table">
			<thead>
				<tr>
					<th>Part Number</th>
					<th>Part Name</th>
					<th>Model</th>
					<th>Color Code</th>
					<th>Color Name</th>
					<th>Quantity</th>
					<th>Unit</th>
					<th>Frame Number</th>
					<th>Engine Number</th>
					<th>Key Ring Number</th>
					<th>Battery Number</th>
					<th>LC Number</th>
					<th>Invoice Number</th>
					<th>Remarks</th>
					<th class="fixed-width">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$stmt = $con->prepare("SELECT * FROM delivery_parts WHERE ENTRY_REFERENCE_ID = :ENTRY_REFERENCE_ID AND TYPE = :TYPE");
				$stmt->execute(array('ENTRY_REFERENCE_ID' => $ref, 'TYPE' => 'normal'));
				$rows = $stmt->fetchAll(\PDO::FETCH_OBJ);
				foreach ($rows as $r) {
					echo "
					<tr class='show-row'>
						<td>
							<span class = 'cell-value'>$r->PART_NUMBER</span>
							<input type='text' class='edit-input'>
						</td>

						<td>
							<span class = 'cell-value'>$r->PART_NAME</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->MODEL</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->COLOR_CODE</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->COLOR_NAME</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->QUANTITY</span>
							<input type='text' class='edit-input medium-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->UNIT</span>
							<select class='edit-input'>
								<option value=''>Select Unit</option>
								<option value='KG'>KG</option>
								<option value='Litter'>Litter</option>
								<option value='Pair'>Pair</option>
								<option value='Pieces'>Pieces</option>
								<option value='Unit'>Unit</option>
							</select>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->FRAME_NUMBER</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->ENGINE_NUMBER</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->KEY_RING_NUMBER</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->BATTERY_NUMBER</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->LC_NUMBER</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->INVOICE_NUMBER</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->REMARKS</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td class='fixed-width'>
							<a href='#' data-type='delivery' data-key='$r->KEY_ID' class='inline-edit btn btn-default'><i class='fa fa-pencil'></i></a>
							<a href='$base_url/functions/process-forms.php?del=execute&param=KEY_ID&target=$r->KEY_ID&entity=delivery_parts&page=display/order-delivery-details.php?ref=$ref' class='btn btn-default' onclick=\"return confirm('Are you sure that you want to delete this record?');\"><i class = 'fa fa-trash'></i></a>
						</td>
					</tr>";
				}
				?>
			</tbody>
		</table>
	</div> <!-- /table-responsive -->	
</div> <!-- /content -->









<div id="parts-modal" class="content-modal">
	<div class="content-fadeout"></div>
	<div class="content-modal-body">
		<h3>Enter Parts of This Delivery Order</h3>
		<h4>
			Delivery Challan Number  - <?php echo $ref;?>
		</h4>
		


		
		<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive multiple-rows no-padding">
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
							<th>Key Ring Number</th>
							<th>Battery Number</th>
							<th>LC Number</th>
							<th>Invoice Number</th>
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
							<td><input type="text" name="unit[]" placeholder="Unit" value=""></td>
							<td><input type="text" name="frame-number[]" placeholder="Frame Number" value=""></td>
							<td><input type="text" name="engine-number[]" placeholder="Engine Number" value=""></td>
							<td><input type="text" name="key-ring-number[]" placeholder="Key Ring Number" value=""></td>
							<td><input type="text" name="battery-number[]" placeholder="Battery Number" value=""></td>
							<td><input type="text" name="lc-number[]" placeholder="LC Number" value=""></td>
							<td><input type="text" name="invoice-number[]" placeholder="Invoice Number" value=""></td>
							<td><input type="text" name="remarks[]" placeholder="Remarks" value=""></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div> <!-- /mulitple-rows-->
		



			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-input no-padding">
				<input type="hidden" name="reference" value="<?php echo $ref;?>">
				<input type="hidden" name="delivery-type" value="normal">
				<label for="excel-file" class="btn btn-primary">Upload Excel</label>
				<input type="file" id="excel-file" name="file" class="hidden">
				<button type="submit" class="btn btn-primary" name="save-more-delivery">Save</button>
				<br><br>
				<p id="file-name"></p>
			</div> <!-- /form-input -->
			<br><br><br>
		</form>
	</div> <!-- /content-modal-body -->
</div> <!-- /parts-modal -->

</body>
</html>