<?php namespace suzuki\export_excel;
error_reporting(0);
require_once '../excel-reader/Classes/PHPExcel.php';
require_once '../excel-reader/Classes/PHPExcel/IOFactory.php';
require_once 'functions.php';
require '../dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use suzuki\fetch_functions;
use suzuki\export_excel;

$main_table = $_SESSION['entries'];
$parts_table = $_SESSION['parts'];
$id = $_SESSION['key_id'];
$action = $_SESSION['action'];
$file_name = $_SESSION['file_name'] . '_' . time();
$_SESSION['entries'] = $_SESSION['parts'] = $_SESSION['key_id'] = $_SESSION['action'] = $_SESSION['file_name'] = "";
$entry = fetch_functions\get_row($main_table, 'KEY_ID', $id)[0];
if($parts_table == "delivery_parts"){
	if($main_table == "delivery") $delivery_type = "normal";
	else if($main_table == "additional_delivery") $delivery_type = "additional";
	else if($main_table == "backup_delivery") $delivery_type = "backup";
	$stmt = $con->prepare("SELECT * FROM $parts_table WHERE ENTRY_REFERENCE_ID = :ENTRY_REFERENCE_ID AND TYPE = :TYPE");
	$stmt->execute(array('ENTRY_REFERENCE_ID' => $id, 'TYPE' => $delivery_type));
	$parts = $stmt->fetchAll(\PDO::FETCH_OBJ);
}
else{
	$parts = fetch_functions\get_row($parts_table, 'ENTRY_REFERENCE_ID', $id); 
}
$type = $entry->TYPE;


