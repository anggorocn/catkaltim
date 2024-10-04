<? 
/* *******************************************************************************************************
MODUL NAME 			: SIMWEB
FILE NAME 			: Menu.php
AUTHOR				: MRF
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: Entity-base class for tabel Menu implementation
***************************************************************************************************** */

  /***
  * Entity-class untuk mengimplementasikan tabel menu.
  * 
  * @author M Reza Faisal
  * @generated by Entity Generator 5.8.3
  * @generated on 20-Apr-2005,20:28
  ***/
  include_once("../WEB/classes/base/MenuBase.php");

  class Menu extends MenuBase{ 

    /************************** <STANDARD METHODS> **************************************/
    /**
    * Class constructor.
    * @author M Reza Faisal
    **/
    function Menu(){
      /** !!DO NOT REMOVE/CHANGE CODES IN THIS SECTION!! **/
      $this->MenuBase(); //execute Entity constructor
      /** YOU CAN INSERT/CHANGE CODES IN THIS SECTION **/				
			
	
    }

    /************************** </STANDARD METHODS> **********************************/

    /************************** <ADDITIONAL METHODS> *********************************/
	function findByParent($id_induk){
		if(trim($id_induk)=="")
			$id_induk = "0";
		$str = "SELECT * FROM menu WHERE id_induk='$id_induk' ORDER BY urut_menu";
		return $this->select($str);
	}
	
	function selectUserGroup($paramsArray=array(),$limit=-1,$from=-1){
      $str = "SELECT menu.id_menu AS id_menu,
	  				 menu.id_induk AS id_induk,
					 menu.nama_menu AS nama_menu,
					 menu.caption AS caption,
					 menu.level AS level,
					 menu.urut_menu AS urut_menu,
					 menu.ket_menu AS ket_menu,
					 menu.link AS link,
					 menu.target AS target
	  		  FROM menu
			  WHERE id_menu IS NOT NULL "; 
      while(list($key,$val)=each($paramsArray)){
        $str .= " AND $key = '$val' ";
      }
      $str .= " ORDER BY urut_menu";
	  $this->query = $str;
      return $this->selectLimit($str,$limit,$from); 
    }

    /************************** </ADDITIONAL METHODS> *******************************/
  } //end of class Menu
?>