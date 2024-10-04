<?
$reloadTime= '2888';
?>

function executeOnClick(varItem, table, pegawaiId){
$("a").removeClass("menuAktifDynamis");
$("a").removeClass("menuAktifDynamisMerah");
$("a").removeClass("menuAktifDynamisMerahAbu");

$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable='+table,
function(data){
	info_data= data.info_data
});

setTimeout(function () { setMenu(table, pegawaiId); }, <?=$reloadTime?>);

//alert(varItem);
if(varItem == "idpegawai")
{
    parent.mainFrame.location.href='identitas_edit<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = 'none';
}
else if(varItem == "pengalamankerja")
{
    parent.mainFrame.location.href='pengalaman_kerja<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='pengalaman_kerja_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "sk-cpns")
{
    parent.mainFrame.location.href='skcpns_edit<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = 'none';
}
else if(varItem == "sk-pns")
{
    parent.mainFrame.location.href='skpns_edit<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';	
	parent.document.getElementById('trdetil').style.display = 'none';
}
else if(varItem == "riwayatpangkat")
{	
    parent.mainFrame.location.href='pangkat<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='pangkat_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "riwayatjabatan")
{
    parent.mainFrame.location.href='jabatan<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='jabatan_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "riwayatgaji")
{	
    parent.mainFrame.location.href='gaji<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='gaji_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "pendumum")
{
    parent.mainFrame.location.href='pendidikan_umum<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='pendidikan_umum_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "diklatstruktural")
{
    parent.mainFrame.location.href='struktural<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='struktural_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "diklatfungsional")
{
    parent.mainFrame.location.href='fungsional<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='fungsional_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "diklatteknis")
{
    parent.mainFrame.location.href='teknis<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='teknis_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "diklatlpj")
{
	parent.mainFrame.location.href='lpj<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = 'none';
}
else if(varItem == "penataran")
{
    parent.mainFrame.location.href='penataran<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='penataran_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "seminar")
{
    parent.mainFrame.location.href='seminar<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='seminar_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "kursus")
{
    parent.mainFrame.location.href='kursus<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='kursus_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "ortu")
{
    parent.mainFrame.location.href='orang_tua_edit<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = 'none';
}
else if(varItem == "mertua")
{
    parent.mainFrame.location.href='mertua_edit<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = 'none';
}
else if(varItem == "suamiistri")
{
    parent.mainFrame.location.href='suami_istri_edit<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = 'none';
}
else if(varItem == "anak")
{
    parent.mainFrame.location.href='anak<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='anak_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "saudara")
{
    parent.mainFrame.location.href='saudara<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='saudara_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "organisasi")
{
    parent.mainFrame.location.href='organisasi<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='organisasi_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "penghargaan")
{
    parent.mainFrame.location.href='penghargaan<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='penghargaan_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "dp3")
{
    parent.mainFrame.location.href='penilaian_dp3<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='penilaian_dp3_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "potensidiri")
{
    parent.mainFrame.location.href='potensi_diri<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='potensi_diri_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "catatanprestasi")
{
    parent.mainFrame.location.href='prestasi<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='prestasi_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "hukuman")
{
    parent.mainFrame.location.href='hukuman<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='hukuman_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "cuti")
{
    parent.mainFrame.location.href='cuti<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='cuti_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "riwayatpenugasan")
{
    parent.mainFrame.location.href='penugasan<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='penugasan_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "bahasa")
{
    parent.mainFrame.location.href='penguasaan_bahasa<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='penguasaan_bahasa_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "nikah")
{
    parent.mainFrame.location.href='nikah<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.mainFrameDetil.location.href='nikah_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == "tambmasakerja")
{
    parent.mainFrame.location.href='masa_kerja<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>';
	parent.document.getElementById('trdetil').style.display = 'none';
}   
else if(varItem == 'lokasikerja'){
	$('.lokasikerja').addClass("menuAktifDynamisMerahAbu");
	parent.mainFrame.location.href='lokasi_kerja_edit_satker.php?reqPegawaiId=<?=$reqPegawaiId?>';
	//parent.mainFrameDetil.location.href='lokasi_kerja_detil.php';
	parent.document.getElementById('trdetil').style.display = 'none';	
}

