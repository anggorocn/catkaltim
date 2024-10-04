<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/MasterJabatan.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set = new MasterJabatan();

$id = isset($_POST['id']) ? $_POST['id'] : 0;

if ($id == 0)
{
    $set->selectByParamsMasterJabatanCombo(array("PARENT_ID" => 0), -1, -1, $statement);
    // echo $set->query;exit;
    //echo $set->errorMsg;exit;
    $i=0;
    while($set->nextRow())
    {
        $items[$i]['id'] = $set->getField("ID");
        $items[$i]['text'] = $set->getField("NAMA_JABATAN");
        $items[$i]['rumpunid'] = $set->getField("RUMPUN_ID");
        $items[$i]['rumpunnama'] = $set->getField("RUMPUN_NAMA");
        $items[$i]['satkerid'] = $set->getField("SATKER_ID");
        $items[$i]['satkernama'] = $set->getField("SATKER_NAMA");
        $items[$i]['state'] = has_child($set->getField("ID"), $statement) ? 'closed' : 'open';
        //$items[$i]['state'] = 'closed';
        $i++;
    }
}
else 
{   
    $set->selectByParamsMasterJabatanCombo(array("PARENT_ID" => $id), -1, -1, $statement);
    // echo $set->query;exit;
    $i=0;
    while($set->nextRow())
    {
        $items[$i]['id'] = $set->getField("ID");
        $items[$i]['text'] = $set->getField("NAMA_JABATAN");
        $items[$i]['rumpunid'] = $set->getField("RUMPUN_ID");
        $items[$i]['rumpunnama'] = $set->getField("RUMPUN_NAMA");
        $items[$i]['satkerid'] = $set->getField("SATKER_ID");
        $items[$i]['satkernama'] = $set->getField("SATKER_NAMA");
        $items[$i]['state'] = has_child($set->getField("ID"), $statement) ? 'closed' : 'open';
        //$result[$i]['state'] = 'open';
        $i++;
    }
}

function has_child($id, $statement)
{
    $child = new MasterJabatan();
    $child->selectByParamsMasterJabatanCombo(array("PARENT_ID" => $id), -1, -1, $statement);
    $child->firstRow();
    $tempId= $child->getField("ID");
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