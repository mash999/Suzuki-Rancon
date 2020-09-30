<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>


<div class="topbar">
    <div class="breadcrumbs">
        <a href="#"><i class="fa fa-check-square-o"></i>Order Delivery</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <span>Backup Parts Delivery</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="page-title">
		<span class="title">Backup Parts Delivery</span>
		<a href="<?php echo $base_url;?>/views/forms/backup-delivery-enter.php" class="btn btn-primary btn-sm pull-right">
			<i class="fa fa-pencil fa-rotate-270"></i>&nbsp; Create New
		</a>
		<label for="search-input"><i class="fa fa-search"></i></label>
		<input type="text" id="search-input" class="form-control" placeholder="Search Delivered Order">
	</h1>


	
	
	<div class="table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<table class="table table-bordered table-striped searchable-table">
			<thead>
				<tr>
					<th>Date</th>
					<th>Site</th>
					<th>Requester Name</th>
					<th>Requester Designation</th>
					<th>Requester Department</th>
					<th>Requisition Number</th>
					<th>Reference Number</th>
					<th class="fixed-width">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$stmt = $con->query("SELECT * FROM backup_delivery ORDER BY KEY_ID DESC");
				$delivery = $stmt->fetchAll(\PDO::FETCH_OBJ);
				$i = 1;
				foreach ($delivery as $d) {
					if($i>25) $class_name = "hide-row";
					else $class_name = "show-row";
					$delivery_date = date("d M, Y", strtotime($d->DELIVERY_DATE));
					echo "
					<tr class='$class_name' id='row-num-$i'>
						<td>$delivery_date</td>
						<td>$d->SITE</td>
						<td>$d->REQUESTER_NAME</id>
						<td>$d->REQUESTER_DESIGNATION</td>
						<td>$d->REQUESTER_DEPARTMENT</td>
						<td>$d->REQUISITION_NUMBER</td>
						<td>$d->REFERENCE_NUMBER</td>
						<td class='fixed-width'>
							<a href='$base_url/views/display/backup-order-delivery-details.php?ref=$d->KEY_ID' class='btn btn-primary btn-sm'>Details</a>
							<button class='export-file btn btn-primary btn-sm' data-action='export-excel' data-entries='backup_delivery' data-parts = 'delivery_parts' data-key='$d->KEY_ID' data-file-name = 'backup_delivery'>Get Excel</button>
							<button class='export-file btn btn-primary btn-sm' data-action='print' data-entries='backup_delivery' data-parts = 'delivery_parts' data-key='$d->KEY_ID' data-file-name = 'backup_delivery'>Print</button>
						</td>
					</tr>";
				}
				?>
			</tbody>
		</table>
	</div> <!-- /table-responsive -->	

	
	<ul class="pagination"></ul>
	
</div> <!-- /content -->

</body>
</html>