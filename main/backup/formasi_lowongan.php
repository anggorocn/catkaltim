<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/Formasi.php");
include_once("../WEB/classes/base/FormasiDetil.php");

$formasi= new Formasi();
$formasi->selectByParams();

?>

<div class="col-lg-8">

    <div id="judul-halaman"><!--Formasi Lowongan-->Posisi</div>
    
    <div id="formasi">
        <table>
        <thead>
            <tr>
                <th>Kode&nbsp;Jabatan</th>
                <th>Nama Jabatan</th>
                <th>Jumlah&nbsp;Kebutuhan</th>
                <th>Penempatan</th>
            </tr>
        </thead>
        <tbody>
        	<?
			while($formasi->nextRow())
			{
				$tempFormasiEnkripId= $formasi->getField("FORMASI_ID_ENKRIP");
				$tempFormasiId= $formasi->getField("FORMASI_ID");
				$tempNamaFormasi= $formasi->getField("NAMA");
            ?>
            <!-- RUMPUN JABATAN ADMINISTRASI -->
            <tr class="formasi-rumpun">
                <td colspan="4"><?php /*?>JABATAN <?php */?><?=strtoupper($tempNamaFormasi)?></td>
            </tr>
				<?
                $formasi_detil= new FormasiDetil();
				$formasi_detil->selectByParamsData(array("A.FORMASI_ID"=>$tempFormasiId),-1,-1, "", "ORDER BY A.KODE ASC");
				
				while($formasi_detil->nextRow())
				{
				$tempDetilRowId= $formasi_detil->getField("FORMASI_DETIL_ID_ENKRIP");
				$tempDetilNama= $formasi_detil->getField("NAMA");
				$tempDetilKode= $formasi_detil->getField("KODE");
				$tempDetilTugas= $formasi_detil->getField("TUGAS");
				$tempDetilPendidikanInfo= $formasi_detil->getField("PENDIDIKAN_INFO");
				$tempDetilPengalamanMinimal= $formasi_detil->getField("PENGALAMAN_MINIMAL");
				$tempDetilKeahlian= $formasi_detil->getField("KEAHLIAN");
				$tempDetilUsia= $formasi_detil->getField("USIA_INFO");
				$tempDetilJumlahKebutuhan= $formasi_detil->getField("JUMLAH_KEBUTUHAN");
				$tempDetilJumlahPelamar= $formasi_detil->getField("JUMLAH_PELAMAR");
				$tempDetilPenempatan= $formasi_detil->getField("PENEMPATAN");
                ?>
                <tr class="formasi-item" style="cursor:pointer" id="<?=$tempDetilRowId?>">
                    <td><?=$tempDetilKode?></td>
                    <td><?=$tempDetilNama?></td>
                    <td><?=$tempDetilJumlahKebutuhan?></td>
                    <td style="text-align:center"><?=$tempDetilPenempatan?></td>
                </tr>
                <tr class="formasi-konten" id="-<?=$tempDetilRowId?>">
                    <td colspan="4"></td>
                </tr>
        	<?
				}
				unset($formasi_detil);
			}
            ?>
            
        </tbody>
        </table>
    </div>
    
</div>