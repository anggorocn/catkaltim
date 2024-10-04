<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-ikk/Kelautan.php");
include_once("../WEB/functions/FileHandler.php");

require('../WEB/lib/spreadsheet-reader/php-excel-reader/excel_reader2.php');
require('../WEB/lib/spreadsheet-reader/SpreadsheetReader.php');

$reqLinkFile = $_FILES["reqLinkFile"];  
// print_r($reqLinkFile); exit;
$file = new FileHandler();

/* UPLOAD FILE */   
$datafileupload= $reqLinkFile["tmp_name"];

$folderfilesimpan= "../uploads/upload_pegawai_eksternal";

if(file_exists($folderfilesimpan)){}
else
{
    makedirs($folderfilesimpan);
}

$renameFile = "EKSPORT_PEGAWAI_EKSTERNAL_".date("Y-m-d").".".strtolower($file->getExtension($reqLinkFile["name"]));

$targetsimpan= $folderfilesimpan."/".$renameFile;
// echo $datafileupload; exit;

// if($file->uploadToDir('reqLinkFile', $FILE_DIR, $renameFile))
if (move_uploaded_file($datafileupload, $targetsimpan))
{
    $importSukses = 0;
    $importGagal  = 0;
    $Spreadsheet = new SpreadsheetReader($targetsimpan);  
    $Sheets = $Spreadsheet -> Sheets();
    foreach ($Sheets as $Index => $Name)
    {
        $namaSheet = $Name;
        
        $Spreadsheet -> ChangeSheet($Index);
        $kolomKe = 1;
        $urut = 1;
        foreach ($Spreadsheet as $Key => $Row)
        {
            $reqNama             = $Row[0];
            $reqNIP1           = $Row[1];
            $reqStatusJenis           = $Row[2];
            $reqStatusPegawaiId           = $Row[3];
            $reqTempatKerja           = $Row[4];
            $reqJabatanNama           = $Row[5];
            $reqJabatanLamar           = $Row[6];

            if(trim($reqTahapan) == "")
                $reqTahapan = $reqJadwalIni;

            if($kolomKe > 1)
            {
                $set= new Kelautan();
                $set->setField('NAMA', setQuote($reqNama));
                $set->setField('NIP_BARU', $reqNIP1);
                $set->setField('STATUS_JENIS', $reqStatusJenis);
                $set->setField('STATUS_PEGAWAI_ID', $reqStatusPegawaiId);
                $set->setField('TEMPAT_KERJA', setQuote($reqTempatKerja));
                $set->setField('LAST_JABATAN', setQuote($reqJabatanNama));
                $set->setField('JABATAN_LAMAR', setQuote($reqJabatanLamar));
                $set->setField('LAST_ESELON_ID', 0);
                if($set->insertpegawaiEksport()){
                    $importSukses++;    
                }
                                
                $urut++;
            }
            
            $kolomKe++;
        }   

    }

    unlink($FILE_DIR.$renameFile);
    echo "Import sukses ".$importSukses." data, gagal ".$importGagal." data.";
    return;
    
}
else{
    echo "xxx-Pilih file terlebih dahulu.";
}
?>