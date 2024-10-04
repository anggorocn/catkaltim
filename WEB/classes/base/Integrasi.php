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

  class Integrasi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Integrasi()
	{
      $this->Entity(); 
    }
	
    function selectByParamsPelamar($statement="", $tahun="")
	{
		$str = "
				SELECT A.BULAN, COALESCE(JUMLAH, 0) JUMLAH  FROM
                (SELECT ROW_NUMBER() OVER () AS BULAN
                           FROM PDS_SIMPEG.PEGAWAI
                         LIMIT 12) A
                LEFT JOIN 
                (
                SELECT TO_CHAR(C.LAST_CREATE_DATE, 'MM')::INTEGER BULAN, COUNT(1) JUMLAH
                FROM pds_rekrutmen.PELAMAR C
                WHERE 1=1 AND TO_CHAR(C.LAST_CREATE_DATE, 'YYYY') = '".$tahun."'
                GROUP BY TO_CHAR(C.LAST_CREATE_DATE, 'MM')::INTEGER
                ) B ON A.BULAN = B.BULAN
				"; 
		
		
		$str .= " ORDER BY BULAN ";
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }

    function selectByParamsPeserta($statement="", $tahun="")
	{
		$str = "
				SELECT A.BULAN, COALESCE(JUMLAH, 0) JUMLAH  FROM
                (SELECT ROW_NUMBER() OVER () AS BULAN
                           FROM PDS_SIMPEG.PEGAWAI
                         LIMIT 12) A
                LEFT JOIN 
                (
                SELECT TO_CHAR(C.TANGGAL_KIRIM, 'MM')::INTEGER BULAN, COUNT(1) JUMLAH
                FROM pds_rekrutmen.PELAMAR_LOWONGAN C
                WHERE 1=1 AND TO_CHAR(C.TANGGAL_KIRIM, 'YYYY') = '".$tahun."'
                GROUP BY TO_CHAR(C.TANGGAL_KIRIM, 'MM')::INTEGER
                ) B ON A.BULAN = B.BULAN
				"; 
		
		
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }

    function selectByParamsPelamarPosisi($statement="", $tahun="")
	{
		$str = "
				SELECT X.NAMA NAMA_JABATAN, COALESCE(JUMLAH, 0) JUMLAH  FROM
                (
				SELECT NAMA FROM pds_rekrutmen.LOWONGAN A
					INNER JOIN pds_rekrutmen.JABATAN B ON B.JABATAN_ID = A.JABATAN_ID
				GROUP BY NAMA
				) X
                LEFT JOIN 
                (
                SELECT NAMA, COUNT(1) JUMLAH FROM pds_rekrutmen.LOWONGAN C 
				INNER JOIN pds_rekrutmen.JABATAN D ON D.JABATAN_ID = C.JABATAN_ID
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN E ON E.LOWONGAN_ID = C.LOWONGAN_ID AND E.TANGGAL_KIRIM IS NOT NULL
                WHERE 1=1 AND TO_CHAR(C.TANGGAL, 'YYYY') = '".$tahun."'
                GROUP BY NAMA
                ) Y ON X.NAMA = Y.NAMA
                ORDER BY X.NAMA
				"; 
		
		
		$str .= "  ";
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }

	function getCountByParamsPelamarPosisi($statement="", $tahun="")
	{
		$str = "SELECT
					SUM (1) JUMLAH
				FROM
					pds_rekrutmen.LOWONGAN A
				INNER JOIN pds_rekrutmen.JABATAN B ON B.JABATAN_ID = A.JABATAN_ID
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN C ON C.LOWONGAN_ID = A.LOWONGAN_ID
				AND C.TANGGAL_KIRIM IS NOT NULL
				WHERE
					1 = 1
				AND TO_CHAR(A .TANGGAL, 'YYYY') = '".$tahun."'
				".$statement;
		
		$this->select($str);
		$this->query = $str; 
	
		if($this->firstRow()) 
			return $this->getField("JUMLAH"); 
		else 
			return 0; 
    }
			
    function selectByParamsVisitor($statement="", $tahun="")
	{
		$str = "SELECT A.BULAN, COALESCE(JUMLAH, 0) JUMLAH  FROM
                (SELECT ROW_NUMBER() OVER () AS BULAN
                           FROM PDS_SIMPEG.PEGAWAI
                         LIMIT 12) A
                LEFT JOIN 
                (
                SELECT TO_CHAR(C.TANGGAL, 'MM')::INTEGER BULAN, sum(HITS::INTEGER) JUMLAH
                FROM pds_rekrutmen.visitor C
                WHERE 1=1 AND TO_CHAR(C.TANGGAL, 'YYYY') = '".$tahun."'
                GROUP BY TO_CHAR(C.TANGGAL, 'MM')::INTEGER
                ) B ON A.BULAN = B.BULAN
				"; 
		
		
		$str .= " ORDER BY BULAN ";
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }

    function selectByParamsPendidikan($statement)
	{
		$str = "SELECT A.PENDIDIKAN_ID, NAMA, COALESCE(SUM(JUMLAH), 0) TOTAL, COALESCE(SUM(CASE WHEN KATEGORI = 'PS' THEN JUMLAH END), 0) JUMLAH_PS,
				COALESCE(SUM(CASE WHEN KATEGORI = 'OPS' THEN JUMLAH END), 0) JUMLAH_OPS,
				COALESCE(SUM(CASE WHEN KATEGORI = 'PBR' THEN JUMLAH END), 0) JUMLAH_PBR,
				COALESCE(SUM(CASE WHEN COALESCE(KATEGORI, '') = '' THEN JUMLAH END), 0) JUMLAH_INTERNAL
				FROM pds_rekrutmen.PENDIDIKAN A 
				LEFT JOIN (
									SELECT  B.PENDIDIKAN_ID, C.KATEGORI, COUNT(1) JUMLAH
									FROM pds_simpeg.PEGAWAI A 
									LEFT JOIN (SELECT PEGAWAI_ID, PENDIDIKAN_ID FROM pds_simpeg.PEGAWAI_PENDIDIKAN) B ON A.PEGAWAI_ID = B.PEGAWAI_ID 
									LEFT JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR C ON A.PEGAWAI_ID = C.PEGAWAI_ID
									WHERE 1 = 1 ".$statement."
									GROUP BY B.PENDIDIKAN_ID, C.KATEGORI
								) B ON A.PENDIDIKAN_ID = B.PENDIDIKAN_ID
				WHERE A.PENDIDIKAN_ID IS NOT NULL 
			GROUP BY A.PENDIDIKAN_ID, NAMA	   
				"; 
		
		
		$str .= " ORDER BY A.PENDIDIKAN_ID::NUMERIC ASC ";
		$this->query = $str;
		
		return $this->selectLimit($str,-1,-1); 
    }

    function selectByParamsUsia($statement)
	{
		$str = "
			SELECT '30' IDKETERANGAN, '<= 30' NAMA, COALESCE(SUM(JUMLAH), 0) TOTAL, COALESCE(SUM(CASE WHEN KATEGORI = 'PS' THEN JUMLAH END), 0) JUMLAH_PS,
				COALESCE(SUM(CASE WHEN KATEGORI = 'OPS' THEN JUMLAH END), 0) JUMLAH_OPS,
				COALESCE(SUM(CASE WHEN KATEGORI = 'PBR' THEN JUMLAH END), 0) JUMLAH_PBR,
				COALESCE(SUM(CASE WHEN COALESCE(KATEGORI, '') = '' THEN JUMLAH END), 0) JUMLAH_INTERNAL
							FROM (
								SELECT KATEGORI, COUNT(A.PEGAWAI_ID) JUMLAH
								FROM pds_simpeg.PEGAWAI  A, (
									SELECT X.PEGAWAI_ID, REPLACE(pds_rekrutmen.AMBIL_MASA_KERJA(TANGGAL_LAHIR, CURRENT_DATE), ',', '.')::NUMERIC AS USIA, KATEGORI
										FROM pds_simpeg.PEGAWAI  X LEFT JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR Y ON X.PEGAWAI_ID = Y.PEGAWAI_ID
									) B
								WHERE 1 = 1 AND A.PEGAWAI_ID = B.PEGAWAI_ID AND B.USIA < 31::NUMERIC ".$statement."
								GROUP BY KATEGORI
							) Y   
			UNION ALL				
			SELECT '3135' IDKETERANGAN, '31 - 35' NAMA, COALESCE(SUM(JUMLAH), 0) TOTAL, COALESCE(SUM(CASE WHEN KATEGORI = 'PS' THEN JUMLAH END), 0) JUMLAH_PS,
				COALESCE(SUM(CASE WHEN KATEGORI = 'OPS' THEN JUMLAH END), 0) JUMLAH_OPS,
				COALESCE(SUM(CASE WHEN KATEGORI = 'PBR' THEN JUMLAH END), 0) JUMLAH_PBR,
				COALESCE(SUM(CASE WHEN COALESCE(KATEGORI, '') = '' THEN JUMLAH END), 0) JUMLAH_INTERNAL
							FROM (
								SELECT KATEGORI, COUNT(A.PEGAWAI_ID) JUMLAH
								FROM pds_simpeg.PEGAWAI  A, (
									SELECT X.PEGAWAI_ID, REPLACE(pds_rekrutmen.AMBIL_MASA_KERJA(TANGGAL_LAHIR, CURRENT_DATE), ',', '.')::NUMERIC AS USIA, KATEGORI
										FROM pds_simpeg.PEGAWAI  X LEFT JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR Y ON X.PEGAWAI_ID = Y.PEGAWAI_ID
									) B
								WHERE 1 = 1 AND A.PEGAWAI_ID = B.PEGAWAI_ID AND B.USIA >= 31::NUMERIC AND B.USIA < 36::NUMERIC ".$statement."
								GROUP BY KATEGORI
							) Y     
			UNION ALL				
			SELECT '3640' IDKETERANGAN, '36 - 40' NAMA, COALESCE(SUM(JUMLAH), 0) TOTAL, COALESCE(SUM(CASE WHEN KATEGORI = 'PS' THEN JUMLAH END), 0) JUMLAH_PS,
				COALESCE(SUM(CASE WHEN KATEGORI = 'OPS' THEN JUMLAH END), 0) JUMLAH_OPS,
				COALESCE(SUM(CASE WHEN KATEGORI = 'PBR' THEN JUMLAH END), 0) JUMLAH_PBR,
				COALESCE(SUM(CASE WHEN COALESCE(KATEGORI, '') = '' THEN JUMLAH END), 0) JUMLAH_INTERNAL
							FROM (
								SELECT KATEGORI, COUNT(A.PEGAWAI_ID) JUMLAH
								FROM pds_simpeg.PEGAWAI  A, (
									SELECT X.PEGAWAI_ID, REPLACE(pds_rekrutmen.AMBIL_MASA_KERJA(TANGGAL_LAHIR, CURRENT_DATE), ',', '.')::NUMERIC AS USIA, KATEGORI
										FROM pds_simpeg.PEGAWAI  X LEFT JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR Y ON X.PEGAWAI_ID = Y.PEGAWAI_ID
									) B
								WHERE 1 = 1 AND A.PEGAWAI_ID = B.PEGAWAI_ID AND B.USIA >= 36::NUMERIC AND B.USIA < 41::NUMERIC ".$statement."
								GROUP BY KATEGORI
							) Y   
			UNION ALL				
			SELECT '4145' IDKETERANGAN, '41 - 45' NAMA, COALESCE(SUM(JUMLAH), 0) TOTAL, COALESCE(SUM(CASE WHEN KATEGORI = 'PS' THEN JUMLAH END), 0) JUMLAH_PS,
				COALESCE(SUM(CASE WHEN KATEGORI = 'OPS' THEN JUMLAH END), 0) JUMLAH_OPS,
				COALESCE(SUM(CASE WHEN KATEGORI = 'PBR' THEN JUMLAH END), 0) JUMLAH_PBR,
				COALESCE(SUM(CASE WHEN COALESCE(KATEGORI, '') = '' THEN JUMLAH END), 0) JUMLAH_INTERNAL
							FROM (
								SELECT KATEGORI, COUNT(A.PEGAWAI_ID) JUMLAH
								FROM pds_simpeg.PEGAWAI  A, (
									SELECT X.PEGAWAI_ID, REPLACE(pds_rekrutmen.AMBIL_MASA_KERJA(TANGGAL_LAHIR, CURRENT_DATE), ',', '.')::NUMERIC AS USIA, KATEGORI
										FROM pds_simpeg.PEGAWAI  X LEFT JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR Y ON X.PEGAWAI_ID = Y.PEGAWAI_ID
									) B
								WHERE 1 = 1 AND A.PEGAWAI_ID = B.PEGAWAI_ID AND B.USIA >= 41::NUMERIC AND B.USIA < 46::NUMERIC ".$statement."
								GROUP BY KATEGORI
							) Y    
			UNION ALL				
			SELECT '4650' IDKETERANGAN, '46 - 50' NAMA, COALESCE(SUM(JUMLAH), 0) TOTAL, COALESCE(SUM(CASE WHEN KATEGORI = 'PS' THEN JUMLAH END), 0) JUMLAH_PS,
				COALESCE(SUM(CASE WHEN KATEGORI = 'OPS' THEN JUMLAH END), 0) JUMLAH_OPS,
				COALESCE(SUM(CASE WHEN KATEGORI = 'PBR' THEN JUMLAH END), 0) JUMLAH_PBR,
				COALESCE(SUM(CASE WHEN COALESCE(KATEGORI, '') = '' THEN JUMLAH END), 0) JUMLAH_INTERNAL
							FROM (
								SELECT KATEGORI, COUNT(A.PEGAWAI_ID) JUMLAH
								FROM pds_simpeg.PEGAWAI  A, (
									SELECT X.PEGAWAI_ID, REPLACE(pds_rekrutmen.AMBIL_MASA_KERJA(TANGGAL_LAHIR, CURRENT_DATE), ',', '.')::NUMERIC AS USIA, KATEGORI
										FROM pds_simpeg.PEGAWAI  X LEFT JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR Y ON X.PEGAWAI_ID = Y.PEGAWAI_ID
									) B
								WHERE 1 = 1 AND A.PEGAWAI_ID = B.PEGAWAI_ID AND B.USIA >= 46::NUMERIC AND B.USIA <= 50::NUMERIC ".$statement."
								GROUP BY KATEGORI
							) Y     
			UNION ALL		
			SELECT '50' IDKETERANGAN, '> 50' NAMA, COALESCE(SUM(JUMLAH), 0) TOTAL, COALESCE(SUM(CASE WHEN KATEGORI = 'PS' THEN JUMLAH END), 0) JUMLAH_PS,
				COALESCE(SUM(CASE WHEN KATEGORI = 'OPS' THEN JUMLAH END), 0) JUMLAH_OPS,
				COALESCE(SUM(CASE WHEN KATEGORI = 'PBR' THEN JUMLAH END), 0) JUMLAH_PBR,
				COALESCE(SUM(CASE WHEN COALESCE(KATEGORI, '') = '' THEN JUMLAH END), 0) JUMLAH_INTERNAL
							FROM (
								SELECT KATEGORI, COUNT(A.PEGAWAI_ID) JUMLAH
								FROM pds_simpeg.PEGAWAI  A, (
									SELECT X.PEGAWAI_ID, REPLACE(pds_rekrutmen.AMBIL_MASA_KERJA(TANGGAL_LAHIR, CURRENT_DATE), ',', '.')::NUMERIC AS USIA, KATEGORI
										FROM pds_simpeg.PEGAWAI  X LEFT JOIN pds_simpeg.PEGAWAI_JABATAN_TERAKHIR Y ON X.PEGAWAI_ID = Y.PEGAWAI_ID
									) B
								WHERE 1 = 1 AND A.PEGAWAI_ID = B.PEGAWAI_ID AND B.USIA > 50::NUMERIC ".$statement."
								GROUP BY KATEGORI
							) Y      	   
				"; 
		
		
		$str .= "  ";
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }

    function selectByParamsJenisPegawai($statement)
	{
		$str = "SELECT A.JENIS_PEGAWAI_ID, NAMA, COALESCE(JUMLAH, 0) JUMLAH, COALESCE(JUMLAH, 0) / 100 JUMLAH_PER_100
				FROM pds_simpeg.JENIS_PEGAWAI  A 
				LEFT JOIN (
									SELECT  B.JENIS_PEGAWAI_ID, COUNT(1) JUMLAH
									FROM pds_simpeg.PEGAWAI A LEFT JOIN (SELECT PEGAWAI_ID, JENIS_PEGAWAI_ID, TMT_JENIS_PEGAWAI FROM pds_simpeg.PEGAWAI_JENIS_PEGAWAI_TERAKHIR) B
									ON A.PEGAWAI_ID = B.PEGAWAI_ID WHERE 1 = 1  ".$statement."
									GROUP BY B.JENIS_PEGAWAI_ID
								) B ON A.JENIS_PEGAWAI_ID = B.JENIS_PEGAWAI_ID
				WHERE A.JENIS_PEGAWAI_ID IS NOT NULL AND A.NAMA IS NOT NULL    
				"; 
		
		
		$str .= " ORDER BY A.JENIS_PEGAWAI_ID ";
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
		
  } 
?>