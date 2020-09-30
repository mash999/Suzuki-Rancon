<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
$country_list = fetch_functions\get_countries();
// SET ALL VARIABLES TO EMPTY STRING TO AVOID PHP WARNINGS
$id = $name = $address = $city = $country = $phone_office = $mobile = $email = $fax = $website = "";
$action = "create";
if(isset($_GET['supplier'])){
	$id = htmlspecialchars($_GET['supplier']);
	// GET INFORMAITION ABOUT THAT PARTICULAR SUPPLIER
	$s_info = fetch_functions\get_row('suppliers','SUPPLIER_CODE',$id)[0];	
	if($s_info){		
		// SUPPLIER ID IS VALID
		$name = $s_info->SUPPLIER_NAME;
		$address = $s_info->SUPPLIER_ADDRESS;
		$city = $s_info->SUPPLIER_CITY;
		$country = $s_info->COUNTRY;
		$phone_office = $s_info->SUPPLIER_PHONE_OFFICE;
		$mobile = $s_info->SUPPLIER_PHONE_MOBILE;
		$email = $s_info->SUPPLIER_EMAIL;
		$fax = $s_info->SUPPLIER_FAX;
		$website = $s_info->SUPPLIER_WEBSITE;
		$action = "modify";
	}
}
else{
	$stmt = $con->query("SELECT MAX(SUPPLIER_CODE) AS SUPPLIER_CODE from suppliers");
	$result = $stmt->fetch(\PDO::FETCH_OBJ);
	$id = $result->SUPPLIER_CODE + 1;
}
?>


<div class="topbar">
    <div class="breadcrumbs">
        <i class="fa fa-bars visible-xs menu-bar" aria-hidden="true"></i>
        <span><i class="fa fa-address-book-o"></i>Profiles</span>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="../display/suppliers.php">Suppliers Profile</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#">Suppliers Profile Form</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="form-title page-title"><span class="title">Suppliers Profile Form</span></h1>
	<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="supplier-code">Supplier Code</label>
			<input type="text" id="supplier-code" class="form-control" placeholder="Supplier Code" value="<?php echo $id;?>" readonly>
		</div> <!-- /form-input -->


		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="full-name">Full Name <strong>*</strong></label>
			<input type="text" id="full-name" class="form-control" name="full-name" placeholder="Full Name" value="<?php echo $name;?>" required>
		</div> <!-- /form-input -->



		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="email">E-mail</label>
			<input type="email" id="email" class="form-control" name="email" placeholder="E-mail" value="<?php echo $email;?>" maxlength="100">
		</div> <!-- /form-input -->



		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="phone-office">Phone No. (office) <strong>*</strong></label>
			<input type="text" id="phone-office" class="form-control" name="phone-office" placeholder="Phone No. (office)" value="<?php echo $phone_office;?>" required>
		</div> <!-- /form-input -->



		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="mobile-number">Mobile No.</label>
			<input type="text" id="mobile-number" class="form-control" name="mobile-number" placeholder="Mobile No." value="<?php echo $mobile;?>">
		</div> <!-- /form-input -->



		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="fax">Fax</label>
			<input type="text" id="fax" class="form-control" name="fax" placeholder="Fax" value="<?php echo $fax;?>">
		</div> <!-- /form-input -->



		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="website">Web site</label>
			<input type="text" id="website" class="form-control" name="website" placeholder="Web site" value="<?php echo $website;?>">
		</div> <!-- /form-input -->



		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="country">Country</label>
			<select class="form-control" name="country">
				<option value = "">---- Select Country ----</option>";
				<?php
					foreach ($country_list as $c) {
						if(trim($c) == trim($country))
							echo "<option value = '$c' selected>$c</option>";
						else
							echo "<option value = '$c'>$c</option>";
					}
				?>
			</select>
		</div> <!-- /form-input -->



		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="city">City <strong>*</strong></label>
			<input type="text" id="city" class="form-control" name="city" placeholder="City" value="<?php echo $city;?>" required>
		</div> <!-- /form-input -->



		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-input">
			<label for="address">Address <strong>*</strong></label>
			<textarea id="address" class="form-control" name="address" placeholder="Address" required><?php echo $address;?></textarea>
			<input type="hidden" name="action" id="action" value="<?php echo $action;?>">
			<input type="hidden" name="supplier-code" value="<?php echo $id;?>">
			<button type="submit" class="btn btn-primary" name="save-supplier">Save</button>
			<a href="<?php echo $base_url;?>/views/display/suppliers.php" class="btn btn-primary">Cancel</a>
		</div> <!-- /form-input -->
	</form>

</div> <!-- /content -->

</body>
</html>