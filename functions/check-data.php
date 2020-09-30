<?php namespace suzuki\process_forms;
require_once 'functions.php';
use suzuki\fetch_functions;
use suzuki\process_forms;

// $stmt = $con->prepare("SELECT * FROM parts WHERE ENGINE_NUMBER = :ENGINE_NUMBER OR FRAME_NUMBER = :FRAME_NUMBER LIMIT 1");
// $stmt->execute(array('ENGINE_NUMBER' => $e, 'FRAME_NUMBER' => $f));
// $row_val = $stmt->fetch(\PDO::FETCH_OBJ);
// if($stmt->rowCount() > 0){
// 	if($e == $row_val->ENGINE_NUMBER){
// 		array_push($engine_in_db, $e);
// 	}
// 	else if($f == $row_val->FRAME_NUMBER){
// 		array_push($frame_in_db, $f);
// 	}
// }



// UPLOAD EXCEL
require_once '../excel-reader/Classes/PHPExcel/IOFactory.php';
if(!empty($_FILES['file']['tmp_name'])){
	$excel_object = \PHPExcel_IOFactory::load($_FILES['file']['tmp_name']);
	$rows = $excel_object->getActiveSheet()->toArray(null);
	$engines = $frames = $duplicate_engines = $duplicate_frames = [];
	$size = sizeof($rows);
	for ($i = 1; $i < $size; $i++) {
		$j = $i + 1;
		$f = htmlspecialchars($rows[$i][7]);
		$e = htmlspecialchars($rows[$i][8]);

		check_data($engines,$duplicate_engines,$e,$j);
		check_data($frames,$duplicate_frames,$f,$j);		
	}

	check_excel_db_conflicts($engines);
	check_excel_db_conflicts($frames);
	check_internal_excel_conflicts($duplicate_engines);
	check_internal_excel_conflicts($duplicate_frames);

	if(sizeof($duplicate_engines) == 0 && sizeof($duplicate_frames) == 0){
		// NO CONFLICTING ENTRIES ON EXCEL SHEET

	}
	else{
		// CONFLICTING ENTRIES ON EXCEL SHEET
		if(sizeof($duplicate_engines) > 0){
			echo "Repeating engine number : ";
			foreach ($duplicate_engines as $key => $value) {
				echo "<br>$key occured on line $value on the excel sheet";
			}
		}
		echo "<br><br>";
		if(sizeof($duplicate_frames) > 0){
			echo "Repeating frame number : ";
			foreach ($duplicate_frames as $key => $value) {
				echo "<br>$key occured on line $value on the excel sheet";
			}
		}
	}
	
} // EXCEL UPLOAD END




function check_data(&$array,&$duplicate_array,$item,$row){
	if($array[$item]){
		if($tmp = $duplicate_array[$item]) $duplicate_array[$item] = $tmp . "," . $row;
		else  $duplicate_array[$item] = $array[$item] . "," . $row;
	}
	else $array[$item] = $row;
}




function check_excel_db_conflicts($array,$column){
	global $con;
	$stmt = $con->prepare("SELECT * FROM parts WHERE TYPE = :TYPE1 OR TYPE = :TYPE2 OR TYPE = :TYPE3 AND $column IN ($array)");
	$stmt->execute('TYPE1' => 'ckd','TYPE2' => 'ckdbom','TYPE3' => 'cbu');
	$rows = $stmt->fetch(\PDO::FETCH_OBJ);
	if($stmt->rowCount() > 0){
		foreach ($rows as $row) {
			
		}
	}
}
?>