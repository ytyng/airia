<?php
chdir("../");

require("config/config.php");
require($CONFIG['authScriptFile']);

require($CONFIG['airiaClassFile']);
if(!isset($_GET['group']))    $_GET['group'] = "";
if(!isset($_GET['file']))     $_GET['file'] = "";
if(!isset($_GET['readmode'])) $_GET['readmode'] = "";
if(!isset($_POST['mode']))    $_POST['mode'] = "";

$airia = new Airia($CONFIG);

switch($_POST['mode']){
case "save":
	if(!isset($_POST['group']))    $_POST['group'] = "";
	if(!isset($_POST['file']))     $_POST['file'] = "";
	if(!isset($_POST['contents'])) $_POST['contents'] = "";
	
	$airia->saveFile(
		$airia->httpInputConvertEncoding($_POST['group']),
		$airia->httpInputConvertEncoding($_POST['file']),
		$airia->httpInputConvertEncoding($_POST['contents'])
	);
	header("Location: ./?readmode=editable&group=".$_POST['group']."&file=".$_POST['file']."#editor");
	die();
	break;
}


if($_GET['group']){
	$airia->setGroup($airia->httpInputConvertEncoding($_GET['group']));
}
$airia->makeAryFiles();

if($_GET['file']){
	$airia->readFile($airia->httpInputConvertEncoding($_GET['file']));
}

if(!$_GET['readmode']){
	$_GET['readmode'] = "editable";
}

header("Content-Type: text/html; charset=".BASE_ENCODING);
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" /> 
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" /><meta name="viewport" content="width=320,user-scalable=no,maximum-scale=1" />
<title><?php echo $CONFIG['appricationTitle']; ?></title>
<link rel="stylesheet" type="text/css" href="m.css" />
<link rel="shortcut icon" href="opt/favicon.ico" />
</head>
<body id="mobile">
<h1><a href="./" target="_parent"><?php echo $CONFIG['appricationTitle']; ?></a></h1>
<form id="groupselector" method="GET" action="./">
<fieldset>
<legend>Group</legend>
<select name="group">
<option value=""><?php echo $CONFIG['defaultGroup']; ?></option>
<?php
foreach($airia->aryGroups as $v){
	$selected = "";
	if($airia->getGroup() == $v){
		$selected = " selected ";
	}
	echo "<option value=\"".htmlspecialchars($v)."\"".$selected.">";
	echo $v;
	echo "</option>\n";
}
?>
</select><br />
<input type="submit" value="セット" />
</fieldset>
</form>
<br />
<form id="fileselector" method="GET" action="./">
<fieldset>
<legend>File</legend>
<select name="file">
<?php
foreach($airia->aryFiles as $v){
	$selected = "";
	if($airia->getFileName() == $v){
		$selected = " selected ";
	}
	$htmlspecialcharsedV = htmlspecialchars($v);
	echo "<option value=\"".$htmlspecialcharsedV."\"".$selected.">";
	echo $htmlspecialcharsedV;
	echo "</option>\n";
}
?>
</select><br />
<input type="hidden" name="group" value="<?=htmlspecialchars($airia->getGroup())?>" />
<div id="mode_switch">
<!-- <small>表示モード:</small> -->
<nobr><input type="radio" name="readmode" value="readonly" id="readmode_readonly" <?=$_GET['readmode']=="readonly"?"checked ":""?>/>
<label for="readmode_readonly">テキスト</label>  </nobr>
<nobr><input type="radio" name="readmode" value="editable" id="readmode_editable" <?=$_GET['readmode']=="editable"?"checked ":""?>/>
<label for="readmode_editable">編集可</label>  </nobr>
<nobr><input type="radio" name="readmode" value="html" id="readmode_html" <?=$_GET['readmode']=="html"?"checked ":""?>/>
<label for="readmode_html">HTML</label>  </nobr>
</div>
<input type="submit" value="読込" />
</fieldset>
</form>

<br />
<a name="editor"></a>
<?php
switch($_GET['readmode']){
case "readonly": //リードオンリーモードなら
	echo $airia->getLinkedFileContents();
	break;
	
case "html": //HTMLモードなら
	echo $airia->getHtmlText();
	break;
case "editable": //編集可能モードなら
	//フォームとして表示
	?>
	<form id="editorform" name="editorform" action="./" method="post">
	<h3>グループ名</h3>
	<input type="text" name="group" value="<?php echo htmlspecialchars($airia->getGroup());?>" />
	<h3>ファイル名</h3>
	<input type="text" name="file"  value="<?php echo htmlspecialchars($airia->getFileName());?>" />
	<h3>内容</h3>
	<textarea name="contents" rows="16" ><?php echo htmlspecialchars($airia->getFileContents());?></textarea><br />
	<input type="submit" value="保存" />
	<input type="hidden" name="mode"  value="save" >
	</form>
	
<?php } ?>

<div id="footer">
<a href="./" target="_parent"><?php echo $CONFIG['appricationTitle']; ?></a>
</div>
</body>
</html>
