 <?php 

function suppliers_report($con){
	$content = title_info("Periodic Suppliers Information");
	$content .= "			
	<table cellpadding='7px' cellspacing='0' style='width: 100%;'>
		<thead>
			<tr>
				<td style='width: 20px; color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>#</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Company</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Phone</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Mobile</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>E-mail</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Fax</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Website</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>City</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Country</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Address</td>
			</tr>
		</thead>
		<tbody>";
		$stmt = $con->query("SELECT * FROM suppliers ORDER BY SUPPLIER_NAME ASC");
		$suppliers = $stmt->fetchAll(\PDO::FETCH_OBJ);
		$i = 1;
		foreach ($suppliers as $s) {
			$content .= "
			<tr>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$i</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$s->SUPPLIER_NAME</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$s->SUPPLIER_PHONE_OFFICE</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$s->SUPPLIER_PHONE_MOBILE</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$s->SUPPLIER_EMAIL</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$s->SUPPLIER_FAX</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$s->SUPPLIER_WEBSITE</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$s->SUPPLIER_CITY</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$s->COUNTRY</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$s->SUPPLIER_ADDRESS</td>
			</tr>";
			$i++;
			}
		$content .= "
		</tbody>
	</table>";

	$content .= footer_info();
	return $content;
}




function customers_report($con){
	$content = title_info("Periodic Customers Information");
	$content .= "
	<table cellpadding='7px' cellspacing='0' style='width: 100%;'>
		<thead>
			<tr>
				<td style='width: 20px; color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>#</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Name</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Type</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Phone</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Mobile</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>E-mail</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Fax</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Website</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>City</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Address</td>
			</tr>
		</thead>
		<tbody>";

		$stmt = $con->query("SELECT * FROM customers ORDER BY CUSTOMER_NAME ASC");
		$customers = $stmt->fetchAll(\PDO::FETCH_OBJ);
		$i = 1;
		foreach ($customers as $c) {
			if($c->CUSTOMER_TYPE == "0") $customer_type = "Corporate";
			if($c->CUSTOMER_TYPE == "1") $customer_type = "Distributor";
			if($c->CUSTOMER_TYPE == "2") $customer_type = "Others";
			$content .= "
			<tr>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$i</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$c->CUSTOMER_NAME</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$customer_type</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$c->CUSTOMER_PHONE_OFFICE</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$c->CUSTOMER_PHONE_MOBILE</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$c->CUSTOMER_EMAIL</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$c->CUSTOMER_FAX</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$c->CUSTOMER_WEBSITE</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$c->CUSTOMER_CITY</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$c->CUSTOMER_ADDRESS</td>
			</tr>";
			$i++;
			}
		$content .= "
		</tbody>
	</table>";

	$content .= footer_info();
	return $content;
}




function stock_status($con){
	$content = title_info("Inventory Stock Status");
	$content .= "
	<table cellpadding='7px' cellspacing='0' style='width: 100%;'>
		<thead>
			<tr>
				<td style='width: 20px; color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>#</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Part Name</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Part Number</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Quantity</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Color Code</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>B.O.M</td>
			</tr>
		</thead>
		<tbody>";
		
		$stmt = $con->query("SELECT * FROM items ORDER BY ITEM_NAME ASC");
		$items = $stmt->fetchAll(\PDO::FETCH_OBJ);
		$i = 1;
		foreach ($items as $item) {
			if($item->SAN == 0) $san = "N/A";
			else if($item->SAN == 1) $san = "Available";
			$content .= "
			<tr>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$i</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$item->ITEM_NAME</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$item->ITEM_NUMBER</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$item->QUANTITY</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$item->COLOR_CODE</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$san</td>
			</tr>";
			$i++;
			}
		$content .= "
		</tbody>
	</table>";

	$content .= footer_info();
	return $content;
}




function bom_report($con,$id,$model){
	$content = title_info("Bom Against $model");
	$content .= "
	<table cellpadding='7px' cellspacing='0' style='width: 100%;'>
		<thead>
			<tr>
				<td style='width: 20px; color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>#</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Part Name</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Part Number</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Quantity</td>
			</tr>
		</thead>
		<tbody>";
		
		$stmt = $con->prepare("SELECT * FROM bill_of_materials WHERE BOM_ID = :BOM_ID ORDER BY ITEM_NAME ASC");
		$stmt->execute(array('BOM_ID' => $id));
		$items = $stmt->fetchAll(\PDO::FETCH_OBJ);
		$i = 1;
		foreach ($items as $item) {
			$content .= "
			<tr>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$i</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$item->ITEM_NAME</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$item->ITEM_NUMBER</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$item->QUANTITY</td>
			</tr>";
			$i++;
			}
		$content .= "
		</tbody>
	</table>";

	$content .= footer_info();
	return $content;
}




