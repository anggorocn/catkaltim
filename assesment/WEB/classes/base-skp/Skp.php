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

  class Skp extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function Skp()
	{
      $xmlfile = "../WEB/web.xml";
	  $data = simplexml_load_file($xmlfile);
	  $rconf_url_info= $data->urlConfig->main->urlskpbase;

	  $this->db=$rconf_url_info;
	  $this->Entity(); 
    }
	
	function selectByParamsPenilaianSkp($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
				select a.idskp_det, a.skp_bsc, a.nama_iku, a.t_sat_o, a.t_ko, 
				sum(b.r_ko) realisasi, sum(b.r_biaya) nilai, a.idskp_header, count(c.idunkerpeg), count(b.triwulan) 
				from ".$this->db.".skp_det a, ".$this->db.".skp_det_month b, ".$this->db.".unkerpeg c, ".$this->db.".unker_skp d, ".$this->db.".skp_header e
				where a.idskp_det = b.idskp_det
				and a.idskp_header = e.idskp_header
				and e.idunkerpeg = c.idunkerpeg
				and c.idunker = d.idunker
				and e.aktif = 'Y'
				and c.aktif = 'Y'
				"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement." group by a.idskp_det, a.skp_bsc, a.nama_iku, a.t_sat_o, a.t_ko, a.idskp_header ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
  } 
?>