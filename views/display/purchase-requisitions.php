<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>


<div class="topbar">
    <div class="breadcrumbs">
        <a href="#"><i class="fa fa-money"></i>Purchase Requisitions</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="page-title">
		<span class="title">Purchase Requisitions</span>
		<a href="<?php echo $base_url;?>/views/forms/purchase-requisitions-enter.php" class="btn btn-primary btn-sm pull-right">
			<i class="fa fa-pencil fa-rotate-270"></i>&nbsp; Create New
		</a>
		<label for="search-input"><i class="fa fa-search"></i></label>
		<input type="text" id="search-input" class="form-control" placeholder="Search Requisitions">
	</h1>


	
	
	<div class="table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<table class="table table-bordered table-striped searchable-table">
			<thead>
				<tr>
					<th>Requisition Number</th>
					<th>Date</th>
					<th>Site</th>
					<th>Requester Name</th>
					<th>Requester Designation</th>
					<th>Requester Department</th>
					<th>Approved By</th>
					<th>Supplier</th>
					<th class="fixed-width">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$stmt = $con->query("SELECT * FROM purchase_requisitions ORDER BY KEY_ID DESC");
				$requisitions = $stmt->fetchAll(\PDO::FETCH_OBJ);
				$i = 1;
				foreach ($requisitions as $r) {
					if($i>25) $class_name = "hide-row";
					else $class_name = "show-row";
					$date = date("d M, Y",strtotime($r->REQUISITION_DATE));
					$stmt = $con->prepare("SELECT SUPPLIER_NAME FROM suppliers WHERE SUPPLIER_CODE = :SUPPLIER_CODE LIMIT 1");
					$stmt->execute(array('SUPPLIER_CODE' =>  $r->SUPPLIER_CODE));
					$supplier = $stmt->fetch(\PDO::FETCH_OBJ);
					echo "
					<tr class='$class_name' id='row-num-$i'>
						<td>$r->KEY_ID</td>
						<td>$date</td>
						<td>$r->SITE</id>
						<td>$r->REQUESTER_NAME</id>
						<td>$r->REQUESTER_DESIGNATION</id>
						<td>$r->REQUESTER_DEPARTMENT</id>
						<td>$r->APPROVED_BY</id>
						<td>$supplier->SUPPLIER_NAME</td>
						<td class='fixed-width'>
							<a href='$base_url/views/display/purchase-requisitions-details.php?ref=$r->KEY_ID' class='btn btn-primary btn-sm'>Details</a>
							<button class='export-file btn btn-primary btn-sm' data-action='export-excel' data-entries='purchase_requisitions' data-parts = 'purchase_requisitions_parts' data-key='$r->KEY_ID' data-file-name = 'purchase_requisitions'>Get Excel</button>
							<button class='export-file btn btn-primary btn-sm' data-action='print' data-entries='purchase_requisitions' data-parts = 'purchase_requisitions_parts' data-key='$r->KEY_ID' data-file-name = 'purchase_requisitions'>Print</button>
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