function pending_purchase_orders($con){
	$content = title_info("Pending Purchase Orders");
	$content .= "			
	<table cellpadding='7px' cellspacing='0' style='width: 100%;'>
		<thead>
			<tr>
				<td style='width: 20px; color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>#</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Supplier</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Part Name</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Part No.</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Order Date</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Receiving Date</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Ordered</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Received</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Pending</td>
			</tr>
		</thead>
		<tbody>";
		$stmt = $con->query("SELECT pm.ITEM_NUMBER, pm.PURCHASE_ORDER_DATE, pm.PURCHASE_RECEIVING_DATE, pd.PURCHASE_ORDER_QUANTITY, pd.PURCHASE_RECEIVING_QUANTITY, s.SUPPLIER_NAME, i.ITEM_NAME FROM purchase_order_details AS pd JOIN purchase_orders_master AS pm ON pd.PURCHASE_ORDER_ID = pm.PURCHASE_ORDER_ID JOIN items AS i ON pm.ITEM_NUMBER = i.ITEM_NUMBER JOIN suppliers as s ON s.SUPPLIER_ID = pm.SUPPLIER_ID WHERE pd.PURCHASE_ORDER_QUANTITY != pd.PURCHASE_RECEIVING_QUANTITY ORDER BY pd.PURCHASE_ORDER_ID");
		$p_orders = $stmt->fetchAll(\PDO::FETCH_OBJ);
		$i = 1;
		foreach ($p_orders as $p) {
			$pending = abs($p->PURCHASE_ORDER_QUANTITY - $p->PURCHASE_RECEIVING_QUANTITY);
			$content .= "
			<tr>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$i</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$p->SUPPLIER_NAME</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$p->ITEM_NAME</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$p->ITEM_NUMBER</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$p->PURCHASE_ORDER_DATE</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$p->PURCHASE_RECEIVING_DATE</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$p->PURCHASE_ORDER_QUANTITY</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$p->PURCHASE_RECEIVING_QUANTITY</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$pending</td>
			</tr>";
			$i++;
			}
		$content .= "
		</tbody>
	</table>";

	$content .= footer_info();
	return $content;
}




