<?php namespace suzuki\process_forms;
require_once 'functions.php';
use suzuki\fetch_functions;
use suzuki\process_forms;




// TRIGGERS
if(isset($_POST['login-user']))
	login_user();

if(isset($_POST['create-user']))
	create_user();

if(isset($_POST['deactivate-user']))
	deactivate_user();

if(isset($_POST['reactivate-user']))
	reactivate_user();

if(isset($_POST['update-user']))
	update_user();

if(isset($_POST['delete-user']))
	delete_user();

if(isset($_POST['save-supplier']))
	save_supplier();

if(isset($_POST['save-customer']))
	save_customer();

if(isset($_POST['supplierCodeChanged']))
	supplier_name_address_autochange();

if(isset($_POST['supplierNameChanged']))
	supplier_code_address_autochange();

if(isset($_POST['customerCodeChanged']))
	customer_name_address_autochange();

if(isset($_POST['customerNameChanged']))
	customer_code_address_autochange();

if(isset($_POST['save-parts']))
	save_parts();

if(isset($_POST['save-more-parts']))
	save_more_parts();

if(isset($_POST['setAdditionalParts']))
	set_additional_parts();

if(isset($_POST['save-issues']))
	save_issues();

if(isset($_POST['save-more-issues']))
	save_more_issues();

if(isset($_POST['issuesReceived']))
	issues_received();

if(isset($_POST['save-delivery']))
	save_delivery();

if(isset($_POST['save-additional-delivery']))
	save_additional_delivery();

if(isset($_POST['save-backup-delivery']))
	save_backup_delivery();

if(isset($_POST['save-more-delivery']))
	save_more_delivery();

if(isset($_POST['setFrameAndEngine'])){
	set_frame_engine_number();
}

if(isset($_POST['save-return-order']))
	save_return_order();

if(isset($_POST['save-more-return-order']))
	save_more_return_order();

if(isset($_POST['setReturnOrder']))
	set_return_order();

if(isset($_POST['getSuppliers']))
	get_suppliers();

if(isset($_POST['getCustomers']))
	get_customers();

if(isset($_POST['save-purchase-requisitions']))
	save_purchase_requisitions();

if(isset($_POST['save-more-requisitions']))
	save_more_requisitions();

if(isset($_POST['save-claims']))
	save_claims();

if(isset($_POST['update-claim-image']))
	update_claim_image();

if(isset($_POST['delete-claim-image']))
	delete_claim_image();

if(isset($_POST['save-more-claims']))
	save_more_claims();

if(isset($_POST['entriesInlineEdit']))
	entries_inline_edit();

if(isset($_POST['partsInlineEdit']))
	parts_inline_edit();

if(isset($_POST['change-password']))
	change_password();

if(isset($_GET['del']) && isset($_GET['param']) && isset($_GET['target']) && isset($_GET['entity']) && isset($_GET['page'])){
	if(htmlspecialchars($_GET['del']) == "execute"){
		$key = htmlspecialchars($_GET['param']);
		$id = htmlspecialchars($_GET['target']);
		$table = htmlspecialchars($_GET['entity']);
		$page = htmlspecialchars($_GET['page']);
		delete_row($key, $id, $table, $page);
	}
}

if(isset($_POST['deleteRelatedRows']))
	delete_related_rows();

if(isset($_POST['setSessionVars'])){
	$_SESSION['entries'] = htmlspecialchars($_POST['entries']);
	$_SESSION['parts'] = htmlspecialchars($_POST['parts']);
	$_SESSION['key_id'] = htmlspecialchars($_POST['key']);
	$_SESSION['action'] = htmlspecialchars($_POST['action']);
	$_SESSION['file_name'] = htmlspecialchars($_POST['fileName']);
}

if(isset($_POST['updatePage']))
	update_page();









function login_user(){
	global $con;
	global $base_url;
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);
	$user = fetch_functions\get_row('users','USER_NAME',$username)[0];
	$_SESSION['username'] = $username;
	if($user){
		$user = $user->USER_NAME;
		$stmt = $con->prepare("SELECT * FROM users WHERE USER_NAME = :USER_NAME");
		$stmt->execute(array('USER_NAME' => $user));
		$authenticated = $stmt->fetch(\PDO::FETCH_OBJ);
		if($authenticated->ACCOUNT_STATUS == 0){
			$deactivated_by = fetch_functions\get_row('users','USER_ID',$authenticated->DEACTIVATED_BY)[0]->USER_NAME;
			$_SESSION['msg'] = "Your account has been deactivated by $deactivated_by on " . date("d M, Y", $authenticated->UPDATED_AT) . ". Please contact a admin or super admin regarding the issue";
			echo "<script>location.href='$base_url';</script>";
			die();
		}
		if($authenticated->USER_PASSWORD == hash('sha512', $password)){
			$_SESSION['rancon_user_id'] = $authenticated->USER_ID;
			$_SESSION['rancon_access_level'] = $authenticated->USER_ACCESS_LEVEL;
			echo "<script>location.href='$base_url/views/display/dashboard.php';</script>";
			die();
		}
		else{
			$_SESSION['msg'] = "Username or password do not match";
		}		
	}
	else{
		$_SESSION['msg'] = "Wrong username - This user does not exist in the database";
	}
	echo "<script>location.href='$base_url';</script>";
	die();
}









function create_user(){
	global $con;
	global $base_url;
	if(!isset($_SESSION['rancon_access_level']) || $_SESSION['rancon_access_level'] < 2 || $_SESSION['rancon_access_level'] > 3){
		echo "<script>location.href = '$base_url/views/display/users.php';</script>";
		die();
	}
	$full_name = htmlspecialchars($_POST['full-name']);
	$username = htmlspecialchars($_POST['username']);
	$username_check = fetch_functions\get_row('users','USER_NAME',$username)[0];
	if($username_check){
		$_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; The user name $username already exists. User name for each user must be unique</p>";
		echo "<script>location.href = '$base_url/views/display/users.php';</script>";
		die();
	}
	$user_access_level = htmlspecialchars($_POST['user-access-level']);
	$default_password = hash('sha512', 'rancon_123');
	if($_SESSION['rancon_access_level'] == 2 || $_SESSION['rancon_access_level'] == 3){
		if($_SESSION['rancon_access_level'] == 2){
			$user_created = fetch_functions\get_row('users','CREATED_BY',$_SESSION['rancon_user_id']);
			if(sizeof($user_created) > 3){
				$_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; You have already created maximum number of user (3). Please contact with a super admin to create more accounts</p>";
				die();		
			}	
		}
		$stmt = $con->prepare("INSERT INTO users (USER_FULL_NAME, USER_NAME, USER_PASSWORD, USER_ACCESS_LEVEL, CREATED_BY) VALUES (:USER_FULL_NAME, :USER_NAME, :USER_PASSWORD, :USER_ACCESS_LEVEL, :CREATED_BY)");
		$executed = $stmt->execute(array('USER_FULL_NAME' => $full_name, 'USER_NAME' => $username, 'USER_PASSWORD' => $default_password, 'USER_ACCESS_LEVEL' => $user_access_level, 'CREATED_BY' => $_SESSION['rancon_user_id']));
		if($executed){
			$_SESSION['msg'] = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; New user successfully created</p>";
		}
		else{
			$_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. User could not be created.</p>";
		}
	}
	echo "<script>location.href = '$base_url/views/display/users.php';</script>";
	die();
}









function deactivate_user(){
	global $con;
	global $base_url;
	if(!isset($_SESSION['rancon_access_level']) || $_SESSION['rancon_access_level'] < 2 || $_SESSION['rancon_access_level'] > 3){
		echo "<script>location.href = '$base_url/views/display/users.php';</script>";
		die();
	}
	$username = htmlspecialchars($_POST['username']);
	$reason = htmlspecialchars($_POST['reason']);
	$user_id = fetch_functions\get_row('users','USER_NAME',$username)[0]->USER_ID;
	$stmt = $con->prepare("UPDATE users SET ACCOUNT_STATUS = :ACCOUNT_STATUS, DEACTIVATED_BY = :DEACTIVATED_BY, DEACTIVATION_REASON = :DEACTIVATION_REASON, UPDATED_AT = :UPDATED_AT WHERE USER_ID = :USER_ID");
	$executed = $stmt->execute(array('ACCOUNT_STATUS' => 0, 'DEACTIVATED_BY' => $_SESSION['rancon_user_id'], 'DEACTIVATION_REASON' => $reason, 'UPDATED_AT' => time() + 4*60*60, 'USER_ID' => $user_id));
	if($executed){
		$_SESSION['msg'] = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; User account of $username has been deactivated</p>";
	}
	else{
		$_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. Request could not be processed. Please try again later.</p>";
	}
	echo "<script>location.href = '$base_url/views/display/users.php';</script>";
	die();
}









function reactivate_user(){
	global $con;
	global $base_url;
	if(!isset($_SESSION['rancon_access_level']) || $_SESSION['rancon_access_level'] < 2 || $_SESSION['rancon_access_level'] > 3){
		echo "<script>location.href = '$base_url/views/display/users.php';</script>";
		die();
	}
	$user_id = htmlspecialchars($_POST['user-id']);
	$stmt = $con->prepare("UPDATE users SET ACCOUNT_STATUS = :ACCOUNT_STATUS WHERE USER_ID = :USER_ID");
	$executed = $stmt->execute(array('ACCOUNT_STATUS' => 1, 'USER_ID' => $user_id));
	if($executed){
		$_SESSION['msg'] = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; User account of $username has been reactivated</p>";
	}
	else{
		$_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. Request could not be processed. Please try again later.</p>";
	}
	echo "<script>location.href = '$base_url/views/display/users.php';</script>";
	die();
}









function update_user(){
	global $con;
	global $base_url;
	if(!isset($_SESSION['rancon_access_level']) || $_SESSION['rancon_access_level'] < 2 || $_SESSION['rancon_access_level'] > 3){
		echo "<script>location.href = '$base_url/views/display/users.php';</script>";
		die();
	}
	$username = htmlspecialchars($_POST['username']);
	$full_name = htmlspecialchars($_POST['full-name']);
	$user_access = htmlspecialchars($_POST['user-access']);
	if(!$user_access){
		$user_access = fetch_functions\get_row('users','USER_NAME',$username)[0]->USER_ACCESS_LEVEL;
	}
	$stmt = $con->prepare("UPDATE users SET USER_FULL_NAME = :USER_FULL_NAME, USER_ACCESS_LEVEL = :USER_ACCESS_LEVEL WHERE USER_NAME = :USER_NAME");
	$executed = $stmt->execute(array('USER_FULL_NAME' => $full_name, 'USER_ACCESS_LEVEL' => $user_access, 'USER_NAME' => $username));
	if($executed){
		$_SESSION['msg'] = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; User account of $username has been deactivated</p>";
	}
	else{
		$_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. Request could not be processed. Please try again later.</p>";
	}
	echo "<script>location.href = '$base_url/views/display/users.php';</script>";
	die();
}









function delete_user(){
	global $con;
	global $base_url;
	if(!isset($_SESSION['rancon_access_level']) || $_SESSION['rancon_access_level'] < 2 || $_SESSION['rancon_access_level'] > 3){
		echo "<script>location.href = '$base_url/views/display/users.php';</script>";
		die();
	}
	$user_id = htmlspecialchars($_POST['user-id']);
	$stmt = $con->prepare("DELETE FROM users WHERE USER_ID = :USER_ID");
	$executed = $stmt->execute(array('USER_ID' => $user_id));
	if($executed){
		$_SESSION['msg'] = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; User account of $username has been deleted</p>";
	}
	else{
		$_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. Request could not be processed. Please try again later.</p>";
	}
	echo "<script>location.href = '$base_url/views/display/users.php';</script>";
	die();
}









function save_supplier(){
	global $con;
	global $base_url;
	$id = htmlspecialchars($_POST['supplier-code']);
	$action = htmlspecialchars($_POST['action']);
	$name = htmlspecialchars($_POST['full-name']);
	$email = htmlspecialchars($_POST['email']);
	$phone_office = htmlspecialchars($_POST['phone-office']);
	$mobile_number = htmlspecialchars($_POST['mobile-number']);
	$fax = htmlspecialchars($_POST['fax']);
	$website = htmlspecialchars($_POST['website']);
	$country = htmlspecialchars($_POST['country']);
	$city = htmlspecialchars($_POST['city']);
	$address = htmlspecialchars($_POST['address']);

	if($action == "create"){
		$success_msg = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; New Supplier Inserted</p>";
		$stmt = $con->prepare("INSERT INTO suppliers (SUPPLIER_NAME, SUPPLIER_ADDRESS, SUPPLIER_CITY, COUNTRY, SUPPLIER_PHONE_OFFICE, SUPPLIER_PHONE_MOBILE, SUPPLIER_EMAIL, SUPPLIER_FAX, SUPPLIER_WEBSITE) VALUES (:SUPPLIER_NAME, :SUPPLIER_ADDRESS, :SUPPLIER_CITY, :COUNTRY, :SUPPLIER_PHONE_OFFICE, :SUPPLIER_PHONE_MOBILE, :SUPPLIER_EMAIL, :SUPPLIER_FAX, :SUPPLIER_WEBSITE)");

		$executed = $stmt->execute(array('SUPPLIER_NAME' => $name, 'SUPPLIER_ADDRESS' => $address, 'SUPPLIER_CITY' => $city, 'COUNTRY' => $country, 'SUPPLIER_PHONE_OFFICE' => $phone_office, 'SUPPLIER_PHONE_MOBILE' => $mobile_number, 'SUPPLIER_EMAIL' => $email, 'SUPPLIER_FAX' => $fax, 'SUPPLIER_WEBSITE' => $website));
	}

	elseif($action == "modify"){
		$success_msg = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; Supplier Information Updated</p>";
		$stmt = $con->prepare("UPDATE suppliers SET SUPPLIER_NAME = :SUPPLIER_NAME, SUPPLIER_ADDRESS = :SUPPLIER_ADDRESS, SUPPLIER_CITY = :SUPPLIER_CITY, COUNTRY = :COUNTRY, SUPPLIER_PHONE_OFFICE = :SUPPLIER_PHONE_OFFICE, SUPPLIER_PHONE_MOBILE = :SUPPLIER_PHONE_MOBILE, SUPPLIER_EMAIL = :SUPPLIER_EMAIL, SUPPLIER_FAX = :SUPPLIER_FAX, SUPPLIER_WEBSITE = :SUPPLIER_WEBSITE WHERE SUPPLIER_CODE = :SUPPLIER_CODE");

		$executed = $stmt->execute(array('SUPPLIER_NAME' => $name, 'SUPPLIER_ADDRESS' => $address, 'SUPPLIER_CITY' => $city, 'COUNTRY' => $country, 'SUPPLIER_PHONE_OFFICE' => $phone_office, 'SUPPLIER_PHONE_MOBILE' => $mobile_number, 'SUPPLIER_EMAIL' => $email, 'SUPPLIER_FAX' => $fax, 'SUPPLIER_WEBSITE' => $website, 'SUPPLIER_CODE' => $id));
	}

	if($executed){
		$_SESSION['msg'] = $success_msg;
	}
	else{
		$_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something Went Wrong. Request Could Not Be Processed Correctly</p>";
	}

	echo "<script>window.location.href='$base_url/views/display/suppliers.php';</script>";
	die();
}









