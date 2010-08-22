<?php
chdir("../");

require("config/config.php");
require($CONFIG['authScriptFile']);
require($CONFIG['airiaClassFile']);

$airia = new Airia($CONFIG);

if(!isset($_POST['mode']))      $_POST['mode'] = "";
if(!isset($_POST['file']))      $_POST['file'] = "";
if(!isset($_POST['group']))     $_POST['group'] = "";
if(!isset($_POST['contents']))  $_POST['contents'] = "";
if(!isset($_POST['position']))  $_POST['position'] = "";

$resultJs = "";

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
	
	$resultJs = "alert('保存しました');";
	
	//ファイル名が無い場合、Airiaが自動生成するので、その値をフォームにセット
	if(!$_POST['file']){
		$resultJs = "parent.document.editorform.file.value='".$airia->getFileName()."';\n" . $resultJs ;
	}
	
	break;
	
case "save-part":
	//追記保存
		
	$airia->setGroup($airia->httpInputConvertEncoding($_POST['group']));
	$airia->readFile($airia->httpInputConvertEncoding($_POST['file']));
	$contents = $airia->getFileContents();
		
	if($_POST['position']=="head"){
		$contents = $airia->httpInputConvertEncoding($_POST['contents']) . "\n" . $contents;
	}else{
		$contents = $contents . "\n" . $airia->httpInputConvertEncoding($_POST['contents']);
	}
	$airia->saveFile(
		$airia->httpInputConvertEncoding($_POST['group']),
		$airia->httpInputConvertEncoding($_POST['file']),
		$contents
	);
	if(isset($_POST['scrollvalue'])){
		$scrollQuery = "&scroll=".$_POST['scrollvalue'];
	}else{
		$scrollQuery = "";
	}
	
	$resultJs = "alert('追記しました');parent.history.back()";
	
	//ファイル名が無い場合、Airiaが自動生成するので、その値をフォームにセット
	//if(!$_POST['file']){
	//	$resultJs = "parent.document.editorform.file.value='".$airia->getFileName()."';\n" . $resultJs ;
	//}
	
	break;
case "delete":
	if($_POST['file']){
		$airia->deleteFile(
			$airia->httpInputConvertEncoding($_POST['group']),
			$airia->httpInputConvertEncoding($_POST['file'])
		);
	}
	$resultJs = "alert('削除しました'); parent.history.back();";
	break;
}

header("Content-Type: text/html; charset=".BASE_ENCODING);
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
?><html>
<head lang="ja">
<meta http-equiv="Content-type" content="text/html; charset=<?php echo BASE_ENCODING; ?>" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
</head>
<body>
<script type="text/JavaScript">
<?php echo $resultJs; ?>
</script>
</body>
</html>
