<? 
/* *******************************************************************************************************
MODUL NAME 			: MTSN LAWANG
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

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
        //    $xmlfile = "../WEB/web.xml";
	  // $data = simplexml_load_file($xmlfile);
	  // $rconf_url_info= $data->urlConfig->main->urlbase;

	  // $this->db=$rconf_url_info;
	  $this->db='simpeg';
	  $this->Entity();   
    }
	
	function insert()
	{
		
		/*Auto-generate primary key(s) by next max value (integer) */
		//$this->setField("SATKER_ID", getMaxIdTree($this->getField("SATKER_ID_PARENT"))); 

		$str = "INSERT INTO SATKER (
				   SATKER_ID, PROPINSI_ID, KABUPATEN_ID, 
				   KECAMATAN_ID, KELURAHAN_ID, SATKER_ID_PARENT, 
				   KODE, NAMA, SIFAT, 
				   ALAMAT, TELEPON, FAXIMILE, 
				   KODEPOS, EMAIL, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_CREATE_SATKER) 
				VALUES (
				  '".$this->getField("SATKER_ID")."',
				  '".$this->getField("PROPINSI_ID")."',
				  '".$this->getField("KABUPATEN_ID")."',
				  '".$this->getField("KECAMATAN_ID")."',
				  '".$this->getField("KELURAHAN_ID")."',
				  '".$this->getField("SATKER_ID_PARENT")."',
				  '".$this->getField("KODE")."',
				  '".$this->getField("NAMA")."',
				  '".$this->getField("SIFAT")."',
				  '".$this->getField("ALAMAT")."',
				  '".$this->getField("TELEPON")."',
				  '".$this->getField("FAXIMILE")."',
				  '".$this->getField("KODEPOS")."',
				  '".$this->getField("EMAIL")."',				 
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_SATKER")."'
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function insertNama()
	{
		
		/*Auto-generate primary key(s) by next max value (integer) */
		//$this->setField("SATKER_ID", getMaxIdTree($this->getField("SATKER_ID_PARENT"))); 

		$str = "INSERT INTO satker (
				   SATKER_ID, SATKER_ID_PARENT, NAMA) 
				VALUES (
				  '".$this->getField("SATKER_ID")."',
				  '".$this->getField("SATKER_ID_PARENT")."',
				  '".$this->getField("NAMA")."'
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }


    function insertmaster()
	{
		
		/*Auto-generate primary key(s) by next max value (integer) */
		//$this->setField("SATKER_ID", getMaxIdTree($this->getField("SATKER_ID_PARENT"))); 
		$this->setField("SATKER_ID", $this->getNextId("SATKER_ID","simpeg.satker"));

		$str = "INSERT INTO simpeg.SATKER (
				   SATKER_ID, SATKER_ID_PARENT,NAMA) 
				VALUES (
				   (SELECT simpeg.satker_generate('".$this->getField("SATKER_ID_PARENT")."')),
				  '".$this->getField("SATKER_ID_PARENT")."',
				  '".$this->getField("NAMA")."'
				 
				)"; 
				
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function inserteksternal()
	{
		
		/*Auto-generate primary key(s) by next max value (integer) */
		//$this->setField("SATKER_ID", getMaxIdTree($this->getField("SATKER_ID_PARENT"))); 
		$this->setField("SATKER_EKSTERNAL_ID", $this->getNextId("SATKER_EKSTERNAL_ID","simpeg.satker_eksternal"));

		$str = "INSERT INTO simpeg.SATKER_EKSTERNAL (
				   SATKER_EKSTERNAL_ID, SATKER_EKSTERNAL_ID_PARENT,NAMA) 
				VALUES (
				   (SELECT simpeg.satker_eksternal_generate('".$this->getField("SATKER_EKSTERNAL_ID_PARENT")."')),
				  '".$this->getField("SATKER_EKSTERNAL_ID_PARENT")."',
				  '".$this->getField("NAMA")."'
				 
				)"; 
				
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	

    function update($var="")
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE SATKER
				SET    
					";
		if($var == 'alamat'){
		$str .="
					   PROPINSI_ID       = '".$this->getField("PROPINSI_ID")."',
					   KABUPATEN_ID    = '".$this->getField("KABUPATEN_ID")."',
					   KECAMATAN_ID             = '".$this->getField("KECAMATAN_ID")."',
					   KELURAHAN_ID     = '".$this->getField("KELURAHAN_ID")."',					   
					   ALAMAT        = '".$this->getField("ALAMAT")."',
					   TELEPON       = '".$this->getField("TELEPON")."',
					   FAXIMILE      = '".$this->getField("FAXIMILE")."',
					   KODEPOS   = '".$this->getField("KODEPOS")."',
					   EMAIL             = '".$this->getField("EMAIL")."',
					  LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
					  LAST_UPDATE_DATE	= ".$this->getField("LAST_UPDATE_DATE").",
					  LAST_UPDATE_SATKER	= '".$this->getField("LAST_UPDATE_SATKER")."'
		";
		}
		if($var == 'satker'){
		$str .="		
					   NAMA  = '".$this->getField("NAMA")."',
					   SIFAT = '".$this->getField("SIFAT")."',
					   ESELON_ID = '".$this->getField("ESELON_ID")."',
					   PANGKAT_ID = '".$this->getField("PANGKAT_ID")."',
					   PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."',
					  LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
					  LAST_UPDATE_DATE	= ".$this->getField("LAST_UPDATE_DATE").",
					  LAST_UPDATE_SATKER	= '".$this->getField("LAST_UPDATE_SATKER")."'
					   
		";
		}
		
		$str .="
				WHERE  SATKER_ID          = '".$this->getField("SATKER_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    } 
	function updateLokasiKerja()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE SATKER
				SET    
					   PROPINSI_ID       = '".$this->getField("PROPINSI_ID")."',
					   KABUPATEN_ID    = '".$this->getField("KABUPATEN_ID")."',
					   KECAMATAN_ID             = '".$this->getField("KECAMATAN_ID")."',
					   KELURAHAN_ID     = '".$this->getField("KELURAHAN_ID")."',				   				
					   ALAMAT        = '".$this->getField("ALAMAT")."',
					   TELEPON       = '".$this->getField("TELEPON")."',
					   FAXIMILE      = '".$this->getField("FAXIMILE")."',
					   KODEPOS   = '".$this->getField("KODEPOS")."',
					   EMAIL             = '".$this->getField("EMAIL")."',
					  LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
					  LAST_UPDATE_DATE	= ".$this->getField("LAST_UPDATE_DATE").",
					  LAST_UPDATE_SATKER	= '".$this->getField("LAST_UPDATE_SATKER")."'
				WHERE  SATKER_ID          = '".$this->getField("SATKER_ID")."'
				"; 
		//echo $str;
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateeksternal()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE simpeg.satker_eksternal
				SET
				   SATKER_EKSTERNAL_ID= '".$this->getField("SATKER_EKSTERNAL_ID")."',
				   NAMA= '".$this->getField("NAMA")."'
				WHERE SATKER_EKSTERNAL_ID= '".$this->getField("SATKER_EKSTERNAL_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	
	function delete()
	{
        $str = "DELETE FROM satker
                WHERE 
                  SATKER_ID = '".$this->getField("SATKER_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function deleteeksternal()
	{
        $str = "DELETE FROM simpeg.satker_eksternal
                WHERE 
                  SATKER_EKSTERNAL_ID = '".$this->getField("SATKER_EKSTERNAL_ID")."'"; 
				  
		$this->query = $str;
		// echo $str;exit;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","KECAMATAN_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	function selectByParamsPejabat($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT  C.ESELON_ID, A.PEGAWAI_ID, NIP_LAMA, AMBIL_FORMAT_NIP_BARU(NIP_BARU) NIP_BARU, GELAR_DEPAN ||  DECODE(GELAR_DEPAN, NULL, '', ' ') || A.NAMA || DECODE(GELAR_BELAKANG, NULL, '', ' ') || GELAR_BELAKANG NAMA,
                        B.GOL_RUANG,
                        TO_CHAR(B.TMT_PANGKAT, 'DD MON YYYY') TMT_PANGKAT,
                        C.ESELON,
						B.PANGKAT_ID,
						C.ESELON_ID,
                        C.JABATAN,
                        TO_CHAR(C.TMT_JABATAN, 'DD-MM-YYYY') TMT_JABATAN,
						A.SATKER_ID
                FROM pegawai A,  
                     (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM PANGKAT_TERAKHIR) B,
                     (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, NVL(ESELON_ID, 99) ESELON_ID FROM JABATAN_TERAKHIR) C,
                     (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN FROM PENDIDIKAN_TERAKHIR X) F
                WHERE
                     A.PEGAWAI_ID = B.PEGAWAI_ID(+) AND
                     A.PEGAWAI_ID = C.PEGAWAI_ID(+) AND
                     A.PEGAWAI_ID = F.PEGAWAI_ID(+) AND
        			 A.STATUS_PEGAWAI IN (1,2) "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
				
		$str .= $statement." ";
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="order by id_tree asc")
	{
		$str = "
				SELECT ID_TREE, PARENT_TREE, 
                   KODE_UNKER, NAMA_UNKER, 
                   ALAMAT_KANTOR, TELP, 
                   ESELON,  NAMA_JABATAN
                FROM ".$this->db.".rb_ref_unker 
                WHERE ID_TREE IS NOT NULL
				";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement." ".$sOrder;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="order by a.id_tree asc")
	{
		$str = "
				SELECT 
					  A.ID_TREE AS ID,
						PARENT_TREE AS PARENT_ID,
						NAMA_UNKER NAMA,
						KODE_UNKER KODE,
						A.ID_TREE AS ID_TABLE,
						PARENT_TREE AS ID_TABLE_PARENT,
					  CONCAT_WS(' ',
						CASE
							WHEN COALESCE(B.JUMLAH_SUB, 0) > 0 THEN
								'<img src=\"../WEB/images/tree-satker.png\"> '
							ELSE '<img src=\"../WEB/images/tree-subsatker.png\"> '
						END, NAMA_UNKER) AS NAMA_WARNA,
						ALAMAT_KANTOR, TELP, 
						ESELON,  NAMA_JABATAN
				FROM ".$this->db.".rb_ref_unker A
				LEFT JOIN 
				(SELECT COUNT(1) JUMLAH_SUB, PARENT_TREE ID_TREE FROM ".$this->db.".rb_ref_unker GROUP BY PARENT_TREE) B ON A.ID_TREE = B.ID_TREE
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPegawaiSatkerId($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
				SELECT AMBIL_SATKER_ID_DYNAMIC(SATKER_ID) SATKER_ID, A.PEGAWAI_ID
                FROM pegawai A                
                WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY A.PEGAWAI_ID ASC";
		$this->query = $str;
		
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectMaxIdSatker($satker_id)
	{
		$str = "SELECT SATKER_GENERATE('".$satker_id."') LASTID
				FROM DUAL";
		$this->select($str); 
		//echo $str;
		if($this->firstRow()) 
			return $this->getField("LASTID"); 
		else 
			return $satker_id."01"; 
	}
	function selectByParamsEdit($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT A.SATKER_ID, A.PROPINSI_ID, A.KABUPATEN_ID, 
				   AMBIL_PROPINSI(A.PROPINSI_ID) NMPROPINSI,
				   AMBIL_KABUPATEN(A.PROPINSI_ID, A.KABUPATEN_ID) NMKABUPATEN,
				   AMBIL_KECAMATAN(A.PROPINSI_ID, A.KABUPATEN_ID, A.KECAMATAN_ID) NMKECAMATAN,
				   AMBIL_KELURAHAN(A.PROPINSI_ID, A.KABUPATEN_ID, A.KECAMATAN_ID, B.KELURAHAN_ID) NMKELURAHAN,
				   AMBIL_SATKER(A.SATKER_ID) NMSATKER,
				   AMBIL_SATKER_NAMA_DYNAMIC(A.SATKER_ID) NMSATKERDETIL,
                   A.KECAMATAN_ID, A.KELURAHAN_ID, A.SATKER_ID_PARENT, 
                   A.KODE, A.NAMA, SIFAT, 
                   A.ALAMAT, A.TELEPON, A.FAXIMILE, 
                   A.KODEPOS, A.EMAIL
                FROM satker A
                JOIN PEGAWAI B ON A.SATKER_ID = B.SATKER_ID 
                WHERE A.SATKER_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY SATKER_ID ASC";
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT *
				FROM simpeg.satker"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY satker_id ASC";
		// echo $str; exit;
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","KECAMATAN_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParamsMonitoring($paramsArray=array())
	{
		$str = "
			SELECT COUNT(1) AS ROWCOUNT FROM
			(
				SELECT 
					  a.id_tree AS id,
						parent_tree AS parent_id,
						nama_unker nama,
						kode_unker kode,
						a.id_tree AS id_table,
						parent_tree AS id_table_parent,
					  CONCAT_WS(' ',
						CASE
							WHEN COALESCE(b.jumlah_sub, 0) > 0 THEN
								'<img src=\"../WEB/images/tree-satker.png\"> '
							ELSE '<img src=\"../WEB/images/tree-subsatker.png\"> '
						END, nama_unker) AS nama_warna,
						alamat_kantor, telp, 
						eselon,  NAMA_JABATAN
				FROM ".$this->db.".rb_ref_unker a
				LEFT JOIN 
				(select count(1) jumlah_sub, parent_tree id_tree from ".$this->db.".rb_ref_unker GROUP BY parent_tree) b on a.id_tree = b.id_tree
				WHERE 1=1
			"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str.= ") A";
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM ".$this->db.".rb_ref_unker WHERE 1=1 "; 
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

    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(SATKER_ID) AS ROWCOUNT FROM satker WHERE SATKER_ID IS NOT NULL "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	
	
	function getMaxIdTree($satker_id)
	{
		$str = "SELECT SATKER_GENERATE('".$satker_id."') ROWCOUNT FROM DUAL"; 

		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }


    function selectByParamsSatuanKerjaEkternal($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="order by A.NAMA asc")
	{
		$str = "
				SELECT A.SATKER_EKSTERNAL_ID AS ID, A.SATKER_EKSTERNAL_ID_PARENT AS PARENT_ID, A.NAMA
				, '' KODE, SATKER_EKSTERNAL_ID AS ID_TABLE, A.SATKER_EKSTERNAL_ID_PARENT AS ID_TABLE_PARENT
				, CASE A.SATKER_EKSTERNAL_ID_PARENT
					WHEN '0'
					THEN
					'<a onClick=\"window.OpenDHTMLPopUp(''satker_eksternal_add.php?reqSatkerParentId=' || A.SATKER_EKSTERNAL_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a> - <a onClick=\"window.OpenDHTMLPopUp(''satker_eksternal_add.php?reqSatkerId=' || A.SATKER_EKSTERNAL_ID || ''')\"><img src=\"../WEB/images/icn_edit.png\"></a> - <a onClick=\"if(confirm(''Apakah anda yakin ingin menghapus data ini?'')) { window.location.href = ''satker_eksternal.php?reqMode=delete&reqId=' || A.SATKER_EKSTERNAL_ID || '''}\"><img src=\"../WEB/images/icn_delete.png\"></a>'
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''satker_eksternal_add.php?reqSatkerParentId=' || A.SATKER_EKSTERNAL_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a> - <a onClick=\"window.OpenDHTMLPopUp(''satker_eksternal_add.php?reqSatkerId=' || A.SATKER_EKSTERNAL_ID || ''')\"><img src=\"../WEB/images/icn_edit.png\"></a> - <a onClick=\"if(confirm(''Apakah anda yakin ingin menghapus data ini?'')) { window.location.href = ''satker_eksternal.php?reqMode=delete&reqId=' || A.SATKER_EKSTERNAL_ID || '''}\"><img src=\"../WEB/images/icn_delete.png\"></a>'
					END
					LINK_URL,
				'' ALAMAT_KANTOR, '' TELP, '' ESELON, '' NAMA_JABATAN
				FROM  ".$this->db.".satker_eksternal A
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsSatuanKerjaEksternal($paramsArray=array(), $statement='')
	{
		$str = "
			SELECT COUNT(1) AS ROWCOUNT FROM
			(
				SELECT A.SATKER_ID AS ID, A.SATKER_ID_PARENT AS PARENT_ID, A.NAMA
				, '' KODE, SATKER_ID AS ID_TABLE, A.SATKER_ID_PARENT AS ID_TABLE_PARENT,
				CASE
					WHEN A.SATKER_ID_PARENT = '0' THEN
						'<img src=\"../WEB/images/tree-satker.png\"> '
					ELSE '<img src=\"../WEB/images/tree-subsatker.png\"> '
				END || ' ' || A.NAMA AS NAMA_WARNA,
				'' ALAMAT_KANTOR, '' TELP, '' ESELON, '' NAMA_JABATAN
				FROM ".$this->db.".satker_eksternal A
				WHERE 1=1
			"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ";
		$str.= ") A";
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }				
  } 
?>