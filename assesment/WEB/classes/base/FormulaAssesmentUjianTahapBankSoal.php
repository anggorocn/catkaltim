<? 
include_once("../WEB/classes/db/Entity.php");

class FormulaAssesmentUjianTahapBankSoal extends Entity{ 

	var $query;
	function FormulaAssesmentUjianTahapBankSoal()
	{
		$this->Entity(); 
	}
	
	function getCountByParamsSimulasiBankSoal()
	{
		$str = "SELECT SIMULASIUJIANBANKSOAL(".$this->getField("FORMULA_ASSESMENT_ID").", '".$this->getField("LAST_CREATE_USER")."') AS ROWCOUNT ";
		$this->query = $str;
		// echo $str;exit;
		$this->select($str);
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return ""; 
    }
} 
?>