function save_customer(){
	global $con;
	$id = htmlspecialchars($_POST['id']);
	$action = htmlspecialchars($_POST['action']);
	if($action == "create" && empty($id)){
		$stmt = $con->query("SELECT max(CUSTOMER_ID) AS id FROM customers");
		$id = $stmt->fetch()['id'] + 1;
	}
	$name = htmlspecialchars($_POST['full-name']);
	$email = htmlspecialchars($_POST['email']);
	$phone_office = htmlspecialchars($_POST['phone-office']);
	$phone = htmlspecialchars($_POST['phone']);
	$mobile_number = htmlspecialchars($_POST['mobile-number']);
	$fax = htmlspecialchars($_POST['fax']);
	$website = htmlspecialchars($_POST['website']);
	$city = htmlspecialchars($_POST['city']);
	$customer_type = htmlspecialchars($_POST['customer-type']);
	$address = htmlspecialchars($_POST['address']);

	if($action == "create"){
		$success_msg = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; New Customer Inserted</p>";
		$stmt = $con->prepare("INSERT INTO customers (CUSTOMER_ID, CUSTOMER_NAME, CUSTOMER_ADDRESS, CUSTOMER_CITY, CUSTOMER_PHONE_OFFICE, CUSTOMER_PHONE_OPTIONAL, CUSTOMER_PHONE_MOBILE, CUSTOMER_EMAIL, CUSTOMER_FAX, CUSTOMER_WEBSITE, CUSTOMER_TYPE) VALUES (:CUSTOMER_ID, :CUSTOMER_NAME, :CUSTOMER_ADDRESS, :CUSTOMER_CITY, :CUSTOMER_PHONE_OFFICE, :CUSTOMER_PHONE_OPTIONAL, :CUSTOMER_PHONE_MOBILE, :CUSTOMER_EMAIL, :CUSTOMER_FAX, :CUSTOMER_WEBSITE, :CUSTOMER_TYPE)");	
	}

	elseif($action == "modify"){
		$success_msg = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; Customer Information Updated</p>";
		$stmt = $con->prepare("UPDATE customers SET CUSTOMER_NAME = :CUSTOMER_NAME, CUSTOMER_ADDRESS = :CUSTOMER_ADDRESS, CUSTOMER_CITY = :CUSTOMER_CITY, CUSTOMER_PHONE_OFFICE = :CUSTOMER_PHONE_OFFICE, CUSTOMER_PHONE_OPTIONAL = :CUSTOMER_PHONE_OPTIONAL, CUSTOMER_PHONE_MOBILE = :CUSTOMER_PHONE_MOBILE, CUSTOMER_EMAIL = :CUSTOMER_EMAIL, CUSTOMER_FAX = :CUSTOMER_FAX, CUSTOMER_WEBSITE = :CUSTOMER_WEBSITE, CUSTOMER_TYPE = :CUSTOMER_TYPE WHERE CUSTOMER_ID = :CUSTOMER_ID");
	}

	$executed = $stmt->execute(array('CUSTOMER_ID' => $id, 'CUSTOMER_NAME' => $name, 'CUSTOMER_ADDRESS' => $address, 'CUSTOMER_CITY' => $city, 'CUSTOMER_PHONE_OFFICE' => $phone_office, 'CUSTOMER_PHONE_OPTIONAL' => $phone, 'CUSTOMER_PHONE_MOBILE' => $mobile_number, 'CUSTOMER_EMAIL' => $email, 'CUSTOMER_FAX' => $fax, 'CUSTOMER_WEBSITE' => $website, 'CUSTOMER_TYPE' => $customer_type));

	if($executed){
		$_SESSION['msg'] = $success_msg;
		header("Location:../views/display/customers.php");
	}
	else{
		$_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something Went Wrong. Request Could Not Be Processed Correctly</p>";
	}
}









function supplier_name_address_autochange(){
	global $con;
	$val = htmlspecialchars($_POST['val']);
	$html = "";
	if(empty($val)){
		echo "";
		die();
	}
	else{
		$supplier = fetch_functions\get_row('suppliers','SUPPLIER_CODE',$val)[0];
	}
	echo $supplier->SUPPLIER_NAME . "//" . $supplier->SUPPLIER_ADDRESS . "//" . $supplier->SUPPLIER_PHONE_OFFICE . "//" . $supplier->SUPPLIER_EMAIL;
}









function supplier_code_address_autochange(){
	global $con;
	$val = htmlspecialchars($_POST['val']);
	$html = "";
	if(empty($val)){
		echo "";
		die();
	}
	else{
		$stmt = $con->prepare("SELECT * FROM suppliers WHERE SUPPLIER_NAME LIKE :SUPPLIER_NAME LIMIT 10");
		$stmt->execute(array('SUPPLIER_NAME' => $val . '%'));
		$results = $stmt->fetchAll(\PDO::FETCH_OBJ);
		if(empty($results)){
			$html = "<li>No result found</li>";	
		} 
		else{
			for ($i = 0; $i < sizeof($results); $i++) {
				if(sizeof($results) == 1){
					$html = "<li class='first-child last-child'";
				}
				else{
					if($i == 0)	$html .= "<li class='first-child' ";
					else if($i == sizeof($results) - 1)	$html .= "<li class='last-child' ";
					else $html .= "<li ";	
				}
				$name = $results[$i]->SUPPLIER_NAME;
				$address = $results[$i]->SUPPLIER_ADDRESS;
				$code = $results[$i]->SUPPLIER_CODE;
				$contact = $results[$i]->SUPPLIER_PHONE_OFFICE;
				$email = $results[$i]->SUPPLIER_EMAIL;
				$html .= "data-address = '$address' data-code = '$code' data-contact = '$contact' data-email = '$email'>$name</li>";
			}	
		}
	}
	echo $html;
}









function customer_name_address_autochange(){
	global $con;
	$val = htmlspecialchars($_POST['val']);
	$html = "";
	if(empty($val)){
		echo "";
		die();
	}
	else{
		$customer = fetch_functions\get_row('customers','CUSTOMER_ID',$val)[0];
	}
	echo $customer->CUSTOMER_NAME . "//" . $customer->CUSTOMER_ADDRESS . "//" . $customer->CUSTOMER_PHONE_OFFICE . "//" . $customer->CUSTOMER_EMAIL;
}









function customer_code_address_autochange(){
	global $con;
	$val = htmlspecialchars($_POST['val']);
	$html = "";
	if(empty($val)){
		echo "";
		die();
	}
	else{
		$stmt = $con->prepare("SELECT * FROM customers WHERE CUSTOMER_NAME LIKE :CUSTOMER_NAME LIMIT 10");
		$stmt->execute(array('CUSTOMER_NAME' => $val . '%'));
		$results = $stmt->fetchAll(\PDO::FETCH_OBJ);
		if(empty($results)){
			$html = "<li>No result found</li>";	
		} 
		else{
			for ($i = 0; $i < sizeof($results); $i++) {
				if(sizeof($results) == 1){
					$html = "<li class='first-child last-child'";
				}
				else{
					if($i == 0)	$html .= "<li class='first-child' ";
					else if($i == sizeof($results) - 1)	$html .= "<li class='last-child' ";
					else $html .= "<li ";	
				}
				$name = $results[$i]->CUSTOMER_NAME;
				$address = $results[$i]->CUSTOMER_ADDRESS;
				$code = $results[$i]->CUSTOMER_ID;
				$contact = $results[$i]->CUSTOMER_PHONE_OFFICE;
				$email = $results[$i]->CUSTOMER_EMAIL;
				$html .= "data-address = '$address' data-code = '$code' data-contact = '$contact' data-email = '$email'>$name</li>";
			}	
		}
	}
	echo $html;
}









function save_parts(){
	global $con;
	global $base_url;
	$requisition_number = htmlspecialchars($_POST['requisition-number']);
	$date = htmlspecialchars($_POST['date']);
	$parts = explode("-", $date);
	$date = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
	$site = htmlspecialchars($_POST['site']);
	$supplier_code = htmlspecialchars($_POST['supplier-code']);
	$invoice_no = htmlspecialchars($_POST['invoice-no']);
	$lc_no = htmlspecialchars($_POST['lc-no']);
	$lot_no = htmlspecialchars($_POST['lot-no']);
	$challan_no = htmlspecialchars($_POST['challan-no']);
	$ppd_no = htmlspecialchars($_POST['ppd-no']);
	$type = htmlspecialchars($_POST['type']);

	$stmt = $con->prepare("INSERT INTO entries (REQUISITION_NUMBER, ENTRY_DATE, TYPE, SITE, SUPPLIER_CODE, INVOICE_NUMBER, LC_NUMBER, LOT_NUMBER, PPD_NUMBER, SUPPLIER_CHALLAN_NUMBER) VALUES (:REQUISITION_NUMBER, :ENTRY_DATE, :TYPE, :SITE, :SUPPLIER_CODE, :INVOICE_NUMBER, :LC_NUMBER, :LOT_NUMBER, :PPD_NUMBER, :SUPPLIER_CHALLAN_NUMBER)");

	$executed = $stmt->execute(array('REQUISITION_NUMBER' => $requisition_number, 'ENTRY_DATE' => $date, 'TYPE' => $type, 'SITE' => $site, 'SUPPLIER_CODE' => $supplier_code, 'INVOICE_NUMBER' => $invoice_no, 'LC_NUMBER' => $lc_no, 'LOT_NUMBER' => $lot_no, 'PPD_NUMBER' => $ppd_no, 'SUPPLIER_CHALLAN_NUMBER' => $challan_no ));

	if($executed){		
		$msg = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; New data successfully inserted in the database.</p>";
		$reference_id = $con->lastInsertId();
		parts_entry($reference_id,$con,$type);
	}
	else{
		$msg = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. Request could not be processed.</p>";
	}

	echo "<script>window.location.href = '$base_url/views/display/$type.php';</script>";
	die();
}








function save_more_parts(){
	global $con;
	global $base_url;
	$ref = htmlspecialchars($_POST['reference']);
	$type = htmlspecialchars($_POST['type']);
	parts_entry($ref,$con,$type);
	echo "<script>window.location.href = '$base_url/views/display/$type-details.php?ref=$ref';</script>";
	die();
}









function parts_entry($reference_id, $con, $type){
	$size = sizeof($_POST['part-number']);
	$duplicate_engines = $duplicate_frames = [];
	for($i = 0; $i < $size; $i++){
		if($type == "ckd" || $type == "ckdbom" || $type == "cbu"){
			if(!empty($_POST['part-number'][$i]) || !empty($_POST['part-name'][$i]) || !empty($_POST['model'][$i]) || !empty($_POST['color-code'][$i]) || !empty($_POST['color-name'][$i]) || !empty($_POST['quantity'][$i]) || !empty($_POST['unit'][$i]) || !empty($_POST['frame-number'][$i]) || !empty($_POST['engine-number'][$i]) || !empty($_POST['remarks'][$i])){

				$this_engine_number = htmlspecialchars($_POST['engine-number'][$i]);
				$this_frame_number = htmlspecialchars($_POST['frame-number'][$i]);
				if(!empty($this_engine_number)) $engine_found = fetch_functions\get_row('parts','ENGINE_NUMBER',$this_engine_number)[0];
				if(!empty($this_frame_number)) $frame_found = fetch_functions\get_row('parts','FRAME_NUMBER',$this_frame_number)[0];
				if($engine_found || $frame_found){
					if($engine_found && !in_array($this_engine_number, $duplicate_engines)) 
						array_push($duplicate_engines, $this_engine_number);
					if($frame_found && !in_array($this_frame_number, $duplicate_frames)) 
						array_push($duplicate_frames, $this_frame_number);
				}
				else{
					$stmt = $con->prepare("INSERT INTO parts (ENTRY_REFERENCE_ID, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME, QUANTITY, UNIT, FRAME_NUMBER, ENGINE_NUMBER, REMARKS) VALUES (:ENTRY_REFERENCE_ID, :PART_NUMBER, :PART_NAME, :MODEL, :COLOR_CODE, :COLOR_NAME, :QUANTITY, :UNIT, :FRAME_NUMBER, :ENGINE_NUMBER, :REMARKS)");

					$executed = $stmt->execute(array('ENTRY_REFERENCE_ID' => $reference_id, 'PART_NUMBER' => htmlspecialchars($_POST['part-number'][$i]), 'PART_NAME' => htmlspecialchars($_POST['part-name'][$i]), 'MODEL' => htmlspecialchars($_POST['model'][$i]), 'COLOR_CODE' => htmlspecialchars($_POST['color-code'][$i]), 'COLOR_NAME' => htmlspecialchars($_POST['color-name'][$i]), 'QUANTITY' => htmlspecialchars($_POST['quantity'][$i]), 'UNIT' => htmlspecialchars($_POST['unit'][$i]), 'FRAME_NUMBER' => htmlspecialchars($_POST['frame-number'][$i]), 'ENGINE_NUMBER' => htmlspecialchars($_POST['engine-number'][$i]), 'REMARKS' => htmlspecialchars($_POST['remarks'][$i])));	
				}
			}  
		} 	


		if($type == "manufacturing-parts" || $type == "spare-parts" || $type == "additional-parts"){
			if(!empty($_POST['part-number'][$i]) || !empty($_POST['part-name'][$i]) || !empty($_POST['model'][$i]) || !empty($_POST['color-code'][$i]) || !empty($_POST['color-name'][$i]) || !empty($_POST['quantity'][$i]) || !empty($_POST['unit'][$i]) || !empty($_POST['remarks'][$i])){

				$stmt = $con->prepare("INSERT INTO parts (ENTRY_REFERENCE_ID, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME, QUANTITY, UNIT, REMARKS) VALUES (:ENTRY_REFERENCE_ID, :PART_NUMBER, :PART_NAME, :MODEL, :COLOR_CODE, :COLOR_NAME, :QUANTITY, :UNIT, :REMARKS)");

				$executed = $stmt->execute(array('ENTRY_REFERENCE_ID' => $reference_id, 'PART_NUMBER' => htmlspecialchars($_POST['part-number'][$i]), 'PART_NAME' => htmlspecialchars($_POST['part-name'][$i]), 'MODEL' => htmlspecialchars($_POST['model'][$i]), 'COLOR_CODE' => htmlspecialchars($_POST['color-code'][$i]), 'COLOR_NAME' => htmlspecialchars($_POST['color-name'][$i]), 'QUANTITY' => htmlspecialchars($_POST['quantity'][$i]), 'UNIT' => htmlspecialchars($_POST['unit'][$i]), 'REMARKS' => htmlspecialchars($_POST['remarks'][$i])));
			}  
		}
	} 	// FOR END


	// UPLOAD EXCEL
	require_once '../excel-reader/Classes/PHPExcel/IOFactory.php';
	if(!empty($_FILES['file']['tmp_name'])){
		$excel_object = \PHPExcel_IOFactory::load($_FILES['file']['tmp_name']);
		$rows = $excel_object->getActiveSheet()->toArray(null);
		$size = sizeof($rows);
		for ($i=1; $i < $size; $i++) {
			$this_engine_number = htmlspecialchars($rows[$i][8]);
			$this_frame_number = htmlspecialchars($rows[$i][7]);
			if(!empty($this_engine_number)) $engine_found = fetch_functions\get_row('parts','ENGINE_NUMBER',$this_engine_number)[0];
			if(!empty($this_frame_number)) $frame_found = fetch_functions\get_row('parts','FRAME_NUMBER',$this_frame_number)[0];
			if($engine_found || $frame_found){
				if($engine_found && !in_array($this_engine_number, $duplicate_engines)) 
					array_push($duplicate_engines, $this_engine_number);
				if($frame_found && !in_array($this_frame_number, $duplicate_frames)) 
					array_push($duplicate_frames, $this_frame_number);
			}
			else{
				$stmt = $con->prepare("INSERT INTO parts (ENTRY_REFERENCE_ID, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME, QUANTITY, UNIT, FRAME_NUMBER, ENGINE_NUMBER, REMARKS) VALUES (:ENTRY_REFERENCE_ID, :PART_NUMBER, :PART_NAME, :MODEL, :COLOR_CODE, :COLOR_NAME, :QUANTITY, :UNIT, :FRAME_NUMBER, :ENGINE_NUMBER, :REMARKS)");				

				$executed = $stmt->execute(array('ENTRY_REFERENCE_ID' => $reference_id, 'PART_NUMBER' => htmlspecialchars($rows[$i][0]), 'PART_NAME' => htmlspecialchars($rows[$i][1]), 'MODEL' => htmlspecialchars($rows[$i][2]), 'COLOR_CODE' => htmlspecialchars($rows[$i][3]), 'COLOR_NAME' => htmlspecialchars($rows[$i][4]), 'QUANTITY' => htmlspecialchars($rows[$i][5]), 'UNIT' => htmlspecialchars($rows[$i][6]), 'FRAME_NUMBER' => htmlspecialchars($rows[$i][7]), 'ENGINE_NUMBER' => htmlspecialchars($rows[$i][8]), 'REMARKS' => htmlspecialchars($rows[$i][9])));
			}
		}
	} // EXCEL UPLOAD END


	$eng_msg = $frame_msg = "";
	$arr_size = sizeof($duplicate_engines);
	if($arr_size > 0){
		if($arr_size == 1) $s = "";
		else $s = "s";
		foreach ($duplicate_engines as $e){
			$eng_msg .= ", <span class='text-danger'>$e</span>";
		}
		$eng_msg = substr($eng_msg, 2);
		$eng_msg = "The following engine number$s already exists and therefore the corresponding rows could not be inserted : " . $eng_msg . "<br>";
	}

	$arr_size = sizeof($duplicate_frames);
	if($arr_size > 0){
		if($arr_size == 1) $s = "";
		else $s = "s";
		foreach ($duplicate_frames as $f){
			$frame_msg .= ", <span class='text-danger'>$f</span>";
		}
		$frame_msg = substr($frame_msg, 2);
		$frame_msg = "The following frame number$s already exists and therefore the corresponding rows could not be inserted : " . $frame_msg;
	}
	if(!empty($eng_msg) || !empty($frame_msg)){ 
		$_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i></p>$eng_msg<br>$frame_msg";
	}
	else{
		$_SESSION['msg'] = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; New data successfully inserted in the database.</p>";
	}
}









