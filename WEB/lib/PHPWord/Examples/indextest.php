<?php	
	require_once '../PHPWord.php';
	
	$PHPWord = new PHPWord();	
	$document = $PHPWord->loadTemplate('kgb.docx');
	
	// prepare data for tables
	$data1 = array(
		'num' => array(1,2,3,4,5,6,7),
		'lama' => array('aaa', 'bbb', 'ccc', 'dddd', 'eeee', 'xxx', 'ssss'),
		'baru' => array('aaa1', 'bbb1', 'ccc1', 'dddd1', 'eee1e', 'xxx1', 'ss1ss'),
		'jabatan' => array('aaaa1', 'bvbb1', 'cccc1', 'ddcdd1', 'eeec1e', 'xxcx1', 'ss1scs')
	);	
		
	// clone rows	
	$document->cloneRow('TBL1', $data1);
	
	$document->save('model.docx');
	$file = 'model.docx';
	
	$down = 'model.docx';
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.basename($down));
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Content-Length: ' . filesize($down));
	ob_clean();
	flush();
	readfile($down);
	unlink($down);
	unset($oPrinter);
	exit;
			
	exit();
?>