<?php 
include '../partials/sidebar.php';
use suzuki\fetch_functions;
// SET ALL VARIABLES TO EMPTY STRING TO AVOID PHP WARNINGS
$id = $name = $address = $city = $phone_office = $phone_optional = $mobile = $email = $fax = $website = $type = "";
$action = "create";
if(isset($_GET['customer'])){
	$id = htmlspecialchars($_GET['customer']);
	// GET INFORMAITION ABOUT THAT PARTICULAR CUSTOMER
	$c_info = fetch_functions\get_row('customers','CUSTOMER_ID',$id)[0];	
	if($c_info){		
		// CUSTOMER ID IS VALID
		$name = $c_info->CUSTOMER_NAME;
		$address = $c_info->CUSTOMER_ADDRESS;
		$city = $c_info->CUSTOMER_CITY;
		$phone_office = $c_info->CUSTOMER_PHONE_OFFICE;
		$phone_optional = $c_info->CUSTOMER_PHONE_OPTIONAL;
		$mobile = $c_info->CUSTOMER_PHONE_MOBILE;
		$email = $c_info->CUSTOMER_EMAIL;
		$fax = $c_info->CUSTOMER_FAX;
		$website = $c_info->CUSTOMER_WEBSITE;
		$type = $c_info->CUSTOMER_TYPE;
		$action = "modify";
	}
}
?>


<div class="topbar">
    <div class="breadcrumbs">
        <i class="fa fa-bars visible-xs menu-bar" aria-hidden="true"></i>
        <span><i class="fa fa-address-book-o"></i>Profiles</span>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="../display/customers.php">Customers Profile</a>
        <i class="fa fa-caret-right" aria-hidden="true"></i>
        <a href="#">Customers Profile Form</a>
    </div> <!-- /breadcrumbs -->
    <div class="signin">
        <span>Hey, Admin</span>
    </div> <!-- /signin -->
</div> <!-- /topbar -->









<div class="content">
	<h1 class="form-title page-title"><span class="title">Customers Profile Form</span></h1>
	<form action="<?php echo $base_url;?>/functions/process-forms.php" method="post" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="full-name">Full Name <strong>*</strong></label>
			<input type="text" id="full-name" class="form-control" name="full-name" placeholder="Full Name" value="<?php echo $name;?>" required>
		</div> <!-- /form-input -->



		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="email">E-mail</label>
			<input type="email" id="email" class="form-control" name="email" placeholder="E-mail" value="<?php echo $email;?>">
		</div> <!-- /form-input -->



		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="phone-office">Phone No. (office) <strong>*</strong></label>
			<input type="text" id="phone-office" class="form-control" name="phone-office" placeholder="Phone No. (office)" value="<?php echo $phone_office;?>" required>
		</div> <!-- /form-input -->



		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="phone">Phone No.</label>
			<input type="text" id="phone" class="form-control" name="phone" placeholder="Phone No." value="<?php echo $phone_optional;?>">
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
			<label for="city">City <strong>*</strong></label>
			<input type="text" id="city" class="form-control" name="city" placeholder="City" value="<?php echo $city;?>" required>
		</div> <!-- /form-input -->



		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-input">
			<label for="customer-type">Customer Type <strong>*</strong></label>
			<select id="customer-type" class="form-control" name="customer-type" required>
				<?php 
				if($type == "Dealer") echo "<option value='Dealer' selected>Dealer</option>";
				else echo "<option value='Dealer'>Dealer</option>";
				if($type == "Corporate") echo "<option value='Corporate' selected>Corporate</option>";
				else echo "<option value='Corporate'>Corporate</option>";
				if($type == "Retail") echo "<option value='Retail' selected>Retail</option>";
				else echo "<option value='Retail'>Retail</option>";
				if($type == "Others") echo "<option value='Others' selected>Others</option>";
				else echo "<option value='Others'>Others</option>";
				?>
			</select>
		</div> <!-- /form-input -->



		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-input">
			<label for="address">Address <strong>*</strong></label>
			<input type="hidden" name="id" value="<?php echo $id;?>">
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<textarea id="address" class="form-control" name="address" placeholder="Address" required"><?php echo $address;?></textarea>
			<button type="submit" class="btn btn-primary" name="save-customer">Save</button>
			<a href="<?php echo $base_url;?>/views/display/customers.php" class="btn btn-primary">Cancel</a>
		</div> <!-- /form-input -->
	</form>

</div> <!-- /content -->

</body>
</html>