function set_additional_parts(){
	global $con;
	$arr = array();
	$req_num = htmlspecialchars($_POST['reqNum']);
	$stmt = $con->prepare("SELECT * FROM purchase_requisitions WHERE KEY_ID = :KEY_ID");
	$executed = $stmt->execute(array('KEY_ID' => $req_num));
	if($executed){
		$req_info = $stmt->fetch(\PDO::FETCH_OBJ);
		$parts = explode("-", $req_info->REQUISITION_DATE);
		$date = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
		array_push($arr, $date);
		array_push($arr, $req_info->SITE);
		array_push($arr, $req_info->SUPPLIER_CODE);
		$supplier = fetch_functions\get_row('suppliers','SUPPLIER_CODE',$req_info->SUPPLIER_CODE)[0];
		array_push($arr, $supplier->SUPPLIER_NAME);
		array_push($arr, $supplier->SUPPLIER_ADDRESS);

		$stmt = $con->prepare("SELECT * FROM purchase_requisitions_parts WHERE ENTRY_REFERENCE_ID = :ENTRY_REFERENCE_ID");
		$executed = $stmt->execute(array('ENTRY_REFERENCE_ID' => $req_num));
		$rows = $stmt->fetchAll(\PDO::FETCH_OBJ);
		if($executed){
			$str = "";
			foreach ($rows as $row) {
				$str .= "<tr><td class='delete'><i class='fa fa-trash trash-it'></i></td><td><input type='text' name='part-number[]' placeholder='Part Number' value='$row->PART_NUMBER'></td><td><input type='text' name='part-name[]' placeholder='Part Name' value='$row->PART_NAME'></td><td><input type='text' name='model[]' placeholder='Model' value='$row->MODEL'></td><td><input type='text' name='color-code[]' placeholder='Color Code' value='$row->COLOR_CODE'></td><td><input type='text' name='color-name[]' placeholder='Color Name' value='$row->COLOR_NAME'></td><td><input type='text' name='quantity[]' placeholder='Quantity' value='$row->QUANTITY'></td><td><input type='text' name='unit[]' value='$row->UNIT' placeholder='Unit'></td><td><input type='text' name='remarks[]' placeholder='Remarks' value='$row->REMARKS'></td></tr>";
			}
			array_push($arr, $str);
			echo json_encode($arr);
		}
		else echo "error";
	}
	else echo "error";
}









function save_issues(){
	global $con;
	global $base_url;
	$date = htmlspecialchars($_POST['date']);
	$parts = explode("-", $date);
	$date = $parts[2] . $parts[1] . $parts[0];
	$site = htmlspecialchars($_POST['site']);
	$name = htmlspecialchars($_POST['name']);
	$designation = htmlspecialchars($_POST['designation']);
	$department = htmlspecialchars($_POST['department']);
	$invoice_no = htmlspecialchars($_POST['invoice-no']);
	$lc_no = htmlspecialchars($_POST['lc-no']);
	$lot_no = htmlspecialchars($_POST['lot-no']);
	$ppd_no = htmlspecialchars($_POST['ppd-no']);
	$type = htmlspecialchars($_POST['type']);
	$received = htmlspecialchars($_POST['received']);
	$reference_number = htmlspecialchars($_POST['reference-number']);

	$stmt = $con->prepare("INSERT INTO issues (TYPE, RECEIVED, ENTRY_DATE, SITE, NAME, DESIGNATION, DEPARTMENT, INVOICE_NUMBER, LC_NUMBER, LOT_NUMBER, PPD_NUMBER) VALUES (:TYPE, :RECEIVED, :ENTRY_DATE, :SITE, :NAME, :DESIGNATION, :DEPARTMENT, :INVOICE_NUMBER, :LC_NUMBER, :LOT_NUMBER, :PPD_NUMBER)");
	$executed = $stmt->execute(array('TYPE' => $type, 'RECEIVED' => $received, 'ENTRY_DATE' => $date, 'SITE' => $site, 'NAME' => $name, 'DESIGNATION' => $designation, 'DEPARTMENT' => $department, 'INVOICE_NUMBER' => $invoice_no, 'LC_NUMBER' => $lc_no, 'LOT_NUMBER' => $lot_no, 'PPD_NUMBER' => $ppd_no ));

	if($executed){
		$msg = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; New data successfully inserted in the database.</p>";
		$reference_id = $con->lastInsertId();
		if($type != "manufacturing-issue"){
			if($reference_number && $received == 1){
				$stmt = $con->prepare("UPDATE issues SET RECEIVED = :RECEIVED WHERE KEY_ID = :ID");
				$executed = $stmt->execute(array('RECEIVED' => 2, 'ID' => $reference_number));
				$stmt = $con->prepare("UPDATE issues SET REFERENCE_NUMBER = :REFERENCE_NUMBER WHERE KEY_ID = :ID");
				$executed = $stmt->execute(array('REFERENCE_NUMBER' => $reference_number, 'ID' => $reference_id));
			}	
		}
		parts_with_issue_entry($reference_id,$con,$type);
	}
	else{
		$msg = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. Request could not be processed.</p>";
	}
	$_SESSION['msg'] = $msg;
	if($received == 1) $type .= "-received";
	echo "<script>window.location.href = '$base_url/views/display/$type.php';</script>";
	die();
}









function save_more_issues(){
	global $con;
	global $base_url;
	$ref = htmlspecialchars($_POST['reference']);
	$type = htmlspecialchars($_POST['type']);
	$msg = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; New data successfully inserted in the database.</p>";
	parts_with_issue_entry($ref,$con,$type);
	$_SESSION['msg'] = $msg;
	$_SESSION['entries'] = "issues";
	$_SESSION['parts'] = "issue_records";
	$_SESSION['key_id'] = $ref;
	echo "<script>window.location.href = '$base_url/views/display/$type-details.php?ref=$ref';</script>";
	die();
}









function parts_with_issue_entry($reference_id, $con, $type){
	$size = sizeof($_POST['part-number']);
	for($i = 0; $i < $size; $i++){
		if($type == "manufacturing-issue" || $type == "spare-issue"){
			if(!empty($_POST['part-number'][$i]) || !empty($_POST['part-name'][$i]) || !empty($_POST['model'][$i]) || !empty($_POST['color-code'][$i]) || !empty($_POST['color-name'][$i]) || !empty($_POST['quantity'][$i]) || !empty($_POST['unit'][$i]) || !empty($_POST['frame-number'][$i])  || !empty($_POST['engine-number'][$i]) || !empty($_POST['remarks'][$i])){

				$stmt = $con->prepare("INSERT INTO issue_records (ENTRY_REFERENCE_ID, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME, QUANTITY, UNIT, FRAME_NUMBER, ENGINE_NUMBER, REMARKS) VALUES (:ENTRY_REFERENCE_ID, :PART_NUMBER, :PART_NAME, :MODEL, :COLOR_CODE, :COLOR_NAME, :QUANTITY, :UNIT, :FRAME_NUMBER, :ENGINE_NUMBER, :REMARKS)");

				$executed = $stmt->execute(array('ENTRY_REFERENCE_ID' => $reference_id, 'PART_NUMBER' => $part_number = htmlspecialchars($_POST['part-number'][$i]), 'PART_NAME' => htmlspecialchars($_POST['part-name'][$i]), 'MODEL' => htmlspecialchars($_POST['model'][$i]), 'COLOR_CODE' => htmlspecialchars($_POST['color-code'][$i]), 'COLOR_NAME' => htmlspecialchars($_POST['color-name'][$i]), 'QUANTITY' => htmlspecialchars($_POST['quantity'][$i]), 'UNIT' => htmlspecialchars($_POST['unit'][$i]), 'FRAME_NUMBER' => htmlspecialchars($_POST['frame-number'][$i]), 'ENGINE_NUMBER' => htmlspecialchars($_POST['engine-number'][$i]), 'REMARKS' => htmlspecialchars($_POST['remarks'][$i])));
			}  
		}


		else{
			if(!empty($_POST['part-number'][$i]) || !empty($_POST['part-name'][$i]) || !empty($_POST['model'][$i]) || !empty($_POST['color-code'][$i]) || !empty($_POST['color-name'][$i]) || !empty($_POST['quantity'][$i]) || !empty($_POST['unit'][$i]) || !empty($_POST['frame-number'][$i]) || !empty($_POST['engine-number'][$i]) || !empty($_POST['cripple-reason'][$i]) || !empty($_POST['remarks'][$i])){

				$stmt = $con->prepare("INSERT INTO issue_records (ENTRY_REFERENCE_ID, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME, QUANTITY, UNIT, FRAME_NUMBER, ENGINE_NUMBER, CRIPPLE_REASON, REMARKS) VALUES (:ENTRY_REFERENCE_ID, :PART_NUMBER, :PART_NAME, :MODEL, :COLOR_CODE, :COLOR_NAME, :QUANTITY, :UNIT, :FRAME_NUMBER, :ENGINE_NUMBER, :CRIPPLE_REASON, :REMARKS)");

				$executed = $stmt->execute(array('ENTRY_REFERENCE_ID' => $reference_id, 'PART_NUMBER' => htmlspecialchars($_POST['part-number'][$i]), 'PART_NAME' => htmlspecialchars($_POST['part-name'][$i]), 'MODEL' => htmlspecialchars($_POST['model'][$i]), 'COLOR_CODE' => htmlspecialchars($_POST['color-code'][$i]), 'COLOR_NAME' => htmlspecialchars($_POST['color-name'][$i]), 'QUANTITY' => htmlspecialchars($_POST['quantity'][$i]), 'UNIT' => htmlspecialchars($_POST['unit'][$i]), 'FRAME_NUMBER' => htmlspecialchars($_POST['frame-number'][$i]), 'ENGINE_NUMBER' => htmlspecialchars($_POST['engine-number'][$i]), 'CRIPPLE_REASON' => htmlspecialchars($_POST['cripple-reason'][$i]), 'REMARKS' => htmlspecialchars($_POST['remarks'][$i])));	
			}  
		} 

		if(!$executed){
			$_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. One or more data could not be inserted.</p>";
			break;
		}		
	}




	// UPLOAD EXCEL
	require_once '../excel-reader/Classes/PHPExcel/IOFactory.php';
	if(!empty($_FILES['file']['tmp_name'])){
		$excel_object = \PHPExcel_IOFactory::load($_FILES['file']['tmp_name']);
		$rows = $excel_object->getActiveSheet()->toArray(null);
		$size = sizeof($rows);
		for ($i=1; $i < $size; $i++) {
			$stmt = $con->prepare("INSERT INTO issue_records (ENTRY_REFERENCE_ID, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME, QUANTITY, UNIT, FRAME_NUMBER, ENGINE_NUMBER, CRIPPLE_REASON, REMARKS) VALUES (:ENTRY_REFERENCE_ID, :PART_NUMBER, :PART_NAME, :MODEL, :COLOR_CODE, :COLOR_NAME, :QUANTITY, :UNIT, :FRAME_NUMBER, :ENGINE_NUMBER, :CRIPPLE_REASON, :REMARKS)");				
			$executed = $stmt->execute(array('ENTRY_REFERENCE_ID' => $reference_id, 'PART_NUMBER' => htmlspecialchars($rows[$i][0]), 'PART_NAME' => htmlspecialchars($rows[$i][1]), 'MODEL' => htmlspecialchars($rows[$i][2]), 'COLOR_CODE' => htmlspecialchars($rows[$i][3]), 'COLOR_NAME' => htmlspecialchars($rows[$i][4]), 'QUANTITY' => htmlspecialchars($rows[$i][5]), 'UNIT' => htmlspecialchars($rows[$i][6]), 'FRAME_NUMBER' => htmlspecialchars($rows[$i][7]), 'ENGINE_NUMBER' => htmlspecialchars($rows[$i][8]), 'CRIPPLE_REASON' => htmlspecialchars($rows[$i][9]), 'REMARKS' => htmlspecialchars($rows[$i][10])));
		}
	}
}









