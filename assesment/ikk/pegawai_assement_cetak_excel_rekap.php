<?$reqId = $this->input->get("reqId");
		$mode = $this->input->get("mode");
		$reqTipe = $this->input->get("reqTipe");

		$this->load->model('JobPlan');

		//ANGGORO TAMBAHAN QUERY
		$checker= new JobPlan();
		$stasss= " AND A.VERSI IS NULL";
        $checker->selectByParamsApproval(array("A.JOB_PLAN_ID"=>$reqId, "A.PERAN"=>"checker", "A.TIPE"=>"rap"),-1,-1,$stasss);
        // echo $checker->query;exit;
        while ($checker->nextRow()) 
        {
            $reqPegawaiJabatanRiwayatIdChecker[]=$checker->getField("JABATAN_RIWAYAT_ID");
            $reqPegawaiNamaChecker[]=$checker->getField("PEGAWAI_NAMA");
            $reqPegawaiJabatanChecker[]=$checker->getField("NAMA_JABATAN"); 
        }
        // print_r($reqPegawaiJabatanChecker); exit;

		$set = new JobPlan();
		$set->selectByParams(array("A.JOB_PLAN_ID" => $reqId));
		$set->firstRow();
		$reqRegisterPekerjaan= $set->getField("REGISTER_PEKERJAAN");
		$reqPemberiTugas= $set->getField("CUSTOMER_NAMA");
		unset($set);

		$set = new JobPlan();
		$set->selectByParamsRapRabHead(array("A.JOB_PLAN_ID"=>$reqId, "B.TIPE"=>'rap'),-1,-1," AND B.VERSI IS NULL");
    	// echo $set->query;exit;
		$set->firstRow();
		$reqRapRabHeadId= $set->getField("RAP_RAB_HEAD_ID");
		$reqJudul= $set->getField("JUDUL");
		$reqLokasi= $set->getField("LOKASI");
		$reqWaktuPelaksanaan= $set->getField("WAKTU_PELAKSANAAN");
		
		$reqStatus= $set->getField("STATUS");
		$reqCatatan= $set->getField("CATATAN");

		$reqPembuatPegJabatan= $set->getField("PEMBUAT_JABATAN");
    	$reqPembuatPegNama= $set->getField("PEMBUAT_PEG_NAMA");

		$reqEstimasiBiaya= 0;
		$reqEstimasiBiayaPph= 0;

		if ($reqRapRabHeadId) 
		{
			$statemennn= " AND B.VERSI IS NULL";
			$reqEstimasiBiaya= $set->getSumEstimasi(array("B.JOB_PLAN_ID"=>$reqId, "B.TIPE"=>"rap"), $statemennn);
			$reqEstimasiPph= $set->getSumEstimasiPph(array("B.JOB_PLAN_ID"=>$reqId, "B.TIPE"=>"rap"), $statemennn);
			$reqEstimasiBiayaPph= $reqEstimasiBiaya+$reqEstimasiPph;
		}
		unset($set);

		$set = new JobPlan();

		$statement = " AND JOB_PLAN_ID = ".$reqId." AND TIPE = '".$reqTipe."' AND RAP_RAB_HEAD_ID = '".$reqRapRabHeadId."'";
		$set->selectByParamsTree(array(), -1,-1, $statement); 
		// echo $set->query;exit;


		$objPHPexcel = PHPExcel_IOFactory::load('template/cetak_rap.xlsx');
		$BStyle = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)				
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$BStyleRight = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)				
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			),
			'font'  => array(
				'size'  => 11,
				'name'  => 'Arial Narrow'
			)
		);
		$BStyleBold = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)				
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			),
			'font'  => array(
				'bold'  => true,
				'size'  => 12,
				'name'  => 'Arial Narrow'
			)
		);
		$BStyleBoldRight = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)				
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			),
			'font'  => array(
				'bold'  => true,
				'size'  => 11,
				'name'  => 'Arial Narrow'
			)
		);

		$parentstyle = array(
			'borders' => array(
				'allborders' => array(

					'style' => PHPExcel_Style_Border::BORDER_THIN
				)				
			),
			'font'  => array(
				'size'  => 12,
				'name'  => 'Arial Narrow'
			)
		);
		$parentBoldstyle = array(
			'borders' => array(
				'allborders' => array(

					'style' => PHPExcel_Style_Border::BORDER_THIN
				)				
			),
			'font'  => array(
				'bold'  => true,
				'size'  => 12,
				'name'  => 'Arial Narrow'
			)
		);
		$StyleCenter = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$StyleCenterBold = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			),
			'font'  => array(
				'bold'  => true,
				'size'  => 12,
				'name'  => 'Arial Narrow'
			)
		);

		$StyleJabatan= array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				 'wrap' => true,
				 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			)
		);

		$sheetIndex= 0;
		$symbol = "Rp";
		$objPHPexcel->setActiveSheetIndex($sheetIndex);
		$objWorksheet= $objPHPexcel->getActiveSheet();

		$objWorksheet->getStyle("E9")->getNumberFormat()->setFormatCode("_(".$symbol."* #,##0.00_);_(* \(#,##0.00\);_(".$symbol."* \"-\"??_);_(@_)");
		$objWorksheet->getStyle("E10")->getNumberFormat()->setFormatCode("_(".$symbol."* #,##0.00_);_(* \(#,##0.00\);_(".$symbol."* \"-\"??_);_(@_)");
		$objWorksheet->setCellValue("E6", $reqLokasi);
		$objWorksheet->setCellValue("E7", $reqWaktuPelaksanaan);
		$objWorksheet->setCellValue("E8", $reqPemberiTugas);
		$objWorksheet->setCellValue("E9", $reqEstimasiBiaya);
		$objWorksheet->setCellValue("E10",$reqEstimasiBiayaPph);
		$objWorksheet->setCellValue("B3", $reqJudul);
		$objWorksheet->setCellValue("B4", $reqRegisterPekerjaan);
			
		$row = 15;
		$tempRowAwal= 2;
		$field= "";
		$field= array("NOMOR", "ITEM_PEKERJAAN","VOLUME","SATUAN","HARGA_SATUAN","PPN","JUMLAH_HARGA_SATUAN","PPH_PERCENT","PPH_RP");

		$nomor=2;
		$tempTotal= 0;
		while($set->nextRow())
		{
			$index_kolom= 1;
			for($i=0; $i<count($field); $i++)
			{
				$kolom= getColomsNew($index_kolom);
				$reqParentId = $set->getField("PARENT_ID");
				$countparent = strlen($reqParentId);
				if($field[$i] == "ITEM_PEKERJAAN")
				{
					if ($countparent == 1)
					{
						$objWorksheet->getStyle($kolom.$row)->applyFromArray($parentBoldstyle);
						$objWorksheet->setCellValue($kolom.$row,$set->getField($field[$i]));

					}
					else if ($countparent == 3) 
					{
						$objWorksheet->getStyle($kolom.$row)->applyFromArray($parentstyle);
						$objWorksheet->setCellValue($kolom.$row,"   ".$set->getField($field[$i]));
					}
					else
					{
						$objWorksheet->getStyle($kolom.$row)->applyFromArray($parentstyle);
						$objWorksheet->setCellValue($kolom.$row,"        ".$set->getField($field[$i]));
					}

				}
				else if ($field[$i] == "HARGA_SATUAN" || $field[$i] == "PPN" || $field[$i] == "JUMLAH_HARGA_SATUAN" || $field[$i] == "PPH_RP") {
					$objWorksheet->getStyle($kolom.$row)->applyFromArray($BStyleRight);
					$objWorksheet->getStyle($kolom.$row)->getNumberFormat()->setFormatCode("#,##");
					$objWorksheet->setCellValue($kolom.$row,$set->getField($field[$i]));
				}
				else if ($field[$i] == "PPH_PERCENT") {
					$objWorksheet->getStyle($kolom.$row)->applyFromArray($BStyleRight);
					$objWorksheet->getStyle($kolom.$row)->getNumberFormat()->setFormatCode("#,##0.00");
					$objWorksheet->setCellValue($kolom.$row,$set->getField($field[$i]));
				}
				else
				{
					$objWorksheet->getStyle($kolom.$row)->applyFromArray($BStyle);
					$objWorksheet->setCellValue($kolom.$row,$set->getField($field[$i]));
				}
				$index_kolom++;
			}
			$nomor++;
			$row++;
			
		}

		//ANGGORO TAMBAHAN QUERY
		$approval= new JobPlan();
		$stasss= " AND A.VERSI IS NULL";
        $approval->selectByParamsApproval(array("A.JOB_PLAN_ID"=>$reqId, "A.PERAN"=>"approval", "A.TIPE"=>"rap"),-1,-1,$stasss);
        // echo $approval->query;exit;
        $approval->firstRow();
        $reqPegawaiJabatanRiwayatIdApproval=$approval->getField("JABATAN_RIWAYAT_ID");
        $reqPegawaiNamaApproval=$approval->getField("PEGAWAI_NAMA");
        $reqPegawaiJabatanApproval=$approval->getField("NAMA_JABATAN"); 

        // print_r($reqPegawaiJabatanApproval); exit;

		// exit;
		$dateNow = date("Y-m-d");
		$objWorksheet->setCellValue("I".($row+3), "Surabaya, ");
		$objWorksheet->SetCellValue("J".($row+3),getFormattedDate($dateNow))->getStyle("J".($row+3))->applyFromArray(
			$StyleCenterBold
		);
		$objWorksheet->setCellValue("C".$row, "Total ");
		$objWorksheet->getStyle("H".$row)->applyFromArray(
			$BStyleBoldRight
		);
		$objWorksheet->getStyle("H".$row)->getNumberFormat()->setFormatCode("_(".$symbol."* #,##_);_(* \(#,##\);_(".$symbol."* \"-\"??_);_(@_)");
		$objWorksheet->getStyle("J".$row)->applyFromArray(
			$BStyleBoldRight
		);
		$objWorksheet->getStyle("J".$row)->getNumberFormat()->setFormatCode("_(".$symbol."* #,##_);_(* \(#,##\);_(".$symbol."* \"-\"??_);_(@_)");

		$objWorksheet->setCellValue("H".$row, "=SUM(H16:H".($row-1).")");
		$objWorksheet->setCellValue("J".$row, "=SUM(J16:J".($row-1).")");
		$total0 = $objWorksheet->getCell("H".$row)->getCalculatedValue();
		$total1 = $objWorksheet->getCell("J".$row)->getCalculatedValue();
		$total = $total0 + $total1; 
		$objWorksheet->setCellValue("C".($row+1), 'Terbilang : " '.terbilang($total).' " Sudah termasuk PPn dan PPh');
		$objWorksheet->setCellValue("C".($row+2), "Catatan :".$reqCatatan);
		$objWorksheet->setCellValue("C".($row+4), "Disetujui Oleh :")->getStyle("C".($row+4))->applyFromArray(
			$StyleCenter
		);
		$objWorksheet->setCellValue("C".($row+10), $reqPegawaiNamaApproval)->getStyle("C".($row+10))->applyFromArray(
			$StyleCenterBold
		);
		$objWorksheet->setCellValue("C".($row+11), $reqPegawaiJabatanApproval)->getStyle("C".($row+11))->applyFromArray(
			$StyleJabatan
		);
		$objWorksheet->setCellValue("F".($row+4), "Diperiksa Oleh :")->getStyle("F".($row+4))->applyFromArray(
			$StyleCenter
		);

		// ANGGORO TANDA TANGAN DUA 
		if(count($reqPegawaiJabatanChecker)==2)
		{
			$objWorksheet->setCellValue("f".($row+10), $reqPegawaiNamaChecker[0])->getStyle("f".($row+10))->applyFromArray(
				$StyleCenterBold
			);
			$objWorksheet->setCellValue("f".($row+11), $reqPegawaiJabatanChecker[0])->getStyle("f".($row+11))->applyFromArray(
					$StyleJabatan
				);
			$objWorksheet->setCellValue("g".($row+10), $reqPegawaiNamaChecker[1])->getStyle("g".($row+10))->applyFromArray(
			$StyleCenterBold
			);
			$objWorksheet->setCellValue("g".($row+11), $reqPegawaiJabatanChecker[1])->getStyle("g".($row+11))->applyFromArray(
					$StyleJabatan
				);
		}
		else 
		{
			$objWorksheet->setCellValue("f".($row+10), $reqPegawaiNamaChecker[0])->getStyle("f".($row+10))->applyFromArray(
				$StyleCenterBold
			);
			$objWorksheet->setCellValue("f".($row+11), $reqPegawaiJabatanChecker[0])->getStyle("f".($row+11))->applyFromArray(
					$StyleJabatan
				);
		}

		
		$objWorksheet->setCellValue("J".($row+4), "Dibuat Oleh :")->getStyle("J".($row+4))->applyFromArray(
			$StyleCenter
		);
		$objWorksheet->setCellValue("J".($row+10), $reqPembuatPegNama)->getStyle("J".($row+10))->applyFromArray(
			$StyleCenterBold
		);
		$objWorksheet->setCellValue("J".($row+11), $reqPembuatPegJabatan)->getStyle("J".($row+11))->applyFromArray(
				$StyleJabatan
			);

		
		$objWorksheet->getStyle("C".($row+11))->applyFromArray(
			$StyleCenter
		);
		$objWorksheet->getStyle("B".$row.":J".$row)->applyFromArray($BStyle);
		$objWorksheet->mergeCells("C".($row+1).":J".($row+1).'');
		$objWorksheet->mergeCells("C".($row+2).":J".($row+2).'');
		$objWorksheet->mergeCells("F".($row+4).":G".($row+4).'');

		if(count($reqPegawaiJabatanChecker)==1)
		{
			$objWorksheet->mergeCells("F".($row+5).":G".($row+5).'');
			$objWorksheet->mergeCells("F".($row+10).":G".($row+10).'');
			$objWorksheet->mergeCells("F".($row+11).":G".($row+11).'');
		}

		$objWorksheet->getStyle("B".($row+1).":J".($row+1))->applyFromArray($BStyleBold);
		$objWorksheet->getStyle("B".($row+2).":J".($row+2))->applyFromArray($parentBoldstyle);

		//set image png qr code ttd
		$gdImage = imagecreatefrompng('uploads/'.$reqId.'/QRSBMTRAP'.md5($reqId).'.png');
		// Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
		$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
		$objDrawing->setName('Sample image');
		$objDrawing->setDescription('Sample image');
		$objDrawing->setImageResource($gdImage);
		$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$objDrawing->setHeight(100);
		$objDrawing->setOffsetX(25);
		$objDrawing->setOffsetY(5);
		$objDrawing->setCoordinates('J'.($row+5));
		$objDrawing->setWorksheet($objWorksheet);
		//end set image
		
		// ANGGORO TANDA TANGAN DUA 
		if(count($reqPegawaiJabatanChecker)==1)
		{
			$gdImage = imagecreatefrompng('uploads/'.$reqId.'/QRCHCKRAP'.md5($reqId.$reqPegawaiJabatanRiwayatIdChecker[0]).'.png');
			// Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
			$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
			$objDrawing->setName('Sample image');
			$objDrawing->setDescription('Sample image');
			$objDrawing->setImageResource($gdImage);
			$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
			$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
			$objDrawing->setHeight(100);
			$objDrawing->setOffsetX(200);
			$objDrawing->setOffsetY(5);
			$objDrawing->setCoordinates('F'.($row+5));
			$objDrawing->setWorksheet($objWorksheet);
		}
		else{
			//set image png qr code ttd
			$gdImage = imagecreatefrompng('uploads/'.$reqId.'/QRCHCKRAP'.md5($reqId.$reqPegawaiJabatanRiwayatIdChecker[0]).'.png');
			// echo $gdImage; exit;
			// Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
			$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
			$objDrawing->setName('Sample image');
			$objDrawing->setDescription('Sample image');
			$objDrawing->setImageResource($gdImage);
			$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
			$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
			$objDrawing->setHeight(100);
			$objDrawing->setOffsetX(75);
			$objDrawing->setOffsetY(5);
			$objDrawing->setCoordinates('F'.($row+5));
			$objDrawing->setWorksheet($objWorksheet);

			//set image png qr code ttd
			$gdImage = imagecreatefrompng('uploads/'.$reqId.'/QRCHCKRAP'.md5($reqId.$reqPegawaiJabatanRiwayatIdChecker[1]).'.png');
			// Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
			$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
			$objDrawing->setName('Sample image');
			$objDrawing->setDescription('Sample image');
			$objDrawing->setImageResource($gdImage);
			$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
			$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
			$objDrawing->setHeight(100);
			$objDrawing->setOffsetX(75);
			$objDrawing->setOffsetY(5);
			$objDrawing->setCoordinates('G'.($row+5));
			$objDrawing->setWorksheet($objWorksheet);
		}
		//end set image

		//set image png qr code ttd
		$gdImage = imagecreatefrompng('uploads/'.$reqId.'/QRRAP'.md5($reqId).'.png');
		// Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
		$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
		$objDrawing->setName('Sample image');
		$objDrawing->setDescription('Sample image');
		$objDrawing->setImageResource($gdImage);
		$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$objDrawing->setHeight(100);
		$objDrawing->setOffsetX(250);
		$objDrawing->setOffsetY(5);
		$objDrawing->setCoordinates('C'.($row+5));
		$objDrawing->setWorksheet($objWorksheet);
		//end set image

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'Excel2007');
		$objWriter->save('template/download/cetak_rap.xlsx');

		$down = 'template/download/cetak_rap.xlsx';
		$filename= 'cetak_rap.xlsx';
		ob_end_clean();
		header('Content-Description: File Transfer');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename='.$filename);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, get-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($down));
		ob_end_clean();
		readfile($down);
		exit();
