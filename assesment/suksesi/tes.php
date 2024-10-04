<?
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Cetak_Identitas_Pegawai_Excel.xls");
/*header("Content-type: application/msword");
header("Content-Disposition: attachment; filename=Cetak_Identitas_Pegawai_Excel.doc");*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
<style>
	body, table{
		font-size:12px;
		font-family:Arial, Helvetica, sans-serif
	}
	th {
		text-align:center;
		font-weight: bold;
	}
	td {
		vertical-align: top;
  		text-align: left;
	}
	.str{
	  mso-number-format:"\@";/*force text*/
	}
	</style>
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th style="width:10px;">Id</th>
            <th width="10px">No</th>
            <th width="100px">NIP</th>
            <th width="120px">NIP Baru</th>
            <th width="220px">Nama</th>
            <th width="90px">Tempat Lahir</th>
            <th width="90px">Tgl. Lahir</th>
            <th width="90px">Status</th>
            <th width="80px">Gol. Ruang</th>
            <th width="120px">TMT Pangkat</th>      
            <th width="20px">Eselon</th>            
            <th width="320px">Jabatan</th>                  
            <th width="120px">TMT Jabatan</th>                                     
            <th width="300px">Alamat</th>                                         
            <th width="300px">Satuan Kerja</th>                                         
            <th width="100px">TMT Pensiun</th>
        </tr>
    </thead>
    </table>
</body>
</html>