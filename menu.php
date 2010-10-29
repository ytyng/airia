<?php

require("config/config.php");
require($CONFIG['authScriptFile']);

require($CONFIG['airiaClassFile']);
$airia = new Airia($CONFIG);
if(isset($_GET['group']) && $_GET['group']){
	//$airia->setGroup(rawurldecode($_GET['group']));
	
	$airia->setGroup($airia->httpInputConvertEncoding($_GET['group']));
}
$airia->makeAryFiles();

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
<link rel="stylesheet" type="text/css" href="opt/default.css" />
<link rel="shortcut icon" href="opt/favicon.ico" />
<title><?php echo $CONFIG['appricationTitle']; ?></title>
<script type="text/JavaScript">
function setGroup(strArg){
	parent.frame_editor.document.editorform.group.value=strArg;
	parent.frame_editor.document.editorform.file.value="";
	parent.frame_editor.document.editorform.contents.value="";
}
function updateTitle(title){
	parent.document.title = title;
}
	
</script>
</head>
<body id="menu">
<h1><a href="./" target="_parent"><?php echo $CONFIG['appricationTitle']; ?></a></h1>

<?php if($CONFIG['grepEnable']): ?>
	<form class="grepform" method="get" target="frame_editor" action="./grep.php">
	<input type="text" name="q" />
	<input type="submit" value="Grep" />
	</form>
<?php endif; ?>

<ul id="group">
<li><a target="_self" href="menu.php" onClick="setGroup('');" ><?php echo $CONFIG['defaultGroup']; ?></a></li>

<?php
foreach($airia->aryGroups as $v){
	$selected = "";
	if($airia->getGroup() == $v){
		$selected = " class=\"selected\" ";
	}
	echo "<li".$selected.">";
	echo "<a target=\"_self\" href=\"menu.php?";
	echo "group=".rawurlencode($v)."\" ";
	echo "onClick=\"setGroup('".htmlspecialchars($v)."');\" ";
	echo ">";
	echo htmlspecialchars($v);
	echo "</a>";
	echo "</li>\n";
}
?>
</ul>


<ul>
<?php
foreach($airia->aryFiles as $v){
	echo "<li>";
	echo "<a target=\"frame_editor\" href=\"editor.php?";
	echo "file=".rawurlencode($v)."&";
	echo "group=".rawurlencode($airia->getGroup())."\" ";
	echo "onClick=\"updateTitle('".htmlspecialchars($v)."');\" ";
	echo ">";
	echo htmlspecialchars($v);
	echo "</a>";
	echo "</li>\n";
}
?>
</ul>

<!--
<pre>
<?php echo($airia->getDebugMessage()); ?>
</pre>
-->

</body>
</html>
