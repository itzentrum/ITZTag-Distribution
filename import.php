<?php
session_start();
if (isset($_FILES["upload-file"]))
  {
    var_dump($_FILES["upload-file"]["type"]);
    if ($_FILES["upload-file"]["type"]=="text/rtf" || $_FILES["upload-file"]["type"]=="application/rtf" || ($_FILES["upload-file"]["type"]=="application/msword" && stristr($_FILES["upload-file"]["name"],".rtf")!=FALSE))
      {
	$cmd="unrtf ". $_FILES["upload-file"]["tmp_name"];
	exec($cmd,$html);
	$html=implode($html);
	$html=str_replace("<!---","<!--",$html);
	$html=str_replace("--->","-->",$html);
      }
    else if ($_FILES["upload-file"]["type"]=="application/xml" || $_FILES["upload-file"]["type"]=="text/xml" || $_FILES["upload-file"]["type"]=="text/html" || $_FILES["upload-file"]["type"]=="application/sgml" || $_FILES["upload-file"]["type"]=="text/sgml"  || $_FILES["upload-file"]["type"]=="text/plain")
      {
	$html=implode(file($_FILES["upload-file"]["tmp_name"]));
      }
    else
      {
	echo "<h1>Ung&uuml;ltiges Dateiformat: ".$_FILES["upload-file"]["type"]."</h1>";
      }
    $_SESSION["data"]=$html;
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta http-equiv="refresh" content="10; URL=index.php">
</head>
</html>