function issues_received(){
	global $con;
	$arr = array();
	$req_num = htmlspecialchars($_POST['reqNum']);
	$type = htmlspecialchars($_POST['type']);
	$stmt = $con->prepare("SELECT * FROM issues WHERE KEY_ID = :KEY_ID AND TYPE = :TYPE");
	$executed = $stmt->execute(array('KEY_ID' => $req_num, 'TYPE' => $type));
	if($executed){
		$issue_info = $stmt->fetch(\PDO::FETCH_OBJ);
		array_push($arr, $issue_info->SITE);
		array_push($arr, $issue_info->NAME);
		array_push($arr, $issue_info->DESIGNATION);
		array_push($arr, $issue_info->DEPARTMENT);
		array_push($arr, $issue_info->INVOICE_NUMBER);
		array_push($arr, $issue_info->LC_NUMBER);
		array_push($arr, $issue_info->LOT_NUMBER);
		array_push($arr, $issue_info->PPD_NUMBER);

		$stmt = $con->prepare("SELECT * FROM issue_records WHERE ENTRY_REFERENCE_ID = :ENTRY_REFERENCE_ID");
		$executed = $stmt->execute(array('ENTRY_REFERENCE_ID' => $issue_info->KEY_ID));
		$rows = $stmt->fetchAll(\PDO::FETCH_OBJ);
		if($executed){
			$str = "";
			foreach ($rows as $row) {
				$str .= "<tr><td class='delete'><i class='fa fa-trash trash-it'></i></td><td><input type='text' name='part-number[]' placeholder='Part Number' value='$row->PART_NUMBER'></td><td><input type='text' name='part-name[]' placeholder='Part Name' value='$row->PART_NAME'></td><td><input type='text' name='model[]' placeholder='Model' value='$row->MODEL'></td><td><input type='text' name='color-code[]' placeholder='Color Code' value='$row->COLOR_CODE'></td><td><input type='text' name='color-name[]' placeholder='Color Name' value='$row->COLOR_NAME'></td><td><input type='text' name='quantity[]' placeholder='Quantity' value='$row->QUANTITY'></td><td><input type='text' name='unit[]' value='$row->UNIT' placeholder='Unit'></td><td><input type='text' name='frame-number[]' placeholder='Frame Number' value='$row->FRAME_NUMBER'></td>";
				
				if($issue_info->TYPE == "manufacturing-issue")	
					$str .= "<td><input type='text' name='engine-number[]' placeholder='Swing Arm Number' value='$row->ENGINE_NUMBER'></td>";
				else
					$str .= "<td><input type='text' name='engine-number[]' placeholder='Engine Number' value='$row->ENGINE_NUMBER'></td>";

				if($issue_info->TYPE == "cripple-issue")
					$str .= "<td><input type='text' name='cripple-reason[]' placeholder='Cripple Reason' value='$row->CRIPPLE_REASON'></td>";

				$str .= "<td><input type='text' name='remarks[]' placeholder='Remarks' value='$row->REMARKS'></td></tr>";
			}
			array_push($arr, $str);
			echo json_encode($arr);
		}
		else echo "error";
	}
	else echo "error";
}









function save_delivery(){
	global $con;
	global $base_url;
	$actual_do_date = htmlspecialchars($_POST['actual-do-date']);
	$parts = explode("-", $actual_do_date);
	$actual_do_date = $parts[2] . $parts[1] . $parts[0];
	$delivery_date = htmlspecialchars($_POST['delivery-date']);
	$parts = explode("-", $delivery_date);
	$delivery_date = $parts[2] . $parts[1] . $parts[0];
	$site = htmlspecialchars($_POST['site']);
	$reference_do_no = htmlspecialchars($_POST['reference-do-no']);
	$reference_co_no = htmlspecialchars($_POST['reference-co-no']);
	$customer_code = htmlspecialchars($_POST['customer-code']);
	$transport_name = htmlspecialchars($_POST['transport-name']);
	$truck_no = htmlspecialchars($_POST['truck-no']);
	$driver_name = htmlspecialchars($_POST['driver-name']);
	$driver_mobile_no = htmlspecialchars($_POST['driver-mobile-no']);
	$sales_channel = htmlspecialchars($_POST['sales-channel']);

	$stmt = $con->prepare("INSERT INTO delivery (DO_DATE, DELIVERY_DATE, SITE, REFERENCE_DO_NUMBER, REFERENCE_CO_NUMBER, CUSTOMER_CODE, TRANSPORT_NAME, TRUCK_NUMBER, DRIVER_NAME, DRIVER_MOBILE_NUMBER, SALES_CHANNEL, ENTRY_TIME ) VALUES (:DO_DATE, :DELIVERY_DATE, :SITE, :REFERENCE_DO_NUMBER,  :REFERENCE_CO_NUMBER, :CUSTOMER_CODE, :TRANSPORT_NAME, :TRUCK_NUMBER, :DRIVER_NAME, :DRIVER_MOBILE_NUMBER, :SALES_CHANNEL, :ENTRY_TIME)");

	$executed = $stmt->execute(array('DO_DATE' => $actual_do_date, 'DELIVERY_DATE' => $delivery_date, 'SITE' => $site, 'REFERENCE_DO_NUMBER' => $reference_do_no, 'REFERENCE_CO_NUMBER' => $reference_co_no, 'CUSTOMER_CODE' => $customer_code, 'TRANSPORT_NAME' => $transport_name, 'TRUCK_NUMBER' => $truck_no, 'DRIVER_NAME' => $driver_name, 'DRIVER_MOBILE_NUMBER' => $driver_mobile_no, 'SALES_CHANNEL' => $sales_channel, 'ENTRY_TIME' => time() + 4*60*60 ));

	if($executed){
		$msg = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; New data successfully inserted in the database.</p>";
		$reference_id = $con->lastInsertId();
		delivery_parts_entry($reference_id, $con, "normal");
		$_SESSION['entries'] = "delivery";
		$_SESSION['parts'] = "delivery_parts";
		$_SESSION['key_id'] = $reference_id;
	}
	else{
		$msg = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. Request could not be processed.</p>";
	}
	$_SESSION['msg'] = $msg;
	echo "<script>window.location.href = '$base_url/views/display/order-delivery.php';</script>";
	die();
}









function save_additional_delivery(){
	global $con;
	global $base_url;
	$actual_do_date = htmlspecialchars($_POST['actual-do-date']);
	$parts = explode("-", $actual_do_date);
	$actual_do_date = $parts[2] . $parts[1] . $parts[0];
	$delivery_date = htmlspecialchars($_POST['delivery-date']);
	$parts = explode("-", $delivery_date);
	$delivery_date = $parts[2] . $parts[1] . $parts[0];
	$site = htmlspecialchars($_POST['site']);
	$reference_do_no = htmlspecialchars($_POST['reference-do-no']);
	$reference_co_no = htmlspecialchars($_POST['reference-co-no']);
	$customer_code = htmlspecialchars($_POST['customer-code']);
	$sales_channel = htmlspecialchars($_POST['sales-channel']);

	$stmt = $con->prepare("INSERT INTO additional_delivery (DO_DATE, DELIVERY_DATE, SITE, REFERENCE_DO_NUMBER, REFERENCE_CO_NUMBER, CUSTOMER_CODE, SALES_CHANNEL, ENTRY_TIME ) VALUES (:DO_DATE, :DELIVERY_DATE, :SITE, :REFERENCE_DO_NUMBER,  :REFERENCE_CO_NUMBER, :CUSTOMER_CODE, :SALES_CHANNEL, :ENTRY_TIME)");

	$executed = $stmt->execute(array('DO_DATE' => $actual_do_date, 'DELIVERY_DATE' => $delivery_date, 'SITE' => $site, 'REFERENCE_DO_NUMBER' => $reference_do_no, 'REFERENCE_CO_NUMBER' => $reference_co_no, 'CUSTOMER_CODE' => $customer_code, 'SALES_CHANNEL' => $sales_channel, 'ENTRY_TIME' => time() + 4*60*60 ));

	if($executed){
		$msg = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; New data successfully inserted in the database.</p>";
		$reference_id = $con->lastInsertId();
		delivery_parts_entry($reference_id, $con, "additional");
	}
	else{
		$msg = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. Request could not be processed.</p>";
	}
	$_SESSION['msg'] = $msg;
	echo "<script>window.location.href = '$base_url/views/display/additional-order-delivery.php';</script>";
	die();
}









function save_backup_delivery(){
	global $con;
	global $base_url;
	$date = htmlspecialchars($_POST['date']);
	$parts = explode("-", $date);
	$date = $parts[2] . $parts[1] . $parts[0];
	$site = htmlspecialchars($_POST['site']);
	$requester_name = htmlspecialchars($_POST['requester-name']);
	$requester_designation = htmlspecialchars($_POST['requester-designation']);
	$requester_department = htmlspecialchars($_POST['requester-department']);
	$requisition_number = htmlspecialchars($_POST['requisition-number']);
	$reference_number = htmlspecialchars($_POST['reference-number']);

	$msg = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; New data successfully inserted in the database.</p>";
	$stmt = $con->prepare("INSERT INTO backup_delivery (DELIVERY_DATE, SITE, REQUESTER_NAME, REQUESTER_DESIGNATION, REQUESTER_DEPARTMENT, REQUISITION_NUMBER, REFERENCE_NUMBER ) VALUES (:DELIVERY_DATE, :SITE, :REQUESTER_NAME,  :REQUESTER_DESIGNATION, :REQUESTER_DEPARTMENT, :REQUISITION_NUMBER, :REFERENCE_NUMBER)");

	$executed = $stmt->execute(array('DELIVERY_DATE' => $date, 'SITE' => $site, 'REQUESTER_NAME' => $requester_name, 'REQUESTER_DESIGNATION' => $requester_designation, 'REQUESTER_DEPARTMENT' => $requester_department, 'REQUISITION_NUMBER' => $requisition_number, 'REFERENCE_NUMBER' => $reference_number));

	if($executed){
		$reference_id = $con->lastInsertId();
		delivery_parts_entry($reference_id, $con, "backup");
	}
	else{
		$msg = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. Request could not be processed.</p>";
	}
	$_SESSION['msg'] = $msg;
	echo "<script>window.location.href = '$base_url/views/display/backup-order-delivery.php';</script>";
	die();
}









function save_more_delivery(){
	global $con;
	global $base_url;
	$ref = htmlspecialchars($_POST['reference']);
	$type = htmlspecialchars($_POST['delivery-type']);
	$msg = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; New data successfully inserted in the database.</p>";
	delivery_parts_entry($ref, $con, $type);
	
	if($type == "normal") $page = "order-delivery-details";		
	else if($type=="backup") $page = "backup-order-delivery-details";
	else $page = "additional-order-delivery-details";
	
	echo "<script>window.location.href = '$base_url/views/display/$page.php?ref=$ref';</script>";
	die();
}









function set_frame_engine_number(){
	global $con;
	$part_number = trim(htmlspecialchars($_POST['partNumber']));
	$color_code = trim(htmlspecialchars($_POST['colorCode']));
	$stmt = $con->prepare("SELECT FRAME_NUMBER,ENGINE_NUMBER FROM issue_records WHERE PART_NUMBER = :PART_NUMBER AND COLOR_CODE = :COLOR_CODE AND ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM issues WHERE TYPE != 'manufacturing-issue' AND RECEIVED = 1)");
	$stmt->execute(array('PART_NUMBER' => $part_number, 'COLOR_CODE' => $color_code));
	$results = $stmt->fetchAll(\PDO::FETCH_OBJ);
	$frame_html = "<option value=''>Frame Number</option>";
	$engine_html = "<option value=''>Engine Number</option>";
	$arr = array();
	for ($i = 0; $i < sizeof($results); $i++) {
		$frame_number = $results[$i]->FRAME_NUMBER;
		$engine_number = $results[$i]->ENGINE_NUMBER;
		if(!empty($frame_number) && $frame_number != null)	$frame_html .= "<option value='$frame_number'>$frame_number</option>";
		if(!empty($engine_number) && $engine_number != null) $engine_html .= "<option value='$engine_number'>$engine_number</option>";
	}
	array_push($arr, $frame_html);	
	array_push($arr, $engine_html);
	echo json_encode($arr);
}









