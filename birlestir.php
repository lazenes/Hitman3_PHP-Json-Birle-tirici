<?php
//Enes BİBER Json Dosyaları Birleştirici
function utf8_converter($array)
{
    array_walk_recursive($array, function (&$item, $key) {
        if (!mb_detect_encoding($item, 'utf-8', true)) {
                $item = utf8_encode($item);
        }
    });

    return $array;
}


$folderhost = opendir("TMP/");

while (false !== ($jSONfileName = readdir($folderhost))) {
    if ($jSONfileName == "." || $jSONfileName == ".." || strpos($jSONfileName, "meta")) {
        continue;
    }
    
   /* 
   //Debug Satırı
   echo $jSONfileName."<br>";
    
}*/
    
   
   $jsondata = file_get_contents("TMP/".$jSONfileName);
    if (stripos($jSONfileName, "JSON")) {
  
 $obj = json_decode($jsondata,true);
 

  $tempArray[$jSONfileName]=$obj;
  
}
}

$arrayTojson=json_encode($tempArray,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);



header('Content-disposition: attachment; filename=jsonFile.json');
header('Content-type: application/json');


print_r($arrayTojson);


closedir($folderhost);

$dir = 'TMP/';
foreach(glob($dir.'*.*') as $v){
    unlink($v);
}


?>