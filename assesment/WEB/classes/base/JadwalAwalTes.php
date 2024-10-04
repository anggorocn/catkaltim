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

class JadwalAwalTes extends Entity
{

	var $query;
	var $db;
	/**
	 * Class constructor.
	 **/
	function JadwalAwalTes()
	{
		//    $xmlfile = "../WEB/web.xml";
		// $data = simplexml_load_file($xmlfile);
		// $rconf_url_info= $data->urlConfig->main->urlbase;

		// $this->db=$rconf_url_info;
		$this->db = 'simpeg';
		$this->Entity();
	}

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JADWAL_AWAL_TES_ID", $this->getNextId("JADWAL_AWAL_TES_ID", "jadwal_awal_tes"));

		$str = "
		INSERT INTO jadwal_awal_tes 
		(
			JADWAL_AWAL_TES_ID, FORMULA_ESELON_ID, TANGGAL_TES, TANGGAL_TES_AKHIR
			, ACARA, TEMPAT, ALAMAT, KETERANGAN, STATUS_JENIS, LAST_CREATE_USER, LAST_CREATE_DATE
		) 
		VALUES 
		(
			" . $this->getField("JADWAL_AWAL_TES_ID") . ",
			" . $this->getField("FORMULA_ESELON_ID") . ",
			" . $this->getField("TANGGAL_TES") . ",
			" . $this->getField("TANGGAL_TES_AKHIR") . ",
			'" . $this->getField("ACARA") . "',
			'" . $this->getField("TEMPAT") . "',
			'" . $this->getField("ALAMAT") . "',
			'" . $this->getField("KETERANGAN") . "',
			" . $this->getField("STATUS_JENIS") . ",
			'" . $this->getField("LAST_CREATE_USER") . "',
			" . $this->getField("LAST_CREATE_DATE") . "
		)";
		$this->id = $this->getField("JADWAL_AWAL_TES_ID");
		$this->query = $str;
		return $this->execQuery($str);
	}

	function updateDinamis()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE " . $this->getField("TABLE") . "
				SET    
					   " . $this->getField("FIELD") . " = " . $this->getField("FIELD_VALUE") . "
				WHERE  " . $this->getField("FIELD_ID") . " = " . $this->getField("FIELD_ID_VALUE") . "
				";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE jadwal_awal_tes SET
			FORMULA_ESELON_ID= " . $this->getField("FORMULA_ESELON_ID") . ",
			TANGGAL_TES= " . $this->getField("TANGGAL_TES") . ",
			TANGGAL_TES_AKHIR= " . $this->getField("TANGGAL_TES_AKHIR") . ",
			ACARA= '" . $this->getField("ACARA") . "',
			TEMPAT= '" . $this->getField("TEMPAT") . "',
			ALAMAT= '" . $this->getField("ALAMAT") . "',
			KETERANGAN= '" . $this->getField("KETERANGAN") . "',
			STATUS_JENIS= " . $this->getField("STATUS_JENIS") . ",
			LAST_UPDATE_USER= '" . $this->getField("LAST_UPDATE_USER") . "',
			LAST_UPDATE_DATE= " . $this->getField("LAST_UPDATE_DATE") . "	
		WHERE JADWAL_AWAL_TES_ID = " . $this->getField("JADWAL_AWAL_TES_ID") . "
		";
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function delete()
	{
		$str = "
        DELETE FROM jadwal_awal_tes
        WHERE 
        JADWAL_AWAL_TES_ID = " . $this->getField("JADWAL_AWAL_TES_ID") . "";

		$this->query = $str;
		return $this->execQuery($str);
	}

	/** 
	 * Cari record berdasarkan array parameter dan limit tampilan 
	 * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
	 * @param int limit Jumlah maksimal record yang akan diambil 
	 * @param int from Awal record yang diambil 
	 * @return boolean True jika sukses, false jika tidak 
	 **/
	function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = '', $sOrder = "ORDER BY JADWAL_AWAL_TES_ID DESC")
	{
		$str = "
		SELECT
			JADWAL_AWAL_TES_ID, TANGGAL_TES, TANGGAL_TES_AKHIR
			, ACARA, TEMPAT, ALAMAT, KETERANGAN, STATUS_JENIS
		FROM jadwal_awal_tes 
		WHERE 1=1 ";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement . " " . $sOrder;
		$this->query = $str;

		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsFormulaEselon($paramsArray = array(), $limit = -1, $from = -1, $statement = '', $sOrder = "ORDER BY A.JADWAL_AWAL_TES_ID DESC")
	{
		$str = "
		SELECT A.JADWAL_AWAL_TES_ID, A.TANGGAL_TES, A.TANGGAL_TES_AKHIR, A.ACARA, A.TEMPAT, A.ALAMAT, A.KETERANGAN
		, A.FORMULA_ESELON_ID, D.FORMULA || ' untuk (' || COALESCE((C.NOTE || ' ' || C.NAMA), C.NAMA) || ')' NAMA_FORMULA_ESELON
		, A.STATUS_JENIS
		FROM jadwal_awal_tes A
		INNER JOIN formula_eselon B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID
		INNER JOIN eselon C ON C.ESELON_ID = B.ESELON_ID
		INNER JOIN formula_assesment D ON D.FORMULA_ID = B.FORMULA_ID
		WHERE 1=1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement . " " . $sOrder;
		$this->query = $str;

		return $this->selectLimit($str, $limit, $from);
	}

	/** 
	 * Hitung jumlah record berdasarkan parameter (array). 
	 * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
	 * @return long Jumlah record yang sesuai kriteria 
	 **/

	function getCountByParams($paramsArray = array(), $statement = '')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM jadwal_awal_tes WHERE 1=1 " . $statement;
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}
}
