<?php

if(isset($_GET['birlestir'])){
include("birlestir.php");
    
}else if(isset($_GET['ayir'])){
    
      $jsondata = file_get_contents("TEKJSON/".$_GET['ayir']);
    
  
 $obj = json_decode($jsondata,true);
 
foreach($obj as $key => $val) {
    $File = fopen("AYIR/".$key, 'w');
    fwrite($File, json_encode($obj[$key],JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    fclose($File);
 //print_r();
}

ziple();






}else if (isset($_GET['ayikla'])){
    
if($_FILES["zip_file1"]["name"]) {
    $filename = $_FILES["zip_file1"]["name"];
    $source = $_FILES["zip_file1"]["tmp_name"];
    $type = $_FILES["zip_file1"]["type"];

    $name = explode(".", $filename);
    $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
    foreach($accepted_types as $mime_type) {
        if($mime_type == $type) {
            $okay = true;
            break;
        } 
    }

    $continue = strtolower($name[1]) == 'zip' ? true : false;
    if(!$continue) {
        $message = "Lütfen Zip uzantılı bir dosya seçin";
    }

  /* PHP current path */
  $filenoext = basename ($filename, '.zip');  // absolute path to the directory where zipper.php is in (lowercase)
  $filenoext = basename ($filenoext, '.ZIP');  // absolute path to the directory where zipper.php is in (when uppercase)
 $targetdir = "TEKJSON/"; // target directory
  $targetzip = "TEKJSON/" . $filename; // target zip file


    if(move_uploaded_file($source, $targetzip)) {
        
$zip = new ZipArchive;
$res = $zip->open($targetzip);
if ($res === TRUE) {
  $zip->extractTo(  $targetdir );
  $zip->close();
   $message = "Dosyanız Yüklendi İçeriğinizi <a href='?ayir=".$filenoext.".json'>zİP Olarak burdan indirin</a>";
       unlink($targetzip);
} else {
      unlink($targetzip);

  echo 'Arşiv Okunmuyor!';
}
            
        
       
    } else {    
        $message = "Yükleme Sırasında Bir Sorun Yaşandı.";
    }
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>HitMan 3 JSON Export</title>
</head>

<body>
<?php if($message) echo "<p>$message</p>"; ?>
<form enctype="multipart/form-data" method="post" action="">
 <p> Daha Önce Json Olarak indirdiğiniz dosyayı Zip  arşivi yaparak  Sisteme uplad edin ardından  sistem sizin için Oyunun Orjinal isimdeki JSON dosyalarına ayırsın</p>
<label>Zip Dosyası : <input type="file" name="zip_file1" /></label>
<br />
<input type="submit" name="submit" value="Upload" />
</form>
</body>
</html>
<?php    
    
}else{
if($_FILES["zip_file"]["name"]) {
    $filename = $_FILES["zip_file"]["name"];
    $source = $_FILES["zip_file"]["tmp_name"];
    $type = $_FILES["zip_file"]["type"];

    $name = explode(".", $filename);
    $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
    foreach($accepted_types as $mime_type) {
        if($mime_type == $type) {
            $okay = true;
            break;
        } 
    }

    $continue = strtolower($name[1]) == 'zip' ? true : false;
    if(!$continue) {
        $message = "Lütfen Zip uzantılı bir dosya seçin";
    }

  /* PHP current path */
  $filenoext = basename ($filename, '.zip');  // absolute path to the directory where zipper.php is in (lowercase)
  $filenoext = basename ($filenoext, '.ZIP');  // absolute path to the directory where zipper.php is in (when uppercase)
$targetdir = "TMP/"; // target directory
  $targetzip = "TMP/" . $filename; // target zip file


    if(move_uploaded_file($source, $targetzip)) {
        
$zip = new ZipArchive;
$res = $zip->open($targetzip);
if ($res === TRUE) {
  $zip->extractTo(  $targetdir );
  $zip->close();
   $message ="Dosyanız Yüklendi İçeriğinizi <a href='?birlestir'>Json Olarak burdan indirin</a>";
       unlink($targetzip);
} else {
      unlink($targetzip);

  echo 'Arşiv Okunmuyor!';
}
} else {    
        $message = "Yükleme Sırasında Bir Sorun Yaşandı.";
    }
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>HitMan 3 JSON  İmport</title>
</head>

<body>
<?php if($message) echo "<p>$message</p>"; ?>
<form enctype="multipart/form-data" method="post" action="">
 <p> Kullanım Talimatı Hazırladığım  RPKG2json Tool ile Oluşturduğunuz DLGE / RTLV / LOCR  kalsörleri içerisindeki<br> .JSON uzantılı Dosyaları (Her klasör Ayrı ayrı) Zip leyerek Upload Ediniz. ve ardından oluşan json dosyasını indirip içeriği Düzenleyiniz</p>
<label>Zip Dosyası : <input type="file" name="zip_file" /></label>
<br />
<input type="submit" name="submit" value="Upload" />
</form>
</body>
</html>
<?php
    
}
//phpinfo();

function ziple(){
$dir = 'AYIR';
$zip_file = 'Jsonlar.zip';

// Get real path for our folder
$rootPath = realpath($dir);

// Initialize archive object
$zip = new ZipArchive();
$zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // Add current file to archive
        $zip->addFile($filePath, $relativePath);
    }
}

// Zip archive will be created only after closing object
$zip->close();

foreach(glob($dir.'/*.JSON') as $v){
    unlink($v);
  
}
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.basename($zip_file));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($zip_file));
readfile($zip_file);



}


?>