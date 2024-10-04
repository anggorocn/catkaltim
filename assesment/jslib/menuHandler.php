function perubahanData(varItem=""){
	
	if(varItem == "pegawai")
	{
		<? if($menu->getPerubahanDataMenu("PEGAWAI", $reqPegawaiId)){?>$('#idpegawai').addClass("menuAktifDynamisMerahAbu");<? } else{?>$('#idpegawai').addClass("menuAktifDynamis");<? }?>
	}
	else
	{
		<? if($menu->getPerubahanDataMenu("PEGAWAI", $reqPegawaiId)){?>$('#idpegawai').addClass("menuAktifDynamisMerah");<? }?>
	}
		
	// FIP - 01
	<? if($menu->getPerubahanDataMenu("PENGALAMAN", $reqPegawaiId)){?>$('#pengalamankerja').addClass("menuAktifDynamisMerah");<? }?>
	
	<? if($menu->getPerubahanDataMenu("SK_CPNS", $reqPegawaiId)){?>$('#sk-cpns').addClass("menuAktifDynamisMerah");<? }?>
	
	<? if($menu->getPerubahanDataMenu("SK_PNS", $reqPegawaiId)){?>$('#sk-pns').addClass("menuAktifDynamisMerah");<? }?>
	
	// FIP - 02
	<? if($menu->getPerubahanDataMenu("PANGKAT_RIWAYAT", $reqPegawaiId)){?>$('#riwayatpangkat').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("JABATAN_RIWAYAT", $reqPegawaiId)){?>$('#riwayatjabatan').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("GAJI_RIWAYAT", $reqPegawaiId)){?>$('#riwayatgaji').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("PENDIDIKAN_RIWAYAT", $reqPegawaiId)){?>$('#pendumum').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("DIKLAT_STRUKTURAL", $reqPegawaiId)){?>$('#diklatstruktural').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("DIKLAT_FUNGSIONAL", $reqPegawaiId)){?>$('#diklatfungsional').addClass("menuAktifDynamisMerah");<? }?>
	
	<? if($menu->getPerubahanDataMenu("DIKLAT_TEKNIS", $reqPegawaiId)){?>$('#diklatteknis').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("PENATARAN", $reqPegawaiId)){?>$('#penataran').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("SEMINAR", $reqPegawaiId)){?>$('#seminar').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("KURSUS", $reqPegawaiId)){?>$('#kursus').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("ORANG_TUA", $reqPegawaiId)){?>$('#ortu').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("MERTUA", $reqPegawaiId)){?>$('#mertua').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("SUAMI_ISTRI", $reqPegawaiId)){?>$('#suamiistri').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("ANAK", $reqPegawaiId)){?>$('#anak').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("SAUDARA", $reqPegawaiId)){?>$('#saudara').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("ORGANISASI_RIWAYAT", $reqPegawaiId)){?>$('#organisasi').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("PENGHARGAAN", $reqPegawaiId)){?>$('#penghargaan').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("PENILAIAN", $reqPegawaiId)){?>$('#dp3').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("POTENSI_DIRI", $reqPegawaiId)){?>$('#potensidiri').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("PRESTASI_KERJA", $reqPegawaiId)){?>$('#catatanprestasi').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("HUKUMAN", $reqPegawaiId)){?>$('#hukuman').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("CUTI", $reqPegawaiId)){?>$('#cuti').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("TUGAS", $reqPegawaiId)){?>$('#riwayatpenugasan').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("BAHASA", $reqPegawaiId)){?>$('#bahasa').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("NIKAH_RIWAYAT", $reqPegawaiId)){?>$('#nikah').addClass("menuAktifDynamisMerah");<? }?>
	<? if($menu->getPerubahanDataMenu("TAMBAHAN_MASA_KERJA", $reqPegawaiId)){?>$('#tambmasakerja').addClass("menuAktifDynamisMerah");<? }?>
	

}