<?php namespace suzuki\reports;
error_reporting(0);
require_once '../excel-reader/Classes/PHPExcel.php';
require_once '../excel-reader/Classes/PHPExcel/IOFactory.php';
require_once 'functions.php';
require '../dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use suzuki\fetch_functions;
use suzuki\export_excel;




$main_table = htmlspecialchars($_POST['entries']);
$parts_table = htmlspecialchars($_POST['parts']);
$type = htmlspecialchars($_POST['type']);
$report_type = htmlspecialchars($_POST['report-type']);
$selection_type = htmlspecialchars($_POST['selection-type']);
$selection_value = htmlspecialchars($_POST['selection-value']);

if($selection_type == "generic"){
	if($selection_value == "hour"){
		if(($main_table == "entries" && $parts_table == "parts") || ($main_table == "claims" && $parts_table == "claims_parts")){
			$stmt = $con->prepare("SELECT * FROM $main_table WHERE TYPE = :TYPE AND DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT ORDER BY HOUR(ENTERED_AT)");
			$stmt->execute(array('TYPE' => $type, 'ENTERED_AT' => htmlspecialchars($_POST['hour'])));	
		}
		
		else if($main_table == "issues" && $parts_table == "issue_records"){
			$status = htmlspecialchars($_POST['status']);
			if($status == "issued"){
				$stmt = $con->prepare("SELECT * FROM $main_table WHERE TYPE = :TYPE AND RECEIVED != 1 AND DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT ORDER BY HOUR(ENTERED_AT)");
				$stmt->execute(array('TYPE' => $type, 'ENTERED_AT' => htmlspecialchars($_POST['hour'])));		
			}
			else if($status == "received"){
				$stmt = $con->prepare("SELECT * FROM $main_table WHERE TYPE = :TYPE AND RECEIVED = 1 AND DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT ORDER BY HOUR(ENTERED_AT)");
				$stmt->execute(array('TYPE' => $type, 'ENTERED_AT' => htmlspecialchars($_POST['hour'])));			
			}
		}

		else if(($main_table == "delivery" || $main_table == "additional_delivery" || $main_table == "backup_delivery") && $parts_table == "delivery_parts"){
			$stmt = $con->prepare("SELECT * FROM $main_table WHERE DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT ORDER BY HOUR(ENTERED_AT)");
			$stmt->execute(array('ENTERED_AT' => htmlspecialchars($_POST['hour'])));
		}

		else if(($main_table == "purchase_requisitions" && $parts_table == "purchase_requisitions_parts") || ($main_table == "return_order" && $parts_table == "returned_parts") || ($main_table == "suppliers" || $main_table == "customers")){
			$stmt = $con->prepare("SELECT * FROM $main_table WHERE DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT ORDER BY HOUR(ENTERED_AT)");
			$stmt->execute(array('ENTERED_AT' => htmlspecialchars($_POST['hour'])));	
		}
	}


	if($selection_value == "date-range"){
		$date_parts = explode('-', htmlspecialchars($_POST['date-range-start']));
		$starting_date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
		$date_parts = explode('-', htmlspecialchars($_POST['date-range-end']));
		$end_date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];

		if(($main_table == "entries" && $parts_table == "parts") || ($main_table == "claims" && $parts_table == "claims_parts")){
			$stmt = $con->prepare("SELECT * FROM $main_table WHERE TYPE = :TYPE AND  DATE(ENTERED_AT) BETWEEN :STARING_DATE AND :ENDING_DATE ORDER BY DATE(ENTERED_AT)");
			$stmt->execute(array('TYPE' => $type, 'STARING_DATE' => $starting_date, 'ENDING_DATE' => $end_date));
		}
		else if($main_table == "issues" && $parts_table == "issue_records"){
			$status = htmlspecialchars($_POST['status']);
			if($status == "issued"){
				$stmt = $con->prepare("SELECT * FROM $main_table WHERE TYPE = :TYPE AND RECEIVED != 1 AND DATE(ENTERED_AT) BETWEEN :STARING_DATE AND :ENDING_DATE ORDER BY DATE(ENTERED_AT)");
				$stmt->execute(array('TYPE' => $type, 'STARING_DATE' => $starting_date, 'ENDING_DATE' => $end_date));
			}

			else if($status == "received"){
				$stmt = $con->prepare("SELECT * FROM $main_table WHERE TYPE = :TYPE AND RECEIVED = 1 AND DATE(ENTERED_AT) BETWEEN :STARING_DATE AND :ENDING_DATE ORDER BY DATE(ENTERED_AT)");
				$stmt->execute(array('TYPE' => $type, 'STARING_DATE' => $starting_date, 'ENDING_DATE' => $end_date));
			}
		}

		else if(($main_table == "delivery" || $main_table == "additional_delivery" || $main_table == "backup_delivery") && $parts_table == "delivery_parts"){
			$stmt = $con->prepare("SELECT * FROM $main_table WHERE DATE(ENTERED_AT) BETWEEN :STARING_DATE AND :ENDING_DATE ORDER BY DATE(ENTERED_AT)");
			$stmt->execute(array('STARING_DATE' => $starting_date, 'ENDING_DATE' => $end_date));
		}

		else if(($main_table == "purchase_requisitions" && $parts_table == "purchase_requisitions_parts") || ($main_table == "return_order" && $parts_table == "returned_parts") || ($main_table == "suppliers" || $main_table == "customers")){
			$stmt = $con->prepare("SELECT * FROM $main_table WHERE DATE(ENTERED_AT) BETWEEN :STARING_DATE AND :ENDING_DATE ORDER BY DATE(ENTERED_AT)");
			$stmt->execute(array('STARING_DATE' => $starting_date, 'ENDING_DATE' => $end_date));
		}
	}


	if($selection_value == "specify-date"){
		$date_parts = explode('-', htmlspecialchars($_POST['specify-date']));
		$date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
		if(($main_table == "entries" && $parts_table == "parts") || ($main_table == "claims" && $parts_table == "claims_parts")){
			$stmt = $con->prepare("SELECT * FROM $main_table WHERE TYPE = :TYPE AND DATE(ENTERED_AT) = :SPECIFIED_DATE");
			$stmt->execute(array('TYPE' => $type, 'SPECIFIED_DATE' => $date));
		}
		
		else if($main_table == "issues" && $parts_table == "issue_records"){
			$status = htmlspecialchars($_POST['status']);
			if($status == "issued"){
				$stmt = $con->prepare("SELECT * FROM $main_table WHERE TYPE = :TYPE AND RECEIVED != 1 AND DATE(ENTERED_AT) = :SPECIFIED_DATE");
				$stmt->execute(array('TYPE' => $type, 'SPECIFIED_DATE' => $date));
			}
			else if($status == "received"){
				$stmt = $con->prepare("SELECT * FROM $main_table WHERE TYPE = :TYPE AND RECEIVED = 1 AND DATE(ENTERED_AT) = :SPECIFIED_DATE");
				$stmt->execute(array('TYPE' => $type, 'SPECIFIED_DATE' => $date));
			}
		}

		else if(($main_table == "delivery" || $main_table == "additional_delivery" || $main_table == "backup_delivery") && $parts_table == "delivery_parts"){
			$stmt = $con->prepare("SELECT * FROM $main_table WHERE DATE(ENTERED_AT) = :SPECIFIED_DATE");
			$stmt->execute(array('SPECIFIED_DATE' => $date));
		}

		else if(($main_table == "purchase_requisitions" && $parts_table == "purchase_requisitions_parts") || ($main_table == "return_order" && $parts_table == "returned_parts") || ($main_table == "suppliers" || $main_table == "customers")){
			$stmt = $con->prepare("SELECT * FROM $main_table WHERE DATE(ENTERED_AT) = :SPECIFIED_DATE");
			$stmt->execute(array('SPECIFIED_DATE' => $date));
		}
	}
}


if(($main_table != "stock" && $parts_table != "stock_parts") && ($main_table != "pending" && $parts_table != "pending_parts")) {
	$entries = $stmt->fetchAll(\PDO::FETCH_OBJ);
}