function delivery_parts_entry($reference_id, $con, $type){
	$size = sizeof($_POST['part-number']);
	for($i = 0; $i < $size; $i++){
		if($type == "normal"){
			if(!empty($_POST['part-number'][$i]) || !empty($_POST['part-name'][$i]) || !empty($_POST['model'][$i]) || !empty($_POST['color-code'][$i]) || !empty($_POST['color-name'][$i]) || !empty($_POST['quantity'][$i]) || !empty($_POST['unit'][$i]) || !empty($_POST['frame-number'][$i]) || !empty($_POST['engine-number'][$i]) || !empty($_POST['key-ring-number'][$i]) || !empty($_POST['battery-number'][$i]) || !empty($_POST['lc-number'][$i]) || !empty($_POST['invoice-number'][$i]) || !empty($_POST['remarks'][$i])){

				$stmt = $con->prepare("INSERT INTO delivery_parts (TYPE, ENTRY_REFERENCE_ID, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME, QUANTITY, UNIT, FRAME_NUMBER, ENGINE_NUMBER, KEY_RING_NUMBER, BATTERY_NUMBER, LC_NUMBER, INVOICE_NUMBER, REMARKS) VALUES (:TYPE, :ENTRY_REFERENCE_ID, :PART_NUMBER, :PART_NAME, :MODEL, :COLOR_CODE, :COLOR_NAME, :QUANTITY, :UNIT, :FRAME_NUMBER, :ENGINE_NUMBER, :KEY_RING_NUMBER, :BATTERY_NUMBER, :LC_NUMBER,  :INVOICE_NUMBER, :REMARKS)");

				$executed = $stmt->execute(array('TYPE' => 'normal', 'ENTRY_REFERENCE_ID' => $reference_id, 'PART_NUMBER' => htmlspecialchars($_POST['part-number'][$i]), 'PART_NAME' => htmlspecialchars($_POST['part-name'][$i]), 'MODEL' => htmlspecialchars($_POST['model'][$i]), 'COLOR_CODE' => htmlspecialchars($_POST['color-code'][$i]), 'COLOR_NAME' => htmlspecialchars($_POST['color-name'][$i]), 'QUANTITY' => htmlspecialchars($_POST['quantity'][$i]), 'UNIT' => htmlspecialchars($_POST['unit'][$i]), 'FRAME_NUMBER' => htmlspecialchars($_POST['frame-number'][$i]), 'ENGINE_NUMBER' => htmlspecialchars($_POST['engine-number'][$i]), 'KEY_RING_NUMBER' => htmlspecialchars($_POST['key-ring-number'][$i]), 'BATTERY_NUMBER' => htmlspecialchars($_POST['battery-number'][$i]), 'LC_NUMBER' => htmlspecialchars($_POST['lc-number'][$i]), 'INVOICE_NUMBER' => htmlspecialchars($_POST['invoice-number'][$i]), 'REMARKS' => htmlspecialchars($_POST['remarks'][$i])));	
			}  	
		}


		if($type == "additional"){
			if(!empty($_POST['part-number'][$i]) || !empty($_POST['part-name'][$i]) || !empty($_POST['model'][$i]) || !empty($_POST['color-code'][$i]) || !empty($_POST['color-name'][$i]) || !empty($_POST['quantity'][$i]) || !empty($_POST['unit'][$i]) || !empty($_POST['remarks'][$i])){

				$stmt = $con->prepare("INSERT INTO delivery_parts (TYPE, ENTRY_REFERENCE_ID, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME, QUANTITY, UNIT, REMARKS) VALUES (:TYPE, :ENTRY_REFERENCE_ID, :PART_NUMBER, :PART_NAME, :MODEL, :COLOR_CODE, :COLOR_NAME, :QUANTITY, :UNIT, :REMARKS)");

				$executed = $stmt->execute(array('TYPE' => 'additional', 'ENTRY_REFERENCE_ID' => $reference_id, 'PART_NUMBER' => htmlspecialchars($_POST['part-number'][$i]), 'PART_NAME' => htmlspecialchars($_POST['part-name'][$i]), 'MODEL' => htmlspecialchars($_POST['model'][$i]), 'COLOR_CODE' => htmlspecialchars($_POST['color-code'][$i]), 'COLOR_NAME' => htmlspecialchars($_POST['color-name'][$i]), 'QUANTITY' => htmlspecialchars($_POST['quantity'][$i]), 'UNIT' => htmlspecialchars($_POST['unit'][$i]), 'REMARKS' => htmlspecialchars($_POST['remarks'][$i])));	
			}  	
		}


		if($type == "backup"){
			if(!empty($_POST['part-number'][$i]) || !empty($_POST['part-name'][$i]) || !empty($_POST['model'][$i]) || !empty($_POST['color-code'][$i]) || !empty($_POST['color-name'][$i]) || !empty($_POST['quantity'][$i]) || !empty($_POST['unit'][$i]) || !empty($_POST['remarks'][$i])){

				$stmt = $con->prepare("INSERT INTO delivery_parts (TYPE, ENTRY_REFERENCE_ID, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME, QUANTITY, UNIT, REMARKS) VALUES (:TYPE, :ENTRY_REFERENCE_ID, :PART_NUMBER, :PART_NAME, :MODEL, :COLOR_CODE, :COLOR_NAME, :QUANTITY, :UNIT, :REMARKS)");

				$executed = $stmt->execute(array('TYPE' => 'backup', 'ENTRY_REFERENCE_ID' => $reference_id, 'PART_NUMBER' => htmlspecialchars($_POST['part-number'][$i]), 'PART_NAME' => htmlspecialchars($_POST['part-name'][$i]), 'MODEL' => htmlspecialchars($_POST['model'][$i]), 'COLOR_CODE' => htmlspecialchars($_POST['color-code'][$i]), 'COLOR_NAME' => htmlspecialchars($_POST['color-name'][$i]), 'QUANTITY' => htmlspecialchars($_POST['quantity'][$i]), 'UNIT' => htmlspecialchars($_POST['unit'][$i]), 'REMARKS' => htmlspecialchars($_POST['remarks'][$i])));	
			}  	
		}

		if(!$executed){
			$_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. One or more data could not be inserted.</p>";
			break;
		}	
	} 	// FOR END


	// UPLOAD EXCEL
	require_once '../excel-reader/Classes/PHPExcel/IOFactory.php';
	if(!empty($_FILES['file']['tmp_name'])){
		$excel_object = \PHPExcel_IOFactory::load($_FILES['file']['tmp_name']);
		$rows = $excel_object->getActiveSheet()->toArray(null);
		$size = sizeof($rows);
		for ($i=1; $i < $size; $i++) {
			$stmt = $con->prepare("INSERT INTO delivery_parts (ENTRY_REFERENCE_ID, TYPE, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME, QUANTITY, UNIT, FRAME_NUMBER, ENGINE_NUMBER, KEY_RING_NUMBER, BATTERY_NUMBER, LC_NUMBER, INVOICE_NUMBER, REMARKS) VALUES (:ENTRY_REFERENCE_ID, :TYPE, :PART_NUMBER, :PART_NAME, :MODEL, :COLOR_CODE, :COLOR_NAME, :QUANTITY, :UNIT, :FRAME_NUMBER, :ENGINE_NUMBER, :KEY_RING_NUMBER, :BATTERY_NUMBER, :LC_NUMBER, :INVOICE_NUMBER, :REMARKS)");				
			$executed = $stmt->execute(array('ENTRY_REFERENCE_ID' => $reference_id, 'TYPE' => $type, 'PART_NUMBER' => htmlspecialchars($rows[$i][0]), 'PART_NAME' => htmlspecialchars($rows[$i][1]), 'MODEL' => htmlspecialchars($rows[$i][2]), 'COLOR_CODE' => htmlspecialchars($rows[$i][3]), 'COLOR_NAME' => htmlspecialchars($rows[$i][4]), 'QUANTITY' => htmlspecialchars($rows[$i][5]), 'UNIT' => htmlspecialchars($rows[$i][6]), 'FRAME_NUMBER' => htmlspecialchars($rows[$i][7]), 'ENGINE_NUMBER' => htmlspecialchars($rows[$i][8]), 'KEY_RING_NUMBER' => htmlspecialchars($rows[$i][9]), 'BATTERY_NUMBER' => htmlspecialchars($rows[$i][10]), 'LC_NUMBER' => htmlspecialchars($rows[$i][11]), 'INVOICE_NUMBER' => htmlspecialchars($rows[$i][12]), 'REMARKS' => htmlspecialchars($rows[$i][13])));
		}
	} // EXCEL UPLOAD END
}









function save_return_order(){
	global $con;
	global $base_url;
	$date = htmlspecialchars($_POST['date']);
	$parts = explode("-", $date);
	$date = $parts[2] . $parts[1] . $parts[0];
	$delivery_challan_number = htmlspecialchars($_POST['delivery-challan-number']);
	$sales_channel = htmlspecialchars($_POST['sales-channel']);
	$customer_code = htmlspecialchars($_POST['customer-code']);

	$msg = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; New data successfully inserted in the database.</p>";
	$stmt = $con->prepare("INSERT INTO return_order (RETURN_DATE, DELIVERY_CHALLAN_NUMBER, CUSTOMER_CODE, SALES_CHANNEL) VALUES (:RETURN_DATE, :DELIVERY_CHALLAN_NUMBER, :CUSTOMER_CODE, :SALES_CHANNEL)");

	$executed = $stmt->execute(array('RETURN_DATE' => $date, 'DELIVERY_CHALLAN_NUMBER' => $delivery_challan_number, 'CUSTOMER_CODE' => $customer_code, 'SALES_CHANNEL' => $sales_channel));

	if($executed){
		$reference_id = $con->lastInsertId();
		return_order_parts_entry($reference_id, $con);
		$_SESSION['entries'] = "return_order";
		$_SESSION['parts'] = "returned_parts";
		$_SESSION['key_id'] = $reference_id;
	}
	else{
		$msg = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. Request could not be processed.</p>";
	}
	$_SESSION['msg'] = $msg;
	echo "<script>window.location.href = '$base_url/views/display/return-order.php';</script>";
	die();
}









function save_more_return_order(){
	global $con;
	global $base_url;
	$ref = htmlspecialchars($_POST['reference']);
	$msg = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; New data successfully inserted in the database.</p>";
	return_order_parts_entry($reference_id, $con);
	$_SESSION['msg'] = $msg;
	$_SESSION['entries'] = "return_order";
	$_SESSION['parts'] = "returned_parts";
	$_SESSION['key_id'] = $reference_id;
	echo "<script>window.location.href = '$base_url/views/display/return-order-delivery.php?ref=$ref';</script>";
	die();
}









function return_order_parts_entry($reference_id, $con){
	$size = sizeof($_POST['part-number']);
	for($i = 0; $i < $size; $i++){
		if(!empty($_POST['part-number'][$i]) || !empty($_POST['part-name'][$i]) || !empty($_POST['model'][$i]) || !empty($_POST['color-code'][$i]) || !empty($_POST['color-name'][$i]) || !empty($_POST['quantity'][$i]) || !empty($_POST['unit'][$i]) || !empty($_POST['return-reason'][$i]) || !empty($_POST['remarks'][$i])){

			$stmt = $con->prepare("INSERT INTO returned_parts (ENTRY_REFERENCE_ID, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME, QUANTITY, UNIT, RETURN_REASON, REMARKS) VALUES (:ENTRY_REFERENCE_ID, :PART_NUMBER, :PART_NAME, :MODEL, :COLOR_CODE, :COLOR_NAME, :QUANTITY, :UNIT, :RETURN_REASON, :REMARKS)");

			$executed = $stmt->execute(array('ENTRY_REFERENCE_ID' => $reference_id, 'PART_NUMBER' => htmlspecialchars($_POST['part-number'][$i]), 'PART_NAME' => htmlspecialchars($_POST['part-name'][$i]), 'MODEL' => htmlspecialchars($_POST['model'][$i]), 'COLOR_CODE' => htmlspecialchars($_POST['color-code'][$i]), 'COLOR_NAME' => htmlspecialchars($_POST['color-name'][$i]), 'QUANTITY' => htmlspecialchars($_POST['quantity'][$i]), 'UNIT' => htmlspecialchars($_POST['unit'][$i]), 'RETURN_REASON' => htmlspecialchars($_POST['return-reason'][$i]), 'REMARKS' => htmlspecialchars($_POST['remarks'][$i])));	
		}

		if(!$executed){
			$_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. One or more data could not be inserted.</p>";
			break;
		}	
	} 	// FOR END


	// UPLOAD EXCEL
	require_once '../excel-reader/Classes/PHPExcel/IOFactory.php';
	if(!empty($_FILES['file']['tmp_name'])){
		$excel_object = \PHPExcel_IOFactory::load($_FILES['file']['tmp_name']);
		$rows = $excel_object->getActiveSheet()->toArray(null);
		$size = sizeof($rows);
		for ($i=1; $i < $size; $i++) {
			$stmt = $con->prepare("INSERT INTO returned_parts (ENTRY_REFERENCE_ID, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME, QUANTITY, UNIT, FRAME_NUMBER, ENGINE_NUMBER, REMARKS) VALUES (:ENTRY_REFERENCE_ID, :PART_NUMBER, :PART_NAME, :MODEL, :COLOR_CODE, :COLOR_NAME, :QUANTITY, :UNIT, :REMARKS)");				
			$executed = $stmt->execute(array('ENTRY_REFERENCE_ID' => $reference_id, 'PART_NUMBER' => htmlspecialchars($rows[$i][0]), 'PART_NAME' => htmlspecialchars($rows[$i][1]), 'MODEL' => htmlspecialchars($rows[$i][2]), 'COLOR_CODE' => htmlspecialchars($rows[$i][3]), 'COLOR_NAME' => htmlspecialchars($rows[$i][4]), 'QUANTITY' => htmlspecialchars($rows[$i][5]), 'UNIT' => htmlspecialchars($rows[$i][6]), 'RETURN_REASON' => htmlspecialchars($rows[$i][7]), 'REMARKS' => htmlspecialchars($rows[$i][8])));
		}
	} // EXCEL UPLOAD END
}









function set_return_order(){
	global $con;
	$arr = array();
	$challan_num = htmlspecialchars($_POST['challanNum']);
	$stmt = $con->prepare("SELECT * FROM delivery WHERE KEY_ID = :KEY_ID");
	$executed = $stmt->execute(array('KEY_ID' => $challan_num));
	if($executed){
		$delivery_info = $stmt->fetch(\PDO::FETCH_OBJ);
		array_push($arr, $delivery_info->SALES_CHANNEL);
		array_push($arr, $delivery_info->CUSTOMER_CODE);
		$customer = fetch_functions\get_row('customers','CUSTOMER_ID',$delivery_info->CUSTOMER_CODE)[0];
		array_push($arr, $customer->CUSTOMER_NAME);
		array_push($arr, $customer->CUSTOMER_ADDRESS);
		array_push($arr, $customer->CUSTOMER_PHONE_OFFICE);

		$stmt = $con->prepare("SELECT * FROM delivery_parts WHERE ENTRY_REFERENCE_ID = :ENTRY_REFERENCE_ID AND TYPE = :TYPE");
		$executed = $stmt->execute(array('ENTRY_REFERENCE_ID' => $challan_num, 'TYPE' => 'normal'));
		$rows = $stmt->fetchAll(\PDO::FETCH_OBJ);
		if($executed){
			$str = "";
			foreach ($rows as $row) {
				$str .= "<tr><td class='delete'><i class='fa fa-trash trash-it'></i></td><td><input type='text' name='part-number[]' placeholder='Part Number' value='$row->PART_NUMBER'></td><td><input type='text' name='part-name[]' placeholder='Part Name' value='$row->PART_NAME'></td><td><input type='text' name='model[]' placeholder='Model' value='$row->MODEL'></td><td><input type='text' name='color-code[]' placeholder='Color Code' value='$row->COLOR_CODE'></td><td><input type='text' name='color-name[]' placeholder='Color Name' value='$row->COLOR_NAME'></td><td><input type='text' name='quantity[]' placeholder='Quantity' value='$row->QUANTITY'></td><td><input type='text' name='unit[]' value='$row->UNIT' placeholder='Unit'></td><td><input type='text' name='return-reason[]' placeholder='Return Reason' value=''></td><td><input type='text' name='remarks[]' placeholder='Remarks' value='$row->REMARKS'></td></tr>";
			}
			array_push($arr, $str);
			echo json_encode($arr);
		}
		else echo "error";
	}
	else echo "error";
}









function get_suppliers(){
	global $con;
	$stmt = $con->query("SELECT * FROM suppliers");
	$suppliers = $stmt->fetchAll(\PDO::FETCH_OBJ);
	$str = "";
	foreach ($suppliers as $s) {
		$str .= "<option value = '$s->SUPPLIER_CODE'>$s->SUPPLIER_CODE</option>";
 	}
 	echo $str;
}









function get_customers(){
	global $con;
	$stmt = $con->query("SELECT * FROM customers");
	$customers = $stmt->fetchAll(\PDO::FETCH_OBJ);
	$str = "";
	foreach ($customers as $c) {
		$str .= "<option value = '$c->CUSTOMER_ID'>$c->CUSTOMER_ID</option>";
 	}
 	echo $str;
}









function save_purchase_requisitions(){
	global $con;
	global $base_url;
	$date = htmlspecialchars($_POST['date']);
	$parts = explode("-", $date);
	$date = $parts[2] . $parts[1] . $parts[0];
	$site = htmlspecialchars($_POST['site']);
	$requester_name = htmlspecialchars($_POST['requester-name']);
	$requester_designation = htmlspecialchars($_POST['requester-designation']);
	$requester_department = htmlspecialchars($_POST['requester-department']);
	$approved_by = htmlspecialchars($_POST['approve-by']);
	$supplier_code = htmlspecialchars($_POST['supplier-code']);

	$stmt = $con->prepare("INSERT INTO purchase_requisitions (REQUISITION_DATE, SITE, REQUESTER_NAME, REQUESTER_DESIGNATION, REQUESTER_DEPARTMENT, APPROVED_BY, SUPPLIER_CODE ) VALUES (:REQUISITION_DATE, :SITE, :REQUESTER_NAME,  :REQUESTER_DESIGNATION, :REQUESTER_DEPARTMENT, :APPROVED_BY, :SUPPLIER_CODE)");

	$executed = $stmt->execute(array('REQUISITION_DATE' => $date, 'SITE' => $site, 'REQUESTER_NAME' => $requester_name, 'REQUESTER_DESIGNATION' => $requester_designation, 'REQUESTER_DEPARTMENT' => $requester_department, 'APPROVED_BY' => $approved_by, 'SUPPLIER_CODE' => $supplier_code));

	if($executed){
		$msg = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; New data successfully inserted in the database.</p>";
		$reference_id = $con->lastInsertId();
		purchase_requisitions_parts_entry($reference_id, $con);
		$_SESSION['entries'] = "purchase_requisitions";
		$_SESSION['parts'] = "purchase_requisitions_parts";
		$_SESSION['key_id'] = $reference_id;
	}
	else{
		$msg = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. Request could not be processed.</p>";
	}
	$_SESSION['msg'] = $msg;
	echo "<script>window.location.href = '$base_url/views/display/purchase-requisitions.php';</script>";
	die();
}









