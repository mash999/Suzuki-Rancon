<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>


<div class="topbar">
    <div class="breadcrumbs">
        <span><i class="fa fa-pencil-square-o"></i>Enter</span>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#"><span>CKD Parts Enter</span></a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="page-title">
		<span class="title">CKD Parts Enter</span>
		<a href="<?php echo $base_url;?>/views/forms/ckd-enter.php" class="btn btn-primary btn-sm pull-right">
			<i class="fa fa-pencil fa-rotate-270"></i>&nbsp; Create New
		</a>
		<label for="search-input"><i class="fa fa-search"></i></label>
		<input type="text" id="search-input" class="form-control" placeholder="Search CKD">
	</h1>


	
	
	<div class="table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<table class="table table-bordered table-striped searchable-table">
			<thead>
				<tr>
					<th>Requisition Number</th>
					<th>Date</th>
					<th>Site</th>
					<th>Supplier Name</th>
					<th>Invoice Number</th>
					<th>LC Number</th>
					<th>Lot Number</th>
					<th class="fixed-width">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$stmt = $con->query("SELECT * FROM entries WHERE TYPE = 'ckd' ORDER BY ENTRY_DATE DESC");
				$ckds = $stmt->fetchAll(\PDO::FETCH_OBJ);
				$i = 1;
				foreach ($ckds as $c) {
					if($i>25) $class_name = "hide-row";
					else $class_name = "show-row";
					$date = date("d M, Y",strtotime($c->ENTRY_DATE));
					$stmt = $con->prepare("SELECT SUPPLIER_NAME FROM suppliers WHERE SUPPLIER_CODE = :SUPPLIER_CODE LIMIT 1");
					$stmt->execute(array('SUPPLIER_CODE' =>  $c->SUPPLIER_CODE));
					$supplier = $stmt->fetch(\PDO::FETCH_OBJ);
					if($c->REQUISITION_NUMBER == 0) $requisition_number = "----";
					else $requisition_number = $c->REQUISITION_NUMBER;
					echo "
					<tr class='$class_name' id='row-num-$i'>
						<td>$requisition_number</td>
						<td>$date</td>
						<td>$c->SITE</id>
						<td>$supplier->SUPPLIER_NAME</td>
						<td>$c->INVOICE_NUMBER</td>
						<td>$c->LC_NUMBER</td>
						<td>$c->LOT_NUMBER</td>
						<td class='fixed-width'>
							<a href='$base_url/views/display/ckd-details.php?ref=$c->KEY_ID' class='btn btn-primary btn-sm'>Details</a>
							<button class='export-file btn btn-primary btn-sm' data-action='export-excel' data-entries='entries' data-parts = 'parts' data-key='$c->KEY_ID' data-file-name = 'ckd_enter'>Get Excel</button>
							<button class='export-file btn btn-primary btn-sm' data-action='print' data-entries='entries' data-parts = 'parts' data-key='$c->KEY_ID' data-file-name = 'ckd_enter'>Print</button>
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