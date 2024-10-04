<? 
/* *******************************************************************************************************
MODUL NAME 			: SIMWEB
FILE NAME 			: Users.php
AUTHOR				: MRF
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: Entity-base class for tabel Users implementation
***************************************************************************************************** */

  /***
  * Entity-class untuk mengimplementasikan tabel users.
  * 
  * @author M Reza Faisal
  * @generated by Entity Generator 5.8.3
  * @generated on 21-Apr-2005,06:36
  ***/
  include_once("../WEB/classes/base/Informasi.php");

  class InformasiAktif extends Informasi{ 
    var $query;

    /************************** <STANDARD METHODS> **************************************/
    /**
    * Class constructor.
    * @author M Reza Faisal
    **/
    function InformasiAktif(){
      /** !!DO NOT REMOVE/CHANGE CODES IN THIS SECTION!! **/
      $this->Informasi(); //execute Entity constructor
      /** YOU CAN INSERT/CHANGE CODES IN THIS SECTION **/				
			
	
    }

    /************************** </STANDARD METHODS> **********************************/

    /************************** <ADDITIONAL METHODS> *********************************/
	
	function selectAktifByParams($paramsArray=array(),$limit=-1,$from=-1,$statusInformasi="0",$orderByRand=false)
	{
		$str = "SELECT i.INID, 
					i.UID, 
					i.nama as i_nama, 
					i.tanggal, 
					i.keterangan, 
					i.status_halaman_depan, 
					i.status_aktif, 
					i.status_informasi, 
					i.link_file,
				u.UID, u.nama as u_nama, u.level
				FROM informasi i, users u 
				WHERE INID IS NOT NULL 
					AND u.UID = i.UID 
					AND i.status_aktif = '1' ";
		
		if($statusInformasi !== "-1")
			$str .= " AND i.status_informasi = '$statusInformasi' "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		if($orderByRand == true)
			$str .= " ORDER BY RAND() ";
		else
			$str .= " ORDER BY i.status_halaman_depan DESC, INID DESC";
		
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountAktifByParams($paramsArray=array(),$statusInformasi="0")
	{
		$str = "SELECT COUNT(i.INID) AS ROWCOUNT 
				FROM informasi i 
				WHERE i.INID IS NOT NULL 
					AND i.status_aktif = '1' 
					AND i.status_informasi = '$statusInformasi'"; 
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

	function selectAktifByParamsLike($paramsArray=array(),$limit=-1,$from=-1,$statusInformasi="0",$varStatement="",$varOrder=" ORDER BY INID DESC")
	{
		$str = "SELECT i.INID, 
					i.UID, 
					i.nama as i_nama, 
					i.tanggal, 
					i.keterangan, 
					i.status_halaman_depan, 
					i.status_aktif, 
					i.status_informasi, 
					i.link_file,
				u.UID, u.nama as u_nama, u.level
				FROM informasi i, users u 
				WHERE INID IS NOT NULL 
					AND u.UID = i.UID 
					AND i.status_aktif = '1' 
					AND i.status_informasi = '$statusInformasi'"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= " ".$varStatement." ";
		$this->query = $str;
		$str .= " ".$varOrder;
				
		return $this->selectLimit($str,$limit,$from); 
    }	
	
	function getCountAktifByParamsLike($paramsArray=array(),$statusInformasi="0",$varStatement="")
	{
		$str = "SELECT COUNT(i.INID) AS ROWCOUNT 
				FROM informasi i 
				WHERE i.INID IS NOT NULL 
					AND i.status_aktif = '1' 
					AND i.status_informasi = '$statusInformasi'"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= " ".$varStatement." ";
		
		$this->query = $str;
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	
	
	
	function selectDistinctTahunAktif($statusInformasi="0")
	{
		$str = "SELECT 
                	DISTINCT(YEAR(tanggal)) AS year
               FROM informasi
			   WHERE INID IS NOT NULL 
					AND status_aktif = '1' 
					AND status_informasi = '$statusInformasi'";
		
		$str .= " ORDER BY tanggal DESC ";
		$this->query = $str;
		
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectDistinctBulanAktifByParams($paramsArray=array(),$limit=-1,$from=-1,$statusInformasi="0")
	{
		$str = "SELECT 
                	DISTINCT(MONTH(tanggal)) AS month
               FROM informasi
			   WHERE INID IS NOT NULL 
					AND status_aktif = '1' 
					AND status_informasi = '$statusInformasi'";

		while(list($key,$val)=each($paramsArray)){
			$str .= " AND $key = '$val' ";
		}
		$str .= " ORDER BY tanggal ASC ";
		
		$this->query = $str;
		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsStatement($paramsArray=array(),$limit=-1,$from=-1,$varStatement="")
	{
		$str = "SELECT i.INID, i.UID, i.nama as i_nama, i.tanggal, i.keterangan, i.status_halaman_depan, i.status_aktif, i.status_informasi, i.link_file,
				u.UID, u.nama as u_nama, u.level
				FROM informasi i, users u WHERE INID IS NOT NULL AND u.UID = i.UID"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$str .= " ".$varStatement;
		$this->query = $str;	
		return $this->selectLimit($str,$limit,$from); 
    }


    /************************** </ADDITIONAL METHODS> *******************************/
  } //end of class Users
?>