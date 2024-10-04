<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/PelatihanHcdp.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set = new PelatihanHcdp();

$id = isset($_POST['id']) ? $_POST['id'] : 0;

$statement= "
AND EXISTS
(
	SELECT 1
	FROM
	(
		SELECT
		PERMEN_ID
		FROM PERMEN
		WHERE STATUS = '1'
	) XXX WHERE A.PERMEN_ID = XXX.PERMEN_ID
)
";

if ($id == 0)
{
    $set->selectByParams(array("PELATIHAN_HCDP_ID_PARENT" => 0), -1, -1, $statement);
    // echo $set->query;exit;
    //echo $set->errorMsg;exit;
    $i=0;
    while($set->nextRow())
    {
        $items[$i]['id'] = $set->getField("PELATIHAN_HCDP_ID");
        $items[$i]['text'] = $set->getField("NAMA");
        $items[$i]['parentid'] = $set->getField("PELATIHAN_HCDP_ID_PARENT");
        $items[$i]['state'] = has_child($set->getField("PELATIHAN_HCDP_ID"), $statement) ? 'closed' : 'open';
        //$items[$i]['state'] = 'closed';
        $i++;
    }
}
else 
{   
    $set->selectByParams(array("PELATIHAN_HCDP_ID_PARENT" => $id), -1, -1, $statement);
    // echo $set->query;exit;
    $i=0;
    while($set->nextRow())
    {
        $items[$i]['id'] = $set->getField("PELATIHAN_HCDP_ID");
        $items[$i]['text'] = $set->getField("NAMA");
        $items[$i]['parentid'] = $set->getField("PELATIHAN_HCDP_ID_PARENT");
        $items[$i]['state'] = has_child($set->getField("PELATIHAN_HCDP_ID"), $statement) ? 'closed' : 'open';
        //$result[$i]['state'] = 'open';
        $i++;
    }
}

function has_child($id, $statement)
{
    $child = new PelatihanHcdp();
    $child->selectByParams(array("PELATIHAN_HCDP_ID_PARENT" => $id), -1, -1, $statement);
    $child->firstRow();
    $tempId= $child->getField("PELATIHAN_HCDP_ID");
    //echo $child->query;exit;
    //echo $child->errorMsg;exit;
    //echo $tempId;exit;
    if($tempId == "")
    return false;
    else
    return true;
    unset($child);
}

echo json_encode($items);
?>