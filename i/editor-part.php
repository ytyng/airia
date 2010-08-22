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
</head>
<body id="editor">
<form id="editorform" name="editorform" action="write.php" method="post" target="submitFrame">

<table id="editorTable" >
<tr class="form1L">
<td class="small">グループ</td>
<td>
<input type="text" name="group" value="<?php echo htmlspecialchars($airia->getGroup());?>" title="グループ(ディレクトリ)" />
</td>


<tr class="form1L">
<td class="small">ファイル</td>
<td>
<input type="text" name="file" value="<?php echo htmlspecialchars($airia->getFileName());?>" title="ファイル名" />
</td>
</tr>

<tr class="form1L">
<td class="small">追記位置</td>
<td>
<input type="radio" name="position" value="head" checked="checked" id="radio-position-head" />
<label for="radio-position-head">文頭</label>
<input type="radio" name="position" value="tail" id="radio-position-tail" />
<label for="radio-position-tail">文末</label>
</td>
</tr>

<tr>
<td colspan="2">
<textarea name="contents" style="height:200px;"></textarea>
</td>
</tr>

<tr>
<td colspan="2" style="text-align:center;">
<input type="hidden" name="mode" value="save-part" />
<input type="hidden" name="scrollvalue" value="" />
<input type="submit" value="保存" title="ファイルの保存" style="width:50%;"/>
</td>
</tr>
</table>
</form>
<iframe name="submitFrame" id="submitFrame" src="about:blank"></iframe>
</body>
</html>
