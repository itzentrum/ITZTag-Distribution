<?php
if (!isset($_REQUEST["logout"]))
  {
    session_start();
    
  }
else
  {
    session_destroy();
    setcookie(session_name(), '', time()-42000, '/');
  }
include_once "color_list.php";
include_once "mysql_config.php";

mysql_connect('localhost',$mysql_user,$mysql_pass);
mysql_select_db('itztag');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" href="dojo/dijit/themes/claro/claro.css">
  <link rel="stylesheet" href="dojo/dojox/editor/plugins/resources/css/Save.css">
  <script src='dojo/dojo/dojo.js.uncompressed.js' data-dojo-config='parseOnLoad: true, isDebug: true'></script>
  <!-- script src='dojo-engine/dojo/dojo.js.uncompressed.js' data-dojo-config='parseOnLoad: true, isDebug: true'></script -->
  <?php
  if (isset($_POST["name"])||isset($_SESSION["name"]))
    {
      //var_dump($_SESSION);
      if ($_SESSION["data"]=="" && isset($_POST["name"]))
	{					
	  $result=mysql_fetch_array(mysql_query('SELECT * FROM sessions WHERE Name="'.$_POST["name"].'";'));
	  if (!$result && $_POST["name"])
	    {
	      mysql_query('INSERT INTO sessions (Name, Password) VALUES ("'.$_POST["name"].'", "'.md5($_POST["pass"]).'");');
	      echo "<h1>Neue Session erstellt</h1>";
	      $result=mysql_fetch_array(mysql_query('SELECT * FROM sessions WHERE Name="'.$_POST["name"].'";'));
	      $data="";
	    }
	  else if ($result["Password"]==md5($_POST["pass"]))
    {
	      echo "<h1>Erfolgreich angemeldet f&uuml;r Projekt ".$_POST["name"]."</h1>";
	      $data=$result["Data"];						
	    }
	  else
	    {
	      echo 'SELECT * FROM sessions WHERE Name="'.$_POST["name"].'";';
	      session_destroy();
	      setcookie(session_name(), '', time()-42000, '/');
	      echo "<h1>Falscher Benutzername oder falsches Passwort</h1>";
	      echo '<a href="index.php">Zur&uuml;ck</a>';
	      exit(0); 
	    }
	  $_SESSION["name"]=$_POST["name"];
	  $_SESSION["pass"]=$_POST["pass"];
	  $_SESSION["id"]=$result["SessionID"];
	  $_SESSION["data"]=$data;
	}
      $result=mysql_query("SELECT Tag, Farbe FROM tags WHERE SessionID=".$_SESSION["id"].";");
      $tagplugins="";
      $style_file="./css/".$_SESSION["name"].".css";
      $fh=fopen($style_file,"w+");
      echo "<style type=\"text/css\">\n";
      $count=0;
      while ($t=mysql_fetch_array($result))
	{
	  fputs($fh,strtolower($t["Tag"])." { background-color: ".$colors[$t["Farbe"]]."; }\n");
	  $tagplugins=$tagplugins."{name: 'itztag', label: '".$t["Tag"]."', color: '".$colors[$t["Farbe"]]."', number: $count}, ";
	  echo ".dijitEditorIconITZTag_".$t["Tag"]." { background-image:url(images/button_".$t["Farbe"].".gif); }\n";
	  $count++;
	}
      fclose($fh);
      echo $tagstyles;
      echo "</style>\n";
      ?>
      <title>Projekt: <?php echo $_SESSION["name"]; ?></title>
      <script>require(["dijit/Editor","dijit/_editor/plugins/ITZTag","dijit/_editor/plugins/FullScreen","dojox/editor/plugins/Save","dijit/_editor/plugins/ViewSource"]);</script>
      <script type="text/javascript" src="dom.js">
      </script>
      <script type="text/javascript" src="balkengrafik.js">
      </script>
      </head>
      
      <body class="claro">
      <form action="index.php" method="post">
      <input type="submit" name="logout" value="Abmelden" />
      </form>
      <br />
      <table>
      <tr>
      <td width="100%" valign="top">
      <div data-dojo-type="dijit/Editor" id="editor1" data-dojo-props="height: '600px', plugins:['cut','copy','paste','|','undo','redo','|'],extraPlugins:[<?php echo $tagplugins; ?>'|',{name: 'save', url: 'save.php'},'|','viewsource','fullscreen'], style:'wort {background-color: #0000AA; }', styleSheets: '<?php echo $style_file; ?>'">
      <?php echo $_SESSION["data"]; ?>
      </div>
      <form action="import.php" method="post" enctype="multipart/form-data" accept-charset="UTF-8" target="_self">
      <input type="file" name="upload-file" size="50" maxlength="5000000"> <br />
      <input type="submit" value="Hochladen" name="upload" size="100"> <br />
      </form>
      <a href="setup.php" target="_blank">Einrichten</a> (Wichtig: Zuerst speichern)<br />
      <a href="export.php" target="_blank">Exportieren</a><br />
      <input type="button" value="DOM" onclick="print_dom(window.frames[0].document.getElementsByTagName('*')[0]);" />
      <input type="button" value="Balken aktualisieren" onclick="balken(window.frames[0].document.getElementById('dijitEditorBody'),getElementById('balken'),<?php echo "[".rtrim($tagplugins,", ")."]"; ?>);" /><br/></br />
      </td>
      <td width="20px" valign="top">
      <table id="balken" cellspacing="0" cellpadding="0" width="15" border="1" rules="none" frame="box">
      </table>
      </td>
      </tr>
      </table>
      <script type="text/javascript">
	setTimeout(function() {
	    balken(window.frames[0].document.getElementById('dijitEditorBody'),getElementById('balken'),<?php echo "[".rtrim($tagplugins,", ")."]"; ?>);
	  },2000);
      }
      </script>
      <?php
    }
  else
    {
      ?>
      <form method="post" action="index.php">
	<h3>Bestehende Sitzungen</h3>
	<table>
	<?php
	mysql_connect('localhost',$mysql_user,$mysql_pass);
      mysql_select_db('itztag');
      $qresult=mysql_query('SELECT * FROM sessions;');
      $result=mysql_fetch_array($qresult, MYSQL_ASSOC);
      while ($result)
	{
	  //var_dump($result);
	  echo "<tr>\n<td width=400><input type=\"radio\" name=\"name\" value=\"".$result["Name"]."\">".$result["Name"]."</input></td>\n";
	  if ($result["Public"])
	    echo "<td><a href=\"preview.php?name=".rawurlencode($result["Name"])."\" target=\"_blank\">Vorschau</a></td>\n";
	  echo "</tr>\n";
	  $result=mysql_fetch_array($qresult, MYSQL_ASSOC);
	}
      ?>
      </table>
	  Passwort<br />
	  <input type="password" name="pass" size="80" /><br />
	  <input type="submit" value="Anmelden" />
	  </form>
	  <form method="post" action="index.php">
	  <h3>Neue Sitzung</h3>
	  Name<br />
	 <input type="text" name="name" size="80" /><br />
	  Passwort<br />
	 <input type="password" name="pass" size="80" /><br />
	 <input type="submit" value="Anmelden" />
	 </form>
	  <?php
	  }
?>
</body>
</html>