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
  include_once("../WEB/classes/base/Artikel.php");

  class ArtikelApproved extends Artikel{ 
    var $query;

    /************************** <STANDARD METHODS> **************************************/
    /**
    * Class constructor.
    * @author M Reza Faisal
    **/
    function ArtikelApproved(){
      /** !!DO NOT REMOVE/CHANGE CODES IN THIS SECTION!! **/
      $this->Artikel(); //execute Entity constructor
      /** YOU CAN INSERT/CHANGE CODES IN THIS SECTION **/				
			
	
    }

    /************************** </STANDARD METHODS> **********************************/

    /************************** <ADDITIONAL METHODS> *********************************/
	
	function selectApprovedByParamsLike($paramsArray=array(),$limit=-1,$from=-1,$varAKID="-1",$varStatement="")
	{
		$str = "SELECT a.ARID, 
					a.AKID, 
					a.UID, 
					a.tanggal, 
					a.judul, 
					a.isi, 
					a.status_approve,
					ak.AKID, ak.nama as ak_nama,
					u.UID, u.nama as u_nama, u.level
				FROM artikel a, artikel_kategori ak, users u 
				WHERE ARID IS NOT NULL 
					AND ak.AKID = a.AKID AND u.UID = a.UID
					AND a.status_approve = '1' ";
		
		if($varAKID !== "-1")
			$str .= " AND a.AKID = '$varAKID' "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= " ".$varStatement." ";
		$this->query = $str;
		
		$str .= " ORDER BY ARID DESC";
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountApprovedByParamsLike($paramsArray=array(),$varAKID="-1",$varStatement="")
	{
		$str = "SELECT COUNT(a.ARID) AS ROWCOUNT 
				FROM artikel a, artikel_kategori ak, users u 
				WHERE ARID IS NOT NULL 
					AND ak.AKID = a.AKID AND u.UID = a.UID
					AND a.status_approve = '1' ";
					
		if($varAKID !== "-1")
			$str .= " AND a.AKID = '$varAKID' "; 
	
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= " ".$varStatement." ";
		$this->select($str);
		 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	
	
	
	
	


    /************************** </ADDITIONAL METHODS> *******************************/
  } //end of class Users
?>