<?php

/*
Grep結果を表示する

q=文字列
	検索クエリ
*/

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

if(!isset($_GET['q'])){
	header("Content-Type: text/html; charset=".BASE_ENCODING);
	die('No query');
}

$q = $_GET['q'];
$findResult = $airia->find($q);
$grepResult = $airia->grep($q);

header("Content-Type: text/html; charset=".BASE_ENCODING);
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");


$pageTitle = "grep ".htmlspecialchars($q);

$colorId=0;
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
<title><?php echo $pageTitle; ?></title>

</head>
<body id="grep">
<table id="listTable"><tbody>
<tr><td class="header">find result</td></tr>

<?php foreach( $findResult as $record): ?>
	<tr onClick="location.href='<?php echo $record['mobileHref']; ?>';" class="color<?php echo $colorId++%2; ?>">
	<td>
	<a href="<?php echo $record['mobileHref']; ?>" target="_parent">
	<?php echo htmlspecialchars($record['group']); ?>/<br />
	<?php echo htmlspecialchars($record['file']); ?></a>
	</td>
	</tr>
<?php endforeach; ?>

<tr><td class="header">grep result</td></tr>
<?php foreach( $grepResult as $record): ?>
	<tr onClick="location.href='<?php echo $record['mobileHref']; ?>';" class="color<?php echo $colorId++%2; ?>">
	<td>
	<a href="<?php echo $record['mobileHref']; ?>" target="_parent">
	<?php echo htmlspecialchars($record['group']); ?>/<br />
	<?php echo htmlspecialchars($record['file']); ?></a>
	<small>(<?php echo htmlspecialchars($record['line']); ?>)</small>
	<br />
	<p class="grepped-text"><?php echo htmlspecialchars($record['text']); ?></p>
	</td>
	</tr>
		
<?php endforeach; ?>
</body>
</html>
