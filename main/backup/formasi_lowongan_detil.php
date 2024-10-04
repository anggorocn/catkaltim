<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/Formasi.php");
include_once("../WEB/classes/base/FormasiDetil.php");

$tempLinkSecurity= $INDEX_SUB."/";

$reqRowDetilId= httpFilterGet("reqRowDetilId");

$formasi_detil= new FormasiDetil();
$formasi_detil->selectByParamsData(array("md5(CAST(A.FORMASI_DETIL_ID as TEXT))"=>$reqRowDetilId),-1,-1, "", "ORDER BY A.KODE ASC");

while($formasi_detil->nextRow())
{
$tempDetilId= $formasi_detil->getField("FORMASI_DETIL_ID");
$tempDetilRowId= $formasi_detil->getField("FORMASI_DETIL_ID_ENKRIP");
$tempNamaFormasi= $formasi_detil->getField("NAMA_FORMASI");
$tempDetilNama= $formasi_detil->getField("NAMA");
$tempDetilKode= $formasi_detil->getField("KODE");
$tempDetilTugas= $formasi_detil->getField("TUGAS");
$tempDetilFungsi= $formasi_detil->getField("FUNGSI");	
$tempDetilTugas_New= $formasi_detil->getField("TUGAS_NEW");
$tempDetilPendidikanInfo= $formasi_detil->getField("PENDIDIKAN_INFO");
$tempDetilPengalamanMinimal= $formasi_detil->getField("PENGALAMAN_MINIMAL");
$tempDetilKeahlian= $formasi_detil->getField("KEAHLIAN");
$tempDetilUsia= $formasi_detil->getField("USIA_INFO");
$tempDetilUsia= "Usia Maksimal 57 Tahun per Tanggal 10 Juni 2015";

$tempDetilJumlahKebutuhan= $formasi_detil->getField("JUMLAH_KEBUTUHAN");
$tempDetilJumlahPelamar= $formasi_detil->getField("JUMLAH_PELAMAR");
?>
    <td colspan="4">
        <div class="tabel">
            <div class="baris" style="display:none">
                <div class="kolom">Rumpun jabatan</div>
                <div class="kolom">:</div>
                <div class="kolom"><?=ucfirst(strtolower($tempNamaFormasi))?></div>
            </div>
            <div class="baris">
                <div class="kolom">Nama jabatan</div>
                <div class="kolom">:</div>
                <div class="kolom"><?=$tempDetilNama?></div>
            </div>
            <?php /*?><div class="baris">
                <div class="kolom">Kode Posisi</div>
                <div class="kolom">:</div>
                <div class="kolom"><?=$tempDetilKode?></div>
            </div><?php */?>
            <?php /*?><div class="baris">
                <div class="kolom">Atasan Langsung</div>
                <div class="kolom">:</div>
                <div class="kolom">PIMPINAN KPK</div>
            </div><?php */?>
            <div class="baris">
                <div class="kolom">Tujuan Jabatan</div>
                <div class="kolom">:</div>
                <div class="kolom">Menjamin terlaksananya pembinaan atas manajemen perencanaan, pengelolaan keuangan,organisasi dan tatalaksana, manajemen strategis dan manajemen kinerja, pelayanan umum, manajemen sumber daya manusia, perancangan peraturan, litigasi dan bantuan hukum serta hubungan masyarakat</div>
            </div>
            <?php /*?><div class="baris">
                <div class="kolom">Persyaratan Khusus</div>
                <div class="kolom">:</div>
                <div class="kolom"><?=$tempDetilTugas?></div>
            </div><?php */?>
            <div class="baris">
                <div class="kolom">Tanggung Jawab Utama dan tugas pokok</div>
                <div class="kolom">:</div>
                <div class="kolom"><?=$tempDetilTugas?></div>
            </div>
            <div class="baris">
                <div class="kolom">Persyaratan</div>
                <div class="kolom">:</div>
                <div class="kolom"><?=$tempDetilFungsi?></div>
            </div>
            <div class="baris">
                <div class="kolom">Dokumen Terlampir</div>
                <div class="kolom">:</div>
                <div class="kolom"><?=$tempDetilKeahlian?></div>
            </div>
            <?php /*?><div class="baris">
                <div class="kolom">Pendidikan</div>
                <div class="kolom">:</div>
                <div class="kolom"><?=$tempDetilPendidikanInfo?></div>
            </div>
            <div class="baris" style="display:none">
                <div class="kolom">Pengalaman minimal</div>
                <div class="kolom">:</div>
                <div class="kolom"><?=$tempDetilPengalamanMinimal?></div>
            </div><?php */?>
            <?php /*?><div class="baris">
                <div class="kolom">Usia</div>
                <div class="kolom">:</div>
                <div class="kolom">Berusia setinggi-tingginya 58 (lima puluh delapan) tahun pada akhir masa pendaftaran.</div>
            </div><?php */?>
            <div class="baris" style="display:none">
                <div class="kolom">Jumlah kebutuhan pegawai</div>
                <div class="kolom">:</div>
                <div class="kolom"><?=$tempDetilJumlahKebutuhan?> orang</div>
            </div>
        </div>
        
        <?
        if($userLogin->userPelamarId == ""){}
        else
        {
            $set= new Pelamar();
            $reqId= $userLogin->userPelamarEnkripId;
            $set->selectByParamsData(array("md5(CAST(A.PELAMAR_ID as TEXT))"=>$reqId, "md5(CAST(A.FORMASI_ID as TEXT))"=>$tempDetilRowId),-1,-1);
            //echo $set->query;//exit;
            $set->firstRow();
            $tempPelamarKe= $set->getField("PELAMAR_KE");
            unset($set);
            
            $set= new Pelamar();
            $reqId= $userLogin->userPelamarEnkripId;
            $set->selectByParamsData(array("md5(CAST(A.PELAMAR_ID as TEXT))"=>$reqId),-1,-1);
            //echo $set->query;//exit;
            $set->firstRow();
            $tempIsKirimLamaran= $set->getField("IS_KIRIM_LAMARAN");
            unset($set);
        }
        ?>
        <div id="info-pelamar">
            <div>Jumlah pelamar posisi ini sampai saat ini : <?=$tempDetilJumlahPelamar?></div>
            <?
            if($userLogin->userPelamarId == ""){}
            else
            {
            ?>
            <div>Anda Pelamar Ke : <?=$tempPelamarKe?></div>
            <?
            }
            ?>
        </div>
        
        <div>
        	<?
            //if($tempDetilId == 5)
			//{
			?>
            <?
            if($userLogin->userPelamarId == "")
            {
            ?>
            
            <!-- SEBELUM LOGIN -->
            <div class="kirim-lamaran">
            <!--<a href="#" class="md-trigger" data-modal="modal-kirim-formasi">-->
            <?php /*?><a href="#" class="md-trigger" data-modal="modal-10" onclick="setModal('setLookupLogin', '<?=$tempLinkSecurity?>modal/login')" >Kirim Lamaran</a><?php */?>
            <a href="#" class="md-trigger" data-modal="modal-10" onclick="setModal('setLookupLogin', 'modal_login.php')" >Kirim Lamaran</a>
            </div>
            <?
            }
            else
            {
                if($tempIsKirimLamaran == "1"){}
                else
                {
            ?>
                <!-- SETELAH LOGIN -->
                <div class="kirim-lamaran"><a href="?pg=formasi_lowongan_daftar&reqRowId=<?=$tempDetilRowId?>" >Kirim Lamaran</a></div>
            <?
                }
            }
            ?>
            <?
			//}
            ?>
        </div>
    </td>
<?
}
unset($formasi_detil);
?>