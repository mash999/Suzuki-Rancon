<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>


<div class="topbar">
    <div class="breadcrumbs">
        <a href="#"><i class="fa fa-check-square-o"></i>Order Delivery</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <span>Delivery Challan</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="page-title">
		<span class="title">Delivery Challan</span>
		<a href="<?php echo $base_url;?>/views/forms/order-delivery-enter.php" class="btn btn-primary btn-sm pull-right">
			<i class="fa fa-pencil fa-rotate-270"></i>&nbsp; Create New
		</a>
		<label for="search-input"><i class="fa fa-search"></i></label>
		<input type="text" id="search-input" class="form-control" placeholder="Search Delivered Order">
	</h1>


	
	
	<div class="table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<table class="table table-bordered table-striped searchable-table">
			<thead>
				<tr>
					<th>Actual DO Date</th>
					<th>Delivery Date</th>
					<th>Site</th>
					<th>Reference DO No</th>
					<th>Reference CO No</th>
					<th>Customer Name</th>
					<th>Driver Name</th>
					<th>Sales Channel</th>
					<th class="fixed-width">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$stmt = $con->query("SELECT * FROM delivery ORDER BY KEY_ID DESC");
				$delivery = $stmt->fetchAll(\PDO::FETCH_OBJ);
				$i = 1;
				foreach ($delivery as $d) {
					if($i>25) $class_name = "hide-row";
					else $class_name = "show-row";
					$do_date = date("d M, Y", strtotime($d->DO_DATE));
					$delivery_date = date("d M, Y", strtotime($d->DELIVERY_DATE));
					$stmt = $con->prepare("SELECT CUSTOMER_NAME FROM customers WHERE CUSTOMER_ID = :CUSTOMER_ID LIMIT 1");
					$stmt->execute(array('CUSTOMER_ID' =>  $d->CUSTOMER_CODE));
					$customer = $stmt->fetch(\PDO::FETCH_OBJ);
					echo "
					<tr class='$class_name' id='row-num-$i'>
						<td>$do_date</td>
						<td>$delivery_date</td>
						<td>$d->SITE</id>
						<td>$d->REFERENCE_DO_NUMBER</td>
						<td>$d->REFERENCE_CO_NUMBER</td>
						<td>$customer->CUSTOMER_NAME</td>
						<td>$d->DRIVER_NAME</td>
						<td>$d->SALES_CHANNEL</td>
						<td class='long-cell'>
							<a href='$base_url/views/display/order-delivery-details.php?ref=$d->KEY_ID' class='btn btn-primary btn-sm'>Details</a>
							<button class='export-file btn btn-primary btn-sm' data-action='export-excel' data-entries='delivery' data-parts = 'delivery_parts' data-key='$d->KEY_ID' data-file-name = 'delivery_challan'>Get Excel</button>
							<button class='export-file btn btn-primary btn-sm' data-action='print' data-entries='delivery' data-parts = 'delivery_parts' data-key='$d->KEY_ID' data-file-name = 'delivery_challan'>Print</button>
							<button class='export-file btn btn-primary btn-sm' data-action='gate-pass' data-entries='delivery' data-parts = 'delivery_parts' data-key='$d->KEY_ID' data-file-name = 'gate_pass'>Gate Pass</button>
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