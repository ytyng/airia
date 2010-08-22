<?php

/*
GETクエリ

addBefore=文字列
	本文読み込み時、文頭に文字列を追加する

addAfter=文字列
	本文読み込み時、文末に文字列を追加する
*/



require("config/config.php");
require($CONFIG['authScriptFile']);
require($CONFIG['airiaClassFile']);

$airia = new Airia($CONFIG);

$errorMessage = $airia->selfTest();

if($errorMessage){
	header("Content-Type: text/html; charset=".BASE_ENCODING);
	die($errorMessage);
}

if(!isset($_POST['mode']))      $_POST['mode'] = "";
if(!isset($_POST['file']))      $_POST['file'] = "";
if(!isset($_POST['group']))     $_POST['group'] = "";
if(!isset($_POST['contents']))  $_POST['contents'] = "";
if(!isset($_GET['group']))      $_GET['group'] = "";
if(!isset($_GET['file']))       $_GET['file'] = "";
if(!isset($_GET['reloadMenu'])) $_GET['reloadMenu'] = "";

$menuReloadJs = "";


switch($_POST['mode']){
case "save":
	$airia->saveFile(
		$airia->httpInputConvertEncoding($_POST['group']),
		$airia->httpInputConvertEncoding($_POST['file']),
		$airia->httpInputConvertEncoding($_POST['contents'])
	);
	if(isset($_POST['scrollvalue'])){
		$scrollQuery = "&scroll=".$_POST['scrollvalue'];
	}else{
		$scrollQuery = "";
	}
	
	//ファイル名が無い場合、Airiaが自動生成するので、その値をリダイレクトに使う
	if(!$_POST['file']) $_POST['file'] = $airia->getFileName();
	
	//メニューリロードのクエリ
	$menuReloadQuery = $airia->isRequireReloadMenu()?"&reloadMenu=1":"";
	
	header("Location: editor.php?group=".rawurlencode($_POST['group'])."&file=".rawurlencode($_POST['file']).$scrollQuery.$menuReloadQuery);
	die();
	break;
	
case "delete":
	if($_POST['file']){
		$airia->deleteFile(
			$airia->httpInputConvertEncoding($_POST['group']),
			$airia->httpInputConvertEncoding($_POST['file'])
		);
	}
	//メニューリロードのクエリ (deleteの場合は必ずtrue)
	$menuReloadQuery = $airia->isRequireReloadMenu()?"&reloadMenu=1":"";
	header("Location: editor.php?group=".rawurlencode($_POST['group']).$menuReloadQuery);
	die();
	break;
	
default:
	if($_GET['group']){
		$airia->setGroup($airia->httpInputConvertEncoding($_GET['group']));
	}
	if($_GET['file']){
		$airia->readFile($airia->httpInputConvertEncoding($_GET['file']));
	}
	if($_GET['reloadMenu']){
		$menuReloadJs = "reloadMenu();";
	}
	break;
}


//本文に文字を自動追加する
if(isset($_GET['addBefore'])){
	$airia->addTextBeforeContent($_GET['addBefore']);
}
if(isset($_GET['addAfter'])){
	$airia->addTextAfterContent($_GET['addAfter']);
}

$pageTitle = htmlspecialchars($airia->getGroup()) ." ". htmlspecialchars($airia->getFileName()) ." - ". $CONFIG['appricationTitle'];

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
<title><?php echo $pageTitle; ?></title>
<script src="opt/default.js" type="text/JavaScript" language="JavaScript" encoding="utf-8"></script>
<script src="opt/tabtextarea.js" type="text/JavaScript" language="JavaScript"></script>
<script type="text/JavaScript">
<?php
if(isset($_GET['scroll']) && $_GET['scroll']){
	echo "scrollPosition=".(int)$_GET['scroll'].";\n";
}else{
	echo "scrollPosition=0;\n";
}
?>
</script>
</head>
<body id="editor" onLoad="taScroll(scrollPosition);saveTaOldData();<?php echo $menuReloadJs; ?>">
<form id="editorform" name="editorform" action="editor.php" method="post" onSubmit="formOnSubmit();">
<table id="layoutgrid" >
<tr style="height:30px;">
<td>
<input type="text" name="group" style="width:35%;" value="<?php echo htmlspecialchars($airia->getGroup());?>" title="グループ(ディレクトリ)" />
<input type="text" name="file" style="width:60%;" value="<?php echo htmlspecialchars($airia->getFileName());?>" title="ファイル名" /> 
<a href="./?group=<?php echo rawurlencode($airia->getGroup());?>&file=<?php echo rawurlencode($airia->getFileName());?>"
 target="_parent" title="このページのURL" style="font-size:80%">U</a> 
<input type="hidden" name="scrollvalue" value="" />
</td>
<td id="indicator">
</td>
<td style="width:210px;text-align:right;">
<input type="button" value="━" onClick="insertHr();" title="水平線(ヘッダー)の挿入" />
<input type="submit" value="保存" title="ファイルの保存(Ctrl+S)" />
<input type="button" value="削除" onClick="delete_confirm();" title="ファイルの削除" />
<input type="button" value="新規" onClick="new_file();" title="内容のクリア" />
<input type="hidden" name="mode" value="save" />
</td>
</tr>
<tr>
<td colspan="3">
<textarea
	name="contents"
	onKeydown='SetTab();setBgEditing();if(event.ctrlKey){return(executeShortcut(event.keyCode));}'
	onKeypress='SetTab2(event);'
><?php echo htmlspecialchars($airia->getFileContents()); ?></textarea>
</td>
</tr>
</table>
</form>

<!--
<pre>
<?php echo $airia->getDebugMessage(); ?>
</pre>
-->

</body>
</html>
