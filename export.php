<?php
  session_start();
  header('Content-disposition: attachment; filename="tagged.xml"'); 
  header ('Content-Type: application/xml; charset=utf-8');
  echo str_replace("\\\"","\"",$_SESSION["data"]);
?>