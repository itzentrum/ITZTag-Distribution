<?php
session_start();
var_dump($_REQUEST);
include_once "mysql_config.php";
include_once "color_list.php";

mysql_connect('localhost',$mysql_user,$mysql_pass);
mysql_select_db('itztag');

if (isset($_REQUEST))
  {
    mysql_query("UPDATE sessions SET public=0 WHERE SessionID=".$_SESSION["id"].";");
    foreach(array_keys($_REQUEST) as $k)
      {
	if (strstr($k,"remove"))
	  {
	    $tag=substr($k,7,4096);
	    mysql_query("DELETE FROM tags WHERE SessionID=".$_SESSION["id"]." AND Tag=\"".$tag."\";");
	  }
	else if (strstr($k,"add"))
	  {      
	    if ($_REQUEST["tag_new"]!="Neuer Tag...")
	      {
		mysql_query("INSERT INTO tags (SessionID, Tag, Farbe) VALUES (".$_SESSION["id"].", \"".$_REQUEST["tag_new"]."\", \"".array_search($_REQUEST["color_new"],$colors)."\");"); 
	      }
	  }
	else if (strstr($k,"tag"))
	  {
	    $ct=substr($k,4);
	    if ($k!="tag_new")
	      mysql_query("UPDATE tags SET Farbe=\"".array_search($_REQUEST["color_$ct"],$colors)."\" WHERE Tag=\"".$_REQUEST[$k]."\" AND SessionID=".$_SESSION["id"].";");
	  }
	else if (strstr($k,"public"))
	  {
	    mysql_query("UPDATE sessions SET public=1 WHERE SessionID=".$_SESSION["id"].";");
	  }
 
      }
  }

$result=mysql_query('SELECT * FROM tags WHERE SessionID="'.$_SESSION["id"].'";');
$rpublic=mysql_query('SELECT public FROM sessions WHERE SessionID="'.$_SESSION["id"].'";');
$pub=mysql_fetch_row($rpublic);
?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript">
function change_color(pos,color)
{
  element=document.getElementsByName("color_"+pos)[0];
  element.value=color;
}
function select_color(pos)
{
  get_color = window.open("color.php?pos="+pos,"Farbw&auml;hler","dependent=no,height=400,width=800,menubar=no"); 
}
</script>
<body>
<form action="setup.php" method="post">
<table>
<?php
  $ct=0;
  while($t=mysql_fetch_array($result))
  {
    echo "<tr>\n";
    echo "  <td><input type=\"text\" name=\"tag_$ct\" value=\"".$t["Tag"]."\" /></td>\n";
    echo "  <td><input type=\"text\" name=\"color_$ct\" size=\"8\" value=\"".$colors[$t["Farbe"]]."\" /></td>\n";

    echo "  <td><button type=\"button\" onclick=\"select_color($ct)\"><img src=\"images/button_".$t["Farbe"].".gif\"></button></td>\n";
    echo "  <td><input type=\"submit\" value=\"Entfernen\" name=\"remove_".$t["Tag"]."\" /></td>\n";
    echo "</tr>\n";
    $ct++;
  }
?>
<tr>
<td><input type="text" name="tag_new" value="Neuer Tag..." /></td>
<td><input type="text" name="color_new" size="8" value="...neue Farbe" /></td>
<td><button type="button" onclick="select_color('new')"><img src="images/blank.gif"></button></td>
<td><input type="submit" value="Einf&uuml;gen" name="add" /></td>
</tr>
<tr>
<td>
    <input type="checkbox" name="public" value="public" <?php if ($pub[0]=="1") { echo "checked"; } ?>>&Ouml;ffentlich<br>
</td>
</tr>
<tr>
<td><input type="submit" value="Speichern"></td>
<td colspan=3><input type="button" value="Schlie&szlig;en und Editor neu laden" onclick="window.opener.location.reload(); window.close();" width="100%"></td>
</tr>
</table>
</form>
</body>
</html>