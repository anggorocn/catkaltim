<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

$userLogin->checkLoginPelamar();

$reqId = httpFilterRequest("reqId");
$reqMode = httpFilterRequest("reqMode");
$reqSubmit = httpFilterPost("reqSubmit");
$reqRowId= httpFilterRequest("reqRowId");


?>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/globalfunction.js"></script>



<div class="col-lg-8">

    <div id="judul-halaman"><?=$arrayJudul["download_dokumen"]["judul"]?></div>
    
   
    
    <div class="judul-halaman2"><img src="../WEB/images/icon-input.png"> Form Download</div>
    <div id="pendaftaran">
        <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <table>
                <tr>
                    <td>Download Formulir Portofolio</td>
                    <td>
                        <a class="link-button" href="../Templates/portfolio.doc" target="_blank"><img src="../WEB/images/down.png" width="15" height="15" /> Download </a>
                    </td>        
                </tr>
                <!--  <tr>
                    <td>Download Formulir Q Kompetensi </td>
                    <td>
                        <a class="link-button" href="../Templates/form_q_competensi.docx" target="_blank"><img src="../WEB/images/down.png" width="15" height="15" /> Download </a>
                    </td>        
                </tr>
                 <tr>
                    <td>Download Form Data Critical Incident</td>
                    <td>
                        <a class="link-button" href="../Templates/form_critical_insident.docx" target="_blank"><img src="../WEB/images/down.png" width="15" height="15" /> Download </a>
                    </td>        
                </tr> -->
            </table>      
            <br>
           <!--  -->
        </form>
    </div>
    
</div>

<script type="text/javascript">
$('#reqNip').bind('keyup paste', function(){
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>