function finished_products($con,$quick_date,$year,$month,$day){
	$condition = "";
	$months_arr = array('January','February','March','April','May','June','July','August','September','October','November','December');
	if(!$quick_date){
		if(!empty($year) && !empty($month) && !empty($day)){
			$condition = "YEAR(dm.SALES_DELIVERY_DATE) = $year AND MONTH(dm.SALES_DELIVERY_DATE) = $month AND DAY(dm.SALES_DELIVERY_DATE) = $day";
			$date = $day . ' ' . $months_arr[$month - 1] . ', ' . $year;
		}
		if(!empty($year) && !empty($month) && empty($day)){
			$condition = "YEAR(dm.SALES_DELIVERY_DATE) = $year AND MONTH(dm.SALES_DELIVERY_DATE) = $month";
			$date = $months_arr[$month - 1] . ', ' . $year;
		}
		if(!empty($year) && empty($month) && !empty($day)){
			$condition = "YEAR(dm.SALES_DELIVERY_DATE) = $year AND DAY(dm.SALES_DELIVERY_DATE) = $day";
			$date = "$day of Every Month of Year $year";
		}
		if(!empty($year) && empty($month) && empty($day)){
			$condition = "YEAR(dm.SALES_DELIVERY_DATE) = $year";
			$date = $year;
		}
		if(empty($year) && !empty($month) && !empty($day)){
			$condition = "MONTH(dm.SALES_DELIVERY_DATE) = $month AND DAY(dm.SALES_DELIVERY_DATE) = $day";
			$date = $day . ', ' . $months_arr[$month - 1];
		}
		if(empty($year) && !empty($month) && empty($day)){
			$condition = "MONTH(dm.SALES_DELIVERY_DATE) = $month";
			$date = $months_arr[$month - 1];
		}
		if(empty($year) && empty($month) && !empty($day)){
			$condition = "DAY(dm.SALES_DELIVERY_DATE) = $day";
			$date = $day . ' of All Months of All Years';
		}
		if(empty($year) && empty($month) && empty($day)){
			$condition = "1 = 1";
			$date = "All";
		}
	}
	else{
		$time = time() + 6*60*60;
		if($quick_date == "today"){
			$condition = "YEAR(dm.SALES_DELIVERY_DATE) = " . date('Y',$time) . " AND MONTH(dm.SALES_DELIVERY_DATE) = " . date('m',$time) . " AND DAY(dm.SALES_DELIVERY_DATE) = " . date('d',$time);
			$date = date('d M, Y', time() + 6*60*60);
		}
		if($quick_date == "this-month"){
			$condition = "YEAR(dm.SALES_DELIVERY_DATE) = " . date('Y',$time) . " AND MONTH(dm.SALES_DELIVERY_DATE) = " . date('m',$time);
			$date = date('M, Y', time() + 6*60*60);
		}
		if($quick_date == "this-year"){
			$condition = "YEAR(dm.SALES_DELIVERY_DATE) = " . date('Y',$time);
			$date = date('Y', time() + 6*60*60);
		}
	}

	$content = title_info("Finished Products - $date");
	$content .= "			
	<table cellpadding='7px' cellspacing='0' style='width: 100%;'>
		<thead>
			<tr>
				<td style='width: 20px; color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>#</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Invoice No.</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Ordered on</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Delivered on</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Order Quantity</td>
				<td style='color: #333; font-size: 16px; font-family: helvetica, sans-serif; border: 1px solid #ccc;'>Delivery Quantity</td>
			</tr>
		</thead>
		<tbody>";

		$stmt = $con->query("SELECT dm.SALES_INVOICE_NUMBER, dm.DELIVERY_ORDER_DATE, dm.SALES_DELIVERY_DATE, dd.ORDER_QUANTITY, dd.DELIVERY_QUANTITY FROM delivery_master AS dm JOIN delivery_details AS dd ON dm.DELIVERY_ORDER_NUMBER = dd.DELIVERY_ORDER_NUMBER WHERE dd.ORDER_QUANTITY = dd.DELIVERY_QUANTITY AND " . $condition);
		$products = $stmt->fetchAll(\PDO::FETCH_OBJ);
		$i = 1;
		foreach ($products as $p) {
			$content .= "
			<tr>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$i</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$p->SALES_INVOICE_NUMBER</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$p->DELIVERY_ORDER_DATE</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$p->SALES_DELIVERY_DATE</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$p->ORDER_QUANTITY</td>
				<td style='color: #444; border: 1px solid #ccc; font-family: helvetica; font-size:15px;'>$p->DELIVERY_QUANTITY</td>
			</tr>";
			$i++;
			}
		$content .= "
		</tbody>
	</table>";

	$content .= footer_info();
	return $content;
}




function title_info($title){
	$date = date("d M, Y", time() + 6*60*60);
	$header = "
	<div class='page' style='width:1024px; height:auto; margin: auto;'>
		<div class='header' style = 'width: 100%; height: 120px; margin-top: 20px; position:relative;'>
			<img src='../../../img/rancon-motors.jpg' alt='Rancon Motors' style='position: absolute; left: 0; top: 0;'>
			<p style='font-size: 18px; font-family: helvetica,sans-serif; color: #333; max-width: 450px; position: absolute; left: 0; top: 40px;'>
				215, Bir Uttam Mir Shawkat Sarak, Dhaka 1208
			</p>
			<h2 style='font-weight: bold; font-size: 35px; color: #333; font-family: helvetica,sans-serif; margin: 0px; position: absolute; right: 0; top: 0px;'>
				Rancon Motors
			</h2>
			<p style='font-size: 18px; color: #333; font-family: helvetica,sans-serif; margin: 0; position: absolute; right: 0; top: 55px;'>
				Date : $date
			</p>
		</div> <!-- /header -->


		<div class='main-section' style='width: 100%; height: auto;'>
			<h1 style='width: 100%; font-size: 25px; font-family: helvetica,sans-serif; border-top: 1px solid #ccc; padding-top: 30px; margin-top: 10px; margin-bottom: 30px; color: #444;'>
				$title
			</h1>
		";

	return $header;
}




function footer_info(){
	$footer = "
	<p style='float: left; font-size: 15px; font-family: helvetica,sans-serif; border-top: 1px solid #ccc; padding-top: 10px; margin-top: 50px; margin-bottom: 10px; color: #444;'>
				Generated by <b>Ruhul Mashbu</b>
			</p>
		</div> <!-- /main-section -->
	</div> <!-- /page -->";

	return $footer;
}