if($action == "export-excel"){
	$obj = new \PHPExcel();
	$obj->getActiveSheet()->setTitle($file_name);
	$border_style = array(
	    'borders' => array(
	        'allborders' => array(
	            'style' => \PHPExcel_Style_Border::BORDER_THIN,
	            'color' => array('rgb' => '000000')
	        )
	    )
	);

	if($main_table == "entries" && $parts_table == "parts"){
		$obj->getActiveSheet()->setCellValue('A1','REQUISITION NUMBER')->getStyle('A1')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B1',$entry->REQUISITION_NUMBER);
		$obj->getActiveSheet()->setCellValue('A2','DATE')->getStyle('A2')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B2',$entry->ENTRY_DATE);
		$obj->getActiveSheet()->setCellValue('A3','SITE')->getStyle('A3')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B3',$entry->SITE);
		if($type == "ckd" || $type == "ckdbom" || $type == "cbu" || $type == "manufacturing-parts" || $type == "spare-parts"){
			$obj->getActiveSheet()->setCellValue('A4','INVOICE NUMBER')->getStyle('A4')->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B4',$entry->INVOICE_NUMBER);
			$obj->getActiveSheet()->setCellValue('A5','LC NUMBER')->getStyle('A5')->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B5',$entry->LC_NUMBER);
			$obj->getActiveSheet()->setCellValue('A6','LOT NUMBER')->getStyle('A6')->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B6',$entry->LOT_NUMBER);
			if($type == "manufacturing-parts" || $type == "spare-parts"){
				$obj->getActiveSheet()->setCellValue('A7','PPD NUMBER')->getStyle('A7')->getFont()->setBold(true);
				$obj->getActiveSheet()->setCellValue('B7',$entry->PPD_NUMBER);
			}
		}
		else if($type == "additional-parts"){
			$obj->getActiveSheet()->setCellValue('A4','SUPPLIER CHALLAN NUMBER')->getStyle('A4')->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B4',$entry->SUPPLIER_CHALLAN_NUMBER);
		}
		$s = fetch_functions\get_row('suppliers','SUPPLIER_CODE',$entry->SUPPLIER_CODE)[0];
		$obj->getActiveSheet()->setCellValue('D1','SUPPLIER CODE')->getStyle('D1')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E1',$entry->SUPPLIER_CODE);
		$obj->getActiveSheet()->setCellValue('D2','SUPPLIER NAME')->getStyle('D2')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E2',$s->SUPPLIER_NAME);
		$obj->getActiveSheet()->setCellValue('D3','SUPPLIER ADDRESS')->getStyle('D3')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E3',$s->SUPPLIER_ADDRESS);
		$obj->getActiveSheet()->setCellValue('D4','SUPPLIER CONTACT')->getStyle('D4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E4',$s->SUPPLIER_PHONE_OFFICE);
		$obj->getActiveSheet()->setCellValue('D5','SUPPLIER EMAIL')->getStyle('D5')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E5',$s->SUPPLIER_EMAIL);
		$obj->getActiveSheet()->setCellValue('A10','PART NUMBER')->getStyle('A10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B10','PART NAME')->getStyle('B10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('C10','MODEL')->getStyle('C10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('D10','COLOR CODE')->getStyle('D10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E10','COLOR NAME')->getStyle('E10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('F10','QUANTITY')->getStyle('F10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('G10','UNIT')->getStyle('G10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('H10','FRAME NUMBER')->getStyle('H10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('I10','ENGINE NUMBER')->getStyle('I10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('J10','REMARKS')->getStyle('J10')->getFont()->setBold(true);
		$obj->getActiveSheet()->getStyle("A10:J10")->applyFromArray($border_style);
		
		$row = 11;
		foreach ($parts as $p) {
			$obj->getActiveSheet()->setCellValue('A' . $row, $p->PART_NUMBER);
			$obj->getActiveSheet()->setCellValue('B' . $row, $p->PART_NAME);
			$obj->getActiveSheet()->setCellValue('C' . $row, $p->MODEL);
			$obj->getActiveSheet()->setCellValue('D' . $row, $p->COLOR_CODE);
			$obj->getActiveSheet()->setCellValue('E' . $row, $p->COLOR_NAME);
			$obj->getActiveSheet()->setCellValue('F' . $row, $p->QUANTITY);
			$obj->getActiveSheet()->setCellValue('G' . $row, $p->UNIT);
			$obj->getActiveSheet()->setCellValue('H' . $row, $p->FRAME_NUMBER);
			$obj->getActiveSheet()->setCellValue('I' . $row, $p->ENGINE_NUMBER);
			$obj->getActiveSheet()->setCellValue('J' . $row, $p->REMARKS);
			
			$obj->getActiveSheet()->getStyle("A" . $row . ":J" . $row)->applyFromArray($border_style);
			$row++;
		} // FOREACH END

		$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

	} // IF END




	if($main_table == "issues" && $parts_table == "issue_records"){
		if($entry->REFERENCE_NUMBER == NULL || empty($entry)){
			$obj->getActiveSheet()->setCellValue('A1','REQUISITION NUMBER')->getStyle('A1')->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('D1','REQUESTER NAME')->getStyle('D1')->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('D2','REQUESTER DESIGNATION')->getStyle('D2')->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('D3','REQUESTER DEPARTMENT')->getStyle('D3')->getFont()->setBold(true);
		}
		else{
			$obj->getActiveSheet()->setCellValue('A1','REFERENCE NUMBER')->getStyle('A1')->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('D1','SENDER NAME')->getStyle('D1')->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('D2','SENDER DESIGNATION')->getStyle('D2')->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('D3','SENDER DEPARTMENT')->getStyle('D3')->getFont()->setBold(true);
		}
		$obj->getActiveSheet()->setCellValue('B1',$entry->KEY_ID);	
		$obj->getActiveSheet()->setCellValue('A2','DATE')->getStyle('A2')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B2',$entry->ENTRY_DATE);
		$obj->getActiveSheet()->setCellValue('A3','SITE')->getStyle('A3')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B3',$entry->SITE);
		$obj->getActiveSheet()->setCellValue('A4','INVOICE NUMBER')->getStyle('A4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B4',$entry->INVOICE_NUMBER);
		$obj->getActiveSheet()->setCellValue('A5','LC NUMBER')->getStyle('A5')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B5',$entry->LC_NUMBER);
		$obj->getActiveSheet()->setCellValue('A6','LOT NUMBER')->getStyle('A6')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B6',$entry->LOT_NUMBER);
		if($type == "manufacturing-issue" || $type == "spare-issue"){
			$obj->getActiveSheet()->setCellValue('A7','PPD NUMBER')->getStyle('A7')->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B7',$entry->PPD_NUMBER);
		}

		$obj->getActiveSheet()->setCellValue('E1',$entry->NAME);
		$obj->getActiveSheet()->setCellValue('E2',$entry->DESIGNATION);
		$obj->getActiveSheet()->setCellValue('E3',$entry->DEPARTMENT);
		$obj->getActiveSheet()->setCellValue('A10','PART NUMBER')->getStyle('A10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B10','PART NAME')->getStyle('B10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('C10','MODEL')->getStyle('C10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('D10','COLOR CODE')->getStyle('D10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E10','COLOR NAME')->getStyle('E10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('F10','QUANTITY')->getStyle('F10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('G10','UNIT')->getStyle('G10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('H10','FRAME NUMBER')->getStyle('H10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('I10','ENGINE NUMBER')->getStyle('I10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('J10','CRIPPLE REASON')->getStyle('J10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('K10','REMARKS')->getStyle('K10')->getFont()->setBold(true);
		$obj->getActiveSheet()->getStyle("A10:K10")->applyFromArray($border_style);
		
		$row = 11;
		foreach ($parts as $p) {
			$obj->getActiveSheet()->setCellValue('A' . $row, $p->PART_NUMBER);
			$obj->getActiveSheet()->setCellValue('B' . $row, $p->PART_NAME);
			$obj->getActiveSheet()->setCellValue('C' . $row, $p->MODEL);
			$obj->getActiveSheet()->setCellValue('D' . $row, $p->COLOR_CODE);
			$obj->getActiveSheet()->setCellValue('E' . $row, $p->COLOR_NAME);
			$obj->getActiveSheet()->setCellValue('F' . $row, $p->QUANTITY);
			$obj->getActiveSheet()->setCellValue('G' . $row, $p->UNIT);
			$obj->getActiveSheet()->setCellValue('H' . $row, $p->FRAME_NUMBER);
			$obj->getActiveSheet()->setCellValue('I' . $row, $p->ENGINE_NUMBER);
			$obj->getActiveSheet()->setCellValue('J' . $row, $p->CRIPPLE_REASON);
			$obj->getActiveSheet()->setCellValue('K' . $row, $p->REMARKS);
			
			$obj->getActiveSheet()->getStyle("A" . $row . ":K" . $row)->applyFromArray($border_style);
			$row++;
		} // FOREACH END

		$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);

	} // IF END




	if($main_table == "delivery" && $parts_table == "delivery_parts"){
		$obj->getActiveSheet()->setCellValue('A1','DELIVERY CHALLAN NUMBER')->getStyle('A1')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B1',$entry->KEY_ID);
		$obj->getActiveSheet()->setCellValue('A2','ACTUAL DO DATE')->getStyle('A2')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B2',$entry->DO_DATE);
		$obj->getActiveSheet()->setCellValue('A3','DELIVERY DATE')->getStyle('A3')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B3',$entry->DELIVERY_DATE);
		$obj->getActiveSheet()->setCellValue('A4','SITE')->getStyle('A4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B4',$entry->SITE);
		$obj->getActiveSheet()->setCellValue('A5','REFERENCE DO NUMBER')->getStyle('A5')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B5',$entry->REFERENCE_DO_NUMBER);
		$obj->getActiveSheet()->setCellValue('A6','REFERENCE CO NUMBER')->getStyle('A6')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B6',$entry->REFERENCE_CO_NUMBER);
		$obj->getActiveSheet()->setCellValue('A7','TRANSPORT NAME')->getStyle('A7')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B7',$entry->TRANSPORT_NAME);
		$obj->getActiveSheet()->setCellValue('A8','TRUCK NUMBER')->getStyle('A8')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B8',$entry->TRUCK_NUMBER);

		$c = fetch_functions\get_row('customers','CUSTOMER_ID',$entry->CUSTOMER_CODE)[0];
		$obj->getActiveSheet()->setCellValue('D1','DRIVER NAME')->getStyle('D1')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E1',$entry->DRIVER_NAME);
		$obj->getActiveSheet()->setCellValue('D2','DRIVER MOBILE NUMBER')->getStyle('D2')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E2',$entry->DRIVER_MOBILE_NUMBER);
		$obj->getActiveSheet()->setCellValue('D3','SALES CHANNEL')->getStyle('D3')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E3',$entry->SALES_CHANNEL);
		$obj->getActiveSheet()->setCellValue('D4','CUSTOMER CODE')->getStyle('D4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E4',$c->CUSTOMER_ID);
		$obj->getActiveSheet()->setCellValue('D5','CUSTOMER NAME')->getStyle('D5')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E5',$c->CUSTOMER_NAME);
		$obj->getActiveSheet()->setCellValue('D6','CUSTOMER ADDRESS')->getStyle('D6')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E6',$c->CUSTOMER_ADDRESS);
		$obj->getActiveSheet()->setCellValue('D7','CUSTOMER CONTACT')->getStyle('D7')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E7',$c->CUSTOMER_PHONE_OFFICE);

		$obj->getActiveSheet()->setCellValue('A10','PART NUMBER')->getStyle('A10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B10','PART NAME')->getStyle('B10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('C10','MODEL')->getStyle('C10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('D10','COLOR CODE')->getStyle('D10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E10','COLOR NAME')->getStyle('E10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('F10','QUANTITY')->getStyle('F10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('G10','UNIT')->getStyle('G10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('H10','FRAME NUMBER')->getStyle('H10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('I10','ENGINE NUMBER')->getStyle('I10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('J10','KEY RING NUMBER')->getStyle('J10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('K10','BATTERY NUMBER')->getStyle('K10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('L10','LC NUMBER')->getStyle('L10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('M10','INVOICE NUMBER')->getStyle('M10')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('N10','REMARKS')->getStyle('N10')->getFont()->setBold(true);
		$obj->getActiveSheet()->getStyle("A10:N10")->applyFromArray($border_style);
		
		$row = 11;
		foreach ($parts as $p) {
			$obj->getActiveSheet()->setCellValue('A' . $row, $p->PART_NUMBER);
			$obj->getActiveSheet()->setCellValue('B' . $row, $p->PART_NAME);
			$obj->getActiveSheet()->setCellValue('C' . $row, $p->MODEL);
			$obj->getActiveSheet()->setCellValue('D' . $row, $p->COLOR_CODE);
			$obj->getActiveSheet()->setCellValue('E' . $row, $p->COLOR_NAME);
			$obj->getActiveSheet()->setCellValue('F' . $row, $p->QUANTITY);
			$obj->getActiveSheet()->setCellValue('G' . $row, $p->UNIT);
			$obj->getActiveSheet()->setCellValue('H' . $row, $p->FRAME_NUMBER);
			$obj->getActiveSheet()->setCellValue('I' . $row, $p->ENGINE_NUMBER);
			$obj->getActiveSheet()->setCellValue('J' . $row, $p->KEY_RING_NUMBER);
			$obj->getActiveSheet()->setCellValue('K' . $row, $p->BATTERY_NUMBER);
			$obj->getActiveSheet()->setCellValue('L' . $row, $p->LC_NUMBER);
			$obj->getActiveSheet()->setCellValue('M' . $row, $p->INVOICE_NUMBER);
			$obj->getActiveSheet()->setCellValue('N' . $row, $p->REMARKS);
	
			$obj->getActiveSheet()->getStyle("A" . $row . ":N" . $row)->applyFromArray($border_style);
			$row++;
		} // FOREACH END

		$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);

	} // IF END




	if($main_table == "backup_delivery" && $parts_table == "delivery_parts"){
		$obj->getActiveSheet()->setCellValue('A1','BACKUP DELIVERY NUMBER')->getStyle('A1')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B1',$entry->KEY_ID);
		$obj->getActiveSheet()->setCellValue('A2','DATE')->getStyle('A2')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B2',$entry->DELIVERY_DATE);
		$obj->getActiveSheet()->setCellValue('A3','SITE')->getStyle('A3')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B3',$entry->SITE);
		$obj->getActiveSheet()->setCellValue('A4','REQUISITION NUMBER')->getStyle('A4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B4',$entry->REQUISITION_NUMBER);
		$obj->getActiveSheet()->setCellValue('A5','REFERENCE NUMBER')->getStyle('A5')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B5',$entry->REFERENCE_NUMBER);
		$obj->getActiveSheet()->setCellValue('D1','REQUESTER NAME')->getStyle('D1')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E1',$entry->REQUESTER_NAME);
		$obj->getActiveSheet()->setCellValue('D2','REQUESTER DESIGNATION')->getStyle('D2')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E2',$entry->REQUESTER_DESIGNATION);
		$obj->getActiveSheet()->setCellValue('D3','REQUESTER DEPARTMENT')->getStyle('D3')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E3',$entry->REQUESTER_DEPARTMENT);

		$obj->getActiveSheet()->setCellValue('A8','PART NUMBER')->getStyle('A8')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B8','PART NAME')->getStyle('B8')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('C8','MODEL')->getStyle('C8')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('D8','COLOR CODE')->getStyle('D8')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E8','COLOR NAME')->getStyle('E8')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('F8','QUANTITY')->getStyle('F8')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('G8','UNIT')->getStyle('G8')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('H8','REMARKS')->getStyle('H8')->getFont()->setBold(true);
		$obj->getActiveSheet()->getStyle("A8:H8")->applyFromArray($border_style);
		
		$row = 9;
		foreach ($parts as $p) {
			$obj->getActiveSheet()->setCellValue('A' . $row, $p->PART_NUMBER);
			$obj->getActiveSheet()->setCellValue('B' . $row, $p->PART_NAME);
			$obj->getActiveSheet()->setCellValue('C' . $row, $p->MODEL);
			$obj->getActiveSheet()->setCellValue('D' . $row, $p->COLOR_CODE);
			$obj->getActiveSheet()->setCellValue('E' . $row, $p->COLOR_NAME);
			$obj->getActiveSheet()->setCellValue('F' . $row, $p->QUANTITY);
			$obj->getActiveSheet()->setCellValue('G' . $row, $p->UNIT);
			$obj->getActiveSheet()->setCellValue('H' . $row, $p->REMARKS);
	
			$obj->getActiveSheet()->getStyle("A" . $row . ":H" . $row)->applyFromArray($border_style);
			$row++;
		} // FOREACH END

		$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

	} // IF END




	if($main_table == "additional_delivery" && $parts_table == "delivery_parts"){
		$obj->getActiveSheet()->setCellValue('A1','ADDITIONAL DELIVERY NUMBER')->getStyle('A1')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B1',$entry->KEY_ID);
		$obj->getActiveSheet()->setCellValue('A2','ACTUAL DO DATE')->getStyle('A2')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B2',$entry->DO_DATE);
		$obj->getActiveSheet()->setCellValue('A3','DELIVERY DATE')->getStyle('A3')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B3',$entry->DELIVERY_DATE);
		$obj->getActiveSheet()->setCellValue('A4','SITE')->getStyle('A4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B4',$entry->SITE);
		$obj->getActiveSheet()->setCellValue('A5','REFERENCE DO NUMBER')->getStyle('A5')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B5',$entry->REFERENCE_DO_NUMBER);
		$obj->getActiveSheet()->setCellValue('A6','REFERENCE CO NUMBER')->getStyle('A6')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B6',$entry->REFERENCE_CO_NUMBER);

		$c = fetch_functions\get_row('customers','CUSTOMER_ID',$entry->CUSTOMER_CODE)[0];
		$obj->getActiveSheet()->setCellValue('D1','SALES CHANNEL')->getStyle('D1')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E1',$entry->SALES_CHANNEL);
		$obj->getActiveSheet()->setCellValue('D2','CUSTOMER CODE')->getStyle('D2')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E2',$c->CUSTOMER_ID);
		$obj->getActiveSheet()->setCellValue('D3','CUSTOMER NAME')->getStyle('D3')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E3',$c->CUSTOMER_NAME);
		$obj->getActiveSheet()->setCellValue('D4','CUSTOMER ADDRESS')->getStyle('D4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E4',$c->CUSTOMER_ADDRESS);
		$obj->getActiveSheet()->setCellValue('D5','CUSTOMER CONTACT')->getStyle('D5')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E5',$c->CUSTOMER_PHONE_OFFICE);

		$obj->getActiveSheet()->setCellValue('A9','PART NUMBER')->getStyle('A9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B9','PART NAME')->getStyle('B9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('C9','MODEL')->getStyle('C9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('D9','COLOR CODE')->getStyle('D9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E9','COLOR NAME')->getStyle('E9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('F9','QUANTITY')->getStyle('F9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('G9','UNIT')->getStyle('G9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('H9','REMARKS')->getStyle('H9')->getFont()->setBold(true);
		$obj->getActiveSheet()->getStyle("A9:H9")->applyFromArray($border_style);
		
		$row = 10;
		foreach ($parts as $p) {
			$obj->getActiveSheet()->setCellValue('A' . $row, $p->PART_NUMBER);
			$obj->getActiveSheet()->setCellValue('B' . $row, $p->PART_NAME);
			$obj->getActiveSheet()->setCellValue('C' . $row, $p->MODEL);
			$obj->getActiveSheet()->setCellValue('D' . $row, $p->COLOR_CODE);
			$obj->getActiveSheet()->setCellValue('E' . $row, $p->COLOR_NAME);
			$obj->getActiveSheet()->setCellValue('F' . $row, $p->QUANTITY);
			$obj->getActiveSheet()->setCellValue('G' . $row, $p->UNIT);
			$obj->getActiveSheet()->setCellValue('H' . $row, $p->REMARKS);
	
			$obj->getActiveSheet()->getStyle("A" . $row . ":H" . $row)->applyFromArray($border_style);
			$row++;
		} // FOREACH END

		$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

	} // IF END




	if($main_table == "return_order" && $parts_table == "returned_parts"){
		$obj->getActiveSheet()->setCellValue('A1','RETURN ORDER ID')->getStyle('A1')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B1',$entry->KEY_ID);
		$obj->getActiveSheet()->setCellValue('A2','DATE')->getStyle('A2')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B2',$entry->RETURN_DATE);
		$obj->getActiveSheet()->setCellValue('A3','DELIVERY CHALLAN NUMBER')->getStyle('A3')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B3',$entry->DELIVERY_CHALLAN_NUMBER);
		$obj->getActiveSheet()->setCellValue('A4','SALES CHANNEL')->getStyle('A4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B4',$entry->SALES_CHANNEL);
		$obj->getActiveSheet()->setCellValue('D1','CUSTOMER CODE')->getStyle('D1')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E1',$entry->CUSTOMER_CODE);
		$obj->getActiveSheet()->setCellValue('D2','CUSTOMER NAME')->getStyle('D2')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E2',$entry->CUSTOMER_NAME);
		$obj->getActiveSheet()->setCellValue('D3','CUSTOMER ADDRESS')->getStyle('D3')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E3',$entry->CUSTOMER_ADDRESS);
		$obj->getActiveSheet()->setCellValue('D4','CUSTOMER CONTACT')->getStyle('D4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E4',$entry->CUSTOMER_PHONE_OFFICE);

		$obj->getActiveSheet()->setCellValue('A7','PART NUMBER')->getStyle('A7')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B7','PART NAME')->getStyle('B7')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('C7','MODEL')->getStyle('C7')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('D7','COLOR CODE')->getStyle('D7')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E7','COLOR NAME')->getStyle('E7')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('F7','QUANTITY')->getStyle('F7')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('G7','UNIT')->getStyle('G7')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('H7','RETURN REASON')->getStyle('H7')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('I7','RETURN REASON')->getStyle('I7')->getFont()->setBold(true);
		$obj->getActiveSheet()->getStyle("A7:I7")->applyFromArray($border_style);
		
		$row = 8;
		foreach ($parts as $p) {
			$obj->getActiveSheet()->setCellValue('A' . $row, $p->PART_NUMBER);
			$obj->getActiveSheet()->setCellValue('B' . $row, $p->PART_NAME);
			$obj->getActiveSheet()->setCellValue('C' . $row, $p->MODEL);
			$obj->getActiveSheet()->setCellValue('D' . $row, $p->COLOR_CODE);
			$obj->getActiveSheet()->setCellValue('E' . $row, $p->COLOR_NAME);
			$obj->getActiveSheet()->setCellValue('F' . $row, $p->QUANTITY);
			$obj->getActiveSheet()->setCellValue('G' . $row, $p->UNIT);
			$obj->getActiveSheet()->setCellValue('H' . $row, $p->RETURN_REASON);
			$obj->getActiveSheet()->setCellValue('I' . $row, $p->REMARKS);
	
			$obj->getActiveSheet()->getStyle("A" . $row . ":I" . $row)->applyFromArray($border_style);
			$row++;
		} // FOREACH END

		$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

	} // IF END




	if($main_table == "purchase_requisitions" && $parts_table == "purchase_requisitions_parts"){
		$s = fetch_functions\get_row('suppliers','SUPPLIER_CODE',$entry->SUPPLIER_CODE)[0];
		$obj->getActiveSheet()->setCellValue('A1','REQUISITION NUMBER')->getStyle('A1')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B1',$entry->KEY_ID);
		$obj->getActiveSheet()->setCellValue('A2','DATE')->getStyle('A2')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B2',$entry->REQUISITION_DATE);
		$obj->getActiveSheet()->setCellValue('A3','SITE')->getStyle('A3')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B3',$entry->SITE);
		$obj->getActiveSheet()->setCellValue('A4','REQUESTER NAME')->getStyle('A4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B4',$entry->REQUESTER_NAME);
		$obj->getActiveSheet()->setCellValue('A5','REQUESTER DESIGNATION')->getStyle('A5')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B5',$entry->REQUESTER_DESIGNATION);
		$obj->getActiveSheet()->setCellValue('A6','REQUESTER DEPARTMENT')->getStyle('A6')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B6',$entry->REQUESTER_DEPARTMENT);

		$obj->getActiveSheet()->setCellValue('D1','APPROVED BY')->getStyle('D1')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E1',$entry->APPROVED_BY);
		$obj->getActiveSheet()->setCellValue('D2','SUPPLIER CODE')->getStyle('D2')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E2',$s->SUPPLIER_CODE);
		$obj->getActiveSheet()->setCellValue('D3','SUPPLIER NAME')->getStyle('D3')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E3',$s->SUPPLIER_NAME);
		$obj->getActiveSheet()->setCellValue('D4','SUPPLIER ADDRESS')->getStyle('D4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E4',$s->SUPPLIER_ADDRESS);
		$obj->getActiveSheet()->setCellValue('D5','SUPPLIER CONTACT')->getStyle('D5')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E5',$s->SUPPLIER_PHONE_OFFICE);

		$obj->getActiveSheet()->setCellValue('A8','PART NUMBER')->getStyle('A8')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B8','PART NAME')->getStyle('B8')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('C8','MODEL')->getStyle('C8')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('D8','COLOR CODE')->getStyle('D8')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E8','COLOR NAME')->getStyle('E8')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('F8','QUANTITY')->getStyle('F8')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('G8','UNIT')->getStyle('G8')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('H8','REMARKS')->getStyle('H8')->getFont()->setBold(true);
		$obj->getActiveSheet()->getStyle("A8:H8")->applyFromArray($border_style);
		
		$row = 9;
		foreach ($parts as $p) {
			$obj->getActiveSheet()->setCellValue('A' . $row, $p->PART_NUMBER);
			$obj->getActiveSheet()->setCellValue('B' . $row, $p->PART_NAME);
			$obj->getActiveSheet()->setCellValue('C' . $row, $p->MODEL);
			$obj->getActiveSheet()->setCellValue('D' . $row, $p->COLOR_CODE);
			$obj->getActiveSheet()->setCellValue('E' . $row, $p->COLOR_NAME);
			$obj->getActiveSheet()->setCellValue('F' . $row, $p->QUANTITY);
			$obj->getActiveSheet()->setCellValue('G' . $row, $p->UNIT);
			$obj->getActiveSheet()->setCellValue('H' . $row, $p->REMARKS);
	
			$obj->getActiveSheet()->getStyle("A" . $row . ":H" . $row)->applyFromArray($border_style);
			$row++;
		} // FOREACH END

		$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

	} // IF END




	if($main_table == "claims" && $parts_table == "claims_parts"){
		$s = fetch_functions\get_row('suppliers','SUPPLIER_CODE',$entry->SUPPLIER_CODE)[0];
		$obj->getActiveSheet()->setCellValue('A1','INVOICE NUMBER')->getStyle('A1')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B1',$entry->INVOICE_NUMBER);
		$obj->getActiveSheet()->setCellValue('A2','CLAIM ISSUE DATE')->getStyle('A2')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B2',$entry->CLAIM_ISSUE_DATE);
		$obj->getActiveSheet()->setCellValue('A3','SITE')->getStyle('A3')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B3',$entry->SITE);
		$obj->getActiveSheet()->setCellValue('A4','CREATED BY')->getStyle('A4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B4',$entry->CREATED_BY);
		$obj->getActiveSheet()->setCellValue('A5','APPROVED BY')->getStyle('A5')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B5',$entry->APPROVED_BY);
		$obj->getActiveSheet()->setCellValue('A6','SHIPPING_MODE')->getStyle('A6')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B6',$entry->SHIPPING_MODE);

		$obj->getActiveSheet()->setCellValue('D1','MODEL')->getStyle('D1')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E1',$entry->MODEL);
		$obj->getActiveSheet()->setCellValue('D2','APD NUMBER')->getStyle('D2')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E2',$entry->APD_NUMBER);
		$obj->getActiveSheet()->setCellValue('D3','PPD NUMBER')->getStyle('D3')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E3',$entry->PPD_NUMBER);
		$obj->getActiveSheet()->setCellValue('D4','CLAIM REFERENCE NUMBER')->getStyle('D4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E4',$entry->CLAIM_REFERENCE_NUMBER);
		$obj->getActiveSheet()->setCellValue('D5','LC NUMBER')->getStyle('D5')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E5',$entry->LC_NUMBER);
		$obj->getActiveSheet()->setCellValue('D6','MONTH - YEAR')->getStyle('D6')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E6', $entry->MONTH . '-' . $entry->YEAR);

		$obj->getActiveSheet()->setCellValue('A9','PART NUMBER')->getStyle('A9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B9','PART NAME')->getStyle('B9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('C9','COLOR CODE')->getStyle('C9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('D9','COLOR NAME')->getStyle('D9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E9','QUANTITY')->getStyle('E9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('F9','UNIT')->getStyle('F9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('G9','BOX NUMBER')->getStyle('G9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('H9','CASE NUMBER')->getStyle('H9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('I9','REFERENCE NUMBER')->getStyle('I9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('J9','LOT NUMBER')->getStyle('J9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('K9','CLAIM TYPE')->getStyle('K9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('L9','CLAIM CODE')->getStyle('L9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('M9','ACTION CODE')->getStyle('M9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('N9','PROCESS CODE')->getStyle('N9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('O9','DETAILS OF DEFECT')->getStyle('O9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('P9','DEFECT FINDING WAY')->getStyle('P9')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('Q9','REMARKS')->getStyle('Q9')->getFont()->setBold(true);
		$obj->getActiveSheet()->getStyle("A9:Q9")->applyFromArray($border_style);
		
		$row = 10;
		foreach ($parts as $p) {
			$obj->getActiveSheet()->setCellValue('A' . $row, $p->PART_NUMBER);
			$obj->getActiveSheet()->setCellValue('B' . $row, $p->PART_NAME);
			$obj->getActiveSheet()->setCellValue('C' . $row, $p->COLOR_CODE);
			$obj->getActiveSheet()->setCellValue('D' . $row, $p->COLOR_NAME);
			$obj->getActiveSheet()->setCellValue('E' . $row, $p->QUANTITY);
			$obj->getActiveSheet()->setCellValue('F' . $row, $p->UNIT);
			$obj->getActiveSheet()->setCellValue('G' . $row, $p->BOX_NUMBER);
			$obj->getActiveSheet()->setCellValue('H' . $row, $p->CASE_NUMBER);
			$obj->getActiveSheet()->setCellValue('I' . $row, $p->REFERENCE_NUMBER);
			$obj->getActiveSheet()->setCellValue('J' . $row, $p->LOT_NUMBER);
			$obj->getActiveSheet()->setCellValue('K' . $row, $p->CLAIM_TYPE);
			$obj->getActiveSheet()->setCellValue('L' . $row, $p->CLAIM_CODE);
			$obj->getActiveSheet()->setCellValue('M' . $row, $p->ACTION_CODE);
			$obj->getActiveSheet()->setCellValue('N' . $row, $p->PROCESS_CODE);
			$obj->getActiveSheet()->setCellValue('O' . $row, $p->DETAILS_OF_DEFECT);
			$obj->getActiveSheet()->setCellValue('P' . $row, $p->DEFECT_FINDING_WAY);
			$obj->getActiveSheet()->setCellValue('Q' . $row, $p->REMARKS);
	
			$obj->getActiveSheet()->getStyle("A" . $row . ":Q" . $row)->applyFromArray($border_style);
			$row++;
		} // FOREACH END

		$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
		$obj->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);

	} // IF END




	$style = array(
	    'alignment' => array(
	        'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
	    )
	);
    $obj->getDefaultStyle()->applyFromArray($style);

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$file_name.xlsx");
	header('Cache-Control: max-age=0');
	
	$writer = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
	$writer->save('php://output');

} // MAIN IF END









if($action == "print"){	
	if($main_table == "entries" && $parts_table == "parts"){
		if($type == "ckd") $title = "CKD Enter";
		if($type == "ckdbom") $title = "CKD Enter By BOM";
		if($type == "cbu") $title = "CBU Enter";
		if($type == "manufacturing-parts") $title = "Manufacturing Parts Enter";
		if($type == "spare-parts") $title = "Backup Spare Parts Enter";
		if($type == "additional-parts") $title = "Additional Parts Enter";
		$content = title_info($title);
		$content .= 
		"
		<div style='position:relative; width:100%; height:165px; margin-bottom:20px;'>
			<table style='position:absolute; left: 0; width:500px;'>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>REQUISITION NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->REQUISITION_NUMBER</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>DATE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->ENTRY_DATE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SITE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->SITE</td>
				</tr>
				";
				if($type == "ckd" || $type == "ckdbom" || $type == "cbu" || $type == "manufacturing-parts" || $type == "spare-parts"){
					$content .= "
					<tr>
						<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>INVOICE NUMBER</th>
						<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->INVOICE_NUMBER</td>
					</tr>
					<tr>
						<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>LC NUMBER</th>
						<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->LC_NUMBER</td>
					</tr>
					<tr>
						<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>LOT NUMBER</th>
						<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->LOT_NUMBER</td>
					</tr>
					"
					;
					if($type == "manufacturing-parts" || $type == "spare-parts"){
						$content .=
						"
						<tr>
							<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>PPD NUMBER</th>
							<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->PPD_NUMBER</td>
						</tr>
						";
					}
				}
				else if($type == "additional-parts"){
					$content .=
					"
					<tr>
						<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SUPPLIER CHALLAN NUMBER</th>
						<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->SUPPLIER_CHALLAN_NUMBER</td>
					</tr>
					"
					;
				}
			$content .= "</table>";
			
			$s = fetch_functions\get_row('suppliers','SUPPLIER_CODE',$entry->SUPPLIER_CODE)[0];
			$content .=
			"
			<table style='position:absolute; right: 0; width:500px;'>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SUPPLIER CODE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$s->SUPPLIER_CODE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SUPPLIER NAME</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$s->SUPPLIER_NAME</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SUPPLIER ADDRESS</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$s->SUPPLIER_ADDRESS</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SUPPLIER PHONE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$s->SUPPLIER_PHONE_OFFICE</td>
				</tr>
			</table>
		</div>

		<table style = 'width:100%;'>
			<tr>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>PART NUMBER</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>PART NAME</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>MODEL</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>COLOR CODE</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>COLOR NAME</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>QUANTITY</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>UNIT</th>
				"
				;
				if($type == "ckd" || $type == "ckdbom" || $type == "cbu"){
					$content .= 
					"
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>FRAME NO</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>ENGINE NO</th>
					"
					;
				}
				$content .= "
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>REMARKS</th>
			</tr>";

		foreach ($parts as $p) {
			$content.=
			"
			<tr>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->PART_NUMBER</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->PART_NAME</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->MODEL</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->COLOR_CODE</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->COLOR_NAME</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->QUANTITY</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->UNIT</td>
			"
			;
			if($type == "ckd" || $type == "ckdbom" || $type == "cbu"){
				$content .= 
				"
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->FRAME_NUMBER</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->ENGINE_NUMBER</td>
				"
				;
			}
			$content .= 
			"
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->REMARKS</td>
			</tr>
			";
		}
		$content .= "</table>" . footer_info("parts");
	} // IF END



	
	if($main_table == "issues" && $parts_table == "issue_records"){
		if($entry->REFERENCE_NUMBER == NULL || empty($entry)){ 
			$ref_req = "REQUISITION"; $req_sen = "REQUESTER"; 
			if($type == "ckd-issue") $title = "CKD Issue to Assembly";
			if($type == "ckd-bom-issue") $title = "CKD By BOM Issue to Assembly";
			if($type == "cripple-issue") $title = "Cripple Issue to Assembly";
			if($type == "cbu-issue") $title = "CBU Issue to Assembly";
			if($type == "manufacturing-issue") $title = "Manufacturing Parts Issue to MF Unit";
			if($type == "spare-issue") $title = "Backup Spare Parts Issue to Assembly";
		}
		else { 
			$ref_req = "REFERENCE"; $req_sen = "SENDER"; 
			if($type == "ckd-issue") $title = "CKD Issue Received";
			if($type == "ckd-bom-issue") $title = "CKD By BOM Issue Received";
			if($type == "cripple-issue") $title = "Cripple Issue Received";
			if($type == "cbu-issue") $title = "CBU Issue Received";
			if($type == "manufacturing-issue") $title = "Manufacturing Parts Issue Received";
		}

		$content = title_info($title);
		$content .= 
		"
		<div style='position:relative; width:100%; height:160px; margin-bottom:20px;'>
			<table style='position:absolute; left: 0; width:500px;'>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$ref_req NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->REFERENCE_NUMBER</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>DATE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->ENTRY_DATE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SITE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->SITE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>INVOICE NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->INVOICE_NUMBER</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>LC NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->LC_NUMBER</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>LOT NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->LOT_NUMBER</td>
				</tr>
				"
				;
				if($type == "manufacturing-issue" || $type == "spare-issue"){
					$content .=
					"
					<tr>
						<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>PPD NUMBER</th>
						<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->PPD_NUMBER</td>
					</tr>
					";
				}
			$content .= 
			"
			</table>

			<table style='position:absolute; right: 0; width:500px;'>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$req_sen NAME</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->NAME</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$req_sen DESIGNATION</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->DESIGNATION</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$req_sen DEPARTMENT</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->DEPARTMENT</td>
				</tr>
			</table>
		</div>

		<table style = 'width:100%;'>
			<tr>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>PART NUMBER</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>PART NAME</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>MODEL</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>COLOR CODE</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>COLOR NAME</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>QUANTITY</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>UNIT</th>
				"
				;
				if($type != "manufacturing-issue" && $type != "spare-issue"){
					$content .= 
					"
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>FRAME NO</th>
					"
					;
				}

		$content .= 
		"
			<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>ENGINE NO</th>
		";
		if($type == "cripple-issue"){
			$content .= "<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>CRIPPLE REASON</th>";
		}
		$content .= 
		"	<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>REMARKS</th>
		</tr>
		";

		foreach ($parts as $p) {
			$content.=
			"
			<tr>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->PART_NUMBER</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->PART_NAME</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->MODEL</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->COLOR_CODE</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->COLOR_NAME</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->QUANTITY</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->UNIT</td>
			"
			;
			if($type != "manufacturing-issue" && $type != "spare-issue"){
				$content .= 
				"<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->FRAME_NUMBER</td>";
			}
			$content .=
			"<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->ENGINE_NUMBER</td>";
			if($type == "cripple-issue"){
				$content .= 
				"<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->CRIPPLE_REASON</td>";
			}
			$content .= 
			"	<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->REMARKS</td>
			</tr>
			";
		}
		$content .= "</table>" . footer_info("issue");
	
	} // IF END



	
	if($main_table == "delivery" && $parts_table == "delivery_parts"){
		$c = fetch_functions\get_row('customers','CUSTOMER_ID',$entry->CUSTOMER_CODE)[0];
		$content = title_info("Delivery Challan");
		$content .= 
		"
		<div style='position:relative; width:100%; height:220px; margin-bottom:20px;'>
			<table style='position:absolute; left: 0; width:500px;'>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>DELIVERY CHALLAN NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->KEY_ID</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>ACTUAL DO DATE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->DO_DATE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>DELIVERY DATE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->DELIVERY_DATE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SITE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->SITE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>REFERENCE DO NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->REFERENCE_DO_NUMBER</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>REFERENCE CO NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->REFERENCE_CO_NUMBER</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>TRANSPORT NAME</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->TRANSPORT_NAME</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>TRUCK NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->TRUCK_NUMBER</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:20px 0px 0px 0px;'>Please receive undermentioned Vehicles / Packages</th>
				</tr>
			</table>

			<table style='position:absolute; right: 0; width:500px;'>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>DRIVER NAME</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->DRIVER_NAME</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>DRIVER MOBILE NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->DRIVER_MOBILE_NUMBER</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SALES CHANNEL</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->SALES_CHANNEL</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CUSTOMER CODE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->CUSTOMER_CODE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CUSTOMER NAME</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$c->CUSTOMER_NAME</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CUSTOMER ADDRESS</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$c->CUSTOMER_ADDRESS</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CUSTOMER CONTACT</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$c->CUSTOMER_PHONE_OFFICE</td>
				</tr>
			</table>
		</div>

		<table style = 'width:100%;'>
			<tr>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>PART NO</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>PART NAME</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>MODEL</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>COLOR CODE</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>COLOR NAME</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>QTY</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>UNIT</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>FRAME NO</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>ENGINE NO</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>KEY RING</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>BATTERY</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>REMARKS</th>
			</tr>
			"
			;

			foreach ($parts as $p) {
			$content.=
			"
			<tr>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->PART_NUMBER</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->PART_NAME</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->MODEL</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->COLOR_CODE</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->COLOR_NAME</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->QUANTITY</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->UNIT</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->FRAME_NUMBER</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->ENGINE_NUMBER</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->KEY_RING_NUMBER</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->BATTERY_NUMBER</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->REMARKS</td>
			</tr>
			"
			;
		}

		$content .= "</table>" . footer_info("delivery");
	
	} // IF END



	
	if($main_table == "additional_delivery" && $parts_table == "delivery_parts"){
		$c = fetch_functions\get_row('customers','CUSTOMER_ID',$entry->CUSTOMER_CODE)[0];
		$content = title_info("Additional Delivery");
		$content .= 
		"
		<div style='position:relative; width:100%; height:140px; margin-bottom:20px;'>
			<table style='position:absolute; left: 0; width:500px;'>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>ADDITIONAL DELIVERY NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->KEY_ID</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>ACTUAL DO DATE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->DO_DATE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>DELIVERY DATE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->DELIVERY_DATE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SITE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->SITE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>REFERENCE DO NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->REFERENCE_DO_NUMBER</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>REFERENCE CO NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->REFERENCE_CO_NUMBER</td>
				</tr>
			</table>

			<table style='position:absolute; right: 0; width:500px;'>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SALES CHANNEL</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->SALES_CHANNEL</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CUSTOMER CODE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->CUSTOMER_CODE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CUSTOMER NAME</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$c->CUSTOMER_NAME</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CUSTOMER ADDRESS</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$c->CUSTOMER_ADDRESS</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CUSTOMER CONTACT</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$c->CUSTOMER_PHONE_OFFICE</td>
				</tr>
			</table>
		</div>

		<table style = 'width:100%;'>
			<tr>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>PART NO</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>PART NAME</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>MODEL</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>COLOR CODE</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>COLOR NAME</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>QTY</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>UNIT</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>REMARKS</th>
			</tr>
			"
			;

			foreach ($parts as $p) {
			$content.=
			"
			<tr>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->PART_NUMBER</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->PART_NAME</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->MODEL</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->COLOR_CODE</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->COLOR_NAME</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->QUANTITY</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->UNIT</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->REMARKS</td>
			</tr>
			"
			;
		}

		$content .= "</table>" . footer_info("additional_delivery");
	
	} // IF END



	
	if($main_table == "backup_delivery" && $parts_table == "delivery_parts"){
		$c = fetch_functions\get_row('customers','CUSTOMER_ID',$entry->CUSTOMER_CODE)[0];
		$content = title_info("Backup Parts Delivery");
		$content .= 
		"
		<div style='position:relative; width:100%; height:120px;'>
			<table style='position:absolute; top:0; left: 0; width:500px;'>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>BACKUP DELIVERY NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->KEY_ID</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>DATE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->DELIVERY_DATE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SITE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->SITE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>REQUISITION NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->REQUISITION_NUMBER</td>
				</tr>
			</table>

			<table style='position:absolute; right: 0; width:500px;'>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>REQUESTER NAME</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->REQUESTER_NAME</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>REQUESTER DESIGNATION</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->REQUESTER_DESIGNATION</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>REQUESTER DEPARTMENT</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->REQUESTER_DEPARTMENT</td>
				</tr>
			</table>
		</div>

		<table style = 'width:100%;'>
			<tr>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>PART NO</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>PART NAME</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>MODEL</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>COLOR CODE</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>COLOR NAME</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>QTY</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>UNIT</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>REMARKS</th>
			</tr>
			"
			;

			foreach ($parts as $p) {
			$content.=
			"
			<tr>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->PART_NUMBER</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->PART_NAME</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->MODEL</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->COLOR_CODE</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->COLOR_NAME</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->QUANTITY</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->UNIT</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->REMARKS</td>
			</tr>
			"
			;
		}

		$content .= "</table>" . footer_info("backup_delivery");
	
	} // IF END



	
	if($main_table == "return_order" && $parts_table == "returned_parts"){
		$c = fetch_functions\get_row('customers','CUSTOMER_ID',$entry->CUSTOMER_CODE)[0];
		$content = title_info("Return in Order");
		$content .= 
		"
		<div style='position:relative; width:100%; height:120px;'>
			<table style='position:absolute; top:0; left: 0; width:500px;'>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>RETURN ORDER ID</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->KEY_ID</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>DATE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->RETURN_DATE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>DELIVERY CHALLAN NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->DELIVERY_CHALLAN_NUMBER</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SALES CHANNEL</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->SALES_CHANNEL</td>
				</tr>
			</table>

			<table style='position:absolute; right: 0; width:500px;'>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CUSTOMER CODE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$c->CUSTOMER_ID</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CUSTOMER NAME</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$c->CUSTOMER_NAME</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CUSTOMER ADDRESS</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$c->CUSTOMER_ADDRESS</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CUSTOMER CONTACT</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$c->CUSTOMER_PHONE_OFFICE</td>
				</tr>
			</table>
		</div>

		<table style = 'width:100%;'>
			<tr>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>PART NUMBER</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>PART NAME</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>MODEL</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>COLOR CODE</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>COLOR NAME</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>QTY</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>UNIT</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>RETURN REASON</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>REMARKS</th>
			</tr>
			"
			;

			foreach ($parts as $p) {
			$content.=
			"
			<tr>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->PART_NUMBER</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->PART_NAME</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->MODEL</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->COLOR_CODE</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->COLOR_NAME</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->QUANTITY</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->UNIT</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->RETURN_REASON</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->REMARKS</td>
			</tr>
			"
			;
		}

		$content .= "</table>" . footer_info("return_order");
	
	} // IF END



	
	if($main_table == "purchase_requisitions" && $parts_table == "purchase_requisitions_parts"){
		$s = fetch_functions\get_row('suppliers','SUPPLIER_CODE',$entry->SUPPLIER_CODE)[0];
		$content = title_info("Purchase Requisitions");
		$content .= 
		"
		<div style='position:relative; width:100%; height:150px;'>
			<table style='position:absolute; top:0; left: 0; width:500px;'>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>PURCHASE REQUISITION NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->KEY_ID</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>DATE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->REQUISITION_DATE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>REQUESTER NAME</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->REQUESTER_NAME</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>REQUESTER DESIGNATION</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->REQUESTER_DESIGNATION</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>REQUESTER DEPARTMENT</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->REQUESTER_DEPARTMENT</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>APPROVED BY</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->APPROVED_BY</td>
				</tr>
			</table>

			<table style='position:absolute; right: 0; width:500px;'>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SUPPLIER CODE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px; padding-left:5px;'>$s->SUPPLIER_CODE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SUPPLIER NAME</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px; padding-left:5px;'>$s->SUPPLIER_NAME</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SUPPLIER ADDRESS</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px; padding-left:5px;'>$s->SUPPLIER_ADDRESS</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SUPPLIER CONTACT</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px; padding-left:5px;'>$s->SUPPLIER_PHONE_OFFICE</td>
				</tr>
			</table>
		</div>

		<table style = 'width:100%;'>
			<tr>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>PART NUMBER</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>PART NAME</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>MODEL</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>COLOR CODE</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>COLOR NAME</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>QTY</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>UNIT</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>REMARKS</th>
			</tr>
			"
			;

			foreach ($parts as $p) {
			$content.=
			"
			<tr>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->PART_NUMBER</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->PART_NAME</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->MODEL</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->COLOR_CODE</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->COLOR_NAME</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->QUANTITY</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->UNIT</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->REMARKS</td>
			</tr>
			"
			;
		}

		$content .= "</table>" . footer_info("purchase_requisitions");
	
	} // IF END



	
	if($main_table == "claims" && $parts_table == "claims_parts"){
		if($type == "ckd-claim") $title = "Claims for CKD";
		if($type == "ckd-bom-claim") $title = "Claims for CKD BOM";
		if($type == "cbu-claim") $title = "Claims for CBU";
		if($type == "manufacturing-claim") $title = "Claims for Manufacturing Parts";
		if($type == "spare-claim") $title = "Claims for Spare Parts";
		if($type == "additional-claim") $title = "Claims for Additional Parts";
		if($type == "claims-claim") $title = "Claims for Claim Parts";
		$content = title_info($title);
		$content .= 
		"
		<div style='position:relative; width:100%; height:150px; margin-bottom:20px;'>
			<table style='position:absolute; left: 0; width:500px;'>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>INVOICE NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->INVOICE_NUMBER</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CLAIM ISSUE DATE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->CLAIM_ISSUE_DATE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CREATED BY</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->CREATED_BY</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>APPROVED BY</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->APPROVED_BY</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SHIPPING MODE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->SHIPPING_MODE</td>
				</tr>
			</table>

			<table style='position:absolute; right: 0; width:500px;'>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SITE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->SITE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>MODEL</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->MODEL</td>
				</tr>";
				if($type == "ckd-claim" || $type == "ckd-bom-claim"){
				$content .= "
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>APD NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->APD_NUMBER</td>
				</tr>";
				}
				if($type != "cbu-claim"){
				$content .="
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>PPD NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->PPD_NUMBER</td>
				</tr>";
				}
				if($type != "additional-claim"){
				$content .="
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CLAIM REFERENCE NO</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->CLAIM_REFERENCE_NUMBER</td>
				</tr>";
				}
				$content .= "
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CUSTOMER CODE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->LC_NUMBER</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>MONTH - YEAR</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->MONTH - $entry->YEAR</td>
				</tr>
			</table>
		</div>

		<table style = 'width:100%;'>
			<tr>
				<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>Part No</th>
				<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>Part Name</th>
				<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>Color </th>
				<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>QTY</th>
				<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>Box</th>
				";
				if($type != "cbu-claim" && $type !="additional-claim"){
				$content .= "
				<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>Case</th>";
				}
				if($type == "ckd-claim" || $type == "ckd-bom-claim"){
				$content .= "
				<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>Ref No</th>";
				}
				$content .= "
				<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>Lot</th>
				<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold; width:35px;'>Claim No</th>
				<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold; width:35px;'>Action No</th>";

				if($type == "ckd-claim" || $type == "ckd-bom-claim"){
					$content .= "
					<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold; width:35px;'>Process No</th>
					";
				}
				$content .= "
				<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold; width:80px;'>Details</th>
				<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold; width:80px;'>Defect Found</th>
				<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold; width:80px;'>Picture</th>
				<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold; width:80px;'>Remarks</th>
			</tr>
			"
			;

			foreach ($parts as $p) {
			if(empty($p->PICTURE)) $img = "";
			else $img = "<img src='$p->PICTURE' style='width:80px; max-height:50px;'>";
			$content.=
			"
			<tr>
				<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->PART_NUMBER</td>
				<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->PART_NAME</td>
				<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->COLOR_NAME</td>
				<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->QUANTITY  $p->UNIT</td>
				<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->BOX_NUMBER</td>";

				if($type != "cbu-claim" && $type != "additional-claim"){
					$content .= "
					<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->CASE_NUMBER</td>
					";
				}

				if($type == "ckd-claim" || $type == "ckd-bom-claim"){
					$content .= "
					<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->REFERENCE_NUMBER</td>";
				}
				$content .= "
				<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->LOT_NUMBER</td>
				<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; width:35px;'>$p->CLAIM_CODE</td>
				<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; width:35px;'>$p->ACTION_CODE</td>";

				if($type == "ckd-claim" || $type == "ckd-bom-claim"){
					$content .= "<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; width:35px;'>$p->PROCESS_CODE</td>";
				}

				$content .= "
				<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; width:80px;'>$p->DETAILS_OF_DEFECT</td>
				<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; width:80px;'>$p->DEFECT_FINDING_WAY</td>
				<td style='border: 1px solid #ccc; padding:3px; width:80px;'>$img</td>
				<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; width:80px;'>$p->REMARKS</td>
			</tr>
			"
			;

		}

		$content .= "</table>" . footer_info("claims");
	
	} // IF END




	$dompdf = new Dompdf();
	$dompdf->loadHtml($content);
	$dompdf->set_paper('A4','landscape');
	$dompdf->render();
	$pdf = $file_name . ".pdf";
	$font = $dompdf->getFontMetrics()->get_font("helvetica");
	$dompdf->getCanvas()->page_text(740, 560, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 11, array(0,0,0));
	$dompdf->stream($pdf,array('Attachment'=>0));

} // MAIN IF END








function title_info($title){
	$header = "
	<html>
	<head><title>$title</title></head>
	<body style = 'margin-bottom: 10px;'>
	<div class='page' style='width:1024px; height:auto; margin: auto; margin-top: -20px;'>
		<div class='header' style = 'width: 100%; height: 65px; margin-top: 0px; position:relative; border-bottom: 1px solid #ccc; padding-bottom:5px; margin-bottom: 20px;'>
			<img src='../img/suzuki-logo.png' alt='Rancon Motors' style='position: absolute; left: 0; top: 0; max-width: 200px;'>
			<h2 style='font-weight: bold; font-size: 15px; color: #333; font-family: helvetica,sans-serif; margin: 0px; text-align:center; margin-top:0; margin-bottom:10px;'>
				$title<br>Rancon Motor Bikes Limited<br>Boro Brobanipur, Kashimpur, Gazipur, Dhaka
			</h2>
		</div> <!-- /header -->


		<div class='main-section' style='width: 100%; height: auto;'>
		";

	return $header;
}




function footer_info($type){
	if($type == "delivery"){
		$signature_content = "
		<div style = 'clear:both; position: relative; width: 100%;'>
			<p style='font-weight:bold; font-family:helvetica; font-size:13px; color:#333;'>Checked & found correct and received the above mentioned vehicles with tools & accessories with satisfaction</p>
			<table style = 'width : 100%; margin-top: 50px;'>
				<tr>
					<td style='width:30%; text-align: center; font-size:11px; font-family:helvetica; margin-right:3%'>
						___________________________________ <br>
						BUYER/DELIVERER SIGNATURE
					</td>
					<td style='width:30%; text-align: center; font-size:11px; font-family:helvetica; margin-right:3%'>
						_____________________________________ <br> 
						FOR RANCON MOTOR BIKE LTD
					</td>
				</tr>
			</table>";
	}

	else if($type == "gate_pass"){
		$signature_content = "
		<div style = 'clear:both; position: relative; width: 100%;'>
			<table style = 'width : 100%; margin-top: 50px;'>
				<tr>
					<td style='width:30%; text-align: center; font-size:11px; font-family:helvetica; margin-right:3%'>
						___________________________________ <br>
						BUYER/DELIVERER SIGNATURE
					</td>
					<td style='width:30%; text-align: center; font-size:11px; font-family:helvetica; margin-right:3%'>
						____________________________________ <br>
						SECURITY DEPT
					</td>
					<td style='width:30%; text-align: center; font-size:11px; font-family:helvetica; margin-right:3%'>
						_____________________________________ <br>
						DELIVERY INCHARGE
					</td>
					<td style='width:30%; text-align: center; font-size:11px; font-family:helvetica; margin-right:3%'>
						_____________________________________ <br> 
						FOR RANCON MOTOR BIKE LTD
					</td>
				</tr>
			</table>";
	}
	else{
		$signature_content = "
		<div style = 'clear:both; position: relative; width: 100%;'>
			<table style = 'width : 100%; margin-top: 50px;'>
				<tr>
					<td style='width:30%; text-align: center; font-size:11px; font-family:helvetica; margin-right:3%'>
						___________________________________ <br>
						PREPARED BY
					</td>
					<td style='width:30%; text-align: center; font-size:11px; font-family:helvetica; margin-right:3%'>
						____________________________________ <br>
						CHECKED BY
					</td>
					<td style='width:30%; text-align: center; font-size:11px; font-family:helvetica; margin-right:3%'>
						_____________________________________ <br>
						APPROVED BY
					</td>
				</tr>
			</table>";
	}
	$time = date('M d, Y',time() + 4*60*60) . " " . date('h:i:s A', time() + 4*60*60);
	$footer = "
		</div> <!-- /main-section -->
		$signature_content
		<img src='../img/rancon-logo.png' alt='rancon logo' style='position:fixed; left:0; bottom:10px; width:200px;'>
		<img src='../img/rangs-logo.png' alt='rangs logo' style='position:fixed; left:220px; bottom:10px; width:80px;'>
		<footer style='position:fixed; left: 480px; bottom:5px; font-family: helvetica;'>$time</footer>
	</div> <!-- /page -->
	</body>
	</html>
	";

	return $footer;
}




if($action == "gate-pass"){
	if($main_table == "delivery" && $parts_table == "delivery_parts"){
		$c = fetch_functions\get_row('customers','CUSTOMER_ID',$entry->CUSTOMER_CODE)[0];
		$content = title_info("Gate Pass");
		$gate_pass = time();
		$content .= 
		"
		<div style='position:relative; width:100%; height:200px; margin-bottom:20px;'>
			<table style='position:absolute; left: 0; width:500px;'>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>Gate Pass</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$gate_pass</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>ACTUAL DO DATE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->DO_DATE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>DELIVERY DATE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->DELIVERY_DATE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SITE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->SITE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>REFERENCE DO NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->REFERENCE_DO_NUMBER</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>REFERENCE CO NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->REFERENCE_CO_NUMBER</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>TRANSPORT NAME</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->TRANSPORT_NAME</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>TRUCK NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->TRUCK_NUMBER</td>
				</tr>
			</table>

			<table style='position:absolute; right: 0; width:500px;'>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>DRIVER NAME</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->DRIVER_NAME</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>DRIVER MOBILE NUMBER</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->DRIVER_MOBILE_NUMBER</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>SALES CHANNEL</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->SALES_CHANNEL</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CUSTOMER CODE</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$entry->CUSTOMER_CODE</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CUSTOMER NAME</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$c->CUSTOMER_NAME</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CUSTOMER ADDRESS</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$c->CUSTOMER_ADDRESS</td>
				</tr>
				<tr>
					<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>CUSTOMER CONTACT</th>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>$c->CUSTOMER_PHONE_OFFICE</td>
				</tr>
			</table>
		</div>

		<table style = 'width:100%;'>
			<tr>
				<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>PART NO</th>
				<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>PART NAME</th>
				<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>MODEL</th>
				<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>COLOR CODE</th>
				<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>COLOR NAME</th>
				<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>QTY</th>
				<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>UNIT</th>
				<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>FRAME NO</th>
				<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>ENGINE NO</th>
				<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>KEY RING</th>
				<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>BATTERY</th>
				<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px; font-weight:bold;'>REMARKS</th>
			</tr>
			"
			;

			foreach ($parts as $p) {
			$content.=
			"
			<tr>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->PART_NUMBER</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->PART_NAME</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->MODEL</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->COLOR_CODE</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->COLOR_NAME</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->QUANTITY</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->UNIT</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->FRAME_NUMBER</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->ENGINE_NUMBER</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->KEY_RING_NUMBER</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->BATTERY_NUMBER</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ccc; padding:3px;'>$p->REMARKS</td>
			</tr>
			"
			;
		}

		$content .= "</table>" . footer_info("gate_pass");

	} // IF END

	$dompdf = new Dompdf();
	$dompdf->loadHtml($content);
	$dompdf->set_paper('A4','landscape');
	$dompdf->render();
	$pdf = $file_name . ".pdf";
	$font = $dompdf->getFontMetrics()->get_font("helvetica");
	$dompdf->getCanvas()->page_text(740, 560, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 11, array(0,0,0));
	$dompdf->stream($pdf,array('Attachment'=>0));
}
?>