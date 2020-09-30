<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>


<div class="topbar">
    <div class="breadcrumbs">
        <a href="#"><i class="fa fa-refresh"></i>Return in Order</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="page-title">
		<span class="title">Return in Order</span>
		<a href="<?php echo $base_url;?>/views/forms/return-order-enter.php" class="btn btn-primary btn-sm pull-right">
			<i class="fa fa-pencil fa-rotate-270"></i>&nbsp; Create New
		</a>
		<label for="search-input"><i class="fa fa-search"></i></label>
		<input type="text" id="search-input" class="form-control" placeholder="Search Orders">
	</h1>


	
	
	<div class="table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<table class="table table-bordered table-striped searchable-table">
			<thead>
				<tr>
					<th>Date</th>
					<th>Return Order Number</th>
					<th>Returned Delivery Challan Number</th>
					<th>Customer Name</th>
					<th>Customer Address</th>
					<th>Customer Contact</th>
					<th>Sales Channel</th>
					<th class="fixed-width">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$stmt = $con->query("SELECT * FROM return_order ORDER BY KEY_ID DESC");
				$return_orders = $stmt->fetchAll(\PDO::FETCH_OBJ);
				$i = 1;
				foreach ($return_orders as $r) {
					if($i>25) $class_name = "hide-row";
					else $class_name = "show-row";
					$date = date("d M, Y",strtotime($r->RETURN_DATE));
					$customer = fetch_functions\get_row('customers','CUSTOMER_ID',$r->CUSTOMER_CODE)[0];
				
					echo "
					<tr class='$class_name' id='row-num-$i'>
						<td>$date</td>
						<td>$r->KEY_ID</td>
						<td>$r->DELIVERY_CHALLAN_NUMBER</td>
						<td>$customer->CUSTOMER_NAME</td>
						<td>$customer->CUSTOMER_ADDRESS</td>
						<td>$customer->CUSTOMER_PHONE_OFFICE</td>
						<td>$r->SALES_CHANNEL</td>
						<td class='fixed-width'>
							<a href='$base_url/views/display/return-order-details.php?ref=$r->KEY_ID' class='btn btn-primary btn-sm'>Details</a>
							<button class='export-file btn btn-primary btn-sm' data-action='export-excel' data-entries='return_order' data-parts = 'returned_parts' data-key='$r->KEY_ID' data-file-name = 'return_order'>Get Excel</button>
							<button class='export-file btn btn-primary btn-sm' data-action='print' data-entries='return_order' data-parts = 'returned_parts' data-key='$r->KEY_ID' data-file-name = 'return_order'>Print</button>
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