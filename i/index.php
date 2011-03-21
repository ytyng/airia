<?php
chdir("../");

require("config/config.php");
require($CONFIG['authScriptFile']);

require($CONFIG['airiaClassFile']);
$airia = new Airia($CONFIG);

$errorMessage = $airia->selfTest();

if($errorMessage){
	header("Content-Type: text/html; charset=".BASE_ENCODING);
	die($errorMessage);
}
$colorId=0;
header("Content-Type: text/html; charset=".BASE_ENCODING);
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=<?php echo BASE_ENCODING; ?>" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<meta name="viewport" content="width=320,user-scalable=no,maximum-scale=1" />
<link rel="stylesheet" type="text/css" href="i.css" />
<link rel="shortcut icon" href="opt/favicon.ico" />
<title><?php echo $CONFIG['appricationTitle']; ?></title>
</head>
<body>
<?php if($CONFIG['grepEnable']): ?>
	<form class="grepform" method="get" action="./grep.php">
	<input type="text" name="q" />
	<input type="submit" value="Search" />
	</form>
<?php endif; ?>
<h3>グループ選択</h3>
<table id="listTable"><tbody>
<tr onClick="location.href='editor.php';" class="color<?php echo $colorId++%2; ?>">
<td><a href="editor.php">新規作成</a></td><td class="nexticon">&gt;</td></tr>
<tr onClick="location.href='filelist.php';" class="color<?php echo $colorId++%2; ?>">
<td><a href="filelist.php"><?php echo $CONFIG['defaultGroup']; ?></a></td><td class="nexticon">&gt;</td></tr>
<?php
foreach($airia->aryGroups as $v){
	$nextUrl="filelist.php?group=".rawurlencode($v);
	
	echo "<tr onClick=\"location.href='".$nextUrl."';\" class=\"color".($colorId++%2)."\">";
	echo "<td>";
	echo "<a href=\"".$nextUrl."\" onClick=\"return false;\">";
	echo htmlspecialchars($v);
	echo "</a>";
	echo "</td>";
	echo "<td class=\"nexticon\">&gt;</td>";
	echo "</tr>\n";
}
?>
</tbody></table>
</body>
<div id="footer">
<a href="./" target="_parent"><?php echo $CONFIG['appricationTitle']; ?></a>
</div>
</html>