return true;
}

var info_pegawai=info_pengalaman=info_sk_cpns=info_sk_pns=
info_riwayat_pangkat=info_riwayat_jabatan=info_riwayat_gaji=info_pend_umum=info_diklat_struktural=
info_diklat_fungsional=info_diklat_teknis=info_penataran=info_seminar=
info_kursus=info_ortu=info_mertua=info_suami_istri=info_anak=
info_saudara=info_organisasi=info_penghargaan=info_dp3=info_potensi_diri=
info_catatan_prestasi=info_hukuman=info_cuti=info_riwayat_penugasan=info_bahasa=
info_nikah=info_tamb_masa_kerja="";

function setPegawai()
{
	if(info_pegawai == 1) 
		$('#idpegawai').addClass("menuAktifDynamisMerah");
}

function setPengalaman()
{
	if(info_pengalaman == 1) 
		$('#pengalamankerja').addClass("menuAktifDynamisMerah");
}

function setSkCpns()
{
	if(info_sk_cpns == 1) 
		$('#sk-cpns').addClass("menuAktifDynamisMerah");
}

function setSkPns()
{
	if(info_sk_pns == 1) 
		$('#sk-pns').addClass("menuAktifDynamisMerah");
}

// FIP - 02
function setRiwayatPangkat()
{
	if(info_riwayat_pangkat == 1) 
		$('#riwayatpangkat').addClass("menuAktifDynamisMerah");
}
function setRiwayatJabatan()
{
	if(info_riwayat_jabatan == 1) 
		$('#riwayatjabatan').addClass("menuAktifDynamisMerah");
}
function setRiwayatGaji()
{
	if(info_riwayat_gaji == 1) 
		$('#riwayatgaji').addClass("menuAktifDynamisMerah");
}
function setPendUmum()
{
	if(info_pend_umum == 1) 
		$('#pendumum').addClass("menuAktifDynamisMerah");
}
function setDiklatStruktural()
{
	if(info_diklat_struktural == 1) 
		$('#diklatstruktural').addClass("menuAktifDynamisMerah");
}
function setDiklatFungsional()
{
	if(info_diklat_fungsional == 1) 
		$('#diklatfungsional').addClass("menuAktifDynamisMerah");
}
function setDiklatTeknis()
{
	if(info_diklat_teknis == 1) 
		$('#diklatteknis').addClass("menuAktifDynamisMerah");
}
function setDiklatLpj()
{
	if(info_diklat_lpj == 1) 
		$('#diklatlpj').addClass("menuAktifDynamisMerah");
}
function setPenataran()
{
	if(info_penataran == 1) 
		$('#penataran').addClass("menuAktifDynamisMerah");
}
function setSeminar()
{
	if(info_seminar == 1) 
		$('#seminar').addClass("menuAktifDynamisMerah");
}
function setKursus()
{
	if(info_kursus == 1) 
		$('#kursus').addClass("menuAktifDynamisMerah");
}
function setOrtu()
{
	if(info_ortu == 1) 
		$('#ortu').addClass("menuAktifDynamisMerah");
}
function setMertua()
{
	if(info_mertua == 1) 
		$('#mertua').addClass("menuAktifDynamisMerah");
}
function setSuamiIstri()
{
	if(info_suami_istri == 1) 
		$('#suamiistri').addClass("menuAktifDynamisMerah");
}
function setAnak()
{
	if(info_anak == 1) 
		$('#anak').addClass("menuAktifDynamisMerah");
}
function setSaudara()
{
	if(info_saudara == 1) 
		$('#saudara').addClass("menuAktifDynamisMerah");
}
function setOrganisasi()
{
	if(info_organisasi == 1) 
		$('#organisasi').addClass("menuAktifDynamisMerah");
}
function setPenghargaan()
{
	if(info_penghargaan == 1) 
		$('#penghargaan').addClass("menuAktifDynamisMerah");
}
function setDp3()
{
	if(info_dp3 == 1) 
		$('#dp3').addClass("menuAktifDynamisMerah");
}
function setPotensiDiri()
{
	if(info_potensi_diri == 1) 
		$('#potensidiri').addClass("menuAktifDynamisMerah");
}
function setCatatanPrestasi()
{
	if(info_catatan_prestasi == 1) 
		$('#catatanprestasi').addClass("menuAktifDynamisMerah");
}
function setHukuman()
{
	if(info_hukuman == 1) 
		$('#hukuman').addClass("menuAktifDynamisMerah");
}
function setCuti()
{
	if(info_cuti == 1) 
		$('#cuti').addClass("menuAktifDynamisMerah");
}
function setRiwayatPenugasan()
{
	if(info_riwayat_penugasan == 1) 
		$('#riwayatpenugasan').addClass("menuAktifDynamisMerah");
}
function setBahasa()
{
	if(info_bahasa == 1) 
		$('#bahasa').addClass("menuAktifDynamisMerah");
}
function setNikah()
{
	if(info_nikah == 1) 
		$('#nikah').addClass("menuAktifDynamisMerah");
}
function setTambMasaKerja()
{
	if(info_tamb_masa_kerja == 1) 
		$('#tambmasakerja').addClass("menuAktifDynamisMerah");
}
	
