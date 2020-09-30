<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
?>


<div class="topbar">
    <div class="breadcrumbs">
        <i class="fa fa-bars visible-xs menu-bar" aria-hidden="true"></i>
        <span><i class="fa fa-address-book-o"></i>Profiles</span>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#">Suppliers Profile</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="page-title">
		<span class="title">Suppliers Profile</span>
		<a href="<?php echo $base_url;?>/views/forms/suppliers-form.php" class="btn btn-primary btn-sm pull-right">
			<i class="fa fa-pencil fa-rotate-270"></i>&nbsp; Create New
		</a>
		<label for="search-input"><i class="fa fa-search"></i></label>
		<input type="text" id="search-input" class="form-control" placeholder="Search Suppliers">
	</h1>


	
	
	<div class="table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<table class="table table-bordered table-striped searchable-table">
			<thead>
				<tr>
					<th>Supplier Code</th>
					<th>Company</th>
					<th>Phone (Office)</th>
					<th>Mobile</th>
					<th>E-mail</th>
					<th>Fax</th>
					<th>Website</th>
					<th>City</th>
					<th>Country</th>
					<th class="long-cell">Address</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$stmt = $con->query("SELECT * FROM suppliers ORDER BY SUPPLIER_NAME ASC");
				$suppliers = $stmt->fetchAll(\PDO::FETCH_OBJ);
				$i = 1;
				foreach ($suppliers as $s) {
					if($i>25) $class_name = "hide-row";
					else $class_name = "show-row";
					echo "
					<tr class='$class_name' id='row-num-$i'>
						<td>$s->SUPPLIER_CODE</td>
						<td>$s->SUPPLIER_NAME</td>
						<td>$s->SUPPLIER_PHONE_OFFICE</td>
						<td>$s->SUPPLIER_PHONE_MOBILE</td>
						<td>$s->SUPPLIER_EMAIL</td>
						<td>$s->SUPPLIER_FAX</td>
						<td>$s->SUPPLIER_WEBSITE</td>
						<td>$s->SUPPLIER_CITY</td>
						<td>$s->COUNTRY</td>
						<td class='long-cell'>$s->SUPPLIER_ADDRESS</td>
						<td class='fixed-width'>
							<a href='$base_url/views/forms/suppliers-form.php?supplier=$s->SUPPLIER_CODE' class='btn btn-default'><i class = 'fa fa-pencil'></i></a>
							<a href='$base_url/functions/process-forms.php?del=execute&param=SUPPLIER_CODE&target=$s->SUPPLIER_CODE&entity=suppliers&page=display/suppliers.php' class='btn btn-default' onclick=\"return confirm('Are you sure that you want to delete this record?');\"><i class = 'fa fa-trash'></i></a>
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