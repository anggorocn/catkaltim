<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/Konfigurasi.php");

$reqSubmit= httpFilterPost("reqSubmit");
$reqRowId= httpFilterPost("reqRowId");

if($reqSubmit == "update")
{
  $set= new Konfigurasi();
  $set->selectByParams(array('KONFIGURASI_ID'=>1), -1, -1);
  $set->firstRow();
  $tempInfoLink= $set->getField('INFO_LINK');
  $tempInfoEmailFrom= $set->getField('INFO_EMAIL_FROM');
  unset($set);
  
  $reqId= $userLogin->userPelamarEnkripId;
  $set= new Pelamar();
  $set->selectByParams(array("md5(CAST(PELAMAR_ID as TEXT))"=>$reqId),-1,-1);
  $set->firstRow();
  $reqId= $set->getField("PELAMAR_ID");
  $tempNama= $set->getField("NAMA");
  $tempEmail1= $set->getField("EMAIL1");
  $tempNpp= $set->getField("NPP");
  unset($set);

  $set= new Pelamar();
  $set->setField("PELAMAR_ID", $reqId);
  $set->setField("FORMASI_ID", $reqRowId);
  if($set->updateKirimLamaran())
  {
	exit;
	$set_registrasi= new Pelamar();
  	$set_registrasi->selectByParamsNoRegistrasi(array("PELAMAR_ID"=>$reqId),-1,-1);
  	$set_registrasi->firstRow();
  	$tempKodeRegistrasi= $set_registrasi->getField("KODE_REGISTRASI");
  	$tempFormasiKode= $set_registrasi->getField("FORMASI_DETIL_KODE");
  	$tempFormasiNama= $set_registrasi->getField("FORMASI_DETIL_NAMA");
  	unset($set_registrasi);
	
	$message = '
				<html xmlns:v="urn:schemas-microsoft-com:vml"
				xmlns:o="urn:schemas-microsoft-com:office:office"
				xmlns:w="urn:schemas-microsoft-com:office:word"
				xmlns:st1="urn:schemas-microsoft-com:office:smarttags"
				xmlns="http://www.w3.org/TR/REC-html40">
				
				<head>
				<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
				<meta name=ProgId content=Word.Document>
				<meta name=Generator content="Microsoft Word 11">
				<meta name=Originator content="Microsoft Word 11">
				<link rel=File-List href="registrasi_files/filelist.xml">
				<title>Registrasi KPK.COM</title>
				<o:SmartTagType namespaceuri="urn:schemas-microsoft-com:office:smarttags"
				 name="City"/>
				<o:SmartTagType namespaceuri="urn:schemas-microsoft-com:office:smarttags"
				 name="place"/>
				<!--[if gte mso 9]><xml>
				 <o:DocumentProperties>
				  <o:Author>kay audrey</o:Author>
				  <o:LastAuthor>kay audrey</o:LastAuthor>
				  <o:Revision>19</o:Revision>
				  <o:TotalTime>53</o:TotalTime>
				  <o:Created>2010-02-18T02:58:00Z</o:Created>
				  <o:LastSaved>2010-05-12T08:29:00Z</o:LastSaved>
				  <o:Pages>1</o:Pages>
				  <o:Words>180</o:Words>
				  <o:Characters>1028</o:Characters>
				  <o:Company>Quantum HRM</o:Company>
				  <o:Lines>8</o:Lines>
				  <o:Paragraphs>2</o:Paragraphs>
				  <o:CharactersWithSpaces>1206</o:CharactersWithSpaces>
				  <o:Version>11.5606</o:Version>
				 </o:DocumentProperties>
				</xml><![endif]--><!--[if gte mso 9]><xml>
				 <w:WordDocument>
				  <w:DontDisplayPageBoundaries/>
				  <w:PunctuationKerning/>
				  <w:ValidateAgainstSchemas/>
				  <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>
				  <w:IgnoreMixedContent>false</w:IgnoreMixedContent>
				  <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>
				  <w:Compatibility>
				   <w:BreakWrappedTables/>
				   <w:SnapToGridInCell/>
				   <w:WrapTextWithPunct/>
				   <w:UseAsianBreakRules/>
				   <w:DontGrowAutofit/>
				  </w:Compatibility>
				  <w:BrowserLevel>MicrosoftInternetExplorer4</w:BrowserLevel>
				 </w:WordDocument>
				</xml><![endif]--><!--[if gte mso 9]><xml>
				 <w:LatentStyles DefLockedState="false" LatentStyleCount="156">
				 </w:LatentStyles>
				</xml><![endif]--><!--[if !mso]><object
				 classid="clsid:38481807-CA0E-42D2-BF39-B33AF135CC4D" id=ieooui></object>
				<style>
				st1\:*{behavior:url(#ieooui) }
				</style>
				<![endif]-->
				<style>
				<!--
				 /* Font Definitions */
				 @font-face
					{font-family:PMingLiU;
					panose-1:2 1 6 1 0 1 1 1 1 1;
					mso-font-alt:\65B0\7D30\660E\9AD4;
					mso-font-charset:136;
					mso-generic-font-family:auto;
					mso-font-format:other;
					mso-font-pitch:variable;
					mso-font-signature:1 134742016 16 0 1048576 0;}
				@font-face
					{font-family:"\@PMingLiU";
					panose-1:0 0 0 0 0 0 0 0 0 0;
					mso-font-charset:136;
					mso-generic-font-family:auto;
					mso-font-format:other;
					mso-font-pitch:variable;
					mso-font-signature:1 134742016 16 0 1048576 0;}
				 /* Style Definitions */
				 p.MsoNormal, li.MsoNormal, div.MsoNormal
					{mso-style-parent:"";
					margin:0cm;
					margin-bottom:.0001pt;
					mso-pagination:widow-orphan;
					font-size:12.0pt;
					font-family:"Times New Roman";
					mso-fareast-font-family:PMingLiU;}

				a:link, span.MsoHyperlink
					{color:blue;
					text-decoration:underline;
					text-underline:single;}
				a:visited, span.MsoHyperlinkFollowed
					{color:purple;
					text-decoration:underline;
					text-underline:single;}
				@page Section1
					{size:612.0pt 792.0pt;
					margin:72.0pt 90.0pt 72.0pt 90.0pt;
					mso-header-margin:35.4pt;
					mso-footer-margin:35.4pt;
					mso-paper-source:0;}
				div.Section1
					{page:Section1;}
				 /* List Definitions */
				 @list l0
					{mso-list-id:373507483;
					mso-list-template-ids:1098394468;}
				@list l0:level1
					{mso-level-number-format:bullet;
					mso-level-text:\F0B7;
					mso-level-tab-stop:36.0pt;
					mso-level-number-position:left;
					text-indent:-18.0pt;
					mso-ansi-font-size:10.0pt;
					font-family:Symbol;}
				@list l0:level2
					{mso-level-tab-stop:72.0pt;
					mso-level-number-position:left;
					text-indent:-18.0pt;}
				@list l0:level3
					{mso-level-tab-stop:108.0pt;
					mso-level-number-position:left;
					text-indent:-18.0pt;}
				@list l0:level4
					{mso-level-tab-stop:144.0pt;
					mso-level-number-position:left;
				
					text-indent:-18.0pt;}
				@list l0:level5
					{mso-level-tab-stop:180.0pt;
					mso-level-number-position:left;
					text-indent:-18.0pt;}
				@list l0:level6
					{mso-level-tab-stop:216.0pt;
					mso-level-number-position:left;
					text-indent:-18.0pt;}
				@list l0:level7
					{mso-level-tab-stop:252.0pt;
					mso-level-number-position:left;
					text-indent:-18.0pt;}
				@list l0:level8
					{mso-level-tab-stop:288.0pt;
					mso-level-number-position:left;
					text-indent:-18.0pt;}
				@list l0:level9
					{mso-level-tab-stop:324.0pt;
					mso-level-number-position:left;
					text-indent:-18.0pt;}
				@list l1
					{mso-list-id:1116024843;
					mso-list-template-ids:-337212034;}
				@list l1:level1
					{mso-level-number-format:bullet;
					mso-level-text:\F0B7;
					mso-level-tab-stop:36.0pt;
					mso-level-number-position:left;
					text-indent:-18.0pt;
					mso-ansi-font-size:10.0pt;
					font-family:Symbol;}
				ol
					{margin-bottom:0cm;}
				ul
					{margin-bottom:0cm;}
				-->
				</style>
				<!--[if gte mso 10]>
				<style>
				 /* Style Definitions */
				 table.MsoNormalTable
					{mso-style-name:"Table Normal";
					mso-tstyle-rowband-size:0;
					mso-tstyle-colband-size:0;
					mso-style-noshow:yes;
					mso-style-parent:"";
					mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
					mso-para-margin:0cm;
					mso-para-margin-bottom:.0001pt;
					mso-pagination:widow-orphan;
					font-size:10.0pt;
					font-family:"Times New Roman";
					mso-ansi-language:#0400;
					mso-fareast-language:#0400;
					mso-bidi-language:#0400;}
				</style>
				<![endif]--><!--[if gte mso 9]><xml>
				 <o:shapedefaults v:ext="edit" spidmax="11266"/>
				</xml><![endif]--><!--[if gte mso 9]><xml>
				 <o:shapelayout v:ext="edit">
				  <o:idmap v:ext="edit" data="1"/>
				 </o:shapelayout></xml><![endif]-->
				</head>
				
				<body lang=EN-US link=blue vlink=purple style=\'tab-interval:36.0pt\'>
				
				<div class=Section1>
				
				<p class=MsoNormal><span lang=NO-BOK style=\'mso-ansi-language:NO-BOK\'>
				Terima kasih atas kesediaan Anda untuk melengkapi data administrasi dan mengirimkan lamaran.
				<br/>

				Data diri Anda sebagai berikut :<br>
				<b>Nama</b> : '.$tempNama.'<br>
				<b>NPP/NRP/NIK</b> : '.$tempNpp.'<br>
				<b>E-mail</b> : '.$tempEmail1.'<br>
				No. Registrasi Anda	: '.$tempKodeRegistrasi.'<br>
				Posisi yang dilamar	: '.$tempFormasiKode.' - '.$tempFormasiNama.'<br>
				telah terekam dalam database kami.<br>
				<br>
				
				Anda Sukses Mengirim Lamaran.
				
				<br>
				<br>
				Terima kasih.
				<br>
				Panitia Seleksi<br>
				Quantum HRM International
				</div>
				
				</body>
				
				</html>
	
	';
	//sehubungan dengan Program Indonesia Memanggil 9 - Tahun 2015
	//<br>
	//Anda harus mengingat, menyimpan, dan/ atau cetak e-mail ini, untuk dapat digunakan selama Program Indonesia Memanggil 9 - Tahun 2015.
	//<br>				 
	//<br>
	//Pengumuman hasil seleksi administrasi dapat dilihat dengan cara log-in pada situs-web http://kpk.quantum-assessment.com pada tanggal 30 Juli 2015
	//<br>
	
	//Untuk pencetakan KARTU REGISTRASI PESERTA, Silahkan gunakan link berikut ini :<br>
	//1. Log-in pada situs web http://kpk.quantum-assessment.com<br>
	//2. Pilih Menu PENGUMUMAN kemudian klik pilihan KARTU REGISTRASI PESERTA<br>
	//3. Print KARTU REGISTRASI PESERTA<br>
				
	//$subject= "Email Konfirmasi Lamaran E-Recruitment KPK dan Pencetakan KARTU REGISTRASI PESERTA";
	$subject= "Email Konfirmasi Lamaran E-Recruitment KPK";
	$message= $message;

	$to = $tempEmail1;
	
	
//	require_once("../WEB/lib/PHPMailer/PHPMailerAutoload.php");
//	$mail = new PHPMailer();
//	$mail->isSMTP(); // Set mailer to use SMTP
	
	//$mail->Host = 'smtp.bappenas.go.id'; // Specify main and backup SMTP servers
//	$mail->SMTPAuth = true; // Enable SMTP authentication
//	$mail->Username = 'mamun'; // SMTP username
//	$mail->Password = 'amelia'; // SMTP password
//	$mail->SMTPSecure = 'ssl'; // Enable TLS encryption, ssl also accepted
//	$mail->Port = 465; // TCP port to connect to
//	$mail->From = 'noreply@kpk.go.id';
//	$mail->FromName = 'KPK - Komisi Pemberantasan Korupsi';
//	$mail->AddAddress ($tempEmail1);// Add a recipient
//			
//	$mail->WordWrap = 50; // Set word wrap to 50 characters
//	// Optional name
//	$mail->isHTML(true); // Set email format to HTML
//	
//	$mail->Subject = $subject;
//	$mail->Body = $message;
	//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	
	$to= $tempEmail1;
	$headers= array(
	'From: '.$tempInfoEmailFrom,
	'Reply-To: noreply@kpk.go.id',
	'Content-Type: text/html'
	);
	
	$headers = "From: $tempInfoEmailFrom\r\n".
               "MIME-Version: 1.0" . "\r\n" .
               "Content-type: text/html; charset=UTF-8" . "\r\n"; 
			   
	/*$headers= 'From: KPK - Komisi Pemberantasan Korupsi' . "\r\n" .
		'Reply-To: noreply@kpk.go.id' . "\r\n" .
		'Content-Type: text/html'.
		'X-Mailer: PHP/' . phpversion();*/
	
	if(mail($to, $subject, $message, $headers) == false)
	//if(!$mail->send()) 
	{
		echo 'Message could not be sent.';
		//echo 'Mailer Error: ' . $mail->ErrorInfo;
	} 
	else 
	{
		echo "Sukses melakukan kirim ulang validasi. Silakan cek email Anda. Pastikan cek pula di spam folder";
		//echo 'Message has been sent';
	}
	//echo $id."-Data berhasil disimpan.";
  }
  
  //echo $set->query;
}
?>