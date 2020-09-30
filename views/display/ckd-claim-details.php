<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
if(isset($_GET['ref']))	{
	$ref = htmlspecialchars($_GET['ref']);
	$stmt = $con->prepare("SELECT * FROM claims WHERE KEY_ID = :KEY_ID AND TYPE = :TYPE");
	$stmt->execute(array('KEY_ID' => $ref, 'TYPE' => 'ckd-claim'));
	$row = $stmt->fetch(\PDO::FETCH_OBJ);
	if(!$row){
		echo "<script>window.location.href = 'ckd-claim.php';</script>";
	}
}
else echo "<script>window.location.href = 'ckd-claim.php';</script>";
?>


<div class="topbar">
    <div class="breadcrumbs">
        <a href="#"><i class="fa fa-chain-broken"></i>Claims</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="ckd-claim.php"><span>Claims for CKD</span></a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#"><span>Claims for CKD Details</span></a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="page-title">
		<span class="title">Claims for CKD Details</span>
		<span class="btn btn-primary btn-sm pull-right content-modal-trigger">
			<i class="fa fa-plus"></i>&nbsp; New Record
		</span>
		<?php if($_SESSION['rancon_access_level'] > 1){ ?>  
		<a href="<?php echo $base_url;?>/views/forms/ckd-claim-edit.php?ref=<?php echo $ref;?>" class="btn btn-primary btn-sm pull-right">
			<i class="fa fa-pencil"></i>&nbsp; Edit Records
		</a>
		<?php } ?>
		<label for="search-input"><i class="fa fa-search"></i></label>
		<input type="text" id="search-input" class="form-control" placeholder="Search Part Details">
	</h1>




	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 detail-info no-padding">
		<h3>
			Invoice Number - <span class="key-point"><?php echo $row->INVOICE_NUMBER;?></span>
			<?php if($_SESSION['rancon_access_level'] > 1){ ?>  
			<a href="#" class="btn btn-primary btn-sm pull-right delete-records" data-main="claims" data-section="claims_parts" data-ref="<?php echo $ref;?>" style="margin-left: 5px;">Delete</a> 
			<span data-key="<?php echo $ref;?>" data-section="claims" class="btn btn-primary btn-sm pull-right activate-edit" style="margin-left: 5px;">Edit</span>
			<?php } ?>

			<button class="export-file btn btn-primary btn-sm pull-right" data-action="print" data-entries="claims" data-parts = "claims_parts" data-key="<?php echo $row->KEY_ID;?>" data-file-name = "ckd_claims" style="margin-left: 5px;">Print</button>
			<button class="export-file btn btn-primary btn-sm pull-right" data-action="export-excel" data-entries="claims" data-parts = "claims_parts" data-key="<?php echo $row->KEY_ID;?>" data-file-name = "ckd_claims">Get Excel</button>
		</h3>
		



		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 table-responsive info-table no-padding-left">
			<table class="table table-bordered">
				<tr>
					<th>Invoice Number</th>
					<td>
						<span class="cell-value cell-key-point"><?php echo $row->INVOICE_NUMBER;?></span>
						<input type="text" class="edit-basic-input">
					</td>
				</tr>
				<tr>
					<th>Claim Issue Date</th>
					<td>
						<span class="cell-value"><?php echo date("d-m-Y", strtotime($row->CLAIM_ISSUE_DATE));?></span>
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
					<th>Created By</th>
					<td>
						<span class="cell-value"><?php echo $row->CREATED_BY;?></span>
						<input type="text" class="edit-basic-input">
					</td>
				</tr>
				<tr>
					<th>Approved By</th>
					<td>
						<span class="cell-value"><?php echo $row->APPROVED_BY;?></span>
						<input type="text" class="edit-basic-input">
					</td>
				</tr>
				<tr>
					<th>Shipping Mode</th>
					<td>
						<span class="cell-value"><?php echo $row->SHIPPING_MODE;?></span>
						<select class="edit-basic-input">
							<option value="Air">Air</option>
							<option value="Others">Others</option>
						</select>
					</td>
				</tr>
			</table>
		</div> <!-- /form-input -->



		
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 table-responsive info-table no-padding-right">
			<table class="table table-bordered">
				<tr>
					<th>Model</th>
					<td>
						<span class="cell-value"><?php echo $row->MODEL;?></span>
						<input type="text" class="edit-basic-input">
					</td>
				</tr>
				<tr>
					<th>APD Number</th>
					<td>
						<span class="cell-value"><?php echo $row->APD_NUMBER;?></span>
						<input type="text" class="edit-basic-input">
					</td>
				</tr>
				<tr>
					<th>PPD Number</th>
					<td>
						<span class="cell-value"><?php echo $row->PPD_NUMBER;?></span>
						<input type="text" class="edit-basic-input">
					</td>
				</tr>
				<tr>
					<th>Claim Reference Number</th>
					<td>
						<span class="cell-value"><?php echo $row->CLAIM_REFERENCE_NUMBER;?></span>
						<input type="text" class="edit-basic-input">
					</td>
				</tr>
				<tr>
					<th>LC Number</th>
					<td>
						<span class="cell-value"><?php echo $row->LC_NUMBER;?></span>
						<input type="text" class="edit-basic-input">
					</td>
				</tr>
				<tr>
					<th>Month - Year</th>
					<td>
						<span class="cell-value"><?php echo $row->MONTH;?></span>
						<select class="edit-basic-input">
							<?php $months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
							foreach ($months as $m) {
								if(strtolower($m) == strtolower(date("M"))) echo "<option value = '$m' selected>$m</option>";
								else echo "<option value = '$m'>$m</option>";
							}
							?>
						</select>

						<span class="cell-value"><?php echo $row->YEAR;?></span>
						<select class="edit-basic-input" style="margin-top:10px;">
							<?php 
							for($i = date("Y") - 50; $i < date("Y") + 50; $i++){
								if($i == date("Y"))	echo "<option value='$i' selected>$i</option>";
								else echo "<option value='$i'>$i</option>";
							}
							?>
						</select>
					</td>
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
						<th>Color Code</th>
						<th>Color Name</th>
						<th>Quantity</th>
						<th>Unit</th>
						<th>Box Number</th>
						<th>Case Number</th>
						<th>Reference Number</th>
						<th>Lot Number</th>
						<th>Claim Type</th>
						<th>Claim Code</th>
						<th>Action Code</th>
						<th>Process Code</th>
						<th>Details of Defect</th>
						<th>Defect Finding Way</th>
						<th class="fixed-width">Picture</th>
						<th>Remarks</th>
						<th class="fixed-width">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$stmt = $con->prepare("SELECT * FROM claims_parts WHERE ENTRY_REFERENCE_ID = :ENTRY_REFERENCE_ID ORDER BY KEY_ID DESC");
				$stmt->execute(array('ENTRY_REFERENCE_ID' => $ref));
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
							<span class = 'cell-value'>$r->COLOR_CODE</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->COLOR_NAME</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->QUANTITY</span>
							<input type='text' class='edit-input'>
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
							<span class = 'cell-value'>$r->BOX_NUMBER</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->CASE_NUMBER</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->REFERENCE_NUMBER</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->LOT_NUMBER</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->CLAIM_TYPE</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->CLAIM_CODE</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->ACTION_CODE</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->PROCESS_CODE</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->DETAILS_OF_DEFECT</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td>
							<span class = 'cell-value'>$r->DEFECT_FINDING_WAY</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td class='fixed-width'>";
							if(substr($r->PICTURE, 0,7) == "../img/") {
								echo "
								<span class='hidden'>picture</span>
								<img class='image-modal-trigger' data-key = '$r->KEY_ID' data-src='../$r->PICTURE' src='../$r->PICTURE' style='max-width:80px; max-height:50px; cursor: pointer;'>";
							}
							else {
								if(empty($r->PICTURE) || is_null($r->PICTURE)){
									echo "<span class='image-modal-trigger' data-src='../../img/placeholder.jpg' data-key = '$r->KEY_ID' style='cursor: pointer;'><i class='fa fa-image fa-lg'></i></span>";
								}
								else{
									echo "<span class='image-modal-trigger' data-src='../../img/placeholder.jpg' data-key = '$r->KEY_ID' style='cursor: pointer;'>
										$r->PICTURE
										<br>
										<i class='fa fa-image fa-lg'></i>
									</span>";
								}
							}
						echo "
						</td>

						<td>
							<span class = 'cell-value'>$r->REMARKS</span>
							<input type='text' class='edit-input'>
						</td>
						
						<td class='fixed-width'>
							<a href='#' data-type='claim' data-key='$r->KEY_ID' class='inline-edit btn btn-default'><i class='fa fa-pencil'></i></a>
							<a href='$base_url/functions/process-forms.php?del=execute&param=KEY_ID&target=$r->KEY_ID&entity=claims_parts&page=display/ckd-claim-details.php?ref=$ref' class='btn btn-default' onclick=\"return confirm('Are you sure that you want to delete this record?');\"><i class = 'fa fa-trash'></i></a>
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
		<h3>CKD Claims</h3>
		<h4>
			Invoice Number  - <span></span>
		</h4>
		


		
		<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive multiple-rows no-padding">
				<table>
					<thead>
						<tr class="header-row">
							<th class="add add-type"><i class="fa fa-plus add-row"></i></th>
							<th>Part Number</th>
							<th>Part Name</th>
							<th>Color Code</th>
							<th>Color Name</th>
							<th>Quantity</th>
							<th>Unit</th>
							<th>Box Number</th>
							<th>Case Number</th>
							<th>Reference No</th>
							<th>Lot Number</th>
							<th>Claim Type</th>
							<th>Claim Code</th>
							<th>Action Code</th>
							<th>Process Code</th>
							<th>Details of Defect</th>
							<th>Finding Way</th>
							<th>Picture</th>
							<th>Remarks</th>
						</tr>
					</thead>

					<tbody>
						<?php for($i=0; $i<5; $i++) { ?>
						<tr>
							<td class="delete"><i class="fa fa-trash trash-it"></i></td>
							<td><input type="text" name="part-number[]" placeholder="Part Number" value=""></td>
							<td><input type="text" name="part-name[]" placeholder="Part Name" value=""></td>
							<td><input type="text" name="color-code[]" placeholder="Color Code" value=""></td>
							<td><input type="text" name="color-name[]" placeholder="Color Name" value=""></td>
							<td><input type="text" name="quantity[]" placeholder="Quantity" value=""></td>
							<td><input type="text" name="unit[]" placeholder="Unit" value=""></td>
							<td><input type="text" name="box-number[]" placeholder="Box Number" value=""></td>
							<td><input type="text" name="case-number[]" placeholder="Case Number" value=""></td>
							<td><input type="text" name="reference-number[]" placeholder="Reference Number" value=""></td>
							<td><input type="text" name="lot-number[]" placeholder="Lot Number" value=""></td>
							<td><input type="text" name="claim-type[]" placeholder="Claim Type" value=""></td>
							<td><input type="text" name="claim-code[]" placeholder="Claim Code" value=""></td>
							<td><input type="text" name="action-code[]" placeholder="Action Code" value=""></td>
							<td><input type="text" name="process-code[]" placeholder="Process Code" value=""></td>
							<td><input type="text" name="details-of-defect[]" placeholder="Details of Defect" value=""></td>
							<td><input type="text" name="defect-finding-way[]" placeholder="Defect Finding Way" value=""></td>
							<td><input type="file" name="picture[]"></td>
							<td><input type="text" name="remarks[]" placeholder="Remarks" value=""></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div> <!-- /mulitple-rows-->
		



			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-input no-padding">
				<input type="hidden" name="type" value="ckd-claim">
				<input type="hidden" name="reference" value="<?php echo $ref;?>">
				<label for="excel-file" class="btn btn-primary">Upload Excel</label>
				<input type="file" id="excel-file" name="file" class="hidden">
				<button type="submit" class="btn btn-primary" name="save-more-claims">Save</button>
				<br><br>
				<p id="file-name"></p>
			</div> <!-- /form-input -->
			<br><br><br>
		</form>
	</div> <!-- /content-modal-body -->
</div> <!-- /parts-modal -->




<div id="image-modal">
	<div id="image-modal-fadeout"></div>
	<div class="image-modal-body">
		<img src="" id="this-image" alt="Image">
		<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="key" id="image-key" value="">
			<input type="hidden" name="ref" value="<?php echo $ref;?>">
			<input type="hidden" name="type" value="ckd-claim">
			<br>
			<label for="claim-image-file" class="btn btn-default">Upload Image</label>
			<input type="file" name="image[]" id="claim-image-file" class="hidden">
			<button type="submit" name="update-claim-image" class="btn btn-default">Save</button>
			<button type="submit" name="delete-claim-image" class="btn btn-default">Delete</button>
			<button type="submit" id="delete-claim-image" name="delete-claim-image" class="btn btn-default">Delete</button>
		</form>
	</div> <!-- /image-modal-body -->
</div> <!-- /image-modal -->

</body>
</html>