<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>


<div class="topbar">
    <div class="breadcrumbs">
        <span><i class="fa fa-shopping-basket"></i>Issues Received</span>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#"><span>Manufacturing Parts Issues Received</span></a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="page-title">
		<span class="title">Manufacturing Parts Issues Received</span>
		<a href="<?php echo $base_url;?>/views/forms/manufacturing-issue-received-enter.php" class="btn btn-primary btn-sm pull-right">
			<i class="fa fa-pencil fa-rotate-270"></i>&nbsp; Create New
		</a>
		<label for="search-input"><i class="fa fa-search"></i></label>
		<input type="text" id="search-input" class="form-control" placeholder="Search Records">
	</h1>


	
	
	<div class="table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<table class="table table-bordered table-striped searchable-table">
			<thead>
				<tr>
					<th>Date</th>
					<th>Site</th>
					<th>Sender Name</th>
					<th>Sender Designation</th>
					<th>Sender Department</th>
					<th>Requisition Number</th>
					<th>Invoice Number</th>
					<th>LC Number</th>
					<th>Lot Number</th>
					<th>PPD Number</th>
					<th class="fixed-width">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$stmt = $con->query("SELECT * FROM issues WHERE TYPE = 'manufacturing-issue' AND received = 1 ORDER BY ENTRY_DATE DESC");
				$manufacturing_issues = $stmt->fetchAll(\PDO::FETCH_OBJ);
				$i = 1;
				foreach ($manufacturing_issues as $m) {
					if($i>25) $class_name = "hide-row";
					else $class_name = "show-row";
					$date = date("d M, Y",strtotime($m->ENTRY_DATE));
					$stmt = $con->prepare("SELECT KEY_ID FROM issues WHERE REFERENCE_NUMBER = :REFERENCE_NUMBER");
					echo "
					<tr class='$class_name' id='row-num-$i'>
						<td>$date</td>
						<td>$m->SITE</td>
						<td>$m->NAME</id>
						<td>$m->DESIGNATION</td>
						<td>$m->DEPARTMENT</td>
						<td>$m->REFERENCE_NUMBER</td>
						<td>$m->INVOICE_NUMBER</td>
						<td>$m->LC_NUMBER</td>
						<td>$m->LOT_NUMBER</td>
						<td>$m->PPD_NUMBER</td>
						<td class='fixed-width'>
							<a href='$base_url/views/display/manufacturing-issue-received-details.php?ref=$m->KEY_ID' class='btn btn-primary btn-sm'>Details</a>
							<button class='export-file btn btn-primary btn-sm' data-action='export-excel' data-entries='issues' data-parts = 'issue_records' data-key='$m->KEY_ID' data-file-name = 'manufacture_issue'>Get Excel</button>
							<button class='export-file btn btn-primary btn-sm' data-action='print' data-entries='issues' data-parts = 'issue_records' data-key='$m->KEY_ID' data-file-name = 'manufacture_issue'>Print</button>
						</td>
					</tr>";
					$i++;
				}
				?>
			</tbody>
		</table>
	</div> <!-- /table-responsive -->	

	
	<ul class="pagination"></ul>
	
</div> <!-- /content -->

</body>
</html>