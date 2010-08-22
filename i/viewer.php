<?php
chdir("../");

require("config/config.php");
require($CONFIG['authScriptFile']);
require($CONFIG['airiaClassFile']);

$airia = new Airia($CONFIG);

if(!isset($_GET['group'])) $_GET['group'] = "";
if(!isset($_GET['file']))  $_GET['file'] = "";

if($_GET['group']){
	$airia->setGroup($airia->httpInputConvertEncoding($_GET['group']));
}
if($_GET['file']){
	$airia->readFile($airia->httpInputConvertEncoding($_GET['file']));
}


$pageTitle = htmlspecialchars($airia->getGroup()) ." ". htmlspecialchars($airia->getFileName()) ." - ". $CONFIG['appricationTitle'];


$editorUrl = "editor.php?group=".rawurlencode($airia->getGroup())."&file=".rawurlencode($airia->getFileName());

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
<title><?php echo $pageTitle; ?></title>
</script>
</head>
<body id="viewer">
<h2>
<?php if($airia->getGroup()){ echo htmlspecialchars($airia->getGroup())." /<br />"; } ?>
<?php echo htmlspecialchars($airia->getFileName());?>
</h2>
<div class="editorLinkArea">
<button class="editorLink" onClick="location.href='<?php echo $editorUrl; ?>'">編集する</button>
</div>
<p id="viewerContent">
<?php

if(
	(substr_compare($airia->getFileName(),".html",-5)===0)  ||
	(substr_compare($airia->getFileName(),".htm",-4) ===0)
){
	echo $airia->getHtmlText();
}else{
	echo $airia->getLinkedFileContents();
}
?>
</p>
<div class="editorLinkArea">
<button class="editorLink" onClick="location.href='<?php echo $editorUrl; ?>'">編集する</button>
</div>
<div id="footer">
<a href="./" target="_parent"><?php echo $CONFIG['appricationTitle']; ?></a>
</div>

</body>
</html>