function perubahanReloadData(varItem, pegawaiId)
{
	info_pegawai="";
    if(varItem == 'PEGAWAI'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=PEGAWAI',function(data){ info_pegawai= data.info_data});
	setTimeout(setPegawai, <?=$reloadTime?>);
	}
    
	info_pengalaman="";
    if(varItem == 'PENGALAMAN'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=PENGALAMAN',function(data){ info_pengalaman= data.info_data});
	setTimeout(setPengalaman, <?=$reloadTime?>);
	}
    
    info_sk_cpns="";
    if(varItem == 'SK_CPNS'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=SK_CPNS', function(data){ info_sk_cpns= data.info_data});
	setTimeout(setSkCpns, <?=$reloadTime?>);
    }
    
    info_sk_pns="";
    if(varItem == 'SK_PNS'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=SK_PNS', function(data){ info_sk_pns= data.info_data});
	setTimeout(setSkPns, <?=$reloadTime?>);
    }
    
    info_riwayat_pangkat="";
    if(varItem == 'PANGKAT_RIWAYAT'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=PANGKAT_RIWAYAT', function(data){ info_riwayat_pangkat= data.info_data});
	setTimeout(setRiwayatPangkat, <?=$reloadTime?>);
    }
    
    info_riwayat_jabatan="";
    if(varItem == 'JABATAN_RIWAYAT'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=JABATAN_RIWAYAT', function(data){ info_riwayat_jabatan= data.info_data});
	setTimeout(setRiwayatJabatan, <?=$reloadTime?>);
    }
    
    info_riwayat_gaji="";
    if(varItem == 'GAJI_RIWAYAT'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=GAJI_RIWAYAT', function(data){ info_riwayat_gaji= data.info_data});
	setTimeout(setRiwayatGaji, <?=$reloadTime?>);
    }
    
    info_pend_umum="";
    if(varItem == 'PENDIDIKAN_RIWAYAT'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=PENDIDIKAN_RIWAYAT', function(data){ info_pend_umum= data.info_data});
	setTimeout(setPendUmum, <?=$reloadTime?>);
    }
    
    info_diklat_struktural="";
    if(varItem == 'DIKLAT_STRUKTURAL'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=DIKLAT_STRUKTURAL', function(data){ info_diklat_struktural= data.info_data});
	setTimeout(setDiklatStruktural, <?=$reloadTime?>);
    }
    
    info_diklat_fungsional="";
    if(varItem == 'DIKLAT_FUNGSIONAL'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=DIKLAT_FUNGSIONAL', function(data){ info_diklat_fungsional= data.info_data});
	setTimeout(setDiklatFungsional, <?=$reloadTime?>);
    }
    
    info_diklat_teknis="";
    if(varItem == 'DIKLAT_TEKNIS'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=DIKLAT_TEKNIS', function(data){ info_diklat_teknis= data.info_data});
	setTimeout(setDiklatTeknis, <?=$reloadTime?>);
    }
    
    info_diklat_lpj="";
    if(varItem == 'DIKLAT_LPJ'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=DIKLAT_LPJ', function(data){ info_diklat_lpj= data.info_data});
	setTimeout(setDiklatLpj, <?=$reloadTime?>);
    }
    
    info_penataran="";
    if(varItem == 'PENATARAN'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=PENATARAN', function(data){ info_penataran= data.info_data});
	setTimeout(setPenataran, <?=$reloadTime?>);
    }
    
    info_seminar="";
    if(varItem == 'SEMINAR'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=SEMINAR', function(data){ info_seminar= data.info_data});
	setTimeout(setSeminar, <?=$reloadTime?>);
    }
    
    info_kursus="";
    if(varItem == 'KURSUS'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=KURSUS', function(data){ info_kursus= data.info_data});
	setTimeout(setKursus, <?=$reloadTime?>);
    }
    
    info_ortu="";
    if(varItem == 'ORANG_TUA'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=ORANG_TUA', function(data){ info_ortu= data.info_data});
	setTimeout(setOrtu, <?=$reloadTime?>);
    }
    
    info_mertua="";
    if(varItem == 'MERTUA'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=MERTUA', function(data){ info_mertua= data.info_data});
	setTimeout(setMertua, <?=$reloadTime?>);
    }
    
    info_suami_istri="";
    if(varItem == 'SUAMI_ISTRI'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=SUAMI_ISTRI', function(data){ info_suami_istri= data.info_data});
	setTimeout(setSuamiIstri, <?=$reloadTime?>);
    }
    
    info_anak="";
    if(varItem == 'ANAK'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=ANAK', function(data){ info_anak= data.info_data});
	setTimeout(setAnak, <?=$reloadTime?>);
    }
    
    info_saudara="";
    if(varItem == 'SAUDARA'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=SAUDARA', function(data){ info_saudara= data.info_data});
	setTimeout(setSaudara, <?=$reloadTime?>);
    }
    
    info_organisasi="";
    if(varItem == 'ORGANISASI_RIWAYAT'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=ORGANISASI_RIWAYAT', function(data){ info_organisasi= data.info_data});
	setTimeout(setOrganisasi, <?=$reloadTime?>);
    }
    
    info_penghargaan="";
    if(varItem == 'PENGHARGAAN'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=PENGHARGAAN', function(data){ info_penghargaan= data.info_data});
	setTimeout(setPenghargaan, <?=$reloadTime?>);
    }
    
    info_dp3="";
    if(varItem == 'PENILAIAN'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=PENILAIAN', function(data){ info_dp3= data.info_data});
	setTimeout(setDp3, <?=$reloadTime?>);
    }
    
    info_potensi_diri="";
    if(varItem == 'POTENSI_DIRI'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=POTENSI_DIRI', function(data){ info_potensi_diri= data.info_data});
	setTimeout(setPotensiDiri, <?=$reloadTime?>);
    }
    
    info_catatan_prestasi="";
    if(varItem == 'PRESTASI_KERJA'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=PRESTASI_KERJA', function(data){ info_catatan_prestasi= data.info_data});
	setTimeout(setCatatanPrestasi, <?=$reloadTime?>);
    }
    
    info_hukuman="";
    if(varItem == 'HUKUMAN'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=HUKUMAN', function(data){ info_hukuman= data.info_data});
	setTimeout(setHukuman, <?=$reloadTime?>);
    }
    
    info_cuti="";
    if(varItem == 'CUTI'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=CUTI', function(data){ info_cuti= data.info_data});
	setTimeout(setCuti, <?=$reloadTime?>);
    }
    
    info_riwayat_penugasan="";
    if(varItem == 'TUGAS'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=TUGAS', function(data){ info_riwayat_penugasan= data.info_data});
	setTimeout(setRiwayatPenugasan, <?=$reloadTime?>);
    }
    
    info_bahasa="";
    if(varItem == 'BAHASA'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=BAHASA', function(data){ info_bahasa= data.info_data});
	setTimeout(setBahasa, <?=$reloadTime?>);
    }
    
    info_nikah="";
    if(varItem == 'NIKAH_RIWAYAT'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=NIKAH_RIWAYAT', function(data){ info_nikah= data.info_data});
	setTimeout(setNikah, <?=$reloadTime?>);
    }
    
    info_tamb_masa_kerja="";
    if(varItem == 'TAMBAHAN_MASA_KERJA'){}
    else
    {
	$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable=TAMBAHAN_MASA_KERJA', function(data){ info_tamb_masa_kerja= data.info_data});
	setTimeout(setTambMasaKerja, <?=$reloadTime?>);
    }
}

