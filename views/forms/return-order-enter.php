<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>


<div class="topbar">
    <div class="breadcrumbs">
        <i class="fa fa-bars visible-xs menu-bar" aria-hidden="true"></i>
        <a href="../display/return-order.php"><i class="fa fa-refresh"></i>Return in Order</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#">Return in Order Form</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="form-title page-title">
		<span class="title">Return in Order Form</span>
	</h1>	




	<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="delivery-challan-number">Delivery Challan Number <strong>*</strong></label>
			<select name="delivery-challan-number" id="delivery-challan-number" class="form-control" required>
				<option value="">Delivery Number</option>
				<?php 
				$delivery_number = fetch_functions\get_rows('delivery');
				foreach ($delivery_number as $d_number) {
					echo "<option value='$d_number->KEY_ID'>$d_number->KEY_ID</option>";
				}
				?>
			</select>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="date">Date <strong>*</strong></label>
			<input type="text" id="date" class="form-control date-picker" name="date" placeholder="Date" value="<?php echo date('d-m-Y',time());?>" required>
		</div> <!-- /form-input -->



		
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="sales-channel">Sales Channel <strong>*</strong></label>
			<select name="sales-channel" id="sales-channel" class="form-control" required>
				<option value="">Select Sales Channel</option>
				<option value="Dealer">Dealer</option>
				<option value="Corporate">Corporate</option>
				<option value="Retail">Retail</option>
				<option value="Others">Others</option>
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
				<select name="customer-code" id="customer-code" class="form-control" required>
					<option value="">Select Customer Code</option>
					<?php 
					$c_codes = fetch_functions\get_rows('customers');
					foreach ($c_codes as $code) {
						echo "<option value='$code->CUSTOMER_ID'>$code->CUSTOMER_ID</option>";
					}
					?>
				</select>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input input-searchable">
				<label for="customer-name">Customer Name</label>
				<input type="text" id="customer-name" class="form-control" placeholder="Customer Name">
				<ul class="search-result">
					
				</ul> <!-- /search-result -->
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="customer-address">Customer Address</label>
				<input type="text" id="customer-address" class="form-control" placeholder="Choose Customer Code or Name" readonly>
			</div> <!-- /form-input -->




			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
				<label for="customer-contact">Customer Contact</label>
				<input type="text" id="customer-contact" class="form-control" placeholder="Choose Customer Code or Name" readonly>
			</div> <!-- /form-input -->
		</div> <!-- /return-to-customer -->




		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive multiple-rows">
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
						<th>Return Reason</th>
						<th>Remarks</th>
					</tr>
				</thead>

				<tbody id="challan-parts">
					<?php for($i=0; $i<5; $i++) { ?>
					<tr>
						<td class="delete"><i class="fa fa-trash trash-it"></i></td>
						<td><input type="text" name="part-number[]" placeholder="Part Number" value=""></td>
						<td><input type="text" name="part-name[]" placeholder="Part Name" value=""></td>
						<td><input type="text" name="model[]" placeholder="Model" value=""></td>
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
						<td><input type="text" name="return-reason[]" placeholder="Return Reason" value=""></td>
						<td><input type="text" name="remarks[]" placeholder="Remarks" value=""></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div> <!-- /form-input -->




		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-input">
			<label for="excel-file" class="btn btn-primary">Upload Excel</label>
			<input type="file" id="excel-file" name="file" class="hidden">
			<button type="submit" class="btn btn-primary" name="save-return-order">Save</button>
			<a href="../display/return-order.php" class="btn btn-primary">Cancel</a>
			<br><br>
			<p id="file-name"></p>
		</div> <!-- /form-input -->
	</form>

</div> <!-- /content -->

</body>
</html>	