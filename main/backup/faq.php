<?
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Faq.php");

$faq = new Faq();
$faq->selectByParams(array());

?>
<div class="col-lg-8">

	<div id="judul-halaman">FAQ</div>
    
    <div class="faq-area">
    <?
    while($faq->nextRow())
	{
	?>
    	<div class="list">
            <div class="tanya">
                <i class="fa fa-question-circle fa-3x" aria-hidden="true"></i> <?=$faq->getField("PERTANYAAN")?>
            </div>
            <div class="jawab">
            	<i class="fa fa-check-circle fa-2x" aria-hidden="true"></i>
                <?=$faq->getField("JAWABAN")?>
            </div>
        </div>
    <?
	}
	?>    
    </div>

</div>