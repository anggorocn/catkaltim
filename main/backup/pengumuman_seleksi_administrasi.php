<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pengumuman.php");

$pengumuman = new Pengumuman();

$statement = " AND EXISTS (SELECT 1 FROM pds_rekrutmen.PELAMAR_LOWONGAN X WHERE X.LOWONGAN_ID = A.LOWONGAN_ID AND X.PELAMAR_ID = ".$userLogin->userPelamarId.")";
$pengumuman->selectByParamsMonitoring(array("A.PUBLISH"=>1), -1, -1, $statement);
//echo $pengumuman->query;exit;

$FILE_DIR = "../uploads/pengumuman/";
?>
<div class="col-lg-8">

	<div id="judul-halaman">Pengumuman</div>
    
    <div id="data-form">

      <?php /*?><div class="keterangan">
            <strong>Keterangan :</strong><br />
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
        </div><?php */?>
        
        <table class="data-list">
        <thead>
        	<tr>
                <th>Nama</th>
                <th>Keterangan</th>
                <th>Lowongan</th>
                <th>Jabatan</th>
                <th>Aksi</th>
            </tr>
		</thead>
        <tbody>
        	<?
			while($pengumuman->nextRow())
			{
            ?>
            <tr>
            	<td><?=$pengumuman->getField("NAMA")?></td>
            	<td><?=$pengumuman->getField("KETERANGAN")?></td>
            	<td><?=$pengumuman->getField("LOWONGAN_KODE")?></td>
            	<td><?=$pengumuman->getField("JABATAN_NAMA")?></td>
                <?
				if($pengumuman->getField("LINK_FILE")==""){}
				else
				{
                ?>
                <td align="center"><a href="<?=$FILE_DIR.$pengumuman->getField("LINK_FILE")?>" target="_blank"><i class="fa fa-external-link fa-lg" aria-hidden="true"></i></a></td>
                <?
				}
                ?>
            </tr>
            <?
			}
            ?>            
		</tbody>
        </table>
	
    </div>
    
</div>