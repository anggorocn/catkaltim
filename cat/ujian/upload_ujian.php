<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/PermohonanFile.php");
include_once("../WEB/classes/base/Penggalian.php");
include_once("../WEB/functions/string.func.php");

if($userLogin->ujianUid == "")
{
    if($pg == "" || $pg == "home"){}
    else
    {
        echo '<script language="javascript">';
        echo 'top.location.href = "index.php";';
        echo '</script>';
        exit;
    }
}

$tempPegawaiId= $userLogin->pegawaiId;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;
// echo $ujianPegawaiJadwalTesId;exit();

$tempUjianPegawaiLowonganId= $userLogin->ujianPegawaiLowonganId;
$tempUjianPegawaiDaftarId= $userLogin->ujianPegawaiUjianPegawaiDaftarId;

$tempUjianId= $ujianPegawaiUjianId;

$infotahun= getDay(datetimeToPage($tempUjianPegawaiTanggalAwal, 'date'));
// echo $infotahun;exit;
// $arrfilejenis= jenisfiletest();
$idata=0;
$arrfilejenis= [];
$setdetil= new Penggalian();
$setdetil->selectByParams(array(), -1, -1, " AND A.TAHUN = '".$infotahun."' AND A.KODE != 'PT'
and exists
(
    select 1
    from
    (
        select pegawai_id kode 
        from permohonan_file 
        where permohonan_table_nama = 'jadwaltes".$ujianPegawaiJadwalTesId."-soal'
    ) x where a.kode = x.kode
)
");
// echo $setdetil->query;exit;
while($setdetil->nextRow())
{
    $arrfilejenis[$idata]["id"]= $setdetil->getField("PENGGALIAN_ID");
    $arrfilejenis[$idata]["nama"]= $setdetil->getField("NAMA");
    $arrfilejenis[$idata]["kode"]= $setdetil->getField("KODE");
    $idata++;
}
$jumlahpenggalian= $idata;

$reqkuncijenis= $ujianPegawaiJadwalTesId;
$reqfolderjenis= "jadwaltes".$reqkuncijenis;
$reqJenis= $reqfolderjenis."-soal";
$reqJenisPegawai= $reqfolderjenis."-jawab";
$addtoadminfile= "../../assesment/";
?>
<link rel="stylesheet" type="text/css" href="../WEB/lib-ujian/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib-ujian/easyui/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../WEB/lib-ujian/easyui/jquery.easyui.min.js"></script>

<link rel="stylesheet" href="../WEB/lib-ujian/autokomplit/jquery-ui.css">
<script src="../WEB/lib-ujian/autokomplit/jquery-ui.min.js"></script>  
<style>
	.ui-autocomplete {
		max-height: 200px;
		overflow-y: auto;
		/* prevent horizontal scrollbar */
		font-size:11px;
		overflow-x: hidden;
	}
	/* IE 6 doesn't support max-height
	 * we use height instead, but this forces the menu to always be this tall
	 */
	* html .ui-autocomplete {
		height: 200px;
	}
</style>

<!-- AUTO KOMPLIT -->
<script type="text/javascript" src="../WEB/lib-ujian/easyui/easyloader.js"></script>   
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.form.js"></script>  
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.linkbutton.js"></script> 
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.draggable.js"></script> 
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.resizable.js"></script> 
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.panel.js"></script> 
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.window.js"></script> 
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.progressbar.js"></script> 
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.messager.js"></script>      
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.tooltip.js"></script>  
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.validatebox.js"></script>  
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.combo.js"></script>
<link href="../WEB/lib-ujian/multipleselect/multiple-select.css" rel="stylesheet"/>
<script src="../WEB/lib-ujian/multipleselect/jquery.multiple.select.js"></script>

<script type="text/javascript" src="../WEB/lib-ujian/easyui/kalender-easyui.js"></script>

<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
  text-align: center;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

/*#customers tr:hover {background-color: #ddd;}*/

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: center;
  background-color: #4CAF50;
  color: white;
}

