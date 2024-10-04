<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class Satker extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Satker()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("satuan_kerja_id", $this->getNextId("satuan_kerja_id","satuan_kerja")); 
		//$this->tanggal = date("Y-m-d");
		
		$str = "
		INSERT INTO satuan_kerja (
		   satuan_kerja_id, nip, nama, satuan_kerja_id_parent, nama_pimpinan, pangkat_id, admin_surat, kode_wilayah, link_file) 
		VALUES (
				  '".$this->getField("satuan_kerja_id")."',
				  '".$this->getField("nip")."',
				  '".$this->getField("nama")."',
				  '".$this->getField("satuan_kerja_id_parent")."',
				  '".$this->getField("nama_pimpinan")."',
				  '".$this->getField("pangkat_id")."',
				  '".$this->getField("admin_surat")."',
				  '".$this->getField("kode_wilayah")."',
				  '".$this->getField("link_file")."'
				)"; 
				
		$this->query = $str;
		$this->id = $this->getField("satuan_kerja_id");
		//echo $str;
		return $this->execQuery($str);
    }
		
    function update()
	{
		//Auto-generate primary key(s) by next max value (integer) 
		$str = "
		UPDATE satuan_kerja
		SET    nip       = '".$this->getField("nip")."',
			   nama      = '".$this->getField("nama")."',			   
			   nama_pimpinan    = '".$this->getField("nama_pimpinan")."',
			   pangkat_id = '".$this->getField("pangkat_id")."',
			   admin_surat = '".$this->getField("admin_surat")."',
			   kode_wilayah = '".$this->getField("kode_wilayah")."',
			   link_file = '".$this->getField("link_file")."'
		WHERE  satuan_kerja_id = '".$this->getField("satuan_kerja_id")."'
				"; 
				$this->query = $str;
				$this->id = $this->getField("satuan_kerja_id");
		return $this->execQuery($str);
    }
		
	function delete()
	{		
		$str1 = "
		 		DELETE FROM satuan_kerja
                WHERE 
                  satuan_kerja_id = '".$this->getField("satuan_kerja_id")."'";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }

	function updateStatus($statement="")
	{
        $str = "UPDATE satuan_kerja SET satuan_kerja_STATUS = '".$this->getField("satuan_kerja_STATUS")."' ".$statement."
                WHERE 
                  satuan_kerja_id = '".$this->getField("satuan_kerja_id")."'";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function updateStatusRevisi()
	{
        $str = "
		update surat_keluar set 
		is_new = ".$this->getField("is_new").", 
		is_acc = ".$this->getField("is_acc").", 
		is_rev = ".$this->getField("is_rev").", 
		is_final = ".$this->getField("is_final").", 
		is_balasan = ".$this->getField("is_balasan").", 
		satuan_kerja_id_posisi = '".$this->getField("satuan_kerja_id_posisi")."',
		satuan_kerja_id_akhir = '".$this->getField("satuan_kerja_id_akhir")."'
            where surat_keluar_id = '".$this->getField("surat_keluar_id")."'";
		$this->query = $str;
        return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "SELECT 
				   satuan_kerja_id, nip, nama, satuan_kerja_id_parent, nama_pimpinan, pangkat_id, admin_surat, kode_wilayah, link_file
				FROM satuan_kerja
				WHERE satuan_kerja_id IS NOT NULL
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY satuan_kerja_id ASC";
		//echo $str;
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
	  function selectByParamsReportSatker($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
				SELECT c.USER_ID, c.USER_NAMA, c.USER_JABATAN, a.nip, a.nama,
					   a.satuan_kerja_id_parent, a.nama_pimpinan, a.satuan_kerja_KEPERLUAN, b.ARSIP_ID,
					   d.ARSIP_NOMOR, d.ARSIP_TANGGAL, d.ARSIP_JUDUL, a.satuan_kerja_KEMBALI, a.satuan_kerja_CREATE 
				  FROM satuan_kerja a, ARSIP_satuan_kerja b, USER_APP c, ARSIP d
				 WHERE a.satuan_kerja_id = b.satuan_kerja_id
				   AND a.satuan_kerja_USER = c.USER_ID
				   AND b.ARSIP_ID = d.ARSIP_ID
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY d.ARSIP_TANGGAL DESC";
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsReportPenyerahan($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
				SELECT c.USER_ID, c.USER_NAMA, c.USER_JABATAN, a.HAPUS_TANGGAL, a.HAPUS_NAMAPEJABAT, 
					   a.HAPUS_PEJABAT, b.ARSIP_ID,
					   d.ARSIP_NOMOR, d.ARSIP_TANGGAL, d.ARSIP_JUDUL, a.DATE_CREATE
				  FROM HAPUS a, ARSIP_HAPUS b, USER_APP c, ARSIP d
				 WHERE a.HAPUS_ID = b.HAPUS_ID
				   AND a.USERAPP_ID = c.USER_ID
				   AND b.ARSIP_ID = d.ARSIP_ID   
				   AND a.HAPUS_STATUS = 'SERAHKAN'
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY d.ARSIP_TANGGAL ASC";
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
		
	  function selectByParamsReportPemusnahan($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
				SELECT c.USER_ID, c.USER_NAMA, c.USER_JABATAN, a.HAPUS_TANGGAL, a.HAPUS_NAMAPEJABAT, 
					   a.HAPUS_PEJABAT, b.ARSIP_ID,
					   d.ARSIP_NOMOR, d.ARSIP_TANGGAL, d.ARSIP_JUDUL, a.DATE_CREATE
				  FROM HAPUS a, ARSIP_HAPUS b, USER_APP c, ARSIP d
				 WHERE a.HAPUS_ID = b.HAPUS_ID
				   AND a.USERAPP_ID = c.USER_ID
				   AND b.ARSIP_ID = d.ARSIP_ID   
				   AND a.HAPUS_STATUS = 'MUSNAH'
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY d.ARSIP_TANGGAL ASC";
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
	 function selectByParamsReportTunjukSilang($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
				SELECT a.ARSIP_ID, c.KLASIFIKASI_KODE, c.KLASIFIKASI_NAMA,
 				       a.ARSIP_RINGKASAN, a.ARSIP_KATAKUNCI
				  FROM ARSIP a, KLASIFIKASI C
				 WHERE a.ARSIP_KLASIFIKASI = c.KLASIFIKASI_ID
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		//$str .= " ORDER BY d.ARSIP_TANGGAL ASC";
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
	 function selectByParamsReportTunjukSilangDetil($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
				SELECT c.KLASIFIKASI_KODE, c.KLASIFIKASI_NAMA
			  FROM ARSIP_KLASIFIKASI b, KLASIFIKASI c
			 WHERE b.KLASIFIKASI_ID = c.KLASIFIKASI_ID
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		//$str .= " ORDER BY d.ARSIP_TANGGAL ASC";
		 
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsReportKartuKendali($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
				SELECT a.ARSIP_ID, a.ARSIP_KODE, a.ARSIP_RINGKASAN, ORG_NAMA ARSIP_ORGANISASI, a.ARSIP_KOTA,
					   a.ARSIP_JABATAN, a.ARSIP_PEJABAT, a.ARSIP_TANGGAL, a.ARSIP_NOMOR,
					   a.ARSIP_CREATE, b.USER_NAMA, a.ARSIP_KATAKUNCI,
					   (SELECT COUNT(d.LAMP_ID) FROM ARSIP_LAMPIRAN d WHERE a.ARSIP_ID = d.ARSIP_ID) AS LAMPIRAN
				  FROM ARSIP a, USER_APP b, ORGANISASI c
				 WHERE a.ARSIP_AUTHOR = b.USER_ID AND  a.ARSIP_ORGANISASI = c.ORG_ID(+)
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		//$str .= " ORDER BY d.ARSIP_TANGGAL ASC";
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
	 function selectByParamsReportSusut($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
				SELECT c.USER_ID, c.USER_NAMA, c.USER_JABATAN, a.HAPUS_TANGGAL, a.HAPUS_NAMAPEJABAT, 
					   a.HAPUS_PEJABAT, b.ARSIP_ID,
					   d.ARSIP_NOMOR, d.ARSIP_TANGGAL, d.ARSIP_JUDUL, a.DATE_CREATE
				  FROM HAPUS a, ARSIP_HAPUS b, USER_APP c, ARSIP d
				 WHERE a.HAPUS_ID = b.HAPUS_ID
				   AND a.USERAPP_ID = c.USER_ID
				   AND b.ARSIP_ID = d.ARSIP_ID
				   AND a.HAPUS_STATUS = 'SUSUT'
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY d.ARSIP_TANGGAL ASC";
	
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $varStatement="")
	{
		$str = "SELECT 
				   satuan_kerja_id, nip, nama, 
				   satuan_kerja_id_parent, nama_pimpinan, satuan_kerja_KEPERLUAN, 
				   satuan_kerja_STATUS, satuan_kerja_USER, satuan_kerja_CREATE, 
				   satuan_kerja_SETUJU, satuan_kerja_TOLAK, satuan_kerja_SELESAI
				FROM satuan_kerja
				WHERE satuan_kerja_id IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $varStatement." ORDER BY nama ASC";
		//echo $str;		
		return $this->selectLimit($str,$limit,$from);
    }	
   
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(satuan_kerja_id) AS ROWCOUNT FROM satuan_kerja WHERE satuan_kerja_id IS NOT NULL "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

 function getCountArsipSatkerByParams($paramsArray=array())
	{
		$str = " 
				SELECT COUNT(c.KLASIFIKASI_ID) AS ROWCOUNT
				  FROM arsip_satuan_kerja b, satuan_kerja c
				 WHERE b.satuan_kerja_id = c.satuan_kerja_id AND c.KLASIFIKASI_ID IS NOT NULL " ;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		//echo $str;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }


    function getCountByParamsLike($paramsArray=array(), $varStatement="")
	{
		$str = "SELECT COUNT(satuan_kerja_id) AS ROWCOUNT FROM satuan_kerja WHERE satuan_kerja_id IS NOT NULL "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->select($str); 
		//echo $str;	
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getContentTitle($varCID)
	{
		$this->selectByParams(array('satuan_kerja_id' => $varCID));
		$this->firstRow();
		
		return $this->getField('nama');
	}
	
	function getContentText($varCID)
	{
		$this->selectByParams(array('CID' => $varCID));
		$this->firstRow();
		
		return $this->getField('nama_pimpinan');
	}
	
	function getContent($varCID)
	{
		$this->selectByParams(array('CID' => $varCID));
		$this->firstRow();
		
		return $this->getField('isi');
	}
	
  } 
?>