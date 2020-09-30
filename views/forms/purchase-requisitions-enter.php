<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>


<div class="topbar">
    <div class="breadcrumbs">
        <a href="../display/purchase-requisitions.php"><i class="fa fa-money"></i>Purchase Requisitions</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#">Purchase Requisitions Form</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="form-title page-title">
		<span class="title">Purchase Requisitions Form</span>
	</h1>	




	<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="date">Date <strong>*</strong></label>
			<input type="text" id="date" class="form-control date-picker" name="date" placeholder="Date" value="<?php echo date('d-m-Y',time()+6*60*60);?>" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="site">Site <strong>*</strong></label>
			<input type="text" id="site" class="form-control" name="site" placeholder="Site" value="" required>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="requester-name">Requester Name <strong>*</strong></label>
			<input type="text" id="requester-name" class="form-control" name="requester-name" placeholder="Requester Name">
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="requester-designation">Requester Designation <strong>*</strong></label>
			<input type="text" id="requester-designation" class="form-control" name="requester-designation" placeholder="Requester Designation">
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="requester-department">Requester Department <strong>*</strong></label>
			<input type="text" id="requester-department" class="form-control" name="requester-department" placeholder="Requester Department">
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="approve-by">Approved By <strong>*</strong></label>
			<select id="approve-by" class="form-control" name="approve-by" required>
				<option value="">Approved By</option>
				<option value="Mr.Anowar">Mr.Anowar</option>
				<option value="Mr.Fahim">Mr.Fahim</option>
			</select>
		</div> <!-- /form-input -->



		
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="suppliers-code">Suppliers code <strong>*</strong></label>
			<select name="supplier-code" id="supplier-code" class="form-control" required>
				<option value="">Select Supplier Code</option>
				<?php 
				$s_codes = fetch_functions\get_rows('suppliers');
				foreach ($s_codes as $code) {
					echo "<option value='$code->SUPPLIER_CODE'>$code->SUPPLIER_CODE</option>";
				}
				?>
			</select>
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input input-searchable">
			<label for="supplier-name">Supplier Name</label>
			<input type="text" id="supplier-name" class="form-control" placeholder="Supplier Name">
			<ul class="search-result">
				
			</ul> <!-- /search-result -->
		</div> <!-- /form-input -->




		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="supplier-address">Supplier Address</label>
			<input type="text" id="supplier-address" class="form-control" placeholder="Choose Supplier Code or Name" readonly>
		</div> <!-- /form-input -->




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
						<td><input type="text" name="remarks[]" placeholder="Remarks" value=""></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div> <!-- /form-input -->




		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-input">
			<label for="excel-file" class="btn btn-primary">Upload Excel</label>
			<input type="file" id="excel-file" name="file" class="hidden">
			<button type="submit" class="btn btn-primary" name="save-purchase-requisitions">Save</button>
			<a href="../display/purchase-requisitions.php" class="btn btn-primary">Cancel</a>
			<br><br>
			<p id="file-name"></p>
		</div> <!-- /form-input -->
	</form>

</div> <!-- /content -->

</body>
</html>