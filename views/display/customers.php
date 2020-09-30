<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>


<div class="topbar">
    <div class="breadcrumbs">
        <i class="fa fa-bars visible-xs menu-bar" aria-hidden="true"></i>
        <span><i class="fa fa-address-book-o"></i>Profiles</span>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#">Customers Profile</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="page-title">
		<span class="title">Customers Profile</span>
		<a href="<?php echo $base_url;?>/views/forms/customers-form.php" class="btn btn-primary btn-sm pull-right">
			<i class="fa fa-pencil fa-rotate-270"></i> &nbsp;Create New
		</a>
		<label for="search-input"><i class="fa fa-search"></i></label>
		<input type="text" id="search-input" class="form-control" placeholder="Search Customers">
	</h1>
	


	
	<div class="table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<table class="table table-bordered table-striped searchable-table">
			<thead>
				<tr>
					<th>Customer Code</th>
					<th>Name</th>
					<th>Type</th>
					<th>Phone (Office)</th>
					<th>Phone</th>
					<th>Mobile</th>
					<th>E-mail</th>
					<th>Fax</th>
					<th>Website</th>
					<th>City</th>
					<th>Address</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$stmt = $con->query("SELECT * FROM customers ORDER BY CUSTOMER_NAME ASC");
				$customers = $stmt->fetchAll(\PDO::FETCH_OBJ);
				$i = 1;
				foreach ($customers as $c) {
					if($i>25) $class_name = "hide-row";
					else $class_name = "show-row";
					echo "
					<tr class='$class_name' id='row-num-$i'>
						<td>$c->CUSTOMER_ID</td>
						<td>$c->CUSTOMER_NAME</td>
						<td>$c->CUSTOMER_TYPE</td>
						<td>$c->CUSTOMER_PHONE_OFFICE</td>
						<td>$c->CUSTOMER_PHONE_OPTIONAL</td>
						<td>$c->CUSTOMER_PHONE_MOBILE</td>
						<td>$c->CUSTOMER_EMAIL</td>
						<td>$c->CUSTOMER_FAX</td>
						<td>$c->CUSTOMER_WEBSITE</td>
						<td>$c->CUSTOMER_CITY</td>	
						<td>$c->CUSTOMER_ADDRESS</td>
						<td class='fixed-width'>
							<a href='$base_url/views/forms/customers-form.php?customer=$c->CUSTOMER_ID' class='btn btn-default'><i class = 'fa fa-pencil'></i></a>
							<a href='$base_url/functions/process-forms.php?del=execute&param=CUSTOMER_ID&target=$c->CUSTOMER_ID&entity=customers&page=display/customers.php' class='btn btn-default' onclick=\"return confirm('Are you sure that you want to delete this record?');\"><i class = 'fa fa-trash'></i></a>
						</td>
					</tr>";
				$i++;
				}
				?>
			</tbody>
		</table>
	

		<ul class="pagination"></ul>
	</div> <!-- /table-responsive -->
</div> <!-- /content -->

</body>
</html>