function save_more_requisitions(){
	global $con;
	global $base_url;
	$ref = htmlspecialchars($_POST['reference']);
	$msg = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; New data successfully inserted in the database.</p>";
	purchase_requisitions_parts_entry($ref, $con);
	$_SESSION['msg'] = $msg;
	$_SESSION['entries'] = "purchase_requisitions";
	$_SESSION['parts'] = "purchase_requisitions_parts";
	$_SESSION['key_id'] = $reference_id;
	echo "<script>window.location.href = '$base_url/views/display/purchase-requisitions-details.php?ref=$ref';</script>";
	die();
}









function purchase_requisitions_parts_entry($reference_id, $con){
	$size = sizeof($_POST['part-number']);
	for($i = 0; $i < $size; $i++){
		if(!empty($_POST['part-number'][$i]) || !empty($_POST['part-name'][$i]) || !empty($_POST['model'][$i]) || !empty($_POST['color-code'][$i]) || !empty($_POST['color-name'][$i]) || !empty($_POST['quantity'][$i]) || !empty($_POST['unit'][$i]) || !empty($_POST['remarks'][$i])){

			$stmt = $con->prepare("INSERT INTO purchase_requisitions_parts (ENTRY_REFERENCE_ID, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME, QUANTITY, UNIT, REMARKS) VALUES (:ENTRY_REFERENCE_ID, :PART_NUMBER, :PART_NAME, :MODEL, :COLOR_CODE, :COLOR_NAME, :QUANTITY, :UNIT, :REMARKS)");

			$executed = $stmt->execute(array('ENTRY_REFERENCE_ID' => $reference_id, 'PART_NUMBER' => htmlspecialchars($_POST['part-number'][$i]), 'PART_NAME' => htmlspecialchars($_POST['part-name'][$i]), 'MODEL' => htmlspecialchars($_POST['model'][$i]), 'COLOR_CODE' => htmlspecialchars($_POST['color-code'][$i]), 'COLOR_NAME' => htmlspecialchars($_POST['color-name'][$i]), 'QUANTITY' => htmlspecialchars($_POST['quantity'][$i]), 'UNIT' => htmlspecialchars($_POST['unit'][$i]), 'REMARKS' => htmlspecialchars($_POST['remarks'][$i])));	
		}

		if(!$executed){
			$_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. One or more data could not be inserted.</p>";
			break;
		}	
	} 	// FOR END


	// UPLOAD EXCEL
	require_once '../excel-reader/Classes/PHPExcel/IOFactory.php';
	if(!empty($_FILES['file']['tmp_name'])){
		$excel_object = \PHPExcel_IOFactory::load($_FILES['file']['tmp_name']);
		$rows = $excel_object->getActiveSheet()->toArray(null);
		$size = sizeof($rows);
		for ($i=1; $i < $size; $i++) {
			$stmt = $con->prepare("INSERT INTO purchase_requisitions_parts (ENTRY_REFERENCE_ID, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME, QUANTITY, UNIT, REMARKS) VALUES (:ENTRY_REFERENCE_ID, :PART_NUMBER, :PART_NAME, :MODEL, :COLOR_CODE, :COLOR_NAME, :QUANTITY, :UNIT, :REMARKS)");				
			$executed = $stmt->execute(array('ENTRY_REFERENCE_ID' => $reference_id, 'PART_NUMBER' => htmlspecialchars($rows[$i][0]), 'PART_NAME' => htmlspecialchars($rows[$i][1]), 'MODEL' => htmlspecialchars($rows[$i][2]), 'COLOR_CODE' => htmlspecialchars($rows[$i][3]), 'COLOR_NAME' => htmlspecialchars($rows[$i][4]), 'QUANTITY' => htmlspecialchars($rows[$i][5]), 'UNIT' => htmlspecialchars($rows[$i][6]), 'REMARKS' => htmlspecialchars($rows[$i][7])));
		}
	} // EXCEL UPLOAD END
}









function save_claims(){
	global $con;
	global $base_url;
	$date = htmlspecialchars($_POST['claim-issue-date']);
	$parts = explode("-", $date);
	$date = $parts[2] . $parts[1] . $parts[0];
	$site = htmlspecialchars($_POST['site']);
	$created_by = htmlspecialchars($_POST['created-by']);
	$approved_by = htmlspecialchars($_POST['approved-by']);
	$claim_reference_number = htmlspecialchars($_POST['claim-reference-no']);
	$apd_number = htmlspecialchars($_POST['apd-no']);
	$ppd_number = htmlspecialchars($_POST['ppd-no']);
	$invoice_number = htmlspecialchars($_POST['invoice-no']);
	$lc_number = htmlspecialchars($_POST['lc-no']);
	$model = htmlspecialchars($_POST['model']);
	$shipping_mode = htmlspecialchars($_POST['shipping-mode']);
	$month = htmlspecialchars($_POST['month']);
	$year = htmlspecialchars($_POST['year']);
	$type = htmlspecialchars($_POST['type']);

	$stmt = $con->prepare("INSERT INTO claims (TYPE, CLAIM_ISSUE_DATE, SITE, CREATED_BY, APPROVED_BY, APD_NUMBER, PPD_NUMBER, CLAIM_REFERENCE_NUMBER, MODEL, INVOICE_NUMBER, LC_NUMBER, SHIPPING_MODE, MONTH, YEAR) VALUES (:TYPE, :CLAIM_ISSUE_DATE, :SITE, :CREATED_BY, :APPROVED_BY, :APD_NUMBER, :PPD_NUMBER, :CLAIM_REFERENCE_NUMBER, :MODEL, :INVOICE_NUMBER, :LC_NUMBER, :SHIPPING_MODE, :MONTH, :YEAR)");

	$executed = $stmt->execute(array('TYPE' => $type, 'CLAIM_ISSUE_DATE' => $date, 'SITE' => $site, 'CREATED_BY' => $created_by, 'APPROVED_BY' => $approved_by, 'APD_NUMBER' => $apd_number, 'PPD_NUMBER' => $ppd_number, 'CLAIM_REFERENCE_NUMBER' => $claim_reference_number, 'MODEL' => $model, 'INVOICE_NUMBER' => $invoice_number, 'LC_NUMBER' => $lc_number, 'SHIPPING_MODE' => $shipping_mode, 'MONTH' => $month, 'YEAR' => $year));

	if($executed){
		$msg = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; New data successfully inserted in the database.</p>";
		$reference_id = $con->lastInsertId();
		claims_parts_entry($reference_id, $con, $type);
	}
	else{
		$msg = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. Request could not be processed.</p>";
	}
	$_SESSION['msg'] = $msg;
	echo "<script>window.location.href = '$base_url/views/display/$type.php';</script>";
	die();
}









function save_more_claims(){
	global $con;
	global $base_url;
	$ref = htmlspecialchars($_POST['reference']);
	$type = htmlspecialchars($_POST['type']);
	$msg = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; New data successfully inserted in the database.</p>";
	claims_parts_entry($ref, $con, $type);
	echo "<script>window.location.href = '$base_url/views/display/$type-details.php?ref=$ref';</script>";
	die();
}









function claims_parts_entry($reference_id, $con, $type){
	$size = sizeof($_POST['part-number']);
	for($i = 0; $i < $size; $i++){
		if(!empty($_POST['part-number'][$i]) || !empty($_POST['part-name'][$i]) || !empty($_POST['color-code'][$i]) || !empty($_POST['color-name'][$i]) || !empty($_POST['quantity'][$i]) || !empty($_POST['unit'][$i]) || !empty($_POST['box-number'][$i]) || !empty($_POST['case-number'][$i]) || !empty($_POST['reference-number'][$i]) || !empty($_POST['lot-number'][$i]) || !empty($_POST['claim-type'][$i]) || !empty($_POST['claim-code'][$i]) || !empty($_POST['action-code'][$i]) || !empty($_POST['process-code'][$i]) || !empty($_POST['details-of-defect'][$i]) || !empty($_POST['defect-finding-way'][$i]) || !empty($_POST['remarks'][$i])){

			$picture = file_check('picture','../img/',$i);
			if(!$picture){ $picture = "Something went wrong. Image couldn't be inserted. Please try again"; }
			else{
				if($picture == "too_big") { $picture = "Image couldn't be inserted because the size is too big. Max limit : 10MB"; }
				else if($picture == "invalid_ext") { $picture = "Invalid extension. Image must have one of the following extensions: jpg, jpeg, png, gif, tif, bmp"; }
				else if($picture == "empty") { $picture = ""; }
			}

			$stmt = $con->prepare("INSERT INTO claims_parts (ENTRY_REFERENCE_ID, PART_NUMBER, PART_NAME, COLOR_CODE, COLOR_NAME, QUANTITY, UNIT, BOX_NUMBER, CASE_NUMBER, REFERENCE_NUMBER, LOT_NUMBER, CLAIM_TYPE, CLAIM_CODE, ACTION_CODE, PROCESS_CODE, DETAILS_OF_DEFECT, DEFECT_FINDING_WAY, PICTURE, REMARKS) VALUES (:ENTRY_REFERENCE_ID, :PART_NUMBER, :PART_NAME, :COLOR_CODE, :COLOR_NAME, :QUANTITY, :UNIT, :BOX_NUMBER, :CASE_NUMBER, :REFERENCE_NUMBER, :LOT_NUMBER, :CLAIM_TYPE, :CLAIM_CODE, :ACTION_CODE, :PROCESS_CODE, :DETAILS_OF_DEFECT, :DEFECT_FINDING_WAY, :PICTURE, :REMARKS)");

			$executed = $stmt->execute(array('ENTRY_REFERENCE_ID' => $reference_id, 'PART_NUMBER' => htmlspecialchars($_POST['part-number'][$i]), 'PART_NAME' => htmlspecialchars($_POST['part-name'][$i]), 'COLOR_CODE' => htmlspecialchars($_POST['color-code'][$i]), 'COLOR_NAME' => htmlspecialchars($_POST['color-name'][$i]), 'QUANTITY' => htmlspecialchars($_POST['quantity'][$i]), 'UNIT' => htmlspecialchars($_POST['unit'][$i]), 'BOX_NUMBER' => htmlspecialchars($_POST['box-number'][$i]), 'CASE_NUMBER' => htmlspecialchars($_POST['case-number'][$i]), 'REFERENCE_NUMBER' => htmlspecialchars($_POST['reference-number'][$i]), 'LOT_NUMBER' => htmlspecialchars($_POST['lot-number'][$i]), 'CLAIM_TYPE' => htmlspecialchars($_POST['claim-type'][$i]), 'CLAIM_CODE' => htmlspecialchars($_POST['claim-code'][$i]), 'ACTION_CODE' => htmlspecialchars($_POST['action-code'][$i]), 'PROCESS_CODE' => htmlspecialchars($_POST['process-code'][$i]), 'DETAILS_OF_DEFECT' => htmlspecialchars($_POST['details-of-defect'][$i]), 'DEFECT_FINDING_WAY' => htmlspecialchars($_POST['defect-finding-way'][$i]), 'PICTURE' => $picture, 'REMARKS' => htmlspecialchars($_POST['remarks'][$i])));	

			if(!$executed){
				$_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. One or more data could not be inserted.</p>";
				break;
		 	}
		}	
	} 	// FOR END
	

	// UPLOAD EXCEL
	require_once '../excel-reader/Classes/PHPExcel/IOFactory.php';
	if(!empty($_FILES['file']['tmp_name'])){
		$excel_object = \PHPExcel_IOFactory::load($_FILES['file']['tmp_name']);
		$rows = $excel_object->getActiveSheet()->toArray(null);
		$size = sizeof($rows);
		for ($i=1; $i < $size; $i++) {
			$stmt = $con->prepare("INSERT INTO claims_parts (ENTRY_REFERENCE_ID, PART_NUMBER, PART_NAME, COLOR_CODE, COLOR_NAME, QUANTITY, UNIT, BOX_NUMBER, CASE_NUMBER, REFERENCE_NUMBER, LOT_NUMBER, CLAIM_TYPE, CLAIM_CODE, ACTION_CODE, PROCESS_CODE, DETAILS_OF_DEFECT, DEFECT_FINDING_WAY, REMARKS) VALUES (:ENTRY_REFERENCE_ID, :PART_NUMBER, :PART_NAME, :COLOR_CODE, :COLOR_NAME, :QUANTITY, :UNIT, :BOX_NUMBER, :CASE_NUMBER, :REFERENCE_NUMBER, :LOT_NUMBER, :CLAIM_TYPE, :CLAIM_CODE, :ACTION_CODE, :PROCESS_CODE, :DETAILS_OF_DEFECT, :DEFECT_FINDING_WAY, :REMARKS)");

			$executed = $stmt->execute(array('ENTRY_REFERENCE_ID' => $reference_id, 'PART_NUMBER' => htmlspecialchars($rows[$i][0]), 'PART_NAME' => htmlspecialchars($rows[$i][1]), 'COLOR_CODE' => htmlspecialchars($rows[$i][2]), 'COLOR_NAME' => htmlspecialchars($rows[$i][3]), 'QUANTITY' => htmlspecialchars($rows[$i][4]), 'UNIT' => htmlspecialchars($rows[$i][5]), 'BOX_NUMBER' => htmlspecialchars($rows[$i][6]), 'CASE_NUMBER' => htmlspecialchars($rows[$i][7]), 'REFERENCE_NUMBER' => htmlspecialchars($rows[$i][8]), 'LOT_NUMBER' => htmlspecialchars($rows[$i][9]), 'CLAIM_TYPE' => htmlspecialchars($rows[$i][10]), 'CLAIM_CODE' => htmlspecialchars($rows[$i][11]), 'ACTION_CODE' => htmlspecialchars($rows[$i][12]), 'PROCESS_CODE' => htmlspecialchars($rows[$i][13]), 'DETAILS_OF_DEFECT' => htmlspecialchars($rows[$i][14]), 'DEFECT_FINDING_WAY' => htmlspecialchars($rows[$i][15]), 'REMARKS' => htmlspecialchars($rows[$i][16])));	
		}
	} // EXCEL UPLOAD END
}









function update_claim_image(){
	global $con;
	global $base_url;
	$id = htmlspecialchars($_POST['key']);
	$ref = htmlspecialchars($_POST['ref']);
	$type = htmlspecialchars($_POST['type']);
	$picture = file_check('image','../img/',0);
	if(!$picture){ $picture = "Something went wrong. Image couldn't be inserted. Please try again"; }
	else{
		if($picture == "too_big") { $picture = "Image couldn't be inserted because the size is too big. Max limit : 10MB"; echo $picture; }
		else if($picture == "invalid_ext") { $picture = "Invalid extension. Image must have one of the following extensions: jpg, jpeg, png, gif, tif, bmp"; echo $picture; }
		else if($picture == "empty") { $picture = ""; }
		else{
			$image = fetch_functions\get_row('claims_parts','KEY_ID',$id)[0];
			unlink($image->PICTURE);
			$stmt = $con->prepare("UPDATE claims_parts SET PICTURE = :PICTURE WHERE KEY_ID = :ID");
			$stmt->execute(array('PICTURE' => $picture, 'ID' => $id));
		}
	}
	echo "<script>window.location.href = '$base_url/views/display/$type-details.php?ref=$ref';</script>";
	die();
}









