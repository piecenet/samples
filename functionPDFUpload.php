<?php
/* Function to upload images in a form
   Variables Expected:
     $pdfuploaddir = "/home/client/public_html/path/"; // Path to upload to needs to have 777 folder permissions
     $pdfuploadmaxsize = "1500000"; // Maximum allowed file size in bytes
   Returns "Y" or "N|errormessage".
  */

function PDFUpload($pdffieldname, $savename) {
  global $pdfuploaddir, $pdfuploadmaxsize;  // globals are only retrieved for use here
  $pdfdir = "$pdfuploaddir";
  $thiserror = "";
  $uploadedfile_name = $_FILES[$pdffieldname]['name'];
  $uploadedfile_tmp = $_FILES[$pdffieldname]['tmp_name'];
  $uploadedfile_size = $_FILES[$pdffieldname]['size'];
  $uploadedfile_type = $_FILES[$pdffieldname]['type'];
  if ($uploadedfile_size > $pdfuploadmaxsize) {
    $thiserror .= "Error: Uploaded image size must be less than " . $imageuploadmaxsize/1000000 . " MB!<br />";
  } else {
    if (isset($_FILES[$pdffieldname]['name'])) {
      $prod_img = $pdfdir.$savename;
      if ($uploadedfile_type == "application/pdf" || $uploadedfile_type == "image/pdf") { $srcimg=$uploadedfile_tmp; }
      // Also MIME types? application/x-pdf, application/acrobat, applications/vnd.pdf, text/pdf, text/x-pdf
        else {
          $thiserror .= "Error: $uploadedfile_type used - only PDF files allowed!<br />";
        }
      if ($srcimg) { move_uploaded_file($srcimg,$prod_img); }
    }
  }
  if ($thiserror == "") { $success = "Y"; } else { $success = "N|$thiserror"; }
  return $success;
}

?>
