<?php

/*
Grep結果を表示する

q=文字列
	検索クエリ
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

if(!isset($_GET['q'])){
	header("Content-Type: text/html; charset=".BASE_ENCODING);
	die('No query');
}

$q = $_GET['q'];

$grepResult = $airia->grep($q);

header("Content-Type: text/html; charset=".BASE_ENCODING);
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");


$pageTitle = "grep ".htmlspecialchars($q);
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

</head>
<body id="grep">

<?php foreach( $grepResult as $record): ?>

	<a href="<?php echo $record['href']; ?>" target="_parent">
	<?php echo htmlspecialchars($record['group']); ?>/<?php echo htmlspecialchars($record['file']); ?></a>
	<small>(<?php echo htmlspecialchars($record['line']); ?>)</small>
	<br />
	<p><?php echo htmlspecialchars($record['text']); ?></p>
	
	<hr />
		
<?php endforeach; ?>
</body>
</html>