function delete_claim_image(){
	global $con;
	global $base_url;
	$id = htmlspecialchars($_POST['key']);
	$ref = htmlspecialchars($_POST['ref']);
	$type = htmlspecialchars($_POST['type']);
	$picture = file_check('image','../img/',0);
	$image = fetch_functions\get_row('claims_parts','KEY_ID',$id)[0];
	unlink($image->PICTURE);
	$stmt = $con->prepare("UPDATE claims_parts SET PICTURE = :PICTURE WHERE KEY_ID = :ID");
	$stmt->execute(array('PICTURE' => null, 'ID' => $id));
	echo "<script>window.location.href = '$base_url/views/display/$type-details.php?ref=$ref';</script>";
	die();
}









function entries_inline_edit(){
	global $con;
	$id = htmlspecialchars($_POST['id']);
	$values = $_POST['values'];
	$size = sizeof($values);
	$section = htmlspecialchars($_POST['section']);
	

	if($section == "parts"){
		$date = htmlspecialchars($values[2]);
		$parts = explode('-', $date);
		$date = $parts[2] . '-' . $parts[1] . '-' . $parts[0];

		$stmt = $con->prepare("UPDATE entries SET REQUISITION_NUMBER = :REQUISITION_NUMBER, ENTRY_DATE  = :ENTRY_DATE , SITE = :SITE, SUPPLIER_CODE = :SUPPLIER_CODE, INVOICE_NUMBER = :INVOICE_NUMBER, LC_NUMBER = :LC_NUMBER, LOT_NUMBER = :LOT_NUMBER, PPD_NUMBER = :PPD_NUMBER, SUPPLIER_CHALLAN_NUMBER = :SUPPLIER_CHALLAN_NUMBER WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('REQUISITION_NUMBER' => htmlspecialchars($values[0]), 'ENTRY_DATE' => $date, 'SITE' => htmlspecialchars($values[3]), 'SUPPLIER_CODE' => htmlspecialchars($values[8]), 'INVOICE_NUMBER' => htmlspecialchars($values[1]), 'LC_NUMBER' => htmlspecialchars($values[4]), 'LOT_NUMBER' => htmlspecialchars($values[5]), 'SUPPLIER_CHALLAN_NUMBER' => htmlspecialchars($values[6]), 'PPD_NUMBER' => htmlspecialchars($values[7]), 'ID' => $id ));		
	}

	
	if($section == "issues"){
		$date = htmlspecialchars($values[1]);
		$parts = explode('-', $date);
		$date = $parts[2] . '-' . $parts[1] . '-' . $parts[0];

		$stmt = $con->prepare("UPDATE issues SET ENTRY_DATE  = :ENTRY_DATE , SITE = :SITE, NAME = :NAME, DESIGNATION = :DESIGNATION, DEPARTMENT = :DEPARTMENT, INVOICE_NUMBER = :INVOICE_NUMBER, LC_NUMBER = :LC_NUMBER, LOT_NUMBER = :LOT_NUMBER, PPD_NUMBER = :PPD_NUMBER WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('ENTRY_DATE' => $date, 'SITE' => htmlspecialchars($values[2]), 'NAME' => htmlspecialchars($values[6]), 'DESIGNATION' => htmlspecialchars($values[7]), 'DEPARTMENT' => htmlspecialchars($values[8]), 'INVOICE_NUMBER' => htmlspecialchars($values[0]), 'LC_NUMBER' => htmlspecialchars($values[3]), 'LOT_NUMBER' => htmlspecialchars($values[4]), 'PPD_NUMBER' => htmlspecialchars($values[5]), 'ID' => $id ));		
	}

	
	if($section == "delivery"){
		$do_date = htmlspecialchars($values[0]);
		$parts = explode('-', $do_date);
		$do_date = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
		$delivery_date = htmlspecialchars($values[1]);
		$parts = explode('-', $delivery_date);
		$delivery_date = $parts[2] . '-' . $parts[1] . '-' . $parts[0];

		$stmt = $con->prepare("UPDATE delivery SET DO_DATE  = :DO_DATE, DELIVERY_DATE = :DELIVERY_DATE,  SITE = :SITE, REFERENCE_DO_NUMBER = :REFERENCE_DO_NUMBER, REFERENCE_CO_NUMBER = :REFERENCE_CO_NUMBER, CUSTOMER_CODE = :CUSTOMER_CODE, TRANSPORT_NAME = :TRANSPORT_NAME, TRUCK_NUMBER = :TRUCK_NUMBER, DRIVER_NAME = :DRIVER_NAME, DRIVER_MOBILE_NUMBER = :DRIVER_MOBILE_NUMBER, SALES_CHANNEL = :SALES_CHANNEL WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('DO_DATE' => $do_date, 'DELIVERY_DATE' => $delivery_date, 'SITE' => htmlspecialchars($values[2]), 'REFERENCE_DO_NUMBER' => htmlspecialchars($values[3]), 'REFERENCE_CO_NUMBER' => htmlspecialchars($values[4]), 'CUSTOMER_CODE' => htmlspecialchars($values[10]), 'TRANSPORT_NAME' => htmlspecialchars($values[5]), 'TRUCK_NUMBER' => htmlspecialchars($values[6]), 'DRIVER_NAME' => htmlspecialchars($values[7]), 'DRIVER_MOBILE_NUMBER' => htmlspecialchars($values[8]), 'SALES_CHANNEL' => htmlspecialchars($values[9]), 'ID' => $id ));		
	}

	
	if($section == "backup-delivery"){
		$delivery_date = htmlspecialchars($values[0]);
		$parts = explode('-', $delivery_date);
		$delivery_date = $parts[2] . '-' . $parts[1] . '-' . $parts[0];

		$stmt = $con->prepare("UPDATE backup_delivery SET DELIVERY_DATE = :DELIVERY_DATE,  SITE = :SITE, REQUESTER_NAME = :REQUESTER_NAME, REQUESTER_DESIGNATION = :REQUESTER_DESIGNATION, REQUESTER_DEPARTMENT = :REQUESTER_DEPARTMENT, REQUISITION_NUMBER = :REQUISITION_NUMBER, REFERENCE_NUMBER = :REFERENCE_NUMBER WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('DELIVERY_DATE' => $delivery_date, 'SITE' => htmlspecialchars($values[1]), 'REQUISITION_NUMBER' => htmlspecialchars($values[2]), 'REFERENCE_NUMBER' => htmlspecialchars($values[3]), 'REQUESTER_NAME' => htmlspecialchars($values[4]), 'REQUESTER_DESIGNATION' => htmlspecialchars($values[5]), 'REQUESTER_DEPARTMENT' => htmlspecialchars($values[6]), 'ID' => $id ));		
	}

	
	if($section == "additional-delivery"){
		$do_date = htmlspecialchars($values[0]);
		$parts = explode('-', $do_date);
		$do_date = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
		$delivery_date = htmlspecialchars($values[1]);
		$parts = explode('-', $delivery_date);
		$delivery_date = $parts[2] . '-' . $parts[1] . '-' . $parts[0];

		$stmt = $con->prepare("UPDATE additional_delivery SET DO_DATE  = :DO_DATE, DELIVERY_DATE = :DELIVERY_DATE,  SITE = :SITE, REFERENCE_DO_NUMBER = :REFERENCE_DO_NUMBER, REFERENCE_CO_NUMBER = :REFERENCE_CO_NUMBER, CUSTOMER_CODE = :CUSTOMER_CODE, SALES_CHANNEL = :SALES_CHANNEL WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('DO_DATE' => $do_date, 'DELIVERY_DATE' => $delivery_date, 'SITE' => htmlspecialchars($values[2]), 'REFERENCE_DO_NUMBER' => htmlspecialchars($values[3]), 'REFERENCE_CO_NUMBER' => htmlspecialchars($values[4]), 'CUSTOMER_CODE' => htmlspecialchars($values[6]), 'SALES_CHANNEL' => htmlspecialchars($values[5]), 'ID' => $id ));		
	}

	
	if($section == "purchase-requisitions"){
		$delivery_date = htmlspecialchars($values[0]);
		$parts = explode('-', $delivery_date);
		$delivery_date = $parts[2] . '-' . $parts[1] . '-' . $parts[0];

		$stmt = $con->prepare("UPDATE purchase_requisitions SET REQUISITION_DATE = :REQUISITION_DATE,  SITE = :SITE, REQUESTER_NAME = :REQUESTER_NAME, REQUESTER_DESIGNATION = :REQUESTER_DESIGNATION, REQUESTER_DEPARTMENT = :REQUESTER_DEPARTMENT, APPROVED_BY = :APPROVED_BY, SUPPLIER_CODE = :SUPPLIER_CODE WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('REQUISITION_DATE' => $delivery_date, 'SITE' => htmlspecialchars($values[1]), 'REQUESTER_NAME' => htmlspecialchars($values[2]), 'REQUESTER_DESIGNATION' => htmlspecialchars($values[3]), 'REQUESTER_DEPARTMENT' => htmlspecialchars($values[4]), 'APPROVED_BY' => htmlspecialchars($values[5]), 'SUPPLIER_CODE' => htmlspecialchars($values[6]), 'ID' => $id ));		
	}

	
	if($section == "return-order"){
		$delivery_date = htmlspecialchars($values[0]);
		$parts = explode('-', $delivery_date);
		$delivery_date = $parts[2] . '-' . $parts[1] . '-' . $parts[0];

		$stmt = $con->prepare("UPDATE return_order SET RETURN_DATE = :RETURN_DATE, DELIVERY_CHALLAN_NUMBER = :DELIVERY_CHALLAN_NUMBER, CUSTOMER_CODE = :CUSTOMER_CODE, SALES_CHANNEL = :SALES_CHANNEL WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('RETURN_DATE' => $delivery_date, 'DELIVERY_CHALLAN_NUMBER' => htmlspecialchars($values[1]), 'SALES_CHANNEL' => htmlspecialchars($values[2]), 'CUSTOMER_CODE' => htmlspecialchars($values[3]), 'ID' => $id ));		
	}

	
	if($section == "claims"){
		$date = htmlspecialchars($values[1]);
		$parts = explode('-', $date);
		$date = $parts[2] . '-' . $parts[1] . '-' . $parts[0];

		$stmt = $con->prepare("UPDATE claims SET CLAIM_ISSUE_DATE  = :CLAIM_ISSUE_DATE, SITE = :SITE, CREATED_BY = :CREATED_BY, APPROVED_BY = :APPROVED_BY, APD_NUMBER = :APD_NUMBER, PPD_NUMBER = :PPD_NUMBER, CLAIM_REFERENCE_NUMBER = :CLAIM_REFERENCE_NUMBER, MODEL = :MODEL, INVOICE_NUMBER = :INVOICE_NUMBER, LC_NUMBER = :LC_NUMBER, SHIPPING_MODE = :SHIPPING_MODE, MONTH = :MONTH, YEAR = :YEAR WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('CLAIM_ISSUE_DATE' => $date, 'SITE' => htmlspecialchars($values[2]), 'CREATED_BY' => htmlspecialchars($values[3]), 'APPROVED_BY' => htmlspecialchars($values[4]), 'APD_NUMBER' => htmlspecialchars($values[7]), 'PPD_NUMBER' => htmlspecialchars($values[8]), 'CLAIM_REFERENCE_NUMBER' => htmlspecialchars($values[9]), 'MODEL' => htmlspecialchars($values[6]), 'INVOICE_NUMBER' => htmlspecialchars($values[0]), 'LC_NUMBER' => htmlspecialchars($values[10]), 'SHIPPING_MODE' => htmlspecialchars($values[5]), 'MONTH' => htmlspecialchars($values[11]), 'YEAR' => htmlspecialchars($values[12]), 'ID' => $id ));		
	}

	if($executed) echo "true";
	else echo "false";
	die();
}









function parts_inline_edit(){
	global $con;
	$id = htmlspecialchars($_POST['id']);
	$type = htmlspecialchars($_POST['partsInlineEdit']);
	$values = $_POST['values'];
	$size = sizeof($values);
	
	// EDIT PARTS
	if($type == "ckd" || $type == "ckdbom" || $type == "cbu"){
		$stmt = $con->prepare("UPDATE parts SET PART_NUMBER = :PART_NUMBER, PART_NAME = :PART_NAME, MODEL = :MODEL, COLOR_CODE = :COLOR_CODE, COLOR_NAME = :COLOR_NAME, QUANTITY = :QUANTITY, UNIT = :UNIT, FRAME_NUMBER = :FRAME_NUMBER, ENGINE_NUMBER = :ENGINE_NUMBER, REMARKS = :REMARKS WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('PART_NUMBER' => htmlspecialchars($values[0]), 'PART_NAME' => htmlspecialchars($values[1]), 'MODEL' => htmlspecialchars($values[2]), 'COLOR_CODE' => htmlspecialchars($values[3]), 'COLOR_NAME' => htmlspecialchars($values[4]), 'QUANTITY' => htmlspecialchars($values[5]), 'UNIT' => htmlspecialchars($values[6]), 'FRAME_NUMBER' => htmlspecialchars($values[7]), 'ENGINE_NUMBER' => htmlspecialchars($values[8]), 'REMARKS' => htmlspecialchars($values[9]), 'ID' => $id ));	
	}
	
	
	if($type == "manufacturing-parts" || $type == "spare-parts" || $type == "additional-parts"){
		$stmt = $con->prepare("UPDATE parts SET PART_NUMBER = :PART_NUMBER, PART_NAME = :PART_NAME, MODEL = :MODEL, COLOR_CODE = :COLOR_CODE, COLOR_NAME = :COLOR_NAME, QUANTITY = :QUANTITY, UNIT = :UNIT, REMARKS = :REMARKS WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('PART_NUMBER' => htmlspecialchars($values[0]), 'PART_NAME' => htmlspecialchars($values[1]), 'MODEL' => htmlspecialchars($values[2]), 'COLOR_CODE' => htmlspecialchars($values[3]), 'COLOR_NAME' => htmlspecialchars($values[4]), 'QUANTITY' => htmlspecialchars($values[5]), 'UNIT' => htmlspecialchars($values[6]), 'REMARKS' => htmlspecialchars($values[7]), 'ID' => $id ));	
	}
	
	


	// EDIT ISSUES
	if($type == "ckd-issue" || $type == "ckd-bom-issue" || $type == "cbu-issue" || $type == "manufacturing-issue"){
		$stmt = $con->prepare("UPDATE issue_records SET PART_NUMBER = :PART_NUMBER, PART_NAME = :PART_NAME, MODEL = :MODEL, COLOR_CODE = :COLOR_CODE, COLOR_NAME = :COLOR_NAME, QUANTITY = :QUANTITY, UNIT = :UNIT, FRAME_NUMBER = :FRAME_NUMBER, ENGINE_NUMBER = :ENGINE_NUMBER, REMARKS = :REMARKS WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('PART_NUMBER' => htmlspecialchars($values[0]), 'PART_NAME' => htmlspecialchars($values[1]), 'MODEL' => htmlspecialchars($values[2]), 'COLOR_CODE' => htmlspecialchars($values[3]), 'COLOR_NAME' => htmlspecialchars($values[4]), 'QUANTITY' => htmlspecialchars($values[5]), 'UNIT' => htmlspecialchars($values[6]), 'FRAME_NUMBER' => htmlspecialchars($values[7]), 'ENGINE_NUMBER' => htmlspecialchars($values[8]), 'REMARKS' => htmlspecialchars($values[9]), 'ID' => $id ));	
	}


	if($type == "cripple-issue"){
		$stmt = $con->prepare("UPDATE issue_records SET PART_NUMBER = :PART_NUMBER, PART_NAME = :PART_NAME, MODEL = :MODEL, COLOR_CODE = :COLOR_CODE, COLOR_NAME = :COLOR_NAME, QUANTITY = :QUANTITY, UNIT = :UNIT, FRAME_NUMBER = :FRAME_NUMBER, ENGINE_NUMBER = :ENGINE_NUMBER, CRIPPLE_REASON = :CRIPPLE_REASON, REMARKS = :REMARKS WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('PART_NUMBER' => htmlspecialchars($values[0]), 'PART_NAME' => htmlspecialchars($values[1]), 'MODEL' => htmlspecialchars($values[2]), 'COLOR_CODE' => htmlspecialchars($values[3]), 'COLOR_NAME' => htmlspecialchars($values[4]), 'QUANTITY' => htmlspecialchars($values[5]), 'UNIT' => htmlspecialchars($values[6]), 'FRAME_NUMBER' => htmlspecialchars($values[7]), 'ENGINE_NUMBER' => htmlspecialchars($values[8]), 'CRIPPLE_REASON' => htmlspecialchars($values[9]), 'REMARKS' => htmlspecialchars($values[10]), 'ID' => $id ));	
	}


	if($type == "spare-issue"){
		$stmt = $con->prepare("UPDATE issue_records SET PART_NUMBER = :PART_NUMBER, PART_NAME = :PART_NAME, MODEL = :MODEL, COLOR_CODE = :COLOR_CODE, COLOR_NAME = :COLOR_NAME, QUANTITY = :QUANTITY, UNIT = :UNIT, ENGINE_NUMBER = :ENGINE_NUMBER, REMARKS = :REMARKS WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('PART_NUMBER' => htmlspecialchars($values[0]), 'PART_NAME' => htmlspecialchars($values[1]), 'MODEL' => htmlspecialchars($values[2]), 'COLOR_CODE' => htmlspecialchars($values[3]), 'COLOR_NAME' => htmlspecialchars($values[4]), 'QUANTITY' => htmlspecialchars($values[5]), 'UNIT' => htmlspecialchars($values[6]), 'ENGINE_NUMBER' => htmlspecialchars($values[7]), 'REMARKS' => htmlspecialchars($values[8]), 'ID' => $id ));	
	}
	
	


	// EDIT DELIVERY
	if($type == "delivery"){
		$stmt = $con->prepare("UPDATE delivery_parts SET PART_NUMBER = :PART_NUMBER, PART_NAME = :PART_NAME, MODEL = :MODEL, COLOR_CODE = :COLOR_CODE, COLOR_NAME = :COLOR_NAME, QUANTITY = :QUANTITY, UNIT = :UNIT, FRAME_NUMBER = :FRAME_NUMBER, ENGINE_NUMBER = :ENGINE_NUMBER, KEY_RING_NUMBER = :KEY_RING_NUMBER, BATTERY_NUMBER = :BATTERY_NUMBER, LC_NUMBER = :LC_NUMBER, INVOICE_NUMBER = :INVOICE_NUMBER, REMARKS = :REMARKS WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('PART_NUMBER' => htmlspecialchars($values[0]), 'PART_NAME' => htmlspecialchars($values[1]), 'MODEL' => htmlspecialchars($values[2]), 'COLOR_CODE' => htmlspecialchars($values[3]), 'COLOR_NAME' => htmlspecialchars($values[4]), 'QUANTITY' => htmlspecialchars($values[5]), 'UNIT' => htmlspecialchars($values[6]), 'FRAME_NUMBER' => htmlspecialchars($values[7]), 'ENGINE_NUMBER' => htmlspecialchars($values[8]),'KEY_RING_NUMBER' => htmlspecialchars($values[9]), 'BATTERY_NUMBER' => htmlspecialchars($values[10]), 'LC_NUMBER' => htmlspecialchars($values[11]), 'INVOICE_NUMBER' => htmlspecialchars($values[12]), 'REMARKS' => htmlspecialchars($values[13]), 'ID' => $id ));	
	}


	if($type == "backup-delivery"){
		$stmt = $con->prepare("UPDATE delivery_parts SET PART_NUMBER = :PART_NUMBER, PART_NAME = :PART_NAME, MODEL = :MODEL, COLOR_CODE = :COLOR_CODE, COLOR_NAME = :COLOR_NAME, QUANTITY = :QUANTITY, UNIT = :UNIT, REMARKS = :REMARKS WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('PART_NUMBER' => htmlspecialchars($values[0]), 'PART_NAME' => htmlspecialchars($values[1]), 'MODEL' => htmlspecialchars($values[2]), 'COLOR_CODE' => htmlspecialchars($values[3]), 'COLOR_NAME' => htmlspecialchars($values[4]), 'QUANTITY' => htmlspecialchars($values[5]), 'UNIT' => htmlspecialchars($values[6]), 'REMARKS' => htmlspecialchars($values[7]), 'ID' => $id ));	
	}


	if($type == "additional-delivery"){
		$stmt = $con->prepare("UPDATE delivery_parts SET PART_NUMBER = :PART_NUMBER, PART_NAME = :PART_NAME, MODEL = :MODEL, COLOR_CODE = :COLOR_CODE, COLOR_NAME = :COLOR_NAME, QUANTITY = :QUANTITY, UNIT = :UNIT, REMARKS = :REMARKS WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('PART_NUMBER' => htmlspecialchars($values[0]), 'PART_NAME' => htmlspecialchars($values[1]), 'MODEL' => htmlspecialchars($values[2]), 'COLOR_CODE' => htmlspecialchars($values[3]), 'COLOR_NAME' => htmlspecialchars($values[4]), 'QUANTITY' => htmlspecialchars($values[5]), 'UNIT' => htmlspecialchars($values[6]), 'REMARKS' => htmlspecialchars($values[7]), 'ID' => $id ));	
	}
	
	


	// EDIT PURCHASE REQUISITIONS
	if($type == "purchase-requisitions"){
		$stmt = $con->prepare("UPDATE purchase_requisitions_parts SET PART_NUMBER = :PART_NUMBER, PART_NAME = :PART_NAME, MODEL = :MODEL, COLOR_CODE = :COLOR_CODE, COLOR_NAME = :COLOR_NAME, QUANTITY = :QUANTITY, UNIT = :UNIT, REMARKS = :REMARKS WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('PART_NUMBER' => htmlspecialchars($values[0]), 'PART_NAME' => htmlspecialchars($values[1]), 'MODEL' => htmlspecialchars($values[2]), 'COLOR_CODE' => htmlspecialchars($values[3]), 'COLOR_NAME' => htmlspecialchars($values[4]), 'QUANTITY' => htmlspecialchars($values[5]), 'UNIT' => htmlspecialchars($values[6]), 'REMARKS' => htmlspecialchars($values[7]), 'ID' => $id ));	
	}
	
	


	// EDIT PURCHASE REQUISITIONS
	if($type == "return-order"){
		$stmt = $con->prepare("UPDATE returned_parts SET PART_NUMBER = :PART_NUMBER, PART_NAME = :PART_NAME, MODEL = :MODEL, COLOR_CODE = :COLOR_CODE, COLOR_NAME = :COLOR_NAME, QUANTITY = :QUANTITY, UNIT = :UNIT, RETURN_REASON = :RETURN_REASON, REMARKS = :REMARKS WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('PART_NUMBER' => htmlspecialchars($values[0]), 'PART_NAME' => htmlspecialchars($values[1]), 'MODEL' => htmlspecialchars($values[2]), 'COLOR_CODE' => htmlspecialchars($values[3]), 'COLOR_NAME' => htmlspecialchars($values[4]), 'QUANTITY' => htmlspecialchars($values[5]), 'UNIT' => htmlspecialchars($values[6]), 'RETURN_REASON' => htmlspecialchars($values[7]), 'REMARKS' => htmlspecialchars($values[8]), 'ID' => $id ));	
	}
	
	


	// EDIT PURCHASE REQUISITIONS
	if($type == "claim"){
		$stmt = $con->prepare("UPDATE claims_parts SET PART_NUMBER = :PART_NUMBER, PART_NAME = :PART_NAME, COLOR_CODE = :COLOR_CODE, COLOR_NAME = :COLOR_NAME, QUANTITY = :QUANTITY, UNIT = :UNIT, BOX_NUMBER = :BOX_NUMBER, CASE_NUMBER = :CASE_NUMBER, REFERENCE_NUMBER = :REFERENCE_NUMBER, LOT_NUMBER = :LOT_NUMBER, CLAIM_TYPE = :CLAIM_TYPE, CLAIM_CODE = :CLAIM_CODE, ACTION_CODE = :ACTION_CODE, PROCESS_CODE = :PROCESS_CODE, DETAILS_OF_DEFECT = :DETAILS_OF_DEFECT, DEFECT_FINDING_WAY = :DEFECT_FINDING_WAY, REMARKS = :REMARKS WHERE KEY_ID = :ID");

		$executed = $stmt->execute(array('PART_NUMBER' => htmlspecialchars($values[0]), 'PART_NAME' => htmlspecialchars($values[1]), 'COLOR_CODE' => htmlspecialchars($values[2]), 'COLOR_NAME' => htmlspecialchars($values[3]), 'QUANTITY' => htmlspecialchars($values[4]), 'UNIT' => htmlspecialchars($values[5]), 'BOX_NUMBER' => htmlspecialchars($values[6]), 'CASE_NUMBER' => htmlspecialchars($values[7]), 'REFERENCE_NUMBER' => htmlspecialchars($values[8]), 'LOT_NUMBER' => htmlspecialchars($values[9]), 'CLAIM_TYPE' => htmlspecialchars($values[10]), 'CLAIM_CODE' => htmlspecialchars($values[11]), 'ACTION_CODE' => htmlspecialchars($values[12]), 'PROCESS_CODE' => htmlspecialchars($values[13]), 'DETAILS_OF_DEFECT' => htmlspecialchars($values[14]), 'DEFECT_FINDING_WAY' => htmlspecialchars($values[15]), 'REMARKS' => htmlspecialchars($values[16]), 'ID' => $id ));	
	}

	if($executed) echo "true";
	else echo "false";
}









function change_password(){
	global $con;
	global $base_url;
	$current_password = htmlspecialchars($_POST['current-password']);
	$new_password = htmlspecialchars($_POST['new-password']);
	$confirm_password = htmlspecialchars($_POST['confirm-password']);
	$stmt = $con->prepare("SELECT USER_PASSWORD FROM users WHERE USER_ID = :USER_ID");
	$stmt->execute(array('USER_ID' => $_SESSION['rancon_user_id']));
	$fetch = $stmt->fetch(\PDO::FETCH_OBJ);
	$password = $fetch->USER_PASSWORD;
	$given_password = hash('sha512', $current_password);
	if($given_password == $password){
		if($new_password == $confirm_password){
			$new_password = hash('sha512', $new_password);
			$stmt = $con->prepare("UPDATE users SET USER_PASSWORD = :USER_PASSWORD WHERE USER_ID = :USER_ID");
			$query = $stmt->execute(array('USER_PASSWORD' => $new_password,'USER_ID' => $_SESSION['rancon_user_id']));
			if($query) 	{
				$_SESSION['msg'] = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; New password successfully updated</p>";
			}
			else{
			 $_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Something went wrong. Password could not be changed</p>";
			}
		}
		else {
			$_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; The new password and password confirmation do not match</p>";
		}
	}
	else {
		$_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-exclamation-triangle'></i> &nbsp; Current password does not match</p>";
	}

	echo "<script>location.href='$base_url/views/display/settings.php';</script>";
	die();
}









function delete_row($key, $id, $table, $page){
	global $con;
	$stmt = $con->prepare("DELETE FROM $table WHERE $key = :id");
	$executed = $stmt->execute(array('id' => $id));
	if($executed){
		$_SESSION['msg'] = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; Record Deleted</p>";
	}
	else{
		$_SESSION['msg'] = "<p class='request-failed'><i class='fa fa-check-circle'></i> &nbsp; Something went wrong. Could Not Proceed with The Deletion Process</p>";
	}
	header("Location:../views/$page");
	die();
}









function delete_related_rows(){
	global $con;
	global $base_url;
	$id = htmlspecialchars($_POST['id']);
	$type = htmlspecialchars($_POST['type']);
	$main_table = htmlspecialchars($_POST['mainTable']);
	$related_table = htmlspecialchars($_POST['deleteRelatedRows']);

	$stmt = $con->prepare("DELETE FROM $main_table WHERE KEY_ID = :KEY_ID");
	$executed = $stmt->execute(array('KEY_ID' => $id));
	if($executed){
		if($type == "normal" || $type == "backup"){
			$stmt = $con->prepare("DELETE FROM $related_table WHERE ENTRY_REFERENCE_ID = :ENTRY_REFERENCE_ID AND TYPE = :TYPE");
			$rel_executed = $stmt->execute(array('ENTRY_REFERENCE_ID' => $id, 'TYPE' => $type));	
		}
		else{
			$stmt = $con->prepare("DELETE FROM $related_table WHERE ENTRY_REFERENCE_ID = :ENTRY_REFERENCE_ID");
			$rel_executed = $stmt->execute(array('ENTRY_REFERENCE_ID' => $id));	
		}
		if($rel_executed) {
			echo "success";
			$_SESSION['msg'] = "<p class='request-successful'><i class='fa fa-check-circle'></i> &nbsp; Record Deleted</p>";
		}
		else {
			echo "error";
		}
	}
	else {
		echo "error";
	}
	die();
}









function update_page(){
	global $con;
	global $base_url;
	$table = htmlspecialchars($_POST['table']);
	$column = htmlspecialchars($_POST['column']);
	$value = htmlspecialchars($_POST['value']);
	$update_col = htmlspecialchars($_POST['updateCol']);
	$prev_val = htmlspecialchars($_POST['prevVal']);
	$type = htmlspecialchars($_POST['type']);
	
	if($type == "date"){
		$parts = explode("-", $value);
		$value = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
	}
	$stmt = $con->prepare("UPDATE $table SET $column = :VALUE WHERE $update_col = :PREV_VAL");
	$executed = $stmt->execute(array('VALUE' => $value, 'PREV_VAL' => $prev_val));
	if($executed) echo "true";
	else echo "false";
	die();
}









function file_check($name,$temp_dir,$i){
	$allowed_ext = array('jpg','jpeg','png','gif','tif','bmp');
	
	if(empty(basename($_FILES[$name]['name'][$i]))){
		return "empty";
	}

    $ext = strtolower(pathinfo($_FILES[$name]['name'][$i],PATHINFO_EXTENSION));
    if(!in_array($ext, $allowed_ext)){
        return "invalid_ext";
        die();
    }
    if ($_FILES[$name]["size"][$i] > 20000000) {
        return "too_big";
        die();
    }
    
	$target_file = $temp_dir . uniqid() . basename($_FILES[$name]['name'][$i]);
    if (move_uploaded_file($_FILES[$name]["tmp_name"][$i], $target_file)) {
		return $target_file;    	
	}
	else{
		return false;
	}
}