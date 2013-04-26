<?
	include_once "color_list.php";
	$pos=$_REQUEST["pos"];
?>

<html>
<body>
<table>
<tr>
<?php
	$ct=0;
	foreach (array_keys($colors) as $color)
		{
			$ct++;
			echo ("<td onclick=\"window.opener.change_color('".$pos."','".$colors[$color]."'); window.close();\"><img src=\"images/button_$color.gif\" /></td>\n");
			if ($ct % 12 == 0)
				{
					echo("</tr>\n<tr>\n");
				}
		}
?>
</table>
</body>
</html>
