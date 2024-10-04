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

  class Tes extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Tes()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JADWAL_KELOMPOK_RUANGAN_ID", $this->getNextId("JADWAL_KELOMPOK_RUANGAN_ID","jadwal_kelompok_ruangan")); 

		$str = "INSERT INTO jadwal_kelompok_ruangan (
				   JADWAL_KELOMPOK_RUANGAN_ID, JADWAL_TES_ID, JADWAL_ACARA_ID, KELOMPOK_ID, RUANGAN_ID, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
				  ".$this->getField("JADWAL_KELOMPOK_RUANGAN_ID").",
				  ".$this->getField("JADWAL_TES_ID").",
				  ".$this->getField("JADWAL_ACARA_ID").",
				  ".$this->getField("KELOMPOK_ID").",
				  ".$this->getField("RUANGAN_ID").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->id= $this->getField("JADWAL_KELOMPOK_RUANGAN_ID");
		$this->query= $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE jadwal_kelompok_ruangan SET
				  JADWAL_TES_ID= ".$this->getField("JADWAL_TES_ID").",
				  JADWAL_ACARA_ID= ".$this->getField("JADWAL_ACARA_ID").",
				  KELOMPOK_ID= ".$this->getField("KELOMPOK_ID").",
				  RUANGAN_ID= ".$this->getField("RUANGAN_ID").",
   			      LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
			      LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."	
				WHERE JADWAL_KELOMPOK_RUANGAN_ID = ".$this->getField("JADWAL_KELOMPOK_RUANGAN_ID")."
				"; 
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "select a.jadwal_tes_id, a.penggalian_id,count(1) total_atribut
		from jadwal_acara a
		inner join jadwal_tes jt on a.jadwal_tes_id = jt.jadwal_tes_id
		inner join formula_atribut fa on fa.formula_eselon_id = jt.formula_eselon_id
		where 1=1 
		and exists (select 1 from atribut_penggalian x where fa.formula_atribut_id = x.formula_atribut_id and a.penggalian_id = x.penggalian_id)
		group by a.jadwal_tes_id, a.penggalian_id
		order by a.jadwal_tes_id, a.penggalian_id"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsFormula($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "select formula_eselon_id,jadwal_tes_id from jadwal_tes "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	
    
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $jadwal_tes_id='',$penggalian_id='')
	{
		$str = "select count(1) as ROWCOUNT ,pegawai_id
		from
		(
			select jadwal_tes_id, penggalian_id, atribut_id, pegawai_id
			from jadwal_pegawai_detil
			where jadwal_tes_id = ".$jadwal_tes_id."
			and penggalian_id = ".$penggalian_id."
			group by jadwal_tes_id, penggalian_id, atribut_id, pegawai_id order by jadwal_tes_id, penggalian_id, atribut_id, pegawai_id
		) a
		where 1=1 
		group by pegawai_id ".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

     function getCountByParamsatribut($paramsArray=array(), $jadwal_tes_id='',$penggalian_id='')
	{
		$str = "
		select count(1) as ROWCOUNT ,pegawai_id
		from
		(
			select jadwal_tes_id, penggalian_id, atribut_id, pegawai_id
			from jadwal_pegawai_detil_atribut
			where jadwal_tes_id = ".$jadwal_tes_id."
			and penggalian_id = ".$penggalian_id."
			and coalesce(nullif(catatan, ''), null) is not null and catatan != '<br>' and nilai != 0
			group by jadwal_tes_id, penggalian_id, atribut_id, pegawai_id order by jadwal_tes_id, penggalian_id, atribut_id, pegawai_id
		) a
		where 1=1 group by pegawai_id
		 ".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }


    function getCountByParamsPotensi($paramsArray=array(), $vformulaeselonid='', $vjadwaltesId='')
	{
		$str = "select count(1) ROWCOUNT
		from atribut a
		left join
		(
			select a.formula_atribut_id, a.level_id, b.atribut_id, a.nilai_standar, form_permen_id
			from formula_atribut a
			inner join level_atribut b on a.level_id = b.level_id
			where 1=1 and a.formula_eselon_id = ".$vformulaeselonid."
		) b on a.atribut_id = b.atribut_id
		left join
		(
			select a.aspek_id, a.atribut_id, a.atribut_nilai_standar, a.atribut_bobot, a.atribut_skor
			, a.formula_atribut_bobot_id
			from formula_atribut_bobot a
			where 1=1 and a.formula_eselon_id = ".$vformulaeselonid."
		) c on a.atribut_id = c.atribut_id
		where 1=1
		and form_permen_id = permen_id
		and a.aspek_id = 1 ".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }


    function getCountByParamsPotensiAtribut($paramsArray=array(), $vformulaeselonid='', $vjadwaltesId='')
	{
		$str = "
		select count(1) as ROWCOUNT
		from penilaian_detil
		where 1=1 and penilaian_id in
		(
			select penilaian_id from penilaian where aspek_id = 1  and jadwal_tes_id =  ".$vjadwaltesId."
		)
		and gap is not null ".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function getCountByParamsPotensiDeskripsi($paramsArray=array(), $vformulaeselonid='', $vjadwaltesId='')
	{
		$str = "
		select count(1) as ROWCOUNT
		from penilaian_detil where penilaian_detil_id in
		(
			select
			a.penilaian_detil_id
			from penilaian_detil a
			left join formula_assesment_atribut_urutan fut on fut.formula_eselon_id = a.formula_eselon_id and fut.atribut_id = a.atribut_id and fut.permen_id = a.permen_id
			where 1=1 and a.penilaian_id in
			(
				select penilaian_id from penilaian where aspek_id = 1  and jadwal_tes_id =  ".$vjadwaltesId."
			)
			order by coalesce(fut.urut,0) desc, a.atribut_id
			limit 1
		)
		and coalesce(nullif(bukti, ''), null) is not null and bukti != '<br>' ".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }


    function getCountByParamCheck($paramsArray=array(), $vjadwaltesid='', $vpenggalianid='', $pegawaiid='')
	{
		$str = "
		select count(1) as ROWCOUNT
		from jadwal_penggalian_pegawai where jadwal_tes_id = ".$vjadwaltesid." and penggalian_id = ".$vpenggalianid." and pegawai_id =".$pegawaiid."  
		".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	
	
  } 
?>