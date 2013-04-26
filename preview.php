<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
<?php
include_once "mysql_config.php";
include_once "color_list.php";

mysql_connect('localhost',$mysql_user,$mysql_pass);
mysql_select_db('itztag');
$qresult=mysql_query('SELECT * FROM sessions WHERE Name="'.$_GET['name'].'";');
$result=mysql_fetch_array($qresult, MYSQL_ASSOC);
$tagresult=mysql_query('SELECT Tag, Farbe FROM tags WHERE SessionID=(SELECT SessionID FROM sessions WHERE Name="'.$_GET['name'].'");');
$tagplugins="";
$count=0;
$t=mysql_fetch_array($tagresult);
while ($t)
  {
    $tagplugins=$tagplugins."{name: 'itztag', label: '".$t["Tag"]."', color: '".$colors[$t["Farbe"]]."', number: $count}, ";
    $t=mysql_fetch_array($tagresult);
    $count++;
  }
//var_dump($result);
echo "<title>Vorschau: ".$result["Name"]."</title>";
//echo "<link rel=\"stylesheet\" href=\"css/".$result["Name"].".css\" >";
?>

</head>
<body>
<table align="left" width="100%">
<tr>
<td width="25">
<table id="balken" cellspacing="0" cellpadding="0" width="15" border="1" rules="none" frame="box">
  </table>
</td>
<td>
<iframe id="data" width="100%" height="800">
</iframe>
</td>
</tr>
</table>
<script type="text/javascript" src="balkengrafik.js">
</script>
<script type="text/javascript">
  document.getElementById('data').src = "data:text/html;charset=UTF-8,<?php echo rawurlencode("<style>".file_get_contents("css/".$result["Name"].".css")."</style>".$result["Data"]); ?>";

setTimeout(function() {
    balken(window.frames[0].document.getElementsByTagName('body')[0],document.getElementById('balken'),<?php echo "[".rtrim($tagplugins,", ")."]"; ?>);
  },1250);

</script>
</body>
</html>
