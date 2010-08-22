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

header("Content-Type: text/html; charset=".BASE_ENCODING);
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=<?php echo BASE_ENCODING; ?>" /> 
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<meta name="viewport" content="width=320,user-scalable=no,maximum-scale=1" />
<link rel="stylesheet" type="text/css" href="i.css" />
<title><?php echo $pageTitle; ?></title>
<script type="text/JavaScript">
function delete_confirm(){
	switch(confirm('【ファイル削除】\nファイルは削除されます。\nよろしいですか？')){
	case true:
		document.editorform.mode.value="delete";
		document.editorform.submit();
		break;
	}
}
</script>
</head>
<body id="editor">
<form id="editorform" name="editorform" action="write.php" method="post" target="submitFrame">
<table id="editorTable" >
<tr class="form1L">
<td class="small">グループ</td>
<td>
<input type="text" name="group" value="<?php echo htmlspecialchars($airia->getGroup());?>" title="グループ(ディレクトリ)" />
</td>
<td class="small2">
<input type="button" value="削除" onClick="delete_confirm();" title="ファイルの削除" />
</td>
</tr>
<tr class="form1L">
<td class="small">ファイル</td>
<td>
<input type="text" name="file" value="<?php echo htmlspecialchars($airia->getFileName());?>" title="ファイル名" />
</td>
<td class="small2">
<input type="submit" value="保存" title="ファイルの保存" />
<input type="hidden" name="mode" value="save" />
<input type="hidden" name="scrollvalue" value="" />
</td>
</tr>
<!--
<td id="indicator">
</td>
<tr class="form1L">
</tr>
-->
<tr>
<td colspan="3">
<textarea name="contents" ><?php echo htmlspecialchars($airia->getFileContents()); ?></textarea>
</td>
</tr>
</table>
</form>
<iframe name="submitFrame" id="submitFrame" src="about:blank"></iframe>
</body>
</html>
