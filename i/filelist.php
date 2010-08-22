<?php
chdir("../");

require("config/config.php");
require($CONFIG['authScriptFile']);

require($CONFIG['airiaClassFile']);
$airia = new Airia($CONFIG);

if(isset($_GET['group']) && $_GET['group']){
	$airia->setGroup($airia->httpInputConvertEncoding($_GET['group']));
	$groupTitle = htmlspecialchars($airia->getGroup());
}else{
	$groupTitle = $CONFIG['defaultGroup'];
}
$airia->makeAryFiles();

$rawurlencodedGroup = rawurlencode($airia->getGroup());

$colorId=0;

header("Content-Type: text/html; charset=".BASE_ENCODING);
header("Content-Type: text/html; charset=<?php echo BASE_ENCODING; ?>");
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
<title><?php echo $CONFIG['appricationTitle']; ?></title>
</head>
<body>
<h2><?php echo $groupTitle; ?></h2>
<h3>ファイル選択</h3>
<table id="listTable"><tbody>
<tr onClick="location.href='editor.php';" class="color<?php echo $colorId++%2; ?>">
<td><a href="editor.php?group=<?php echo $rawurlencodedGroup; ?>">新規作成</a></td>
<td class="nexticon">&gt;</td></tr>

<?php
foreach($airia->aryFiles as $v){
	$nextUrl="viewer.php?file=".rawurlencode($v)."&group=".$rawurlencodedGroup;
	echo "<tr onClick=\"location.href='".$nextUrl."';\" class=\"color".($colorId++%2)."\">";
	echo "<td>";
	echo "<a href=\"".$nextUrl."\">";
	echo htmlspecialchars($v);
	echo "</a>";
	echo "</td>\n";
	echo "<td class=\"nexticon\">&gt;</td>";
	echo "</tr>\n";
}
?>
</tbody></table>
<div id="footer">
<a href="./" target="_parent"><?php echo $CONFIG['appricationTitle']; ?></a>
</div>
</body>
</html>
