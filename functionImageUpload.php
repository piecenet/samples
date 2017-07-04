<?
/* Function to upload images in a form
   Variables Expected:
     $coreroot // in order to include functionImageCreateFromBMP.php
     $imageuploaddir = "/home/client/public_html/path/"; // Path to upload to needs to have 777 folder permissions
     $imageuploadmaxsize = "1500000"; // Maximum allowed file size in bytes (2MB is default PHP uploaded file limit)
   Returns "Y" or "N|errormessage".
  */

include_once($coreroot."/includes/functionImageCreateFromBMP.php");
// This included function is from DHKold and has not been modified

function ImageUpload($imagefieldname, $savename, $maxheight, $maxwidth, $minheight, $minwidth) {
  global $imageuploaddir, $imageuploadmaxsize, $debuglog;
  // first 2 globals are only retrieved for use here, debuglog is to append error messages
  $imagedir = $imageuploaddir;
  $thiserror = "";
  $uploadedfile_name = $_FILES[$imagefieldname]['name'];
  $uploadedfile_tmp = $_FILES[$imagefieldname]['tmp_name'];
  $uploadedfile_size = $_FILES[$imagefieldname]['size'];
  $uploadedfile_type = $_FILES[$imagefieldname]['type'];
  if ($uploadedfile_size > $imageuploadmaxsize) {
    $thiserror .= "Error: Uploaded image size must be less than " . $imageuploadmaxsize/1000000 . " MB!<br />";
  } else {
    if (isset($_FILES[$imagefieldname]['name'])) {
      $temp_img = $imagedir.'tmp_'.$savename;
      $prod_img = $imagedir.$savename;
      move_uploaded_file($uploadedfile_tmp, $temp_img);
      chmod ($temp_img, octdec('0666'));
      $sizes = getimagesize($temp_img);
      if ($sizes[0] == 0 || $sizes[1] == 0) { $imageszerror = "Y"; $imageulerror = "Y"; }
      else {
        $aspect_ratio = $sizes[1]/$sizes[0];
        if ($sizes[1] <= $maxheight) { $new_width = $sizes[0]; $new_height = $sizes[1]; }
          else { $new_height = $maxheight; $new_width = abs($new_height/$aspect_ratio); }
        if ($new_width <= $maxwidth) { $new_width = $new_width; $new_height = $new_height; }
          else { $new_width = $maxwidth; $new_height = abs($new_width*$aspect_ratio); }
        $destimg=ImageCreateTrueColor($new_width,$new_height);
      }
      $debuglog .= "W: $new_width H: $new_height (After Max Adjustment)<br />\n";
      if ($uploadedfile_type == "image/jpeg" || $uploadedfile_type == "image/pjpeg") { $srcimg=ImageCreateFromJPEG($temp_img); }
      else if ($uploadedfile_type == "image/gif") { $srcimg=ImageCreateFromGIF($temp_img); }
      else if ($uploadedfile_type == "image/png" || $uploadedfile_type == "image/x-png") { $srcimg=ImageCreateFromPNG($temp_img); }
      else if ($uploadedfile_type == "image/bmp") { $srcimg=ImageCreateFromBMP($temp_img); }
      else {
        $imageulerror = "Y";
        if ($imageszerror == "Y") { $thiserror .= "Error: Uploaded file has 0 for either height or length!<br />"; }
        $thiserror .= "Error: $uploadedfile_type used.<br>Only GIF, JPEG, PNG, and Windows BMP files allowed!<br />";
      }
      if ($imageulerror != "Y") {
        if(function_exists('imagecopyresampled')) {
          imagecopyresampled($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg));
        } else {
          Imagecopyresized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg));
        }
      }
      if ($srcimg) {
        // Fix for minimum width and height
        if ($new_width < $minwidth || $new_height < $minheight) {
          if ($new_height < $minheight) {
            $newy = intval(($minheight-$new_height)/2);
            $thisheight = $minheight;
          } else {
            $newy = 0;
            $thisheight = $new_height;
          }
          if ($new_width < $minwidth) {
            $newx = intval(($minwidth-$new_width)/2);
            $thiswidth = $minwidth;
          } else {
            $newx = 0;
            $thiswidth = $new_width;
          }
          $debuglog .= "W: $thiswidth H: $thisheight (After Min Adjustment)<br />\n";
          $destimg=imagecreatetruecolor($thiswidth, $thisheight);
          $bk=imagecolorallocate($destimg, 255, 255, 255);
          imagefill($destimg,0,0,$bk);
          imagecopy($destimg, $srcimg, $newx, $newy, 0, 0, $new_width, $new_height);
        }
        // Sharpen image
        $sharpenmatrix = array(
            array(-1.2, -1, -1.2), // array(-1, -1, -1),
            array(-1.0, 28, -1.0), // array(-1, 16, -1),
            array(-1.2, -1, -1.2), // array(-1, -1, -1),
        );
        $sharpendivisor = array_sum(array_map('array_sum', $sharpenmatrix));
        imageconvolution($destimg, $sharpenmatrix, $sharpendivisor, 0);
        // Finally output production image
        ImageJPEG($destimg,$prod_img,95);
      }
      if ($imageulerror != "Y") { imagedestroy($destimg); }
      if (file_exists($temp_img)) { unlink($temp_img); }
    }
  }
  if ($imageulerror != "Y") { $success = "Y"; } else { $success = "N|$thiserror"; }
  return $success;
}

?>