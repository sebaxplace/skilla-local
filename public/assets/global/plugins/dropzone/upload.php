<?php

function getExtension($str)
{
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
}

function thumb_creator($source,$filename,$uploaddir){
	$extension = ".".getExtension($filename);
	$new_images = "thumb_".date(YmdHis).$extension;
	$tmp_img = $source['tmp_name'];
	$src_size = getimagesize($tmp_img);
	$width = 150;
	$height=round($width*$src_size[1]/$src_size[0]);
	if ($src_size['mime'] === 'image/jpeg'){
		$src = imagecreatefromjpeg($tmp_img);
	} else if ($src_size['mime'] === 'image/jpg'){
		$src = imagecreatefromjpeg($tmp_img);
	} else if ($src_size['mime'] === 'image/png'){
		$src = imagecreatefrompng($tmp_img);
	} else if ($src_size['mime'] === 'image/gif'){
		$src = imagecreatefromgif($tmp_img);
	}
	$photoX = ImagesX($src);
	$photoY = ImagesY($src);
	$images_fin = ImageCreateTrueColor($width, $height);
	ImageCopyResampled($images_fin, $src, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
	if ($src_size['mime'] === 'image/jpeg'){
		ImageJPEG($images_fin,$uploaddir."/".$new_images);
	} else if ($src_size['mime'] === 'image/jpg'){
		ImageJPEG($images_fin,$uploaddir."/".$new_images);
	} else if ($src_size['mime'] === 'image/png'){
		ImagePNG($images_fin,$uploaddir."/".$new_images);
	} else if ($src_size['mime'] === 'image/gif'){
		ImageGIF($images_fin,$uploaddir."/".$new_images);
	}
}

$valid_formats = array("jpg", "png", "gif","jpeg");


$ds          = DIRECTORY_SEPARATOR;  //1
 
$storeFolder = '../../../../uploads';   //2
$storeFolder_thumbs = '../../../../uploads/thumbs';
 
if (!empty($_FILES)) {
     
    $tempFile = $_FILES['file']['tmp_name'];          //3             
      
    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4
    $filename = stripslashes($_FILES['file']['name']);
    $exte = getExtension($filename);
	$exte = strtolower($exte);
    $targetFile =  $targetPath. $_FILES['file']['name'];  //5
 	thumb_creator($_FILES['file'],$filename,$storeFolder_thumbs);
    move_uploaded_file($tempFile,$targetFile); //6
     
}
?>

