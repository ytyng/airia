<?PHP

require("config/config.php");
require($CONFIG['authScriptFile']);

$title=$CONFIG['appricationTitle'];

$group="";
if(isset($_GET['group'])){
	$group=$_GET['group'];
}

$file="";
if(isset($_GET['file'])){
	$file=$_GET['file'];
	$title=$file;
}


header("Content-Type: text/html; charset=".BASE_ENCODING);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=<?php echo BASE_ENCODING; ?>" /> 
<title><?php echo htmlspecialchars($title);  ?></title>
<link rel="shortcut icon" href="opt/favicon.ico" />
</head>
<frameset cols="25%,*">
	<frame src="menu.php?group=<?php echo $group;?>" name="frame_menu" />
	<frame src="editor.php?group=<?php echo $group;?>&file=<?php echo $file; ?>" name="frame_editor" />
</frameset>
</html>

