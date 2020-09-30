<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>


<div class="topbar">
    <div class="breadcrumbs">
        <a href="#"><i class="fa fa-chain-broken"></i>Claims</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#">Claims for Spare Parts</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="page-title">
		<span class="title">Claims for Spare Parts</span>
		<a href="<?php echo $base_url;?>/views/forms/spare-claim-enter.php" class="btn btn-primary btn-sm pull-right">
			<i class="fa fa-pencil fa-rotate-270"></i>&nbsp; Create New
		</a>
		<label for="search-input"><i class="fa fa-search"></i></label>
		<input type="text" id="search-input" class="form-control" placeholder="Search Claims">
	</h1>


	
	
	<div class="table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<table class="table table-bordered table-striped searchable-table">
			<thead>
				<tr>
					<th>Issue Date</th>
					<th>Site</th>
					<th>Created By</th>
					<th>Approved By</th>
					<th>Invoice Number</th>
					<th>Claim Reference No</th>
					<th>PPD Number</th>
					<th>LC Number</th>
					<th>Model</th>
					<th>Shopping Mode</th>
					<th>Month - Year</th>
					<th class="fixed-width">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$stmt = $con->query("SELECT * FROM claims WHERE TYPE = 'spare-claim' ORDER BY KEY_ID DESC");
				$spare_claims = $stmt->fetchAll(\PDO::FETCH_OBJ);
				$i = 1;
				foreach ($spare_claims as $c) {
					if($i>25) $class_name = "hide-row";
					else $class_name = "show-row";
					$date = date("d M, Y",strtotime($c->CLAIM_ISSUE_DATE));
					echo "
					<tr class='$class_name' id='row-num-$i'>
						<td>$date</td>
						<td>$c->SITE</id>
						<td>$c->CREATED_BY</td>
						<td>$c->APPROVED_BY</td>
						<td>$c->INVOICE_NUMBER</td>
						<td>$c->CLAIM_REFERENCE_NUMBER</td>
						<td>$c->PPD_NUMBER</td>
						<td>$c->LC_NUMBER</td>
						<td>$c->MODEL</td>
						<td>$c->SHIPPING_MODE</td>
						<td>$c->MONTH - $c->YEAR</td>
						<td class='fixed-width'>
							<a href='$base_url/views/display/spare-claim-details.php?ref=$c->KEY_ID' class='btn btn-primary btn-sm'>Details</a>
							<button class='export-file btn btn-primary btn-sm' data-action='export-excel' data-entries='claims' data-parts = 'claims_parts' data-key='$c->KEY_ID' data-file-name = 'spare_claims'>Get Excel</button>
							<button class='export-file btn btn-primary btn-sm' data-action='print' data-entries='claims' data-parts = 'claims_parts' data-key='$c->KEY_ID' data-file-name = 'spare_claims'>Print</button>
						</td>
					</tr>";
				}
				?>
			</tbody>
		</table>
	</div> <!-- /table-responsive -->	

	
	<ul class="pagination"></ul>
	
</div> <!-- /content -->









<?php 
if(!empty($_SESSION['entries']) && !empty($_SESSION['parts']) && !empty($_SESSION['key_id'])){ ?>
<div id="export-modal">
	<div class="export-modal-body">
		<h3><?php echo $_SESSION['msg']; ?></h3>
		<form action="<?php echo $base_url;?>/functions/export-file.php" method="post" target="blank">
			<input type="hidden" name="file-name" value="SPARE_CLAIM_">
			<div class="left">
				<img src="../../img/excel-icon.png" alt="Excel-icon">
				<h4>Export to excel</h4>
			</div>

			<div class="right">
				<img src="../../img/pdf-icon.png" alt="PDF-icon">
				<h4>Print data sheet</h4>
			</div>
			<button type="submit" name="export-to-excel" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> &nbsp; Export to Excel</button>
			<button type="submit" name="print-data" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i> &nbsp; Print</button>
			<button type="button" id="export-modal-fadeout-trigger" class="btn btn-primary pull-right">I'm Done</button>
		</form>
	</div> <!-- /export-modal-body -->
</div> <!-- /export-modal -->
<?php } ?>

</body>
</html>