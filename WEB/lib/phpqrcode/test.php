<?php    
    include "qrlib.php";    

    // how to save PNG codes to server 
     
    $tempDir = "temp/"; 
     
    $codeContents = 'This Goes From File'; 
     
    // we need to generate filename somehow,  
    // with md5 or with database ID used to obtains $codeContents... 
    $fileName = '005_file_'.md5($codeContents).'.png'; 
     
    $pngAbsoluteFilePath = $tempDir.$fileName; 
    $urlRelativeFilePath = $tempDir.$fileName; 
     
    // generating 
    if (!file_exists($pngAbsoluteFilePath)) { 
        QRcode::png($codeContents, $pngAbsoluteFilePath); 
    } 
?>

    