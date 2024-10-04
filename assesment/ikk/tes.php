<?php
function getFolderTree($path)
{
    //Recovers files and directories
    $paths = glob($path . "*", GLOB_MARK | GLOB_ONLYDIR | GLOB_NOSORT);
    $files = glob($path . "*");

    //Traverses the directories found
    foreach ($paths as $key => $path)
    {
        //Create directory if exists
        $directory = explode("\\", $path);
        unset($directory[count($directory) - 1]);
        $directories[end($directory)] = getFolderTree($path);

        //Verify if exists files
        foreach ($files as $file)
        {
            if (strpos(substr($file, 2), ".") !== false)
                $directories[] = substr($file, (strrpos($file, "\\") + 1));
        }
    }

    //Return the directories
    if (isset($directories))
    {
        return $directories;
    }
    //Returns the last level of folder
    else
    {
        $files2return = Array();
        foreach ($files as $key => $file)
            $files2return[] = substr($file, (strrpos($file, "\\") + 1));
        return $files2return;
    }
}

/**
 * Creates the HTML for the tree
 * 
 * @param array $directory Array containing the folder structure
 * @return string HTML
 */
function createTree($directory)
{
    $html = "<ul>";
    foreach($directory as $keyDirectory => $eachDirectory)
    {
        if(is_array($eachDirectory))
        {
            $html .= "<li class='closed'><span class='folder'>" . $keyDirectory . "</span>";
            $html .= createTree($eachDirectory);
            $html .=  "</li>";
        }
        else
        {
            $html .= "<li><span class='file'>" . $eachDirectory . "</span></li>";
        }
    }
    $html .= "</ul>";

    return $html;
}

function getFileCari($directory, $findme)
{
	foreach ($directory['INTEPRETASI ES 3'] as $k=>$v)
	{
	 if(stripos($v, $findme) !== false)
	 {
		return $v;
		exit;
	 }
	}
}

//Create output
$directory= getFolderTree('INTEPRETASI ES 3');
$findme="anjang bangun";
echo getFileCari($directory, $findme);

//print_r($directory);

//$directory= getFolderTree('INTEPRETASI ES 4');
//$htmlTree = createTree($directory["videos"]);


echo "----------";
?>