.itemdownload{
    padding-right: 0px;
    top: 20px;
    border: solid black 0.5px; 
    border-radius: 15px; 
    margin-top: 10px;
    margin-bottom: 10px;
}

.tablerapi{
    margin-left: 10px;
    margin-top: 10px;
    margin-right: 10px;
    margin-bottom: 10px;
    width: 95%
}

.imgrapi{
    width: 50px ;
    display: block;  
    margin-left: auto;  
    margin-right: auto;
}
.fontrapi{
    font-size: 20px; 
    color: blue;
    padding-left: 10px
}
</style>


<div class="container utama">
    <div class="row">
        <div class="col-md-12">
            <div class="area-judul-halaman">Upload File Test</div>
        </div>
        
        <div class="col-md-5" style="  border: 5px dashed yellow; height: 400px; background-color: pink;left: 20px;top: 20px;">
            <br>
            <div align=center style="font-size: 20px"><b>INSTRUKSI PENGERJAAN</b></div>
            <img src="../WEB/images-ujian/pdf2.png" style="width: 100px ;display: block;  margin-left: auto;  margin-right: auto; margin-top: 20px;" >
            
            <br>
            <div align=center >Cara Pengerjaan :</div>
            <div align=center >1. Download Soal</div>
            <div align=center >2. Kerjakan Soal Dengan Cermat dan Teliti</div>
            <div align=center >3. Jika Sudah Selesai. Upload Sesuai Dengan Ujian</div>
            <br>
            <div align=center ><b>Tekan tombol logout dibawah ini jika yakin dengan jawaban anda</b></div>
            <br>
            <p  align="center">
                <a href="index.php?reqMode=submitLogout" style='font-size: 17px; height: 40px' class="btn btn-danger"> Logout</a>
                <a href="index.php?pg=ujian_pilihan" style='font-size: 17px; height: 40px' class="btn btn-warning"> Kembali</a>
            </p>

        </div>

        <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <div class="col-md-6" style="overflow: scroll;height: 350px;top: 20px;margin-left: 40px; overflow-x: hidden;">
            <?
            for($x=0; $x < count($arrfilejenis); $x++)
            {
                $infoid= $arrfilejenis[$x]["id"];
                $infolabel= $arrfilejenis[$x]["nama"];
                $infojenislabel= $arrfilejenis[$x]["kode"];

                $setdetil= new PermohonanFile();
                $setdetil->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqkuncijenis, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis, "A.PEGAWAI_ID"=>$infojenislabel));
                $setdetil->firstRow();
                // echo $setdetil->query;exit;
                $infofilerowid= $setdetil->getField("PERMOHONAN_FILE_ID");
                $infolinkfile= $infoketerangan= "";
                if(!empty($infofilerowid))
                {
                    $infolinkfile= $setdetil->getField("LINK_FILE");
                    $infolinkfile= str_replace("../uploads/", $addtoadminfile."uploads/", $infolinkfile);
                    $infoketerangan= $setdetil->getField("KETERANGAN");
                }
            ?>
            <div class="col-md-12 itemdownload" >
               <table class ="tablerapi">
                    <tr>
                        <td rowspan="2" style="width:20%">
                            <?
                            if(file_exists($infolinkfile))
                            {
                            ?>
                                <a href="<?=$infolinkfile?>" target="_blank" style="text-decoration: none; display: block;  margin-left: auto;  margin-right: auto; width: 100px">
                                    <img style="text-align: center;" src="../WEB/images-ujian/pdf2.png" class="imgrapi" >
                                    <div align="center"><b>Download Soal</b></div>
                                </a>
                            <?
                            }
                            else
                            {
                            ?>
                                <img src="../WEB/images-ujian/icn-faq2.png" class="imgrapi" >
                                <div align="center">File Belum Ada</div>
                            <?
                            }
                            ?>
                        </td>
                        <td colspan="2" style="width:80%" align=center >
                             <span class="fontrapi" ><b><?=$infolabel?></b></span>
                        </td>
                    </tr>
                    <tr>
                        <td align=center>
                            <input type='file' accept="application/pdf" style='color: black;padding-left: 10px;font-size: 10px' name='reqLinkFile[]' id='reqLinkFile<?=$infoid?>' />
                            <input type="hidden" name="reqFileJenisId[]" value="<?=$infoid?>" />
                            <input type="hidden" name="reqFileJenisKode[]" value="<?=$infojenislabel?>" />
                            <?
                            $setdetil= new PermohonanFile();
                            $setdetil->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqkuncijenis, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenisPegawai, "A.PEGAWAI_ID"=>$infojenislabel."-".$tempPegawaiId));
                            $setdetil->firstRow();
                            // echo $setdetil->query;exit;
                            $infofilerowid= $setdetil->getField("PERMOHONAN_FILE_ID");
                            $infolinkfile= $infoketerangan= "";
                            if(!empty($infofilerowid))
                            {
                                $infolinkfile= $setdetil->getField("LINK_FILE");
                                $infolinkfile= str_replace("../uploads/", $addtoadminfile."uploads/", $infolinkfile);
                                $infoketerangan= $setdetil->getField("KETERANGAN");
                                 $tipefile= explode(".", $infoketerangan);
                                    // print_r($tipefile); exit;
                                if (strlen($infoketerangan)>20 ){
                                    $infoketerangan= substr($infoketerangan, 0, 15);
                                    $batasawal=strlen($infoketerangan)-4;
                                    $batasakhir=strlen($infoketerangan);                                   
                                    $infoketerangan=$infoketerangan.".......".$tipefile[1];
                                }

                            }   
                            ?>
                            <?
                            if(file_exists($infolinkfile))
                            {
                            ?>
                                <a href="<?=$infolinkfile?>" target="_blank" style="text-decoration: none; display: block;  margin-left: auto;  margin-right: auto;">
                                    <i class="fa fa-download" aria-hidden="true" style="font-size: 25px"><span style="font-size: 18px;margin-left: 10px;"><?=$infoketerangan?></span></i>
                                    <!-- <div></div> -->
                                </a> 
                            <?
                            }
                            else
                            {
                            ?>

                            <?
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
            <?
            }
            ?>
            </div>
        </form>

        <div class="row">
            <div class="col-md-6" style="padding-top: 20px; padding-left: 50px;top: 10px;">
                <a href="javascript:void(0)" style='font-size: 17px; height: 40px' class="btn btn-primary" onclick="$('#ff').submit();"><i class="fa fa-fw fa-send"></i> Upload</a>
            </div>
        </div>

    </div>
</div>

<script>
$('#ff').form({
    url:'../json-ujian/upload_ujian.php',
    onSubmit:function(){

        var f = this;
        var opts = $.data(this, 'form').options;
        if($(this).form('validate') == false){
            return false;
        }
        //var reqDiklatId= $("#reqDiklatId option:selected").text();
        $.messager.confirm('Confirm','Apakah Anda yakin Upload Data Ini ?',function(r){
            if (r){

                $.messager.progress({title:'Proses upload.',msg:'Proses upload...'});
                var bar = $.messager.progress('bar');
                bar.progressbar({text: ''});

                var onSubmit = opts.onSubmit;
                opts.onSubmit = function(){};
                $(f).form('submit');
                opts.onSubmit = onSubmit;
            }
        })
        return false;
        //return $(this).form('validate');
    },
    success:function(data){
        $.messager.progress('close');
        // console.log(data);return false;
        data = data.split("-");
        reqId= data[0];

        $.messager.alert('Info', data[1], 'info');
        if(reqId == "xxx")
        {
            return false;
        }
        document.location.href = 'index.php?pg=upload_ujian';
    }
});
</script>