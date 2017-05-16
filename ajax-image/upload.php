<?
function getExtension($str) {$i=strrpos($str,".");if(!$i){return"";}$l=strlen($str)-$i;$ext=substr($str,$i+1,$l);return $ext;}
$formats = array("jpg", "png", "gif", "bmp", "jpeg", "PNG", "JPG", "JPEG", "GIF", "BMP");
if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
 $name = $_FILES['file']['name'];
 $size = $_FILES['file']['size'];
 $tmp  = $_FILES['file']['tmp_name'];
 if(strlen($name)){
  $ext = getExtension($name);
  if(in_array($ext,$formats)){
   if($size<(1024*1024)){
    $imgn = time().".".$ext;
    if(move_uploaded_file($tmp, "./uploads/".$imgn)){
     echo "File Name : ".$_FILES['file']['name'];
     echo "<br/>File Temporary Location : ".$_FILES['file']['tmp_name'];
     echo "<br/>File Size : ".$_FILES['file']['size'];
     echo "<br/>File Type : ".$_FILES['file']['type'];
     echo "<br/>Image : <img style='margin-left:10px;' src='uploads/".$imgn."'>";
    }else{
     echo "Uploading Failed.";
    }
   }else{
    echo "Image File Size Max 1 MB";
   }
  }else{
   echo "Invalid Image file format.";
  }
 }else{
  echo "Please select an image.";
  exit;
 }
}
?>