if($report_type == "excel"){
	$obj = new \PHPExcel();
	$border_style = array(
	    'borders' => array(
	        'allborders' => array(
	            'style' => \PHPExcel_Style_Border::BORDER_THIN,
	            'color' => array('rgb' => '000000')
	        )
	    )
	);


	if($main_table == "suppliers"){
		$file_name = "SUPPLIERS_" . time();
		$obj->getActiveSheet()->setCellValue('E2','LIST OF SUPPLIERS')->getStyle('E2')->getFont()->setBold(true)->setSize(16);
		$obj->getActiveSheet()->setCellValue('A4','CODE')->getStyle('A4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B4','NAME')->getStyle('B4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('C4','PHONE(OFFICE)')->getStyle('C4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('D4','MOBILE NUMBER')->getStyle('D4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E4','EMAIL')->getStyle('E4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('F4','FAX NUMBER')->getStyle('F4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('G4','CITY')->getStyle('G4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('H4','COUNTRY')->getStyle('H4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('I4','ADDRESS')->getStyle('I4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('J4','WEBSITE')->getStyle('J4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('K4','ENTRY DATE')->getStyle('K4')->getFont()->setBold(true);
		$obj->getActiveSheet()->getStyle("A4:K4")->applyFromArray($border_style);
		
		$i= 5;
		foreach ($entries as $entry) {
			$obj->getActiveSheet()->setCellValue('A' . $i, $entry->SUPPLIER_CODE);
			$obj->getActiveSheet()->setCellValue('B' . $i, $entry->SUPPLIER_NAME);
			$obj->getActiveSheet()->setCellValue('C' . $i, $entry->SUPPLIER_PHONE_OFFICE);
			$obj->getActiveSheet()->setCellValue('D' . $i, $entry->SUPPLIER_PHONE_MOBILE);
			$obj->getActiveSheet()->setCellValue('E' . $i, $entry->SUPPLIER_EMAIL);
			$obj->getActiveSheet()->setCellValue('F' . $i, $entry->SUPPLIER_FAX);
			$obj->getActiveSheet()->setCellValue('G' . $i, $entry->SUPPLIER_CITY);
			$obj->getActiveSheet()->setCellValue('H' . $i, $entry->SUPPLIER_COUNTRY);
			$obj->getActiveSheet()->setCellValue('I' . $i, $entry->SUPPLIER_ADDRESS);
			$obj->getActiveSheet()->setCellValue('J' . $i, $entry->SUPPLIER_WEBSITE);
			$obj->getActiveSheet()->setCellValue('K' . $i, date('d M, Y',strtotime($entry->ENTERED_AT)));
			
			$obj->getActiveSheet()->getStyle("A" . $i . ":K" . $i)->applyFromArray($border_style);
			$i++;
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
	}


	if($main_table == "customers"){
		$file_name = "CUSTOMERS_" . time();
		$obj->getActiveSheet()->setCellValue('G2','LIST OF CUSTOMERS')->getStyle('G2')->getFont()->setBold(true)->setSize(16);
		$obj->getActiveSheet()->setCellValue('A4','ID')->getStyle('A4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('B4','NAME')->getStyle('B4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('C4','TYPE')->getStyle('C4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('D4','PHONE(OFFICE)')->getStyle('D4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('E4','PHONE(OPTIONAL)')->getStyle('E4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('F4','MOBILE NUMBER')->getStyle('F4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('G4','EMAIL')->getStyle('G4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('H4','FAX NUMBER')->getStyle('H4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('I4','CITY')->getStyle('I4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('J4','ADDRESS')->getStyle('J4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('K4','WEBSITE')->getStyle('K4')->getFont()->setBold(true);
		$obj->getActiveSheet()->setCellValue('L4','ENTRY DATE')->getStyle('L4')->getFont()->setBold(true);
		$obj->getActiveSheet()->getStyle("A4:L4")->applyFromArray($border_style);
		
		$i= 5;
		foreach ($entries as $entry) {
			$obj->getActiveSheet()->setCellValue('A' . $i, $entry->CUSTOMER_ID);
			$obj->getActiveSheet()->setCellValue('B' . $i, $entry->CUSTOMER_NAME);
			$obj->getActiveSheet()->setCellValue('C' . $i, $entry->CUSTOMER_TYPE);
			$obj->getActiveSheet()->setCellValue('D' . $i, $entry->CUSTOMER_PHONE_OFFICE);
			$obj->getActiveSheet()->setCellValue('E' . $i, $entry->CUSTOMER_PHONE_OPTIONAL);
			$obj->getActiveSheet()->setCellValue('F' . $i, $entry->CUSTOMER_PHONE_MOBILE);
			$obj->getActiveSheet()->setCellValue('G' . $i, $entry->CUSTOMER_EMAIL);
			$obj->getActiveSheet()->setCellValue('H' . $i, $entry->CUSTOMER_FAX);
			$obj->getActiveSheet()->setCellValue('I' . $i, $entry->CUSTOMER_CITY);
			$obj->getActiveSheet()->setCellValue('J' . $i, $entry->CUSTOMER_ADDRESS);
			$obj->getActiveSheet()->setCellValue('K' . $i, $entry->CUSTOMER_WEBSITE);
			$obj->getActiveSheet()->setCellValue('L' . $i, date('d M, Y',strtotime($entry->ENTERED_AT)));
			
			$obj->getActiveSheet()->getStyle("A" . $i . ":L" . $i)->applyFromArray($border_style);
			$i++;
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
		$obj->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
	}


	if($main_table == "entries" && $parts_table == "parts"){
		if($type == "ckd") $file_name = "CKD_" . time();
		if($type == "ckdbom") $file_name = "CKDBOM_" . time();
		if($type == "cbu") $file_name = "CBU_" . time();
		if($type == "manufacturing-parts") $file_name = "Manufacturing_" . time();
		if($type == "spare-parts") $file_name = "Spare_" . time();
		if($type == "additional-parts") $file_name = "Additional_" . time();

		$i = 1;
		foreach ($entries as $entry) {
			$j = $i;
			$obj->getActiveSheet()->setCellValue('A'.$i,'REQUISITION NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->REQUISITION_NUMBER);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'DATE')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->ENTRY_DATE);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'SITE')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->SITE);
			if($type == "ckd" || $type == "ckdbom" || $type == "cbu" || $type == "manufacturing-parts" || $type == "spare-parts"){
				$i++;
				$obj->getActiveSheet()->setCellValue('A'.$i,'INVOICE NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
				$obj->getActiveSheet()->setCellValue('B'.$i,$entry->INVOICE_NUMBER);
				$i++;
				$obj->getActiveSheet()->setCellValue('A'.$i,'LC NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
				$obj->getActiveSheet()->setCellValue('B'.$i,$entry->LC_NUMBER);
				$i++;
				$obj->getActiveSheet()->setCellValue('A'.$i,'LOT NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
				$obj->getActiveSheet()->setCellValue('B'.$i,$entry->LOT_NUMBER);
				if($type == "manufacturing-parts" || $type == "spare-parts"){
					$i++;
					$obj->getActiveSheet()->setCellValue('A'.$i,'PPD NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
					$obj->getActiveSheet()->setCellValue('B'.$i,$entry->PPD_NUMBER);
				}
			}
			else if($type == "additional-parts"){
				$i++;
				$obj->getActiveSheet()->setCellValue('A'.$i,'SUPPLIER CHALLAN NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
				$obj->getActiveSheet()->setCellValue('B'.$i,$entry->SUPPLIER_CHALLAN_NUMBER);
			}
			$s = fetch_functions\get_row('suppliers','SUPPLIER_CODE',$entry->SUPPLIER_CODE)[0];
			$obj->getActiveSheet()->setCellValue('D'.$j,'SUPPLIER CODE')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$entry->SUPPLIER_CODE);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'SUPPLIER NAME')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$s->SUPPLIER_NAME);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'SUPPLIER ADDRESS')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$s->SUPPLIER_ADDRESS);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'SUPPLIER CONTACT')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$s->SUPPLIER_PHONE_OFFICE);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'SUPPLIER EMAIL')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$s->SUPPLIER_EMAIL);

			$i = $i+2;
			$obj->getActiveSheet()->setCellValue('A'.$i,'PART NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,'PART NAME')->getStyle('B'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('C'.$i,'MODEL')->getStyle('C'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('D'.$i,'COLOR CODE')->getStyle('D'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$i,'COLOR NAME')->getStyle('E'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('F'.$i,'QUANTITY')->getStyle('F'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('G'.$i,'UNIT')->getStyle('G'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('H'.$i,'FRAME NUMBER')->getStyle('H'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('I'.$i,'ENGINE NUMBER')->getStyle('I'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('J'.$i,'REMARKS')->getStyle('J'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->getStyle("A".$i . ":J" . $i)->applyFromArray($border_style);
			
			$i = $i+1;
			$parts = fetch_functions\get_row('parts','ENTRY_REFERENCE_ID',$entry->KEY_ID);
			foreach ($parts as $p) {
				$obj->getActiveSheet()->setCellValue('A' . $i, $p->PART_NUMBER);
				$obj->getActiveSheet()->setCellValue('B' . $i, $p->PART_NAME);
				$obj->getActiveSheet()->setCellValue('C' . $i, $p->MODEL);
				$obj->getActiveSheet()->setCellValue('D' . $i, $p->COLOR_CODE);
				$obj->getActiveSheet()->setCellValue('E' . $i, $p->COLOR_NAME);
				$obj->getActiveSheet()->setCellValue('F' . $i, $p->QUANTITY);
				$obj->getActiveSheet()->setCellValue('G' . $i, $p->UNIT);
				$obj->getActiveSheet()->setCellValue('H' . $i, $p->FRAME_NUMBER);
				$obj->getActiveSheet()->setCellValue('I' . $i, $p->ENGINE_NUMBER);
				$obj->getActiveSheet()->setCellValue('J' . $i, $p->REMARKS);
				
				$obj->getActiveSheet()->getStyle("A" . $i . ":J" . $i)->applyFromArray($border_style);
				$i++;
			} // FOREACH END

			$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

			$i = $i+3;
		} // ENTRIES FOREACH END
	} // IF END




	if($main_table == "issues" && $parts_table == "issue_records"){
		if($type == "ckd-issue") $file_name = "CKD_" . time();
		if($type == "ckd-bom-issue") $file_name = "CKDBOM_" . time();
		if($type == "cbu-issue") $file_name = "CBU_" . time();
		if($type == "manufacturing-issue") $file_name = "Manufacturing_" . time();
		if($type == "spare-issue") $file_name = "Spare_" . time();
		if($type == "cripple-issue") $file_name = "CRIPPLE_" . time();
		$i = 1;
		foreach ($entries as $entry) {
			$j = $i;
			if($entry->REFERENCE_NUMBER == NULL || empty($entry)){
				$obj->getActiveSheet()->setCellValue('A'.$j,'REQUISITION NUMBER')->getStyle('A'.$j)->getFont()->setBold(true);
				$obj->getActiveSheet()->setCellValue('D'.$j,'REQUESTER NAME')->getStyle('D'.$j)->getFont()->setBold(true);
				$j++;
				$obj->getActiveSheet()->setCellValue('D'.$j,'REQUESTER DESIGNATION')->getStyle('D'.$j)->getFont()->setBold(true);
				$j++;
				$obj->getActiveSheet()->setCellValue('D'.$j,'REQUESTER DEPARTMENT')->getStyle('D'.$j)->getFont()->setBold(true);
			}
			else{
				$obj->getActiveSheet()->setCellValue('A'.$j,'REFERENCE NUMBER')->getStyle('A'.$j)->getFont()->setBold(true);
				$obj->getActiveSheet()->setCellValue('D'.$j,'SENDER NAME')->getStyle('D'.$j)->getFont()->setBold(true);
				$j++;
				$obj->getActiveSheet()->setCellValue('D'.$j,'SENDER DESIGNATION')->getStyle('D'.$j)->getFont()->setBold(true);
				$j++;
				$obj->getActiveSheet()->setCellValue('D'.$j,'SENDER DEPARTMENT')->getStyle('D'.$j)->getFont()->setBold(true);
			}
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->KEY_ID);	
			$obj->getActiveSheet()->setCellValue('E'.$i,$entry->NAME);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'DATE')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->ENTRY_DATE);
			$obj->getActiveSheet()->setCellValue('E'.$i,$entry->DESIGNATION);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'SITE')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->SITE);
			$obj->getActiveSheet()->setCellValue('E'.$i,$entry->DEPARTMENT);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'INVOICE NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->INVOICE_NUMBER);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'LC NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->LC_NUMBER);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'LOT NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->LOT_NUMBER);
			$i++;
			if($type == "manufacturing-issue" || $type == "spare-issue"){
				$obj->getActiveSheet()->setCellValue('A'.$i,'PPD NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
				$obj->getActiveSheet()->setCellValue('B'.$i,$entry->PPD_NUMBER);
			}

			$i = $i+2;
			$obj->getActiveSheet()->setCellValue('A'.$i,'PART NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,'PART NAME')->getStyle('B'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('C'.$i,'MODEL')->getStyle('C'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('D'.$i,'COLOR CODE')->getStyle('D'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$i,'COLOR NAME')->getStyle('E'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('F'.$i,'QUANTITY')->getStyle('F'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('G'.$i,'UNIT')->getStyle('G'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('H'.$i,'FRAME NUMBER')->getStyle('H'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('I'.$i,'ENGINE NUMBER')->getStyle('I'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('J'.$i,'CRIPPLE REASON')->getStyle('J'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('K'.$i,'REMARKS')->getStyle('K'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->getStyle('A'.$i . ':K' . $i)->applyFromArray($border_style);
			
			$i = $i+1;
			$parts = fetch_functions\get_row('issue_records','ENTRY_REFERENCE_ID',$entry->KEY_ID);
			foreach ($parts as $p) {
				$obj->getActiveSheet()->setCellValue('A' . $i, $p->PART_NUMBER);
				$obj->getActiveSheet()->setCellValue('B' . $i, $p->PART_NAME);
				$obj->getActiveSheet()->setCellValue('C' . $i, $p->MODEL);
				$obj->getActiveSheet()->setCellValue('D' . $i, $p->COLOR_CODE);
				$obj->getActiveSheet()->setCellValue('E' . $i, $p->COLOR_NAME);
				$obj->getActiveSheet()->setCellValue('F' . $i, $p->QUANTITY);
				$obj->getActiveSheet()->setCellValue('G' . $i, $p->UNIT);
				$obj->getActiveSheet()->setCellValue('H' . $i, $p->FRAME_NUMBER);
				$obj->getActiveSheet()->setCellValue('I' . $i, $p->ENGINE_NUMBER);
				$obj->getActiveSheet()->setCellValue('J' . $i, $p->CRIPPLE_REASON);
				$obj->getActiveSheet()->setCellValue('K' . $i, $p->REMARKS);
				
				$obj->getActiveSheet()->getStyle("A" . $i . ":K" . $i)->applyFromArray($border_style);
				$i++;
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

			$i = $i+4;
		} // ENTRIES FOREACH END
	} // IF END




	if($main_table == "delivery" && $parts_table == "delivery_parts"){
		if($type == "backup") $file_name = "BACKUP_DEL" . time();
		if($type == "additional") $file_name = "ADDITIONAL_DEL" . time();
		if($type == "normal") $file_name = "DELIVERY" . time();
		$i = 1;
		foreach ($entries as $entry) {
			$j = $i;
			$obj->getActiveSheet()->setCellValue('A'.$i,'DELIVERY CHALLAN NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->KEY_ID);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'ACTUAL DO DATE')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->DO_DATE);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'DELIVERY DATE')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->DELIVERY_DATE);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'SITE')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->SITE);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'REFERENCE DO NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->REFERENCE_DO_NUMBER);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'REFERENCE CO NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->REFERENCE_CO_NUMBER);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'TRANSPORT NAME')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->TRANSPORT_NAME);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'TRUCK NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->TRUCK_NUMBER);

			$c = fetch_functions\get_row('customers','CUSTOMER_ID',$entry->CUSTOMER_CODE)[0];
			$obj->getActiveSheet()->setCellValue('D'.$j,'DRIVER NAME')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$entry->DRIVER_NAME);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'DRIVER MOBILE NUMBER')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$entry->DRIVER_MOBILE_NUMBER);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'SALES CHANNEL')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$entry->SALES_CHANNEL);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'CUSTOMER CODE')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$c->CUSTOMER_ID);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'CUSTOMER NAME')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$c->CUSTOMER_NAME);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'CUSTOMER ADDRESS')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$c->CUSTOMER_ADDRESS);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'CUSTOMER CONTACT')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$c->CUSTOMER_PHONE_OFFICE);

			$i = $i+2;
			$obj->getActiveSheet()->setCellValue('A'.$i,'PART NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,'PART NAME')->getStyle('B'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('C'.$i,'MODEL')->getStyle('C'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('D'.$i,'COLOR CODE')->getStyle('D'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$i,'COLOR NAME')->getStyle('E'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('F'.$i,'QUANTITY')->getStyle('F'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('G'.$i,'UNIT')->getStyle('G'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('H'.$i,'FRAME NUMBER')->getStyle('H'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('I'.$i,'ENGINE NUMBER')->getStyle('I'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('J'.$i,'KEY RING NUMBER')->getStyle('J'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('K'.$i,'BATTERY NUMBER')->getStyle('K'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('L'.$i,'LC NUMBER')->getStyle('L'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('M'.$i,'INVOICE NUMBER')->getStyle('M'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('N'.$i,'REMARKS')->getStyle('N'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->getStyle('A'.$i.':N'.$i)->applyFromArray($border_style);
			
			$i = $i+1;
			$stmt = $con->prepare("SELECT * FROM delivery_parts WHERE ENTRY_REFERENCE_ID = :ENTRY_REFERENCE_ID AND TYPE = :TYPE");
			$stmt->execute(array('ENTRY_REFERENCE_ID' => $entry->KEY_ID, 'TYPE' => 'normal'));
			$parts = $stmt->fetchAll(\PDO::FETCH_OBJ);
			foreach ($parts as $p) {
				$obj->getActiveSheet()->setCellValue('A' . $i, $p->PART_NUMBER);
				$obj->getActiveSheet()->setCellValue('B' . $i, $p->PART_NAME);
				$obj->getActiveSheet()->setCellValue('C' . $i, $p->MODEL);
				$obj->getActiveSheet()->setCellValue('D' . $i, $p->COLOR_CODE);
				$obj->getActiveSheet()->setCellValue('E' . $i, $p->COLOR_NAME);
				$obj->getActiveSheet()->setCellValue('F' . $i, $p->QUANTITY);
				$obj->getActiveSheet()->setCellValue('G' . $i, $p->UNIT);
				$obj->getActiveSheet()->setCellValue('H' . $i, $p->FRAME_NUMBER);
				$obj->getActiveSheet()->setCellValue('I' . $i, $p->ENGINE_NUMBER);
				$obj->getActiveSheet()->setCellValue('J' . $i, $p->KEY_RING_NUMBER);
				$obj->getActiveSheet()->setCellValue('K' . $i, $p->BATTERY_NUMBER);
				$obj->getActiveSheet()->setCellValue('L' . $i, $p->LC_NUMBER);
				$obj->getActiveSheet()->setCellValue('M' . $i, $p->INVOICE_NUMBER);
				$obj->getActiveSheet()->setCellValue('N' . $i, $p->REMARKS);
		
				$obj->getActiveSheet()->getStyle("A" . $i . ":N" . $i)->applyFromArray($border_style);
				$i++;
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
	
			$i = $i+3;
		} // ENTRIES FOREACH END
	} // IF END




	if($main_table == "backup_delivery" && $parts_table == "delivery_parts"){
		$i = 1;
		foreach ($entries as $entry) {
			$j = $i;
			$obj->getActiveSheet()->setCellValue('A'.$i,'BACKUP DELIVERY NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->KEY_ID);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'DATE')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->DELIVERY_DATE);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'SITE')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->SITE);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'REQUISITION NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->REQUISITION_NUMBER);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'REFERENCE NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->REFERENCE_NUMBER);
			$i++;

			$obj->getActiveSheet()->setCellValue('D'.$j,'REQUESTER NAME')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$entry->REQUESTER_NAME);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'REQUESTER DESIGNATION')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$entry->REQUESTER_DESIGNATION);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'REQUESTER DEPARTMENT')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$entry->REQUESTER_DEPARTMENT);

			$i = $i+2;
			$obj->getActiveSheet()->setCellValue('A'.$i,'PART NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,'PART NAME')->getStyle('B'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('C'.$i,'MODEL')->getStyle('C'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('D'.$i,'COLOR CODE')->getStyle('D'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$i,'COLOR NAME')->getStyle('E'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('F'.$i,'QUANTITY')->getStyle('F'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('G'.$i,'UNIT')->getStyle('G'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('H'.$i,'REMARKS')->getStyle('H'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($border_style);
			
			$i = $i+1;
			$stmt = $con->prepare("SELECT * FROM delivery_parts WHERE ENTRY_REFERENCE_ID = :ENTRY_REFERENCE_ID AND TYPE = :TYPE");
			$stmt->execute(array('ENTRY_REFERENCE_ID' => $entry->KEY_ID, 'TYPE' => 'backup'));
			$parts = $stmt->fetchAll(\PDO::FETCH_OBJ);
			foreach ($parts as $p) {
				$obj->getActiveSheet()->setCellValue('A' . $i, $p->PART_NUMBER);
				$obj->getActiveSheet()->setCellValue('B' . $i, $p->PART_NAME);
				$obj->getActiveSheet()->setCellValue('C' . $i, $p->MODEL);
				$obj->getActiveSheet()->setCellValue('D' . $i, $p->COLOR_CODE);
				$obj->getActiveSheet()->setCellValue('E' . $i, $p->COLOR_NAME);
				$obj->getActiveSheet()->setCellValue('F' . $i, $p->QUANTITY);
				$obj->getActiveSheet()->setCellValue('G' . $i, $p->UNIT);
				$obj->getActiveSheet()->setCellValue('H' . $i, $p->REMARKS);
		
				$obj->getActiveSheet()->getStyle("A" . $i . ":H" . $i)->applyFromArray($border_style);
				$i++;
			} // FOREACH END

			$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

			$i = $i+3;
		} // ENTRIES FOREACH END
	} // IF END




	if($main_table == "additional_delivery" && $parts_table == "delivery_parts"){
		$i = 1;
		foreach ($entries as $entry) {
			$j = $i;
			$obj->getActiveSheet()->setCellValue('A'.$i,'ADDITIONAL DELIVERY NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->KEY_ID);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'ACTUAL DO DATE')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->DO_DATE);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'DELIVERY DATE')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->DELIVERY_DATE);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'SITE')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->SITE);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'REFERENCE DO NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->REFERENCE_DO_NUMBER);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'REFERENCE CO NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->REFERENCE_CO_NUMBER);

			$c = fetch_functions\get_row('customers','CUSTOMER_ID',$entry->CUSTOMER_CODE)[0];
			$obj->getActiveSheet()->setCellValue('D'.$j,'SALES CHANNEL')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$entry->SALES_CHANNEL);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'CUSTOMER CODE')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$c->CUSTOMER_ID);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'CUSTOMER NAME')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$c->CUSTOMER_NAME);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'CUSTOMER ADDRESS')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$c->CUSTOMER_ADDRESS);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'CUSTOMER CONTACT')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$c->CUSTOMER_PHONE_OFFICE);

			$i = $i+2;
			$obj->getActiveSheet()->setCellValue('A'.$i,'PART NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,'PART NAME')->getStyle('B'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('C'.$i,'MODEL')->getStyle('C'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('D'.$i,'COLOR CODE')->getStyle('D'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$i,'COLOR NAME')->getStyle('E'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('F'.$i,'QUANTITY')->getStyle('F'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('G'.$i,'UNIT')->getStyle('G'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('H'.$i,'REMARKS')->getStyle('H'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($border_style);
			
			$i = $i+1;
			$stmt = $con->prepare("SELECT * FROM delivery_parts WHERE ENTRY_REFERENCE_ID = :ENTRY_REFERENCE_ID AND TYPE = :TYPE");
			$stmt->execute(array('ENTRY_REFERENCE_ID' => $entry->KEY_ID, 'TYPE' => 'additional'));
			$parts = $stmt->fetchAll(\PDO::FETCH_OBJ);
			foreach ($parts as $p) {
				$obj->getActiveSheet()->setCellValue('A' . $i, $p->PART_NUMBER);
				$obj->getActiveSheet()->setCellValue('B' . $i, $p->PART_NAME);
				$obj->getActiveSheet()->setCellValue('C' . $i, $p->MODEL);
				$obj->getActiveSheet()->setCellValue('D' . $i, $p->COLOR_CODE);
				$obj->getActiveSheet()->setCellValue('E' . $i, $p->COLOR_NAME);
				$obj->getActiveSheet()->setCellValue('F' . $i, $p->QUANTITY);
				$obj->getActiveSheet()->setCellValue('G' . $i, $p->UNIT);
				$obj->getActiveSheet()->setCellValue('H' . $i, $p->REMARKS);
		
				$obj->getActiveSheet()->getStyle("A" . $i . ":H" . $i)->applyFromArray($border_style);
				$i++;
			} // FOREACH END

			$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

			$i = $i+3;
		} // ENTRIES FOREACH END
	} // IF END




	if($main_table == "return_order" && $parts_table == "returned_parts"){
		$i = 1;
		foreach ($entries as $entry) {
			$j = $i;
			$obj->getActiveSheet()->setCellValue('A'.$i,'RETURN ORDER ID')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->KEY_ID);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'DATE')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->RETURN_DATE);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'DELIVERY CHALLAN NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->DELIVERY_CHALLAN_NUMBER);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'SALES CHANNEL')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->SALES_CHANNEL);

			$obj->getActiveSheet()->setCellValue('D'.$j,'CUSTOMER CODE')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$entry->CUSTOMER_CODE);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'CUSTOMER NAME')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$entry->CUSTOMER_NAME);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'CUSTOMER ADDRESS')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$entry->CUSTOMER_ADDRESS);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'CUSTOMER CONTACT')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$entry->CUSTOMER_PHONE_OFFICE);

			$i = $i+2;
			$obj->getActiveSheet()->setCellValue('A'.$i,'PART NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,'PART NAME')->getStyle('B'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('C'.$i,'MODEL')->getStyle('C'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('D'.$i,'COLOR CODE')->getStyle('D'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$i,'COLOR NAME')->getStyle('E'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('F'.$i,'QUANTITY')->getStyle('F'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('G'.$i,'UNIT')->getStyle('G'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('H'.$i,'RETURN REASON')->getStyle('H'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('I'.$i,'RETURN REASON')->getStyle('I'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->getStyle('A'.$i.':I'.$i)->applyFromArray($border_style);
			
			$i = $i+2;
			$parts = fetch_functions\get_row('returned_parts','ENTRY_REFERENCE_ID',$entry->KEY_ID);
			foreach ($parts as $p) {
				$obj->getActiveSheet()->setCellValue('A' . $i, $p->PART_NUMBER);
				$obj->getActiveSheet()->setCellValue('B' . $i, $p->PART_NAME);
				$obj->getActiveSheet()->setCellValue('C' . $i, $p->MODEL);
				$obj->getActiveSheet()->setCellValue('D' . $i, $p->COLOR_CODE);
				$obj->getActiveSheet()->setCellValue('E' . $i, $p->COLOR_NAME);
				$obj->getActiveSheet()->setCellValue('F' . $i, $p->QUANTITY);
				$obj->getActiveSheet()->setCellValue('G' . $i, $p->UNIT);
				$obj->getActiveSheet()->setCellValue('H' . $i, $p->RETURN_REASON);
				$obj->getActiveSheet()->setCellValue('I' . $i, $p->REMARKS);
		
				$obj->getActiveSheet()->getStyle("A" . $i . ":I" . $i)->applyFromArray($border_style);
				$i++;
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

			$i = $i+3;
		} // ENTRIES FOREACH END
	} // IF END




	if($main_table == "purchase_requisitions" && $parts_table == "purchase_requisitions_parts"){
		$i = 1;
		foreach ($entries as $entry) {
			$j = $i;
			$s = fetch_functions\get_row('suppliers','SUPPLIER_CODE',$entry->SUPPLIER_CODE)[0];
			$obj->getActiveSheet()->setCellValue('A'.$i,'REQUISITION NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->KEY_ID);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'DATE')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->REQUISITION_DATE);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'SITE')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->SITE);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'REQUESTER NAME')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->REQUESTER_NAME);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'REQUESTER DESIGNATION')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->REQUESTER_DESIGNATION);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'REQUESTER DEPARTMENT')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->REQUESTER_DEPARTMENT);

			$obj->getActiveSheet()->setCellValue('D'.$j,'APPROVED BY')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$entry->APPROVED_BY);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'SUPPLIER CODE')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$s->SUPPLIER_CODE);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'SUPPLIER NAME')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$s->SUPPLIER_NAME);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'SUPPLIER ADDRESS')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$s->SUPPLIER_ADDRESS);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'SUPPLIER CONTACT')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$s->SUPPLIER_PHONE_OFFICE);

			$i = $i+2;
			$obj->getActiveSheet()->setCellValue('A'.$i,'PART NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,'PART NAME')->getStyle('B'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('C'.$i,'MODEL')->getStyle('C'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('D'.$i,'COLOR CODE')->getStyle('D'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$i,'COLOR NAME')->getStyle('E'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('F'.$i,'QUANTITY')->getStyle('F'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('G'.$i,'UNIT')->getStyle('G'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('H'.$i,'REMARKS')->getStyle('H'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($border_style);
			
			$i = $i+2;
			$parts = fetch_functions\get_row('purchase_requisitions_parts','ENTRY_REFERENCE_ID',$entry->KEY_ID);
			foreach ($parts as $p) {
				$obj->getActiveSheet()->setCellValue('A' . $i, $p->PART_NUMBER);
				$obj->getActiveSheet()->setCellValue('B' . $i, $p->PART_NAME);
				$obj->getActiveSheet()->setCellValue('C' . $i, $p->MODEL);
				$obj->getActiveSheet()->setCellValue('D' . $i, $p->COLOR_CODE);
				$obj->getActiveSheet()->setCellValue('E' . $i, $p->COLOR_NAME);
				$obj->getActiveSheet()->setCellValue('F' . $i, $p->QUANTITY);
				$obj->getActiveSheet()->setCellValue('G' . $i, $p->UNIT);
				$obj->getActiveSheet()->setCellValue('H' . $i, $p->REMARKS);
		
				$obj->getActiveSheet()->getStyle("A" . $i . ":H" . $i)->applyFromArray($border_style);
				$i++;
			} // FOREACH END

			$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			$obj->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

			$i = $i+3;
		} // ENTRIES FOREACH END
	} // IF END




	if($main_table == "claims" && $parts_table == "claims_parts"){
		if($type == "ckd-claim") $file_name = "CKD_CLAIM" . time();
		if($type == "ckd-bom-claim") $file_name = "CKDBOM_CLAIM" . time();
		if($type == "cbu-claim") $file_name = "CBU_CLAIM" . time();
		if($type == "manufacturing-claim") $file_name = "MANUFACTURING_CLAIM" . time();
		if($type == "spare-claim") $file_name = "SPARE_CLAIM" . time();
		if($type == "additional-claim") $file_name = "ADDITIONAL_CLAIM" . time();
		if($type == "claims-claim") $file_name = "CLAIMS_CLAIM" . time();

		$i = 1;
		foreach ($entries as $entry) {
			$j = $i;
			$s = fetch_functions\get_row('suppliers','SUPPLIER_CODE',$entry->SUPPLIER_CODE)[0];
			$obj->getActiveSheet()->setCellValue('A'.$i,'INVOICE NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->INVOICE_NUMBER);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'CLAIM ISSUE DATE')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->CLAIM_ISSUE_DATE);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'SITE')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->SITE);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'CREATED BY')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->CREATED_BY);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'APPROVED BY')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->APPROVED_BY);
			$i++;
			$obj->getActiveSheet()->setCellValue('A'.$i,'SHIPPING_MODE')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,$entry->SHIPPING_MODE);

			$obj->getActiveSheet()->setCellValue('D'.$i,'MODEL')->getStyle('D'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$i,$entry->MODEL);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'APD NUMBER')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$entry->APD_NUMBER);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'PPD NUMBER')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$entry->PPD_NUMBER);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'CLAIM REFERENCE NUMBER')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$entry->CLAIM_REFERENCE_NUMBER);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'LC NUMBER')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j,$entry->LC_NUMBER);
			$j++;
			$obj->getActiveSheet()->setCellValue('D'.$j,'MONTH - YEAR')->getStyle('D'.$j)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$j, $entry->MONTH . '-' . $entry->YEAR);

			$i = $i+3;
			$obj->getActiveSheet()->setCellValue('A'.$i,'PART NUMBER')->getStyle('A'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('B'.$i,'PART NAME')->getStyle('B'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('C'.$i,'COLOR CODE')->getStyle('C'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('D'.$i,'COLOR NAME')->getStyle('D'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('E'.$i,'QUANTITY')->getStyle('E'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('F'.$i,'UNIT')->getStyle('F'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('G'.$i,'BOX NUMBER')->getStyle('G'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('H'.$i,'CASE NUMBER')->getStyle('H'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('I'.$i,'REFERENCE NUMBER')->getStyle('I'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('J'.$i,'LOT NUMBER')->getStyle('J'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('K'.$i,'CLAIM TYPE')->getStyle('K'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('L'.$i,'CLAIM CODE')->getStyle('L'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('M'.$i,'ACTION CODE')->getStyle('M'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('N'.$i,'PROCESS CODE')->getStyle('N'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('O'.$i,'DETAILS OF DEFECT')->getStyle('O'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('P'.$i,'DEFECT FINDING WAY')->getStyle('P'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->setCellValue('Q'.$i,'REMARKS')->getStyle('Q'.$i)->getFont()->setBold(true);
			$obj->getActiveSheet()->getStyle('A'.$i.':Q'.$i)->applyFromArray($border_style);
			
			$i = $i+1;
			$parts = fetch_functions\get_row('claims_parts','ENTRY_REFERENCE_ID',$entry->KEY_ID);
			foreach ($parts as $p) {
				$obj->getActiveSheet()->setCellValue('A' . $i, $p->PART_NUMBER);
				$obj->getActiveSheet()->setCellValue('B' . $i, $p->PART_NAME);
				$obj->getActiveSheet()->setCellValue('C' . $i, $p->COLOR_CODE);
				$obj->getActiveSheet()->setCellValue('D' . $i, $p->COLOR_NAME);
				$obj->getActiveSheet()->setCellValue('E' . $i, $p->QUANTITY);
				$obj->getActiveSheet()->setCellValue('F' . $i, $p->UNIT);
				$obj->getActiveSheet()->setCellValue('G' . $i, $p->BOX_NUMBER);
				$obj->getActiveSheet()->setCellValue('H' . $i, $p->CASE_NUMBER);
				$obj->getActiveSheet()->setCellValue('I' . $i, $p->REFERENCE_NUMBER);
				$obj->getActiveSheet()->setCellValue('J' . $i, $p->LOT_NUMBER);
				$obj->getActiveSheet()->setCellValue('K' . $i, $p->CLAIM_TYPE);
				$obj->getActiveSheet()->setCellValue('L' . $i, $p->CLAIM_CODE);
				$obj->getActiveSheet()->setCellValue('M' . $i, $p->ACTION_CODE);
				$obj->getActiveSheet()->setCellValue('N' . $i, $p->PROCESS_CODE);
				$obj->getActiveSheet()->setCellValue('O' . $i, $p->DETAILS_OF_DEFECT);
				$obj->getActiveSheet()->setCellValue('P' . $i, $p->DEFECT_FINDING_WAY);
				$obj->getActiveSheet()->setCellValue('Q' . $i, $p->REMARKS);
		
				$obj->getActiveSheet()->getStyle("A" . $i . ":Q" . $i)->applyFromArray($border_style);
				$i++;
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

			$i = $i+3;
		} // ENTRIES FOREACH END
	} // IF END	




	// STOCK STATUS
	if($main_table == "stock" && $parts_table == "stock_parts"){
		if($type == "ckd") { $issue_type = "ckd-issue"; $file_name = "CKD_STOCK_" .time() ; }
		if($type == "ckdbom") { $issue_type = "ckd-bom-issue"; $file_name = "CKDBOM_STOCK" . time(); }
		if($type == "cbu") { $issue_type = "cbu-issue"; $file_name = "CBU_STOCK_" . time(); }
		if($type == "manufacturing-parts") { $issue_type = "manufacturing-issue"; $file_name = "MANUFACTURING_STOCK_" . time(); }
		if($type == "spare-parts") { $file_name = "BACKUP_STOCK_" . time(); }
		if($type == "additional-parts") { $file_name = "ADDITIONAL_STOCK" . time(); }
		if($type == "mf-frame-sa-stock") { $file_name = "MF_SA_STOCK_" . time(); }
		if($type == "ready-bike") { $file_name = "READY_BIKE_STOCK" . time(); }

		if($selection_value == "hour"){
			if($type == "ckd" || $type == "ckdbom" || $type == "cbu" || $type == "manufacturing-parts" || $type == "spare-parts" || $type == "additional-parts"){
				$stmt = $con->query("SELECT MAX(DATE(ENTERED_AT)) AS MAX_ENTRY_DATE FROM entries WHERE TYPE = '$type'");
				$max_entry_date = $stmt->fetch(\PDO::FETCH_OBJ);
				if($type != "additional-parts" && $type != "spare-parts"){
					$stmt = $con->query("SELECT MAX(DATE(ENTERED_AT)) AS MAX_PARTS_DATE FROM issues WHERE TYPE = '$issue_type'");	
					$max_parts_date = $stmt->fetch(\PDO::FETCH_OBJ);
					if($max_entry_date->MAX_ENTRY_DATE >= $max_parts_date->MAX_PARTS_DATE) { $table = "entries"; $sub_table = "parts"; }
					else { $table = "issues"; $sub_table = "issue_records"; }
				}
				
				if($type == "additional-parts"){
					$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM entries WHERE DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT AND TYPE = :TYPE UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM additional_delivery WHERE DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT");
					$stmt->execute(array('ENTERED_AT' => htmlspecialchars($_POST['hour']), 'TYPE' => 'additional-parts'));
					$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
				}
				else if($type == "spare-parts"){
					$stmt = $con->query("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM entries WHERE DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT AND TYPE = :TYPE UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM backup_delivery WHERE DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT");
					$stmt->execute(array('ENTERED_AT' => htmlspecialchars($_POST['hour']), 'TYPE' => 'spare-parts'));
					$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
				}
				else{
					$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM $table WHERE DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT AND TYPE = :TYPE ORDER BY ENTRY_DATE");
					if($table == 'entries') $this_type = $type;
					else if($table == 'issues') $this_type = $issue_type;
					$stmt->execute(array('ENTERED_AT' => htmlspecialchars($_POST['hour']), 'TYPE' => $this_type));
					$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);	
				}
			}

			if($type == "mf-frame-sa-stock"){
				$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM issues WHERE RECEIVED = :RECEIVED AND DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT AND TYPE = :TYPE AND ORDER BY ENTRY_DATE");
				$stmt->execute(array('ENTERED_AT' => htmlspecialchars($_POST['hour']), 'RECEIVED' => 1, 'TYPE' => 'manufacturing-issue'));
				$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
			}

			if($type == "ready-bike"){
				$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM issues WHERE RECEIVED = 1 AND TYPE IN ('ckd-issue' , 'ckd-bom-issue' , 'cbu-issue') AND DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM return_order WHERE DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM delivery WHERE KEY_ID IN (SELECT ENTRY_REFERENCE_ID FROM delivery_parts WHERE TYPE = 'normal') AND DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT ORDER BY ENTRY_DATE");
				$stmt->execute(array('ENTERED_AT' => htmlspecialchars($_POST['hour'])));
				$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
			}
		}
		
		if($selection_value == "date-range"){
			$date_parts = explode('-', htmlspecialchars($_POST['date-range-start']));
			$starting_date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
			$date_parts = explode('-', htmlspecialchars($_POST['date-range-end']));
			$end_date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
			if($type == "ckd" || $type == "ckdbom" || $type == "cbu" || $type == "manufacturing-parts" || $type == "spare-parts" || $type == "additional-parts"){
				$stmt = $con->query("SELECT MAX(DATE(ENTERED_AT)) AS MAX_ENTRY_DATE FROM entries WHERE TYPE = '$type'");
				$max_entry_date = $stmt->fetch(\PDO::FETCH_OBJ);
				if($type != "additional-parts" && $type != "spare-parts"){
					$stmt = $con->query("SELECT MAX(DATE(ENTERED_AT)) AS MAX_PARTS_DATE FROM issues WHERE TYPE = '$issue_type'");	
					$max_parts_date = $stmt->fetch(\PDO::FETCH_OBJ);
					if($max_entry_date->MAX_ENTRY_DATE >= $max_parts_date->MAX_PARTS_DATE) { $table = "entries"; $sub_table = "parts"; }
					else { $table = "issues"; $sub_table = "issue_records"; }
				}
				
				if($type == "additional-parts"){
					$stmt = $con->query("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM entries WHERE DATE(ENTERED_AT) BETWEEN '$starting_date' AND '$end_date' AND TYPE = 'additional-parts' UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM additional_delivery WHERE DATE(ENTERED_AT) BETWEEN '$starting_date' AND '$end_date'");
					$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
				}
				else if($type == "spare-parts"){
					$stmt = $con->query("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM entries WHERE DATE(ENTERED_AT) BETWEEN '$starting_date' AND '$end_date' AND TYPE = 'spare-parts' UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM backup_delivery WHERE DATE(ENTERED_AT) BETWEEN '$starting_date' AND '$end_date'");
					$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
				}
				else{
					$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM $table WHERE DATE(ENTERED_AT) BETWEEN :STARING_DATE AND :ENDING_DATE AND TYPE = :TYPE ORDER BY ENTRY_DATE");
					if($table == 'entries') $this_type = $type;
					else if($table == 'issues') $this_type = $issue_type;
					$stmt->execute(array('STARING_DATE' => $starting_date, 'ENDING_DATE' => $end_date, 'TYPE'=>$this_type));
					$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);	
				}
			}

			if($type == "ready-bike"){
				$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM issues WHERE RECEIVED = 1 AND TYPE IN ('ckd-issue' , 'ckd-bom-issue' , 'cbu-issue') AND DATE(ENTERED_AT) BETWEEN :STARING_DATE AND :ENDING_DATE UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM return_order WHERE DATE(ENTERED_AT) BETWEEN :STARING_DATE AND :ENDING_DATE UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM delivery WHERE KEY_ID IN (SELECT ENTRY_REFERENCE_ID FROM delivery_parts WHERE TYPE = 'normal') AND DATE(ENTERED_AT) BETWEEN :STARING_DATE AND :ENDING_DATE ORDER BY ENTRY_DATE");
				$stmt->execute(array('STARING_DATE' => $starting_date, 'ENDING_DATE' => $end_date));
				$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
			}

			if($type == "mf-frame-sa-stock"){
				$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM issues WHERE RECEIVED = :RECEIVED AND DATE(ENTERED_AT) BETWEEN :STARING_DATE AND :ENDING_DATE AND TYPE = :TYPE ORDER BY ENTRY_DATE");
				$stmt->execute(array('RECEIVED' => 1, 'STARING_DATE' => $starting_date, 'ENDING_DATE' => $end_date, 'TYPE' => 'manufacturing-issue'));
				$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);	
			}
		}
		

		if($selection_value == "specify-date"){
			$date_parts = explode('-', htmlspecialchars($_POST['specify-date']));
			$date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
			if($type == "ckd" || $type == "ckdbom" || $type == "cbu" || $type == "manufacturing-parts" || $type == "spare-parts" || $type == "additional-parts"){
				$stmt = $con->query("SELECT MAX(DATE(ENTERED_AT)) AS MAX_ENTRY_DATE FROM entries WHERE TYPE = '$type'");
				$max_entry_date = $stmt->fetch(\PDO::FETCH_OBJ);
				if($type != "additional-parts" || $type != "spare-parts"){
					$stmt = $con->query("SELECT MAX(DATE(ENTERED_AT)) AS MAX_PARTS_DATE FROM issues WHERE TYPE = '$type'");
					$max_parts_date = $stmt->fetch(\PDO::FETCH_OBJ);
					if($max_entry_date->MAX_ENTRY_DATE >= $max_parts_date->MAX_PARTS_DATE) { $table = "entries"; $sub_table = "parts"; }
					else { $table = "issues"; $sub_table = "issue_records"; }
				}
				
				if($type == "additional-parts") { 
					$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM additional_delivery WHERE DATE(ENTERED_AT) = :SPECIFIED_DATE");	
					$stmt->execute(array('SPECIFIED_DATE' => $date));
				}
				else if($type == "spare-parts") { 
					$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM backup_delivery WHERE DATE(ENTERED_AT) = :SPECIFIED_DATE");	
					$stmt->execute(array('SPECIFIED_DATE' => $date));
				}
				else{
					$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM $table WHERE TYPE = :TYPE AND DATE(ENTERED_AT) = :SPECIFIED_DATE");	
					if($table == 'entries') $this_type = $type;
					else if($table == 'issues') $this_type = $issue_type;
					$stmt->execute(array('TYPE' => $this_type, 'SPECIFIED_DATE' => $date));	
				}
				$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
				if(sizeof($dates) == 0){
					if($type == "additional-parts" || $type == "spare-parts" ) {
						$stmt = $con->prepare("SELECT MAX(DATE(ENTERED_AT)) AS ENTRY_DATE FROM $table");	
						$stmt->execute();
					}
					else{
						$stmt = $con->prepare("SELECT MAX(DATE(ENTERED_AT)) AS ENTRY_DATE FROM $table WHERE TYPE = :TYPE");	
						$stmt->execute(array('TYPE' => $this_type));
					}
					$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
					$dates[0]->ENTRY_DATE = $date;
				}
			}

			if($type == "ready-bike"){
				$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM issues WHERE RECEIVED = 1 AND TYPE IN ('ckd-issue' , 'ckd-bom-issue' , 'cbu-issue') AND  DATE(ENTERED_AT) = :SPECIFIED_DATE UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM return_order WHERE  DATE(ENTERED_AT) = :SPECIFIED_DATE UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM delivery WHERE KEY_ID IN (SELECT ENTRY_REFERENCE_ID FROM delivery_parts WHERE TYPE = 'normal') AND  DATE(ENTERED_AT) = :SPECIFIED_DATE ORDER BY ENTRY_DATE");
				$stmt->execute(array('SPECIFIED_DATE' => $date));
				$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);		
				if(sizeof($dates) == 0){
					$stmt = $con->prepare("SELECT MAX(DATE(ENTERED_AT)) AS DELIVERY FROM delivery");	
					$stmt->execute();
					$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
					$dates[0]->ENTRY_DATE = $date;
				}
			}

			if($type == "mf-frame-sa-stock"){
				$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM issues WHERE RECEIVED = :RECEIVED AND TYPE = :TYPE AND DATE(ENTERED_AT) = :SPECIFIED_DATE");	
				$stmt->execute(array('RECEIVED' => 1, 'TYPE' => 'manufacturing-issue', 'SPECIFIED_DATE' => $date));
				$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
				$dates_size = sizeof($dates);
				if($dates_size == 0){
					$stmt = $con->prepare("SELECT MAX(DATE(ENTERED_AT)) AS ENTRY_DATE FROM issues WHERE TYPE = :TYPE AND RECEIVED = :RECEIVED");	
					$stmt->execute(array('TYPE' => 'manufacturing-issue', 'RECEIVED' => 1));
					$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
				}
				if($dates_size <= 1) $given_mf_date = date('d M, Y',strtotime($date));
			}
		}

		$count = 1;
		$limit = sizeof($dates);		
		$row = 2;
		foreach($dates as $date){
			$set_header = false;
			$header_at = 0;
			$show_date = date("d M, Y", strtotime($date->ENTRY_DATE));
			$obj->getActiveSheet()->setCellValue('A' . $row, $show_date)->getStyle('A' . $row)->getFont()->setBold(true)->setSize(16);
			$row = $row + 2;

			if($type != "additional-parts" && $type != "spare-parts" && $type != "mf-frame-sa-stock" && $type != "ready-bike"){
				$stmt = $con->query("SELECT DISTINCT PART_NUMBER FROM $sub_table WHERE ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM entries WHERE TYPE = '$type' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')" );	
				$unique_parts = $stmt->fetchAll(\PDO::FETCH_OBJ);	
			}

			else if($type == "additional-parts"){
				$stmt = $con->query("SELECT DISTINCT PART_NUMBER FROM parts WHERE ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM entries WHERE TYPE = 'additional-parts' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')" );
				$unique_values = $stmt->fetchAll(\PDO::FETCH_OBJ);	
			}

			else if($type == "spare-parts"){
				$stmt = $con->query("SELECT DISTINCT PART_NUMBER FROM parts WHERE ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM entries WHERE TYPE = 'spare-parts' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')" );
				$unique_values = $stmt->fetchAll(\PDO::FETCH_OBJ);	
			}

			else if($type == "ready-bike"){
				$stmt = $con->query("SELECT DISTINCT PART_NUMBER FROM issue_records WHERE ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM issues WHERE TYPE IN ('ckd-issue' , 'ckd-bom-issue' , 'cbu-issue') AND RECEIVED = 1 AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')" );
				$unique_parts = $stmt->fetchAll(\PDO::FETCH_OBJ);	
			}


			if($type == "ckd" || $type == "ckdbom" || $type == "cbu"){
				foreach($unique_parts as $part){
					$stmt = $con->query("SELECT COUNT(*) AS NUM_ENTRIES, SUM(QUANTITY) AS PART_QUANTITY, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME FROM parts WHERE PART_NUMBER = '$part->PART_NUMBER' AND ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM entries WHERE TYPE = '$type' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE') GROUP BY PART_NUMBER,COLOR_CODE");
							
					$entry_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
					$entry_quantity = sizeof($entry_num);
					foreach ($entry_num as $e) {
						$stmt = $con->query("SELECT COUNT(*) AS ISSUE_ENTRY_QUANTITY, SUM(QUANTITY) AS ISSUED_QUANTITY
										FROM issue_records
										WHERE PART_NUMBER = '$e->PART_NUMBER'
										AND COLOR_CODE = '$e->COLOR_CODE'
										AND ENTRY_REFERENCE_ID IN 
										(SELECT KEY_ID FROM issues WHERE TYPE = '$issue_type' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE' AND RECEIVED != 1 )
										GROUP BY MODEL,COLOR_CODE
										");
						$issued_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
						$quantity = $e->PART_QUANTITY - $issued_num[0]->ISSUED_QUANTITY;

						if($quantity > 0){
							$set_header = true;
							$header_at++;
							if(empty($e->MODEL)) $this_model = "-------";
							else $this_model = $e->MODEL;
											
							$obj->getActiveSheet()->setCellValue('A' . $row, $e->PART_NUMBER);
							$obj->getActiveSheet()->setCellValue('B' . $row, $e->PART_NAME);
							$obj->getActiveSheet()->setCellValue('C' . $row, $this_model);
							$obj->getActiveSheet()->setCellValue('D' . $row, $e->COLOR_CODE);
							$obj->getActiveSheet()->setCellValue('E' . $row, $e->COLOR_NAME);
							$obj->getActiveSheet()->setCellValue('F' . $row, $quantity);
							$obj->getActiveSheet()->getStyle("A" . $row . ":F" . $row)->applyFromArray($border_style);
							$row++;
						} // QUANTITY != 0 END
					} // ENTRY NUM END
				} // FOREACH UNIQUE PARTS END
				if($set_header){
					$header_cell = $row-$header_at-1;
					$obj->getActiveSheet()->setCellValue('A' . $header_cell, "PART NUMBER");
					$obj->getActiveSheet()->setCellValue('B' . $header_cell, "PART NAME");
					$obj->getActiveSheet()->setCellValue('C' . $header_cell, "MODEL");
					$obj->getActiveSheet()->setCellValue('D' . $header_cell, "COLOR CODE");
					$obj->getActiveSheet()->setCellValue('E' . $header_cell, "COLOR NAME");
					$obj->getActiveSheet()->setCellValue('F' . $header_cell, "QUANTITY");
					$obj->getActiveSheet()->getStyle("A" . $header_cell . ":F" . $header_cell)->applyFromArray($border_style);
					$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
				}
			} // IF END




			if($type == "manufacturing-parts"){
				foreach($unique_parts as $part){
					$stmt = $con->query("SELECT COUNT(*) AS NUM_ENTRIES, SUM(QUANTITY) AS PART_QUANTITY, PART_NUMBER, PART_NAME, MODEL
									FROM parts
									WHERE PART_NUMBER = '$part->PART_NUMBER'
									AND ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM entries WHERE TYPE = '$type' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')
									GROUP BY PART_NUMBER,MODEL");
							
					$entry_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
					$entry_quantity = sizeof($entry_num);
					foreach ($entry_num as $e) {
						$stmt = $con->query("SELECT COUNT(*) AS ISSUE_ENTRY_QUANTITY, SUM(QUANTITY) AS ISSUED_QUANTITY
										FROM issue_records
										WHERE MODEL = '$e->MODEL'
										AND PART_NUMBER = '$e->PART_NUMBER'
										AND ENTRY_REFERENCE_ID IN 
										(SELECT KEY_ID FROM issues WHERE TYPE = '$issue_type' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE' AND RECEIVED != 1 )
										GROUP BY MODEL,PART_NUMBER
										");

						$received_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
						$quantity = $e->PART_QUANTITY - $received_num[0]->ISSUED_QUANTITY;

						if($quantity > 0){
							$set_header = true;
							$header_at++;
							if(empty($e->MODEL)) $this_model = "-------";
							else $this_model = $e->MODEL;
											
							$obj->getActiveSheet()->setCellValue('A' . $row, $this_model);
							$obj->getActiveSheet()->setCellValue('B' . $row, $e->PART_NUMBER);
							$obj->getActiveSheet()->setCellValue('C' . $row, $e->PART_NAME);
							$obj->getActiveSheet()->setCellValue('D' . $row, $quantity);
							$obj->getActiveSheet()->getStyle("A" . $row . ":D" . $row)->applyFromArray($border_style);
							$row++;
						} // QUANTITY != 0 END
					} // ENTRY NUM END 
				} // FOREACH UNIQUE PARTS END
				if($set_header){
					$header_cell = $row-$header_at-1;
					$obj->getActiveSheet()->setCellValue('A' . $header_cell, "MODEL");
					$obj->getActiveSheet()->setCellValue('B' . $header_cell, "PART NUMBER");
					$obj->getActiveSheet()->setCellValue('C' . $header_cell, "PART NAME");
					$obj->getActiveSheet()->setCellValue('D' . $header_cell, "QUANTITY");
					$obj->getActiveSheet()->getStyle("A" . $header_cell . ":D" . $header_cell)->applyFromArray($border_style);
					$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				}
			}  // IF END



			if($type == "additional-parts"){
				foreach($unique_values as $value){
					$stmt = $con->query("SELECT COUNT(*) AS NUM_ENTRIES, SUM(QUANTITY) AS PART_QUANTITY, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE
									FROM parts
									WHERE PART_NUMBER = '$value->PART_NUMBER'
									AND ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM entries WHERE TYPE = '$type' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')
									GROUP BY PART_NUMBER");
							
					$entry_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
					$entry_quantity = sizeof($entry_num);
					foreach ($entry_num as $e) {
						$stmt = $con->query("SELECT COUNT(*) AS NUM_ENTRIES, SUM(QUANTITY) AS DELIVERED_QUANTITY
										FROM delivery_parts
										WHERE PART_NUMBER = '$e->PART_NUMBER'
										AND COLOR_CODE = '$e->COLOR_CODE'
										AND TYPE = 'additional'
										AND ENTRY_REFERENCE_ID IN 
										(SELECT KEY_ID FROM additional_delivery WHERE DATE(ENTERED_AT) <= '$date->ENTRY_DATE')
										GROUP BY PART_NUMBER
										");

						$delivered_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
						$quantity = $e->PART_QUANTITY - $delivered_num[0]->DELIVERED_QUANTITY;

						if($quantity > 0){
							$set_header = true;
							$header_at++;
							if(empty($e->MODEL)) $this_model = "-------";
							else $this_model = $e->MODEL;
											
							$obj->getActiveSheet()->setCellValue('A' . $row, $e->PART_NUMBER);
							$obj->getActiveSheet()->setCellValue('B' . $row, $e->PART_NAME);
							$obj->getActiveSheet()->setCellValue('C' . $row, $this_model);
							$obj->getActiveSheet()->setCellValue('D' . $row, $quantity);
							$obj->getActiveSheet()->getStyle("A" . $row . ":D" . $row)->applyFromArray($border_style);
							$row++;
						} // QUANTITY != 0 END
					} // ENTRY NUM END 
				} // FOREACH UNIQUE MODELS END
				
				if($set_header){
					$header_cell = $row-$header_at-1;
					$obj->getActiveSheet()->setCellValue('A' . $header_cell, "PART NUMBER");
					$obj->getActiveSheet()->setCellValue('B' . $header_cell, "PART NAME");
					$obj->getActiveSheet()->setCellValue('C' . $header_cell, "MODEL");
					$obj->getActiveSheet()->setCellValue('D' . $header_cell, "QUANTITY");
					$obj->getActiveSheet()->getStyle("A" . $header_cell . ":D" . $header_cell)->applyFromArray($border_style);
					$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				}
			} // IF END




			if($type == "spare-parts"){
				foreach($unique_values as $value){
					$tmp = "";
					$stmt = $con->query("SELECT COUNT(*) AS NUM_ENTRIES, SUM(QUANTITY) AS PART_QUANTITY, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE
									FROM parts
									WHERE PART_NUMBER = '$value->PART_NUMBER'
									AND ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM entries WHERE TYPE = '$type' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')
									GROUP BY PART_NUMBER");
							
					$entry_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
					$entry_quantity = sizeof($entry_num);
					foreach ($entry_num as $e) {
						$stmt = $con->query("SELECT COUNT(*) AS NUM_ENTRIES, SUM(QUANTITY) AS DELIVERED_QUANTITY
										FROM delivery_parts
										WHERE PART_NUMBER = '$e->PART_NUMBER'
										AND COLOR_CODE = '$e->COLOR_CODE'
										AND TYPE = 'backup'
										AND ENTRY_REFERENCE_ID IN 
										(SELECT KEY_ID FROM backup_delivery WHERE DATE(ENTERED_AT) <= '$date->ENTRY_DATE')
										GROUP BY PART_NUMBER
										");

						$delivered_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
						$quantity = $e->PART_QUANTITY - $delivered_num[0]->DELIVERED_QUANTITY;

						if($quantity > 0){
							$set_header = true;
							$header_at++;
							if(empty($e->MODEL)) $this_model = "-------";
							else $this_model = $e->MODEL;
											
							$obj->getActiveSheet()->setCellValue('A' . $row, $e->PART_NUMBER);
							$obj->getActiveSheet()->setCellValue('B' . $row, $e->PART_NAME);
							$obj->getActiveSheet()->setCellValue('C' . $row, $this_model);
							$obj->getActiveSheet()->setCellValue('D' . $row, $quantity);
							$obj->getActiveSheet()->getStyle("A" . $row . ":D" . $row)->applyFromArray($border_style);
							$row++;
						} // QUANTITY != 0 END
					} // ENTRY NUM END 
				} // FOREACH UNIQUE PARTS END
				if($set_header){
					$header_cell = $row-$header_at-1;
					$obj->getActiveSheet()->setCellValue('A' . $header_cell, "PART NUMBER");
					$obj->getActiveSheet()->setCellValue('B' . $header_cell, "PART NAME");
					$obj->getActiveSheet()->setCellValue('C' . $header_cell, "MODEL");
					$obj->getActiveSheet()->setCellValue('D' . $header_cell, "QUANTITY");
					$obj->getActiveSheet()->getStyle("A" . $header_cell . ":D" . $header_cell)->applyFromArray($border_style);
					$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				}
			} // IF END



			if($type == "ready-bike"){
				foreach($unique_parts as $part){
					$stmt = $con->query("SELECT COUNT(*) AS NUM_ENTRIES, SUM(QUANTITY) AS PART_QUANTITY, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME
									FROM issue_records
									WHERE PART_NUMBER = '$part->PART_NUMBER'
									AND ENTRY_REFERENCE_ID IN 
									(SELECT KEY_ID FROM issues WHERE TYPE IN ('ckd-issue','ckd-bom-issue','cbu-issue') AND RECEIVED= 1 AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')
									GROUP BY PART_NUMBER,COLOR_CODE");
							
					$entry_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
					$entry_quantity = sizeof($entry_num);
					foreach ($entry_num as $e) {
						$stmt = $con->query("SELECT COUNT(*) AS DELIVERY_ENTRY_QUANTITY, SUM(QUANTITY) AS DELIVERY_QUANTITY
										FROM delivery_parts
										WHERE PART_NUMBER = '$e->PART_NUMBER'
										AND COLOR_CODE = '$e->COLOR_CODE'
										AND MODEL = '$e->MODEL'
										AND TYPE = 'normal'
										AND ENTRY_REFERENCE_ID IN 
										(SELECT KEY_ID FROM delivery WHERE DATE(ENTERED_AT) <= '$date->ENTRY_DATE')
										GROUP BY PART_NUMBER,COLOR_CODE
										");
						$delivered_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
						$quantity = $e->PART_QUANTITY - $delivered_num[0]->DELIVERY_QUANTITY;

						$stmt = $con->query("SELECT COUNT(*) AS RETURN_ENTRY_QUANTITY, SUM(QUANTITY) AS RETURN_QUANTITY
										FROM returned_parts
										WHERE PART_NUMBER = '$e->PART_NUMBER'
										AND COLOR_CODE = '$e->COLOR_CODE'
										AND MODEL = '$e->MODEL'
										AND ENTRY_REFERENCE_ID IN 
										(SELECT KEY_ID FROM return_order WHERE DATE(ENTERED_AT) <= '$date->ENTRY_DATE')
										GROUP BY PART_NUMBER,COLOR_CODE
										");
						$returned_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
						if(sizeof($returned_num)>0){
							$quantity = $quantity + $returned_num[0]->RETURN_QUANTITY;
						}

						if($quantity > 0){
							$set_header = true;
							$header_at++;
							if(empty($e->MODEL)) $this_model = "-------";
							else $this_model = $e->MODEL;
											
							$obj->getActiveSheet()->setCellValue('A' . $row, $e->PART_NUMBER);
							$obj->getActiveSheet()->setCellValue('B' . $row, $e->PART_NAME);
							$obj->getActiveSheet()->setCellValue('C' . $row, $this_model);
							$obj->getActiveSheet()->setCellValue('D' . $row, $e->COLOR_CODE);
							$obj->getActiveSheet()->setCellValue('E' . $row, $e->COLOR_NAME);
							$obj->getActiveSheet()->setCellValue('F' . $row, $quantity);
							$obj->getActiveSheet()->getStyle("A" . $row . ":F" . $row)->applyFromArray($border_style);
							$row++;
						} // QUANTITY != 0 END
					} // ENTRY NUM END 
				} // FOREACH UNIQUE MODELS END

				if($set_header){
					$header_cell = $row-$header_at-1;
					$obj->getActiveSheet()->setCellValue('A' . $header_cell, "PART NUMBER");
					$obj->getActiveSheet()->setCellValue('B' . $header_cell, "PART NAME");
					$obj->getActiveSheet()->setCellValue('C' . $header_cell, "MODEL");
					$obj->getActiveSheet()->setCellValue('D' . $header_cell, "COLOR CODE");
					$obj->getActiveSheet()->setCellValue('E' . $header_cell, "COLOR NAME");
					$obj->getActiveSheet()->setCellValue('F' . $header_cell, "QUANTITY");
					$obj->getActiveSheet()->getStyle("A" . $header_cell . ":F" . $header_cell)->applyFromArray($border_style);
					$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
					$obj->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
				}
			} // READY BIKE IF END



			if($type == "mf-frame-sa-stock"){
				$stmt = $con->query("SELECT COUNT(*) AS NUM_ENTRIES, SUM(QUANTITY) AS PART_QUANTITY, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME
								FROM issue_records
								WHERE ENTRY_REFERENCE_ID 
								IN (SELECT KEY_ID FROM issues WHERE RECEIVED = 1 AND TYPE = 'manufacturing-issue' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')
								GROUP BY PART_NUMBER,MODEL");
				$values = $stmt->fetchAll(\PDO::FETCH_OBJ);
				$limit = sizeof($values);
				if($limit>0){
					$obj->getActiveSheet()->setCellValue('A' . $row, "PART NUMBER");
					$obj->getActiveSheet()->setCellValue('B' . $row, "PART NAME");
					$obj->getActiveSheet()->setCellValue('C' . $row, "MODEL");
					$obj->getActiveSheet()->setCellValue('D' . $row, "COLOR CODE");
					$obj->getActiveSheet()->setCellValue('E' . $row, "COLOR NAME");
					$obj->getActiveSheet()->setCellValue('F' . $row, "QUANTITY");
					$obj->getActiveSheet()->getStyle("A" . $row . ":F" . $row)->applyFromArray($border_style);
					$row++;
					foreach ($values as $value) {
						if(empty($value->MODEL)) $this_model = "-------";
						else $this_model = $value->MODEL;			
						$obj->getActiveSheet()->setCellValue('A' . $row, $value->PART_NUMBER);
						$obj->getActiveSheet()->setCellValue('B' . $row, $value->PART_NAME);
						$obj->getActiveSheet()->setCellValue('C' . $row, $this_model);
						$obj->getActiveSheet()->setCellValue('D' . $row, $value->COLOR_CODE);
						$obj->getActiveSheet()->setCellValue('E' . $row, $value->COLOR_NAME);
						$obj->getActiveSheet()->setCellValue('F' . $row, $value->PART_QUANTITY);
						$obj->getActiveSheet()->getStyle("A" . $row . ":F" . $row)->applyFromArray($border_style);
						$row++;
					}
				}
				$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
				$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
				$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
				$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				$obj->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				$obj->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			} // FRAME SWING ARM IF END
			$row = $row + 2;

		} // FOREACH DATES END
	}  // IF END
	






	// PENDING STATUS
	if($main_table == "pending" && $parts_table == "pending_parts"){
		if($type == "ckd") { $issue_type = "ckd-issue"; $file_name = "CKD_IN_PROGRESS_" .time() ; }
		if($type == "ckdbom") { $issue_type = "ckd-bom-issue"; $file_name = "CKDBOM_IN_PROGRESS_" . time(); }
		if($type == "cbu") { $issue_type = "cbu-issue"; $file_name = "CBU_IN_PROGRESS_" . time(); }

		if($selection_value == "hour"){
			$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM issues WHERE DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT AND TYPE = :TYPE ORDER BY HOUR(ENTERED_AT)");
			$stmt->execute(array('ENTERED_AT' => htmlspecialchars($_POST['hour']), 'TYPE' => $issue_type));
			$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);			
		}
		
		if($selection_value == "date-range"){
			$date_parts = explode('-', htmlspecialchars($_POST['date-range-start']));
			$starting_date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
			$date_parts = explode('-', htmlspecialchars($_POST['date-range-end']));
			$end_date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
		
			$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM issues WHERE DATE(ENTERED_AT) BETWEEN :STARING_DATE AND :ENDING_DATE ORDER BY ENTRY_DATE AND TYPE = :TYPE");
			$stmt->execute(array('STARING_DATE' => $starting_date, 'ENDING_DATE' => $end_date, 'TYPE'=>$issue_type));
			$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);	
		}
		

		if($selection_value == "specify-date"){
			$date_parts = explode('-', htmlspecialchars($_POST['specify-date']));
			$date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
		
			$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM issues WHERE TYPE = :TYPE AND DATE(ENTERED_AT) = :SPECIFIED_DATE");	
			$stmt->execute(array('TYPE' => $issue_type, 'SPECIFIED_DATE' => $date));	
			$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
			if(sizeof($dates) == 0){
				$stmt = $con->prepare("SELECT MAX(DATE(ENTERED_AT)) AS ENTRY_DATE FROM issues WHERE TYPE = :TYPE");	
				$stmt->execute(array('TYPE' => $issue_type));
				$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
				$dates[0]->ENTRY_DATE = $date;
			}
		}

		$count = 1;
		$limit = sizeof($dates);
		$row = 4;
		foreach($dates as $date){
			$set_header = false;
			$header_at = 0;
			$stmt = $con->query("SELECT DISTINCT PART_NUMBER FROM issue_records WHERE ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM issues WHERE TYPE = '$issue_type' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')" );	
			$unique_parts = $stmt->fetchAll(\PDO::FETCH_OBJ);	
			foreach($unique_parts as $part){
				$tmp = "";
				$stmt = $con->query("SELECT COUNT(*) AS NUM_ENTRIES, SUM(QUANTITY) AS ISSUED_QUANTITY, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME FROM issue_records WHERE PART_NUMBER = '$part->PART_NUMBER' AND ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM issues WHERE TYPE = '$issue_type' AND RECEIVED != 1 AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE') GROUP BY PART_NUMBER,COLOR_CODE");
						
				$entry_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
				$entry_quantity = sizeof($entry_num);
				foreach ($entry_num as $e) {
					$stmt = $con->query("SELECT COUNT(*) AS RECEIVED_ENTRY_QUANTITY, SUM(QUANTITY) AS RECEIVED_QUANTITY
									FROM issue_records
									WHERE PART_NUMBER = '$e->PART_NUMBER'
									AND COLOR_CODE = '$e->COLOR_CODE'
										AND ENTRY_REFERENCE_ID IN 
									(SELECT KEY_ID FROM issues WHERE TYPE = '$issue_type' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE' AND RECEIVED = 1 )
									GROUP BY MODEL,COLOR_CODE
									");
					$received_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
					$quantity = $e->ISSUED_QUANTITY - $received_num[0]->RECEIVED_QUANTITY;

					if($quantity > 0){
						$set_header = true;
						$header_at++;
						if(empty($e->MODEL)) $this_model = "-------";
						else $this_model = $e->MODEL;
										
						$obj->getActiveSheet()->setCellValue('A' . $row, $e->PART_NUMBER);
						$obj->getActiveSheet()->setCellValue('B' . $row, $e->PART_NAME);
						$obj->getActiveSheet()->setCellValue('C' . $row, $this_model);
						$obj->getActiveSheet()->setCellValue('D' . $row, $e->COLOR_CODE);
						$obj->getActiveSheet()->setCellValue('E' . $row, $e->COLOR_NAME);
						$obj->getActiveSheet()->setCellValue('F' . $row, $quantity);
						$obj->getActiveSheet()->getStyle("A" . $row . ":F" . $row)->applyFromArray($border_style);
						$row++;
					} // QUANTITY != 0 END
				} // ENTRY NUM END 
			} // FOREACH UNIQUE MODELS END
			
			if($set_header){
				$header_cell = $row-$header_at-1;
				$date_cell = $header_cell - 2;
				$show_date = date("d M, Y", strtotime($date->ENTRY_DATE));
				$obj->getActiveSheet()->setCellValue('A' . $date_cell, $show_date)->getStyle('A' . $date_cell)->getFont()->setBold(true)->setSize(16);
				$row = $row + 2;
				$obj->getActiveSheet()->setCellValue('A' . $header_cell, "PART NUMBER");
				$obj->getActiveSheet()->setCellValue('B' . $header_cell, "PART NAME");
				$obj->getActiveSheet()->setCellValue('C' . $header_cell, "MODEL");
				$obj->getActiveSheet()->setCellValue('D' . $header_cell, "COLOR CODE");
				$obj->getActiveSheet()->setCellValue('E' . $header_cell, "COLOR NAME");
				$obj->getActiveSheet()->setCellValue('F' . $header_cell, "QUANTITY");
				$obj->getActiveSheet()->getStyle("A" . $header_cell . ":F" . $header_cell)->applyFromArray($border_style);
				$obj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
				$obj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
				$obj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
				$obj->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				$obj->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				$obj->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			}
			$row = $row + 3;
		} // FOREACH DATES END

	} // IF PENDING PARTS END




	$style = array(
	    'alignment' => array(
	        'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
	    )
	);
	$obj->getActiveSheet()->setTitle($file_name);
    $obj->getDefaultStyle()->applyFromArray($style);

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$file_name.xlsx");
	header('Cache-Control: max-age=0');
	
	$writer = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
	$writer->save('php://output');
} // MAIN IF END









if($report_type == "pdf"){	
	if($main_table == "customers"){
		$content = "";
		$content .= title_info("CUSTOMERS REPORT");
		$content .= 
		"
		<div style='position:relative; width:100%; height:auto; margin-bottom:20px;'>
		<table style='width:100%;'>
			<tr>
				<th style='width: 50px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>ID</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>NAME</th>
				<th style='width: 85px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PHONE</th>
				<th style='width: 85px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>MOBILE</th>
			 	<th style='width: 100px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>EMAIL</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>ADDRESS</th>
				<th style='width: 80px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>TYPE</th>
				<th style='width: 80px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>ENTRY DATE</th>
			</tr>
		";
		
		foreach ($entries as $entry) {
			$entry_date = date('d M, Y',strtotime($entry->ENTERED_AT));
			$content .= 
			"<tr>
				<td style='width: 50px; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$entry->CUSTOMER_ID</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$entry->CUSTOMER_NAME</td>
				<td style='width: 85px; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$entry->CUSTOMER_PHONE_OFFICE</td>
				<td style='width: 85px; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$entry->CUSTOMER_PHONE_MOBILE</td>
				<td style='width: 100px; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$entry->CUSTOMER_EMAIL</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$entry->CUSTOMER_ADDRESS</td>
				<td style='width: 80px; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$entry->CUSTOMER_TYPE</td>
				<td style='width: 80px; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$entry_date</td>
			</tr>
			";
		} // ENTRIES FOREACH END
		$content .= "</table></div>";
		$content .= footer_info("customers",1,1);
	} // IF END




	if($main_table == "suppliers"){
		$content = "";
		$content .= title_info("SUPPLIERS REPORT");
		$content .= 
		"
		<div style='position:relative; width:100%; height:auto; margin-bottom:20px;'>
		<table style='width:100%;'>
			<tr>
				<th style='width: 50px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>CODE</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>NAME</th>
				<th style='width: 85px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PHONE</th>
				<th style='width: 85px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>MOBILE</th>
			 	<th style='width: 100px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>EMAIL</th>
				<th style='width: 80px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COUNTRY</th>
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>ADDRESS</th>
				<th style='width:80px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>ENTRY DATE</th>
			</tr>
		";
		
		foreach ($entries as $entry) {
			$entry_date = date('d M, Y',strtotime($entry->ENTERED_AT));
			$content .= 
			"<tr>
				<td style='width: 50px; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$entry->SUPPLIER_CODE</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$entry->SUPPLIER_NAME</td>
				<td style='width: 85px; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$entry->SUPPLIER_PHONE_OFFICE</td>
				<td style='width: 85px; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$entry->SUPPLIER_PHONE_MOBILE</td>
				<td style='width: 100px; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$entry->SUPPLIER_EMAIL</td>
				<td style='width: 80px; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$entry->COUNTRY</td>
				<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$entry->SUPPLIER_ADDRESS</td>
				<td style='width:80px; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$entry_date</td>
			</tr>
			";
		} // ENTRIES FOREACH END
		$content .= "</table></div>";
		$content .= footer_info("suppliers",1,1);
	} // IF END




	if($main_table == "entries" && $parts_table == "parts"){
		if($type == "ckd") $title = "CKD ENTER";
		if($type == "ckdbom") $title = "CKD BOM Enter";
		if($type == "cbu") $title = "CBU Enter";
		if($type == "manufacturing-parts") $title = "Manufacturing Parts Enter";
		if($type == "spare-parts") $title = "Backup Spare Parts Enter";
		if($type == "additional-parts") $title = "Additional Parts Enter";
		$content = "";
		$i = sizeof($entries);
		$j = 1;
		foreach ($entries as $entry) {
			$content .= title_info($title);
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

			<table style = 'width:100%; margin-bottom: 50px;'>
				<tr>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NUMBER</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NAME</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>MODEL</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR CODE</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR NAME</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>QUANTITY</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>UNIT</th>
					"
					;
					if($type == "ckd" || $type == "ckdbom" || $type == "cbu"){
						$content .= 
						"
						<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>FRAME NO</th>
						<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>ENGINE NO</th>
						"
						;
					}
					$content .= "
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>REMARKS</th>
				</tr>";

			$parts = fetch_functions\get_row($parts_table, 'ENTRY_REFERENCE_ID', $entry->KEY_ID);
			foreach ($parts as $p) {
				$content.=
				"
				<tr>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->PART_NUMBER</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->PART_NAME</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->MODEL</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->COLOR_CODE</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->COLOR_NAME</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->QUANTITY</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->UNIT</td>
				"
				;
				if($type == "ckd" || $type == "ckdbom" || $type == "cbu"){
					$content .= 
					"
						<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->FRAME_NUMBER</td>
						<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->ENGINE_NUMBER</td>
					"
					;
				}
				$content .= 
				"
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->REMARKS</td>
				</tr>
				";
			} // PARTS FOREACH END
			$content .= "</table>";
			$content .= footer_info("parts",$i,$j);
			$j++;
		} // ENTRIES FOREACH END
	} // IF END



	
	if($main_table == "issues" && $parts_table == "issue_records"){
		if($type == "ckd-issue") $title = "CKD Issue";
		if($type == "ckd-bom-issue") $title = "CKD BOM Issue";
		if($type == "cripple-issue") $title = "Cripple Issue";
		if($type == "cbu-issue") $title = "CBU Issue";
		if($type == "manufacturing-issue") $title = "Manufacturing Parts Issue";
		if($type == "spare-issue") $title = "Backup Spare Parts Issue";
		$content = "";
		$i = sizeof($entries);
		$j = 1;
		foreach ($entries as $entry) {
			if($entry->REFERENCE_NUMBER == NULL || empty($entry)){ $ref_req = "REQUISITION"; $req_sen = "REQUESTER"; }
			else { $ref_req = "REFERENCE"; $req_sen = "SENDER"; }
			$content .= title_info($title);
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

			<table style = 'width:100%; margin-bottom: 50px;'>
				<tr>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NUMBER</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NAME</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>MODEL</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR CODE</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR NAME</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>QUANTITY</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>UNIT</th>
					"
					;
					if($type != "manufacturing-issue" && $type != "spare-issue"){
						$content .= 
						"
						<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>FRAME NO</th>
						"
						;
					}

			$content .= 
			"
				<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>ENGINE NO</th>
			";
			if($type == "cripple-issue"){
				$content .= "<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>CRIPPLE REASON</th>";
			}
			$content .= 
			"	<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>REMARKS</th>
			</tr>
			";

			$parts = fetch_functions\get_row($parts_table, 'ENTRY_REFERENCE_ID', $entry->KEY_ID);
			foreach ($parts as $p) {
				$content.=
				"
				<tr>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->PART_NUMBER</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->PART_NAME</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->MODEL</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->COLOR_CODE</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->COLOR_NAME</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->QUANTITY</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->UNIT</td>
				"
				;
				if($type != "manufacturing-issue" && $type != "spare-issue"){
					$content .= 
					"<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->FRAME_NUMBER</td>";
				}
				$content .=
				"<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->ENGINE_NUMBER</td>";
				if($type == "cripple-issue"){
					$content .= 
					"<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->CRIPPLE_REASON</td>";
				}
				$content .= 
				"	<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->REMARKS</td>
				</tr>
				";
			} // PARTS FOREACH END
			$content .= "</table>";
			$content .= footer_info("issue",$i,$j);
			$j++;
		} // ENTRIES FOREACH END
	} // IF END



	
	if($main_table == "delivery" && $parts_table == "delivery_parts"){
		$content = "";
		$i = sizeof($entries);
		$j = 1;
		foreach ($entries as $entry) {
			$c = fetch_functions\get_row('customers','CUSTOMER_ID',$entry->CUSTOMER_CODE)[0];
			$content .= title_info("Delivery Challan");
			$content .= 
			"
			<div style='position:relative; width:100%; height:220px; margin-bottom:20px;'>
				<table style='position:absolute; left: 0; width:500px;'>
					<tr>
						<th style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; padding:3px;'>DELIVERY NUMBER</th>
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

			<table style = 'width:100%; margin-bottom: 50px;'>
				<tr>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NO</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NAME</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>MODEL</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR CODE</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR NAME</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>QTY</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>UNIT</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>FRAME NO</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>ENGINE NO</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>KEY RING</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>BATTERY</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>REMARKS</th>
				</tr>
				"
				;
				$stmt_parts = $con->prepare("SELECT * FROM delivery_parts WHERE ENTRY_REFERENCE_ID = :ENTRY_REFERENCE_ID AND TYPE = :TYPE");
				$stmt_parts->execute(array('ENTRY_REFERENCE_ID' => $entry->KEY_ID, 'TYPE' => 'normal'));
				$parts = $stmt_parts->fetchAll(\PDO::FETCH_OBJ);
				foreach ($parts as $p) {
				$content.=
				"
				<tr>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->PART_NUMBER</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->PART_NAME</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->MODEL</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->COLOR_CODE</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->COLOR_NAME</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->QUANTITY</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->UNIT</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->FRAME_NUMBER</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->ENGINE_NUMBER</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->KEY_RING_NUMBER</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->BATTERY_NUMBER</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->REMARKS</td>
				</tr>
				"
				;
			} // PARTS FOREACH END
			$content .= "</table>";
			$content .= footer_info("delivery",$i,$j);
			$j++;
		} // ENTRIES FOREACH END
	} // IF END



	
	if($main_table == "backup_delivery" && $parts_table == "delivery_parts"){
		$i = sizeof($entries);
		$j = 1;
		$content = "";
		foreach ($entries as $entry) {
			$c = fetch_functions\get_row('customers','CUSTOMER_ID',$entry->CUSTOMER_CODE)[0];
			$content .= title_info("Backup Parts Delivery");
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

			<table style = 'width:100%; margin-bottom: 50px;'>
				<tr>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NO</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NAME</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>MODEL</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR CODE</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR NAME</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>QTY</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>UNIT</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>REMARKS</th>
				</tr>
				"
				;
				$stmt_parts = $con->prepare("SELECT * FROM delivery_parts WHERE ENTRY_REFERENCE_ID = :ENTRY_REFERENCE_ID AND TYPE = :TYPE");
				$stmt_parts->execute(array('ENTRY_REFERENCE_ID' => $entry->KEY_ID, 'TYPE' => 'backup'));
				$parts = $stmt_parts->fetchAll(\PDO::FETCH_OBJ);

				foreach ($parts as $p) {
				$content.=
				"
				<tr>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->PART_NUMBER</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->PART_NAME</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->MODEL</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->COLOR_CODE</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->COLOR_NAME</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->QUANTITY</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->UNIT</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->REMARKS</td>
				</tr>
				"
				;
			}	// PARTS FOREACH END
			$content .= "</table>";
			$content .= footer_info("backup_delivery",$i,$j);
			$j++;
		} // ENTRIES FOREACH END
	} // IF END



	
	if($main_table == "additional_delivery" && $parts_table == "delivery_parts"){
		$i = sizeof($entries);
		$j = 1;
		$content = "";
		foreach ($entries as $entry) {
			$c = fetch_functions\get_row('customers','CUSTOMER_ID',$entry->CUSTOMER_CODE)[0];
			$content .= title_info("Additional Delivery");
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

			<table style = 'width:100%; margin-bottom: 50px;'>
				<tr>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NO</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NAME</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>MODEL</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR CODE</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR NAME</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>QTY</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>UNIT</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>REMARKS</th>
				</tr>
				"
				;
				$stmt_parts = $con->prepare("SELECT * FROM delivery_parts WHERE ENTRY_REFERENCE_ID = :ENTRY_REFERENCE_ID AND TYPE = :TYPE");
				$stmt_parts->execute(array('ENTRY_REFERENCE_ID' => $entry->KEY_ID, 'TYPE' => 'additional'));
				$parts = $stmt_parts->fetchAll(\PDO::FETCH_OBJ);
				foreach ($parts as $p) {
				$content.=
				"
				<tr>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->PART_NUMBER</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->PART_NAME</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->MODEL</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->COLOR_CODE</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->COLOR_NAME</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->QUANTITY</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->UNIT</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->REMARKS</td>
				</tr>
				"
				;
			} // PART FOREACH END
			$content .= "</table>";
			$content .= footer_info("additional_delivery",$i,$j);
			$j++;
		} // ENTRIES FOREACH END	
	} // IF END



	
	if($main_table == "return_order" && $parts_table == "returned_parts"){
		$i = sizeof($entries);
		$j = 1;
		$content = "";
		foreach ($entries as $entry) {
			$c = fetch_functions\get_row('customers','CUSTOMER_ID',$entry->CUSTOMER_CODE)[0];
			$content .= title_info("Return in Order");
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

			<table style = 'width:100%; margin-bottom: 50px;'>
				<tr>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NUMBER</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NAME</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>MODEL</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR CODE</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR NAME</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>QTY</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>UNIT</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>RETURN REASON</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>REMARKS</th>
				</tr>
				"
				;

				$parts = fetch_functions\get_row('returned_parts','ENTRY_REFERENCE_ID',$entry->KEY_ID);
				foreach ($parts as $p) {
				$content.=
				"
				<tr>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->PART_NUMBER</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->PART_NAME</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->MODEL</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->COLOR_CODE</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->COLOR_NAME</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->QUANTITY</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->UNIT</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->RETURN_REASON</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->REMARKS</td>
				</tr>
				"
				;
			} // PARTS FOREACH END
			$content .= "</table>";
			$content .= footer_info("return_order",$i,$j);
			$j++;
		} // ENTRIES FOREACH END
	} // IF END



	
	if($main_table == "purchase_requisitions" && $parts_table == "purchase_requisitions_parts"){
		$content = "";
		$i = sizeof($entries);
		$j = 1;
		foreach ($entries as $entry) {
			$s = fetch_functions\get_row('suppliers','SUPPLIER_CODE',$entry->SUPPLIER_CODE)[0];
			$content .= title_info("Purchase Requisitions");
			$date = date("d M, Y", strtotime($entry->ENTERED_AT));
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

			<table style = 'width:100%; margin-bottom: 50px;'>
				<tr>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NUMBER</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NAME</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>MODEL</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR CODE</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR NAME</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>QTY</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>UNIT</th>
					<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>REMARKS</th>
				</tr>
				"
				;

				$parts = fetch_functions\get_row('purchase_requisitions_parts','ENTRY_REFERENCE_ID',$entry->KEY_ID);
				foreach ($parts as $p) {
				$content.=
				"
				<tr>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->PART_NUMBER</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->PART_NAME</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->MODEL</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->COLOR_CODE</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->COLOR_NAME</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->QUANTITY</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->UNIT</td>
					<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->REMARKS</td>
				</tr>
				"
				;
			} // PARTS FOREACH END
			$content .= "</table>";
			$content .= footer_info("purchase_requisitions",$i,$j);
			$j++;
		} // ENTRIES FOREACH END
	} // IF END



	
	if($main_table == "claims" && $parts_table == "claims_parts"){
		if($type == "ckd-claim") $title = "CKD Claims";
		if($type == "ckd-bom-claim") $title = "CKD BOM Claims";
		if($type == "cbu-claim") $title = "CBU Claims";
		if($type == "manufacturing-claim") $title = "Manufacturing Parts Claims";
		if($type == "spare-claim") $title = "Spare Parts Claims";
		if($type == "additional-claim") $title = "Additional Parts Claims";
		if($type == "claims-claim") $title = "Claim Parts Claims";
		if($type == "ckd-claim" || $type == "ckd-bom-claim"){
			$top_section = "<div style='position:relative; width:100%; height:170px; margin-bottom:20px;'>";
		}
		else{
			$top_section = "<div style='position:relative; width:100%; height:150px; margin-bottom:20px;'>";
		}
		$i = sizeof($entries);
		$j = 1;
		foreach ($entries as $entry) {
			$content .= title_info($title);
			$date = date("d M, Y", strtotime($entry->ENTERED_AT));
			$content .= 
			"
			$top_section
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

			<table style = 'width:100%; margin-bottom: 50px;'>
				<tr>
					<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NO</th>
					<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART</th>
					<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR CODE</th>
					<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>QTY</th>
					<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>BOX</th>
					";
					if($type != "cbu-claim" && $type !="additional-claim"){
					$content .= "
					<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>CASE</th>";
					}
					if($type == "ckd-claim" || $type == "ckd-bom-claim"){
					$content .= "
					<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>REF NO</th>";
					}
					$content .= "
					<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>LOT</th>
					<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; width:35px;'>CLAIM NOr</th>
					<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; width:35px;'>ACTION NO</th>";

					if($type == "ckd-claim" || $type == "ckd-bom-claim"){
						$content .= "
						<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; width:35px;'>PROCESS NO</th>
						";
					}
					$content .= "
					<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; width:80px;'>DETAILS</th>
					<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; width:80px;'>DEFECT FOUND</th>
					<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; width:80px;'>Picture</th>
					<th style='background: #ddd; color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; width:80px;'>REMARKS</th>
				</tr>
				"
				;

				$parts = fetch_functions\get_row($parts_table, 'ENTRY_REFERENCE_ID', $entry->KEY_ID);
				foreach ($parts as $p) {
				if(empty($p->PICTURE)) $img = "";
				else $img = "<img src='$p->PICTURE' style='width:80px; max-height:50px;'>";
				$content.=
				"
				<tr>
					<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->PART_NUMBER</td>
					<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->PART_NAME</td>
					<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->COLOR_CODE</td>
					<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->QUANTITY  $p->UNIT</td>
					<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->BOX_NUMBER</td>";

					if($type != "cbu-claim" && $type != "additional-claim"){
						$content .= "
						<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->CASE_NUMBER</td>
						";
					}

					if($type == "ckd-claim" || $type == "ckd-bom-claim"){
						$content .= "
						<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->REFERENCE_NUMBER</td>";
					}
					$content .= "
					<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px;'>$p->LOT_NUMBER</td>
					<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; width:35px;'>$p->CLAIM_CODE</td>
					<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; width:35px;'>$p->ACTION_CODE</td>";

					if($type == "ckd-claim" || $type == "ckd-bom-claim"){
						$content .= "<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; width:35px;'>$p->PROCESS_CODE</td>";
					}

					$content .= "
					<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; width:85px;'>$p->DETAILS_OF_DEFECT</td>
					<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; width:85px;'>$p->DEFECT_FINDING_WAY</td>
					<td style='border: 1px solid #ababab; padding:3px; width:80px;'>$img</td>
					<td style='color: #333; font-size: 10.5px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; width:85px;'>$p->REMARKS</td>
				</tr>
				"
				;
			} // PARTS FOREACH END
			$content .= "</table>";
			$content .= footer_info("claims",$i,$j);
			$j++;
		} // ENTRIES FOREACH END
	} // IF END









	// STOCK STATUS
	if($main_table == "stock" && $parts_table == "stock_parts"){
		if($type == "ckd") { $issue_type = "ckd-issue"; $title = "CKD PARTS"; }
		if($type == "ckdbom") { $issue_type = "ckd-bom-issue"; $title = "CKD By BOM PARTS"; }
		if($type == "cbu") { $issue_type = "cbu-issue"; $title = "CBU PARTS"; }
		if($type == "manufacturing-parts") { $issue_type = "manufacturing-issue"; $title = "MANUFACTURING PARTS"; }
		if($type == "spare-parts") { $title = "BACKUP SPARE PARTS"; }
		if($type == "additional-parts") { $title = "ADDITIONAL PARTS"; }
		if($type == "mf-frame-sa-stock") { $title = "MF FRAME & SA STOCK"; }
		if($type == "ready-bike") { $title = "READY BIKE STOCK"; }

		if($selection_value == "hour"){
			if($type == "ckd" || $type == "ckdbom" || $type == "cbu" || $type == "manufacturing-parts" || $type == "spare-parts" || $type == "additional-parts"){
				$stmt = $con->query("SELECT MAX(DATE(ENTERED_AT)) AS MAX_ENTRY_DATE FROM entries WHERE TYPE = '$type'");
				$max_entry_date = $stmt->fetch(\PDO::FETCH_OBJ);
				if($type != "additional-parts" && $type != "spare-parts"){
					$stmt = $con->query("SELECT MAX(DATE(ENTERED_AT)) AS MAX_PARTS_DATE FROM issues WHERE TYPE = '$issue_type'");	
					$max_parts_date = $stmt->fetch(\PDO::FETCH_OBJ);
					if($max_entry_date->MAX_ENTRY_DATE >= $max_parts_date->MAX_PARTS_DATE) { $table = "entries"; $sub_table = "parts"; }
					else { $table = "issues"; $sub_table = "issue_records"; }
				}
				
				if($type == "additional-parts"){
					$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM entries WHERE DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT AND TYPE = :TYPE UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM additional_delivery WHERE DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT");
					$stmt->execute(array('ENTERED_AT' => htmlspecialchars($_POST['hour']), 'TYPE' => 'additional-parts'));
					$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
				}
				else if($type == "spare-parts"){
					$stmt = $con->query("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM entries WHERE DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT AND TYPE = :TYPE UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM backup_delivery WHERE DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT");
					$stmt->execute(array('ENTERED_AT' => htmlspecialchars($_POST['hour']), 'TYPE' => 'spare-parts'));
					$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
				}
				else{
					$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM $table WHERE DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT AND TYPE = :TYPE ORDER BY ENTRY_DATE");
					if($table == 'entries') $this_type = $type;
					else if($table == 'issues') $this_type = $issue_type;
					$stmt->execute(array('ENTERED_AT' => htmlspecialchars($_POST['hour']), 'TYPE' => $this_type));
					$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);	
				}
			}

			if($type == "mf-frame-sa-stock"){
				$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM issues WHERE RECEIVED = :RECEIVED AND DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT AND TYPE = :TYPE AND ORDER BY ENTRY_DATE");
				$stmt->execute(array('ENTERED_AT' => htmlspecialchars($_POST['hour']), 'RECEIVED' => 1, 'TYPE' => 'manufacturing-issue'));
				$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
			}

			if($type == "ready-bike"){
				$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM issues WHERE RECEIVED = 1 AND TYPE IN ('ckd-issue' , 'ckd-bom-issue' , 'cbu-issue') AND DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM return_order WHERE DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM delivery WHERE KEY_ID IN (SELECT ENTRY_REFERENCE_ID FROM delivery_parts WHERE TYPE = 'normal') AND DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT ORDER BY ENTRY_DATE");
				$stmt->execute(array('ENTERED_AT' => htmlspecialchars($_POST['hour'])));
				$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
			}
		}
		
		if($selection_value == "date-range"){
			$date_parts = explode('-', htmlspecialchars($_POST['date-range-start']));
			$starting_date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
			$date_parts = explode('-', htmlspecialchars($_POST['date-range-end']));
			$end_date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
			if($type == "ckd" || $type == "ckdbom" || $type == "cbu" || $type == "manufacturing-parts" || $type == "spare-parts" || $type == "additional-parts"){
				$stmt = $con->query("SELECT MAX(DATE(ENTERED_AT)) AS MAX_ENTRY_DATE FROM entries WHERE TYPE = '$type'");
				$max_entry_date = $stmt->fetch(\PDO::FETCH_OBJ);
				if($type != "additional-parts" && $type != "spare-parts"){
					$stmt = $con->query("SELECT MAX(DATE(ENTERED_AT)) AS MAX_PARTS_DATE FROM issues WHERE TYPE = '$issue_type'");	
					$max_parts_date = $stmt->fetch(\PDO::FETCH_OBJ);
					if($max_entry_date->MAX_ENTRY_DATE >= $max_parts_date->MAX_PARTS_DATE) { $table = "entries"; $sub_table = "parts"; }
					else { $table = "issues"; $sub_table = "issue_records"; }
				}
				
				if($type == "additional-parts"){
					$stmt = $con->query("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM entries WHERE DATE(ENTERED_AT) BETWEEN '$starting_date' AND '$end_date' AND TYPE = 'additional-parts' UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM additional_delivery WHERE DATE(ENTERED_AT) BETWEEN '$starting_date' AND '$end_date'");
					$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
				}
				else if($type == "spare-parts"){
					$stmt = $con->query("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM entries WHERE DATE(ENTERED_AT) BETWEEN '$starting_date' AND '$end_date' AND TYPE = 'spare-parts' UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM backup_delivery WHERE DATE(ENTERED_AT) BETWEEN '$starting_date' AND '$end_date'");
					$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
				}
				else{
					$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM $table WHERE DATE(ENTERED_AT) BETWEEN :STARING_DATE AND :ENDING_DATE AND TYPE = :TYPE ORDER BY ENTRY_DATE");
					if($table == 'entries') $this_type = $type;
					else if($table == 'issues') $this_type = $issue_type;
					$stmt->execute(array('STARING_DATE' => $starting_date, 'ENDING_DATE' => $end_date, 'TYPE'=>$this_type));
					$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);	
				}
			}

			if($type == "ready-bike"){
				$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM issues WHERE RECEIVED = 1 AND TYPE IN ('ckd-issue' , 'ckd-bom-issue' , 'cbu-issue') AND DATE(ENTERED_AT) BETWEEN :STARING_DATE AND :ENDING_DATE UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM return_order WHERE DATE(ENTERED_AT) BETWEEN :STARING_DATE AND :ENDING_DATE UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM delivery WHERE KEY_ID IN (SELECT ENTRY_REFERENCE_ID FROM delivery_parts WHERE TYPE = 'normal') AND DATE(ENTERED_AT) BETWEEN :STARING_DATE AND :ENDING_DATE ORDER BY ENTRY_DATE");
				$stmt->execute(array('STARING_DATE' => $starting_date, 'ENDING_DATE' => $end_date));
				$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
			}

			if($type == "mf-frame-sa-stock"){
				$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM issues WHERE RECEIVED = :RECEIVED AND DATE(ENTERED_AT) BETWEEN :STARING_DATE AND :ENDING_DATE AND TYPE = :TYPE ORDER BY ENTRY_DATE");
				$stmt->execute(array('RECEIVED' => 1, 'STARING_DATE' => $starting_date, 'ENDING_DATE' => $end_date, 'TYPE' => 'manufacturing-issue'));
				$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);	
			}
		}
		

		if($selection_value == "specify-date"){
			$date_parts = explode('-', htmlspecialchars($_POST['specify-date']));
			$date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
			if($type == "ckd" || $type == "ckdbom" || $type == "cbu" || $type == "manufacturing-parts" || $type == "spare-parts" || $type == "additional-parts"){
				$stmt = $con->query("SELECT MAX(DATE(ENTERED_AT)) AS MAX_ENTRY_DATE FROM entries WHERE TYPE = '$type'");
				$max_entry_date = $stmt->fetch(\PDO::FETCH_OBJ);
				if($type != "additional-parts" || $type != "spare-parts"){
					$stmt = $con->query("SELECT MAX(DATE(ENTERED_AT)) AS MAX_PARTS_DATE FROM issues WHERE TYPE = '$type'");
					$max_parts_date = $stmt->fetch(\PDO::FETCH_OBJ);
					if($max_entry_date->MAX_ENTRY_DATE >= $max_parts_date->MAX_PARTS_DATE) { $table = "entries"; $sub_table = "parts"; }
					else { $table = "issues"; $sub_table = "issue_records"; }
				}
				
				if($type == "additional-parts") { 
					$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM additional_delivery WHERE DATE(ENTERED_AT) = :SPECIFIED_DATE");	
					$stmt->execute(array('SPECIFIED_DATE' => $date));
				}
				else if($type == "spare-parts") { 
					$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM backup_delivery WHERE DATE(ENTERED_AT) = :SPECIFIED_DATE");	
					$stmt->execute(array('SPECIFIED_DATE' => $date));
				}
				else{
					$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM $table WHERE TYPE = :TYPE AND DATE(ENTERED_AT) = :SPECIFIED_DATE");	
					if($table == 'entries') $this_type = $type;
					else if($table == 'issues') $this_type = $issue_type;
					$stmt->execute(array('TYPE' => $this_type, 'SPECIFIED_DATE' => $date));	
				}
				$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
				if(sizeof($dates) == 0){
					if($type == "additional-parts" || $type == "spare-parts" ) {
						$stmt = $con->prepare("SELECT MAX(DATE(ENTERED_AT)) AS ENTRY_DATE FROM $table");	
						$stmt->execute();
					}
					else{
						$stmt = $con->prepare("SELECT MAX(DATE(ENTERED_AT)) AS ENTRY_DATE FROM $table WHERE TYPE = :TYPE");	
						$stmt->execute(array('TYPE' => $this_type));
					}
					$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
					$dates[0]->ENTRY_DATE = $date;
				}
			}

			if($type == "ready-bike"){
				$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM issues WHERE RECEIVED = 1 AND TYPE IN ('ckd-issue' , 'ckd-bom-issue' , 'cbu-issue') AND  DATE(ENTERED_AT) = :SPECIFIED_DATE UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM return_order WHERE  DATE(ENTERED_AT) = :SPECIFIED_DATE UNION SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM delivery WHERE KEY_ID IN (SELECT ENTRY_REFERENCE_ID FROM delivery_parts WHERE TYPE = 'normal') AND  DATE(ENTERED_AT) = :SPECIFIED_DATE ORDER BY ENTRY_DATE");
				$stmt->execute(array('SPECIFIED_DATE' => $date));
				$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);		
				if(sizeof($dates) == 0){
					$stmt = $con->prepare("SELECT MAX(DATE(ENTERED_AT)) AS DELIVERY FROM delivery");	
					$stmt->execute();
					$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
					$dates[0]->ENTRY_DATE = $date;
				}
			}

			if($type == "mf-frame-sa-stock"){
				$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM issues WHERE RECEIVED = :RECEIVED AND TYPE = :TYPE AND DATE(ENTERED_AT) = :SPECIFIED_DATE");	
				$stmt->execute(array('RECEIVED' => 1, 'TYPE' => 'manufacturing-issue', 'SPECIFIED_DATE' => $date));
				$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
				$dates_size = sizeof($dates);
				if($dates_size == 0){
					$stmt = $con->prepare("SELECT MAX(DATE(ENTERED_AT)) AS ENTRY_DATE FROM issues WHERE TYPE = :TYPE AND RECEIVED = :RECEIVED");	
					$stmt->execute(array('TYPE' => 'manufacturing-issue', 'RECEIVED' => 1));
					$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
				}
				if($dates_size <= 1) $given_mf_date = date('d M, Y',strtotime($date));
			}
		}


		$content = "";
		$count = 1;
		$limit = sizeof($dates);
		

		foreach($dates as $date){
			$page = "";
			if($type != "additional-parts" && $type != "spare-parts" && $type != "mf-frame-sa-stock" && $type != "ready-bike"){
				$stmt = $con->query("SELECT DISTINCT PART_NUMBER FROM $sub_table WHERE ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM entries WHERE TYPE = '$type' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')" );	
				$unique_parts = $stmt->fetchAll(\PDO::FETCH_OBJ);	
			}

			else if($type == "additional-parts"){
				$stmt = $con->query("SELECT DISTINCT PART_NUMBER FROM parts WHERE ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM entries WHERE TYPE = 'additional-parts' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')" );
				$unique_values = $stmt->fetchAll(\PDO::FETCH_OBJ);	
			}

			else if($type == "spare-parts"){
				$stmt = $con->query("SELECT DISTINCT PART_NUMBER FROM parts WHERE ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM entries WHERE TYPE = 'spare-parts' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')" );
				$unique_values = $stmt->fetchAll(\PDO::FETCH_OBJ);	
			}

			else if($type == "ready-bike"){
				$stmt = $con->query("SELECT DISTINCT PART_NUMBER FROM issue_records WHERE ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM issues WHERE TYPE IN ('ckd-issue' , 'ckd-bom-issue' , 'cbu-issue') AND RECEIVED = 1 AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')" );
				$unique_parts = $stmt->fetchAll(\PDO::FETCH_OBJ);	
			}


		

			if($type == "ckd" || $type == "ckdbom" || $type == "cbu"){
				foreach($unique_parts as $part){
					$tmp = "";
					$stmt = $con->query("SELECT COUNT(*) AS NUM_ENTRIES, SUM(QUANTITY) AS PART_QUANTITY, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME FROM parts WHERE PART_NUMBER = '$part->PART_NUMBER' AND ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM entries WHERE TYPE = '$type' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE') GROUP BY PART_NUMBER,COLOR_CODE");
							
					$entry_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
					$entry_quantity = sizeof($entry_num);
					foreach ($entry_num as $e) {
						$stmt = $con->query("SELECT COUNT(*) AS ISSUE_ENTRY_QUANTITY, SUM(QUANTITY) AS ISSUED_QUANTITY
										FROM issue_records
										WHERE PART_NUMBER = '$e->PART_NUMBER'
										AND COLOR_CODE = '$e->COLOR_CODE'
										AND ENTRY_REFERENCE_ID IN 
										(SELECT KEY_ID FROM issues WHERE TYPE = '$issue_type' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE' AND RECEIVED != 1 )
										GROUP BY MODEL,COLOR_CODE
										");
						$issued_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
						$quantity = $e->PART_QUANTITY - $issued_num[0]->ISSUED_QUANTITY;

						if($quantity > 0){
							if(empty($e->MODEL)) $this_model = "-------";
							else $this_model = $e->MODEL;
							$tmp .= "
							<tr>
								<td style='color: #333; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$e->PART_NUMBER</td>
								<td style='color: #333; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$e->PART_NAME</td>
								<td style='color: #333; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$this_model</td>
								<td style='color: #333; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$e->COLOR_CODE</td>
								<td style='color: #333; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$e->COLOR_NAME</td>
								<td style='color: #333; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$quantity</td>
							</tr>
							";
						} // QUANTITY != 0 END
					} // ENTRY NUM END 
					if(!empty($tmp)){
						$page .= $tmp;
					}
				} // FOREACH UNIQUE MODELS END
				if(!empty($page)){
					$content .= title_info("STOCK STATUS - $title");
					$this_date = date("d M, Y", strtotime($date->ENTRY_DATE));
					$content .= "<h3 style='font-family:helvetica; font-size:17px; text-align:center; color: #333;'>$this_date</h3>";
					$content .=
					"
					<table style = 'width:100%; margin-bottom: 10px;'>
						<tr>
							<th style='width:100px; color: #333; background: #ddd; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NUMBER</th>
							<th style='width:100px; color: #333; background: #ddd; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NAME</th>
							<th style='width:100px; color: #333; background: #ddd; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>MODEL</th>
							<th style='width:100px; color: #333; background: #ddd; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR CODE</th>
							<th style='width:100px; color: #333; background: #ddd; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR NAME</th>
							<th style='width:100px; color: #333; background: #ddd; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>STOCK QUANTITY</th>
						</tr>
					";
					$content .= $page;
					$content .= "</table>";
					$content .= footer_info("stock",$limit,$count);
					$count++;
				}
			} // IF END



			if($type == "manufacturing-parts"){
				foreach($unique_parts as $part){
					$tmp = "";
					$stmt = $con->query("SELECT COUNT(*) AS NUM_ENTRIES, SUM(QUANTITY) AS PART_QUANTITY, PART_NUMBER, PART_NAME, MODEL
									FROM parts
									WHERE PART_NUMBER = '$part->PART_NUMBER'
									AND ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM entries WHERE TYPE = '$type' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')
									GROUP BY PART_NUMBER,MODEL");
							
					$entry_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
					$entry_quantity = sizeof($entry_num);
					foreach ($entry_num as $e) {
						$stmt = $con->query("SELECT COUNT(*) AS ISSUE_ENTRY_QUANTITY, SUM(QUANTITY) AS ISSUED_QUANTITY
										FROM issue_records
										WHERE MODEL = '$e->MODEL'
										AND PART_NUMBER = '$e->PART_NUMBER'
										AND ENTRY_REFERENCE_ID IN 
										(SELECT KEY_ID FROM issues WHERE TYPE = '$issue_type' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE' AND RECEIVED != 1 )
										GROUP BY MODEL,PART_NUMBER
										");

						$received_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
						$quantity = $e->PART_QUANTITY - $received_num[0]->ISSUED_QUANTITY;

						if($quantity > 0){
							if(empty($e->MODEL)) $this_model = "-------";
							else $this_model = $e->MODEL;
							$tmp .= 
							"
							<tr>
								<td style='width:100px; color: #333; font-size: 12px; text-align: center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$this_model</td>
								<td style='width:100px; color: #333; font-size: 12px; text-align: center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$e->PART_NUMBER</td>
								<td style='width:100px; color: #333; font-size: 12px; text-align: center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$e->PART_NAME</td>
								<td style='width:100px; color: #333; font-size: 12px; text-align: center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$quantity</td>
							</tr>
							";
						} // QUANTITY != 0 END
					} // ENTRY NUM END 
					if(!empty($tmp)){
						$page .= $tmp;
					}
				} // FOREACH UNIQUE MODELS END
				if(!empty($page)){
					$content .= title_info("STOCK STATUS - $title");
					$this_date = date("d M, Y", strtotime($date->ENTRY_DATE));
					$content .= "<h3 style='font-family:helvetica; font-size:17px; text-align:center; color: #333;'>$this_date</h3>";
					$content .= 
					"
					<table style = 'width:100%; margin-bottom: 10px;'>
						<tr>
							<th style='width:100px; color: #333; background: #ddd; text-align:center; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>MODEL</th>
							<th style='width:100px; color: #333; background: #ddd; text-align:center; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NUMBER</th>
							<th style='width:100px; color: #333; background: #ddd; text-align:center; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NAME</th>
							<th style='width:100px; color: #333; background: #ddd; text-align:center; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>STOCK QUANTITY</th>
						</tr>
					";
					$content .= $page;
					$content .= "</table>";
					$content .= footer_info("stock",$limit,$count);
					$count++;
				}
			}  // IF END



			if($type == "additional-parts"){
				foreach($unique_values as $value){
					$tmp = "";
					$stmt = $con->query("SELECT COUNT(*) AS NUM_ENTRIES, SUM(QUANTITY) AS PART_QUANTITY, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE
									FROM parts
									WHERE PART_NUMBER = '$value->PART_NUMBER'
									AND ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM entries WHERE TYPE = '$type' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')
									GROUP BY PART_NUMBER");
							
					$entry_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
					$entry_quantity = sizeof($entry_num);
					foreach ($entry_num as $e) {
						$stmt = $con->query("SELECT COUNT(*) AS NUM_ENTRIES, SUM(QUANTITY) AS DELIVERED_QUANTITY
										FROM delivery_parts
										WHERE PART_NUMBER = '$e->PART_NUMBER'
										AND COLOR_CODE = '$e->COLOR_CODE'
										AND TYPE = 'additional'
										AND ENTRY_REFERENCE_ID IN 
										(SELECT KEY_ID FROM additional_delivery WHERE DATE(ENTERED_AT) <= '$date->ENTRY_DATE')
										GROUP BY PART_NUMBER
										");

						$delivered_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
						$quantity = $e->PART_QUANTITY - $delivered_num[0]->DELIVERED_QUANTITY;

						if($quantity > 0){
							if(empty($e->MODEL)) $this_model = "-------";
							else $this_model = $e->MODEL;
							$tmp .= "
							<tr>
								<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>$e->PART_NUMBER</td>
								<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>$e->PART_NAME</td>
								<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>$this_model</td>
								<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>$quantity</td>
							</tr>
							";
						} // QUANTITY != 0 END
					} // ENTRY NUM END 

					if(!empty($tmp)){
						$page .= $tmp;
					}
				} // FOREACH UNIQUE MODELS END
				if(!empty($page)){
					$content .= title_info("STOCK STATUS - $title");
					$this_date = date("d M, Y", strtotime($date->ENTRY_DATE));
					$content .= "<h3 style='font-family:helvetica; font-size:17px; text-align:center; color: #333;'>$this_date</h3>";
					$content .=
					"
					<table style = 'width:100%; margin-bottom:10px'>
						<tr>
							<th style='width:100px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>PART NUMBER</th>
							<th style='width:100px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>PART NAME</th>
							<th style='width:100px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>MODEL</th>
							<th style='width:100px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>STOCK QUANTITY</th>
						</tr>
					";
					$content .= $page;
					$content .= "</table>";
					$content .= footer_info("stock",$limit,$count);
					$count++;
				}
			} // IF END




			if($type == "spare-parts"){
				foreach($unique_values as $value){
					$tmp = "";
					$stmt = $con->query("SELECT COUNT(*) AS NUM_ENTRIES, SUM(QUANTITY) AS PART_QUANTITY, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE
									FROM parts
									WHERE PART_NUMBER = '$value->PART_NUMBER'
									AND ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM entries WHERE TYPE = '$type' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')
									GROUP BY PART_NUMBER");
							
					$entry_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
					$entry_quantity = sizeof($entry_num);
					foreach ($entry_num as $e) {
						$stmt = $con->query("SELECT COUNT(*) AS NUM_ENTRIES, SUM(QUANTITY) AS DELIVERED_QUANTITY
										FROM delivery_parts
										WHERE PART_NUMBER = '$e->PART_NUMBER'
										AND COLOR_CODE = '$e->COLOR_CODE'
										AND TYPE = 'backup'
										AND ENTRY_REFERENCE_ID IN 
										(SELECT KEY_ID FROM backup_delivery WHERE DATE(ENTERED_AT) <= '$date->ENTRY_DATE')
										GROUP BY PART_NUMBER
										");

						$delivered_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
						$quantity = $e->PART_QUANTITY - $delivered_num[0]->DELIVERED_QUANTITY;

						if($quantity > 0){
							if(empty($e->MODEL)) $this_model = "-------";
							else $this_model = $e->MODEL;
							$tmp .= "
							<tr>
								<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>$e->PART_NUMBER</td>
								<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>$e->PART_NAME</td>
								<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>$this_model</td>
								<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>$quantity</td>
							</tr>
							";
						} // QUANTITY != 0 END
					} // ENTRY NUM END 

					if(!empty($tmp)){
						$page .= $tmp;
					}
				} // FOREACH UNIQUE MODELS END
				if(!empty($page)){
					$content .= title_info("STOCK STATUS - $title");
					$this_date = date("d M, Y", strtotime($date->ENTRY_DATE));
					$content .= "<h3 style='font-family:helvetica; font-size:17px; text-align:center; color: #333;'>$this_date</h3>";
					$content .=
					"
					<table style = 'width:100%; margin-bottom:10px'>
						<tr>
							<th style='width:100px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>PART NUMBER</th>
							<th style='width:100px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>PART NAME</th>
							<th style='width:100px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>MODEL</th>
							<th style='width:100px; background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>STOCK QUANTITY</th>
						</tr>
					";
					$content .= $page;
					$content .= "</table>";
					$content .= footer_info("stock",$limit,$count);
					$count++;
				}
			} // IF END



			if($type == "ready-bike"){
				foreach($unique_parts as $part){
					$tmp = "";

					$stmt = $con->query("SELECT COUNT(*) AS NUM_ENTRIES, SUM(QUANTITY) AS PART_QUANTITY, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME
									FROM issue_records
									WHERE PART_NUMBER = '$part->PART_NUMBER'
									AND ENTRY_REFERENCE_ID IN 
									(SELECT KEY_ID FROM issues WHERE TYPE IN ('ckd-issue','ckd-bom-issue','cbu-issue') AND RECEIVED= 1 AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')
									GROUP BY PART_NUMBER,COLOR_CODE");
							
					$entry_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
					$entry_quantity = sizeof($entry_num);
					foreach ($entry_num as $e) {
						$stmt = $con->query("SELECT COUNT(*) AS DELIVERY_ENTRY_QUANTITY, SUM(QUANTITY) AS DELIVERY_QUANTITY
										FROM delivery_parts
										WHERE PART_NUMBER = '$e->PART_NUMBER'
										AND COLOR_CODE = '$e->COLOR_CODE'
										AND MODEL = '$e->MODEL'
										AND TYPE = 'normal'
										AND ENTRY_REFERENCE_ID IN 
										(SELECT KEY_ID FROM delivery WHERE DATE(ENTERED_AT) <= '$date->ENTRY_DATE')
										GROUP BY PART_NUMBER,COLOR_CODE
										");
						$delivered_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
						$quantity = $e->PART_QUANTITY - $delivered_num[0]->DELIVERY_QUANTITY;

						$stmt = $con->query("SELECT COUNT(*) AS RETURN_ENTRY_QUANTITY, SUM(QUANTITY) AS RETURN_QUANTITY
										FROM returned_parts
										WHERE PART_NUMBER = '$e->PART_NUMBER'
										AND COLOR_CODE = '$e->COLOR_CODE'
										AND MODEL = '$e->MODEL'
										AND ENTRY_REFERENCE_ID IN 
										(SELECT KEY_ID FROM return_order WHERE DATE(ENTERED_AT) <= '$date->ENTRY_DATE')
										GROUP BY PART_NUMBER,COLOR_CODE
										");
						$returned_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
						if(sizeof($returned_num)>0){
							$quantity = $quantity + $returned_num[0]->RETURN_QUANTITY;
						}

						if($quantity > 0){
							if(empty($e->MODEL)) $this_model = "-------";
							else $this_model = $e->MODEL;
							$tmp .= "
							<tr>
								<td style='color: #333; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$e->PART_NUMBER</td>
								<td style='color: #333; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$e->PART_NAME</td>
								<td style='color: #333; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$this_model</td>
								<td style='color: #333; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$e->COLOR_CODE</td>
								<td style='color: #333; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$e->COLOR_NAME</td>
								<td style='color: #333; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$quantity</td>
							</tr>
							";
						} // QUANTITY != 0 END
					} // ENTRY NUM END 
					if(!empty($tmp)){
						$page .= $tmp;
					}
				} // FOREACH UNIQUE MODELS END
				if(!empty($page)){
					$content .= title_info("STOCK STATUS - $title");
					$this_date = date("d M, Y", strtotime($date->ENTRY_DATE));
					$content .= "<h3 style='font-family:helvetica; font-size:17px; text-align:center; color: #333;'>$this_date</h3>";
					$content .=
					"
					<table style = 'width:100%; margin-bottom: 10px;'>
						<tr>
							<th style='width:100px; color: #333; background: #ddd; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NUMBER</th>
							<th style='width:100px; color: #333; background: #ddd; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NAME</th>
							<th style='width:100px; color: #333; background: #ddd; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>MODEL</th>
							<th style='width:100px; color: #333; background: #ddd; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR CODE</th>
							<th style='width:100px; color: #333; background: #ddd; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR NAME</th>
							<th style='width:100px; color: #333; background: #ddd; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>STOCK QUANTITY</th>
						</tr>
					";
					$content .= $page;
					$content .= "</table>";
					$content .= footer_info("stock",$limit,$count);
					$count++;
				}
			} // READY BIKE IF END



			if($type == "mf-frame-sa-stock"){
				$stmt = $con->query("SELECT COUNT(*) AS NUM_ENTRIES, SUM(QUANTITY) AS PART_QUANTITY, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME
								FROM issue_records
								WHERE ENTRY_REFERENCE_ID 
								IN (SELECT KEY_ID FROM issues WHERE RECEIVED = 1 AND TYPE = 'manufacturing-issue' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')
								GROUP BY PART_NUMBER,MODEL");
				$values = $stmt->fetchAll(\PDO::FETCH_OBJ);
				$count = 1;
				$limit = sizeof($values);
				if($limit>0){
					$this_date = date("d M, Y", strtotime($date->ENTRY_DATE));
					$content .= title_info("STOCK STATUS - MF FRAME & SA STOCK");
					if(sizeof($dates) > 1) $content .= "<h3 style='font-family:helvetica; font-size:17px; text-align:center; color: #333;'>$this_date</h3>";
					else $content .= "<h3 style='font-family:helvetica; font-size:17px; text-align:center; color: #333;'>$given_mf_date</h3>";
					$content .= "<table style='width:100%; margin-bottom:20px;'>";
					$content .= 
					"<tr>
						<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>PART NUMBER</th>
						<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>PART NAME</th>
						<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>MODEL</th>
						<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>COLOR CODE</th>
						<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>COLOR NAME</th>
						<th style='background: #ddd; color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>STOCK QUANTITY</th>
					</tr>";
					foreach ($values as $value) {
						if(empty($value->MODEL)) $this_model = "-------";
						else $this_model = $value->MODEL;
						$content .= 
						"<tr>
							<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>$value->PART_NUMBER</td>
							<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>$value->PART_NAME</td>
							<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>$this_model</td>
							<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>$value->COLOR_CODE</td>
							<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>$value->COLOR_NAME</td>
							<td style='color: #333; font-size: 12px; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold; text-align:center;'>$value->PART_QUANTITY</td>
						</tr>";
					}
					$content .= footer_info("mf-sa",$limit,$count);
					$count++;
				}

			} // FRAME SWING ARM IF END
		} // FOREACH DATES END


		if(empty($content)){
			$content .= title_info("STOCK STATUS - $title");
			$content .= "<h3 style='font-size:24px; color:#444; line-height: 35px; margin-top:230px; font-family:helvetica; text-align:center; text-transform:uppercase;'>You either have no stock left or there is no data to show that corresponds to the given date(s)</h3>";
		}
	}  // IF END
	






	// PENDING STATUS
	if($main_table == "pending" && $parts_table == "pending_parts"){
		if($type == "ckd") { $issue_type = "ckd-issue"; $title = "In Progress - CKD PARTS"; }
		if($type == "ckdbom") { $issue_type = "ckd-bom-issue"; $title = "In Progress - CKD By BOM PARTS"; }
		if($type == "cbu") { $issue_type = "cbu-issue"; $title = "In Progress - CBU PARTS"; }

		if($selection_value == "hour"){
			$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM issues WHERE DATE(NOW()) = DATE(ENTERED_AT) AND HOUR(NOW()) - HOUR(ENTERED_AT) <= :ENTERED_AT AND TYPE = :TYPE ORDER BY HOUR(ENTERED_AT)");
			$stmt->execute(array('ENTERED_AT' => htmlspecialchars($_POST['hour']), 'TYPE' => $issue_type));
			$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);			
		}
		
		if($selection_value == "date-range"){
			$date_parts = explode('-', htmlspecialchars($_POST['date-range-start']));
			$starting_date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
			$date_parts = explode('-', htmlspecialchars($_POST['date-range-end']));
			$end_date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
		
			$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM issues WHERE DATE(ENTERED_AT) BETWEEN :STARING_DATE AND :ENDING_DATE ORDER BY ENTRY_DATE AND TYPE = :TYPE");
			$stmt->execute(array('STARING_DATE' => $starting_date, 'ENDING_DATE' => $end_date, 'TYPE'=>$issue_type));
			$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);	
		}
		

		if($selection_value == "specify-date"){
			$date_parts = explode('-', htmlspecialchars($_POST['specify-date']));
			$date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
		
			$stmt = $con->prepare("SELECT DISTINCT DATE(ENTERED_AT) AS ENTRY_DATE FROM issues WHERE TYPE = :TYPE AND DATE(ENTERED_AT) = :SPECIFIED_DATE");	
			$stmt->execute(array('TYPE' => $issue_type, 'SPECIFIED_DATE' => $date));	
			$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
			if(sizeof($dates) == 0){
				$stmt = $con->prepare("SELECT MAX(DATE(ENTERED_AT)) AS ENTRY_DATE FROM issues WHERE TYPE = :TYPE");	
				$stmt->execute(array('TYPE' => $issue_type));
				$dates = $stmt->fetchAll(\PDO::FETCH_OBJ);
				$dates[0]->ENTRY_DATE = $date;
			}
		}

		$content = "";
		$count = 1;
		$limit = sizeof($dates);
		foreach($dates as $date){
			$page = "";
			$stmt = $con->query("SELECT DISTINCT PART_NUMBER FROM issue_records WHERE ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM issues WHERE TYPE = '$issue_type' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE')" );	
			$unique_parts = $stmt->fetchAll(\PDO::FETCH_OBJ);	
			foreach($unique_parts as $part){
				$tmp = "";
				$stmt = $con->query("SELECT COUNT(*) AS NUM_ENTRIES, SUM(QUANTITY) AS ISSUED_QUANTITY, PART_NUMBER, PART_NAME, MODEL, COLOR_CODE, COLOR_NAME FROM issue_records WHERE PART_NUMBER = '$part->PART_NUMBER' AND ENTRY_REFERENCE_ID IN (SELECT KEY_ID FROM issues WHERE TYPE = '$issue_type' AND RECEIVED != 1 AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE') GROUP BY PART_NUMBER,COLOR_CODE");
						
				$entry_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
				$entry_quantity = sizeof($entry_num);
				foreach ($entry_num as $e) {
					$stmt = $con->query("SELECT COUNT(*) AS RECEIVED_ENTRY_QUANTITY, SUM(QUANTITY) AS RECEIVED_QUANTITY
									FROM issue_records
									WHERE PART_NUMBER = '$e->PART_NUMBER'
									AND COLOR_CODE = '$e->COLOR_CODE'
										AND ENTRY_REFERENCE_ID IN 
									(SELECT KEY_ID FROM issues WHERE TYPE = '$issue_type' AND DATE(ENTERED_AT) <= '$date->ENTRY_DATE' AND RECEIVED = 1 )
									GROUP BY MODEL,COLOR_CODE
									");
					$received_num = $stmt->fetchAll(\PDO::FETCH_OBJ);
					$quantity = $e->ISSUED_QUANTITY - $received_num[0]->RECEIVED_QUANTITY;

					if($quantity > 0){
						if(empty($e->MODEL)) $this_model = "-------";
						else $this_model = $e->MODEL;
						$tmp .= "
						<tr>
							<td style='color: #333; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$e->PART_NUMBER</td>
							<td style='color: #333; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$e->PART_NAME</td>
							<td style='color: #333; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$this_model</td>
							<td style='color: #333; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$e->COLOR_CODE</td>
							<td style='color: #333; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$e->COLOR_NAME</td>
							<td style='color: #333; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>$quantity</td>
						</tr>
						";
					} // QUANTITY != 0 END
				} // ENTRY NUM END 
				if(!empty($tmp)){
					$page .= $tmp;
				}
			} // FOREACH UNIQUE MODELS END
			if(!empty($page)){
				$content .= title_info($title);
				$this_date = date("d M, Y", strtotime($date->ENTRY_DATE));
				$content .= "<h3 style='font-family:helvetica; font-size:17px; text-align:center; color: #333;'>$this_date</h3>";
				$content .=
				"
				<table style = 'width:100%; margin-bottom: 10px;'>
					<tr>
						<th style='width:100px; color: #333; background: #ddd; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NUMBER</th>
						<th style='width:100px; color: #333; background: #ddd; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>PART NAME</th>
						<th style='width:100px; color: #333; background: #ddd; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>MODEL</th>
						<th style='width:100px; color: #333; background: #ddd; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR CODE</th>
						<th style='width:100px; color: #333; background: #ddd; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>COLOR NAME</th>
						<th style='width:100px; color: #333; background: #ddd; font-size: 12px; text-align:center; font-family: helvetica, sans-serif; border: 1px solid #ababab; padding:3px; font-weight:bold;'>STOCK QUANTITY</th>
					</tr>
				";
				$content .= $page;
				$content .= "</table>";
				$content .= footer_info("in_progress",$limit,$count);
				$count++;
			}
		} // FOREACH DATES END

		if(empty($content)){
			$content .= title_info($title);
			$content .= "<h3 style='font-size:24px; color:#444; line-height: 35px; margin-top:230px; font-family:helvetica; text-align:center; text-transform:uppercase;'>You either have no stock left or there is no data to show that corresponds to the given date(s)</h3>";
		}

	} // IF PENDING PARTS END



	$content .= "</div> <!-- /page --></body></html>";
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
	<body>
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




function footer_info($type,$limit,$count){
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
		";
		if($limit == $count) $footer .= "<footer style='position: fixed; left: 480px; bottom: 5px; font-family: helvetica;'>$time</footer>";
		else $footer .= "<footer style='position: fixed; left: 480px; bottom: 5px; font-family: helvetica; page-break-after:always;'>$time</footer>";
		
	return $footer;
}

?>