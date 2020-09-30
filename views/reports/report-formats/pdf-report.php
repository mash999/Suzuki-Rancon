<?php 
require '../../../functions/functions.php';
require 'report-functions.php';
require '../../../dompdf/vendor/autoload.php';
use Dompdf\Dompdf;

if(isset($_POST['suppliers-report'])){
	$content = suppliers_report($con);
	$report_name = "Suppliers Report";
}

if(isset($_POST['customers-report'])){
	$content = customers_report($con);
	$report_name = "Customers Report";
}

if(isset($_POST['stock-status'])){
	$part_id = htmlspecialchars($_POST['part-id']);

	if(empty($part_id)){
		$content = stock_status($con);
		$report_name = "Stock Status";	
	}

	else{
		$stmt = $con->prepare("SELECT ITEM_NAME, ITEM_NUMBER FROM items WHERE ITEM_ID = :ITEM_ID AND SAN = 1 LIMIT 1");
		$stmt->execute(array('ITEM_ID' => $part_id));
		$part = $stmt->fetch(\PDO::FETCH_OBJ);
		$report_name = "BOM AGAINST " . $part->ITEM_NAME . " - " . $part->ITEM_NUMBER;
		
		$content = bom_report($con, $part_id, $part->ITEM_NAME . $part->ITEM_NUMBER);
	}
}

if(isset($_POST['pending-purchase-orders'])){
	$content = pending_purchase_orders($con);
	$report_name = "Pending Purchase Orders";
}

if(isset($_POST['finished-products'])){
	$quick_date = htmlspecialchars($_POST['quick-date']);
	$year = htmlspecialchars($_POST['year']);
	$month = htmlspecialchars($_POST['month']);
	$day = htmlspecialchars($_POST['day']);
	$content = finished_products($con, $quick_date, $year, $month, $day);
	$report_name = "Finished Products";
}




$dompdf = new Dompdf();
$dompdf->loadHtml($content);
$dompdf->setPaper('A4','landscape');
$dompdf->render();
$pdf = date('Ymdhsi',time()) . $report_name . uniqid() . ".pdf";
$dompdf->stream($pdf,array('Attachment'=>0));
?>