var info_data="";

function executeReloadOnClick(varItem, pegawaiId){
$("a").removeClass("menuAktifDynamis");
$("a").removeClass("menuAktifDynamisMerah");
$("a").removeClass("menuAktifDynamisMerahAbu");

$.getJSON('../json/pegawai_menu_json.php?reqId='+pegawaiId+'&reqTable='+varItem,
function(data){
	info_data= data.info_data
});

setTimeout(function () { setMenu(varItem, pegawaiId); }, <?=$reloadTime?>);

return true;
}

function setMenu(varItem, pegawaiId)
{
    setTimeout(function () { perubahanReloadData(varItem, pegawaiId); }, <?=$reloadTime?>);
    
    if(varItem == "PEGAWAI")
    {
        if(info_data == 1) $("#idpegawai").addClass("menuAktifDynamisMerahAbu"); else $("#idpegawai").addClass("menuAktifDynamis");
    }
    else if(varItem == "PENGALAMAN")
    {
        if(info_data == 1) $("#pengalamankerja").addClass("menuAktifDynamisMerahAbu"); else $("#pengalamankerja").addClass("menuAktifDynamis");
    }
    else if(varItem == "SK_CPNS")
    {
        if(info_data == 1) $('#sk-cpns').addClass("menuAktifDynamisMerahAbu");else $('#sk-cpns').addClass("menuAktifDynamis");
    }
    else if(varItem == "SK_PNS")
    {
        if(info_data == 1) $('#sk-pns').addClass("menuAktifDynamisMerahAbu");else $('#sk-pns').addClass("menuAktifDynamis");
    }
    else if(varItem == "PANGKAT_RIWAYAT")
    {	
        if(info_data == 1) $('#riwayatpangkat').addClass("menuAktifDynamisMerahAbu");else $('#riwayatpangkat').addClass("menuAktifDynamis");
    }
    else if(varItem == "JABATAN_RIWAYAT")
    {
        if(info_data == 1) $('#riwayatjabatan').addClass("menuAktifDynamisMerahAbu");else $('#riwayatjabatan').addClass("menuAktifDynamis");	
    }
	else if(varItem == "GAJI_RIWAYAT")
    {	
        if(info_data == 1) $('#riwayatgaji').addClass("menuAktifDynamisMerahAbu");else $('#riwayatgaji').addClass("menuAktifDynamis");
    }
    else if(varItem == "PENDIDIKAN_RIWAYAT")
    {
        if(info_data == 1) $('#pendumum').addClass("menuAktifDynamisMerahAbu");else $('#pendumum').addClass("menuAktifDynamis");
    }
    else if(varItem == "DIKLAT_STRUKTURAL")
    {
        if(info_data == 1) $('#diklatstruktural').addClass("menuAktifDynamisMerahAbu");else $('#diklatstruktural').addClass("menuAktifDynamis");
    }
    else if(varItem == "DIKLAT_FUNGSIONAL")
    {
        if(info_data == 1) $('#diklatfungsional').addClass("menuAktifDynamisMerahAbu");else $('#diklatfungsional').addClass("menuAktifDynamis");
    }
    else if(varItem == "DIKLAT_TEKNIS")
    {
        if(info_data == 1) $('#diklatteknis').addClass("menuAktifDynamisMerahAbu");else $('#diklatteknis').addClass("menuAktifDynamis");
    }
    else if(varItem == "DIKLAT_LPJ")
    {
        if(info_data == 1) $('#diklatlpj').addClass("menuAktifDynamisMerahAbu");else $('#diklatlpj').addClass("menuAktifDynamis");
    }
    else if(varItem == "PENATARAN")
    {
        if(info_data == 1) $('#penataran').addClass("menuAktifDynamisMerahAbu");else $('#penataran').addClass("menuAktifDynamis");
    }
    else if(varItem == "SEMINAR")
    {
        if(info_data == 1) $('#seminar').addClass("menuAktifDynamisMerahAbu");else $('#seminar').addClass("menuAktifDynamis");
    }
    else if(varItem == "KURSUS")
    {
        if(info_data == 1) $('#kursus').addClass("menuAktifDynamisMerahAbu");else $('#kursus').addClass("menuAktifDynamis");
    }
    else if(varItem == "ORANG_TUA")
    {
        if(info_data == 1) $('#ortu').addClass("menuAktifDynamisMerahAbu");else $('#ortu').addClass("menuAktifDynamis");
    }
    else if(varItem == "MERTUA")
    {
        if(info_data == 1) $('#mertua').addClass("menuAktifDynamisMerahAbu");else $('#mertua').addClass("menuAktifDynamis");
    }
    else if(varItem == "SUAMI_ISTRI")
    {
        if(info_data == 1) $('#suamiistri').addClass("menuAktifDynamisMerahAbu");else $('#suamiistri').addClass("menuAktifDynamis");
    }
    else if(varItem == "ANAK")
    {
		if(info_data== 1) $('#anak').addClass("menuAktifDynamisMerahAbu"); else $('#anak').addClass("menuAktifDynamis");
    }
    else if(varItem == "SAUDARA")
    {
        if(info_data == 1) $('#saudara').addClass("menuAktifDynamisMerahAbu");else $('#saudara').addClass("menuAktifDynamis");
    }
    else if(varItem == "ORGANISASI_RIWAYAT")
    {
        if(info_data == 1) $('#organisasi').addClass("menuAktifDynamisMerahAbu");else $('#organisasi').addClass("menuAktifDynamis");
    }
    else if(varItem == "PENGHARGAAN")
    {
        if(info_data == 1) $('#penghargaan').addClass("menuAktifDynamisMerahAbu");else $('#penghargaan').addClass("menuAktifDynamis");
    }
    else if(varItem == "PENILAIAN")
    {
        if(info_data == 1) $('#dp3').addClass("menuAktifDynamisMerahAbu");else $('#dp3').addClass("menuAktifDynamis");
    }
    else if(varItem == "POTENSI_DIRI")
    {
        if(info_data == 1) $('#potensidiri').addClass("menuAktifDynamisMerahAbu");else $('#potensidiri').addClass("menuAktifDynamis");
    }
    else if(varItem == "PRESTASI_KERJA")
    {
        if(info_data == 1) $('#catatanprestasi').addClass("menuAktifDynamisMerahAbu");else $('#catatanprestasi').addClass("menuAktifDynamis");
    }
    else if(varItem == "HUKUMAN")
    {
        if(info_data == 1) $('#hukuman').addClass("menuAktifDynamisMerahAbu");else $('#hukuman').addClass("menuAktifDynamis");
    }
    else if(varItem == "CUTI")
    {
        if(info_data == 1) $('#cuti').addClass("menuAktifDynamisMerahAbu");else $('#cuti').addClass("menuAktifDynamis");
    }
    else if(varItem == "TUGAS")
    {
        if(info_data == 1) $('#riwayatpenugasan').addClass("menuAktifDynamisMerahAbu");else $('#riwayatpenugasan').addClass("menuAktifDynamis");
    }
    else if(varItem == "BAHASA")
    {
        if(info_data == 1) $('#bahasa').addClass("menuAktifDynamisMerahAbu");else $('#bahasa').addClass("menuAktifDynamis");	
    }
    else if(varItem == "NIKAH_RIWAYAT")
    {
        if(info_data == 1) $('#nikah').addClass("menuAktifDynamisMerahAbu");else $('#nikah').addClass("menuAktifDynamis");
    }
    else if(varItem == "TAMBAHAN_MASA_KERJA")
    {
        if(info_data == 1) $('#tambmasakerja').addClass("menuAktifDynamisMerahAbu");else $('#tambmasakerja').addClass("menuAktifDynamis");
    }
}