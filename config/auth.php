<?php
/*

Airia用 認証スクリプト
各ページのロード時に必ず呼ばれる。

PHP内で認証を行う場合は、そのスクリプトをここに書いてください。

PHP以外で、例えばapacheでHTTP認証する場合や、
そもそも認証が不要な場合は、このスクリプトは空で良いです。

*/

//認証ユーティリティをインクルード
require($CONFIG['authUtilityFile']);


//IPアドレスフィルタ
$allowIpAddress = array(
	'127.0.0.1',
	'192.168.0.2',
	'192.168.0.3',
);
//IPアドレスでフィルタリングする場合、アンコメントで有効化
//AuthUtility::filterIpAddress($allowIpAddress);

//ダイジェスト認証ユーザーリスト
// 'ユーザー名' => 'パスワード' の配列
$authList = array(
	'user' => 'passwordhoge',
);

//ダイジェスト認証する場合、アンコメントで有効化
//AuthUtility::digestAuth($authList,$CONFIG['appricationTitle']);

//ベーシック認証する場合、アンコメントで有効化
//AuthUtility::basicAuth($authList,$CONFIG['appricationTitle']);

?><?php
//追加設定
//**********************************************************
//UserManager Login
//**********************************************************
session_name('s');
//session_set_cookie_params(31*24*60*60,'/',$GLOBAL_CONFIG['baseUrl']);
session_cache_expire(30*24*60);
session_cache_limiter('private');
session_start();

//if(!$_SESSION['common']['login']){
if(
	!isset($_SESSION['common']) ||
	!isset($_SESSION['common']['login']) ||
	!$_SESSION['common']['login']
){
	header('Location: /personal/userManager/login.php?next=/personal/airia/');
	die();
}
//**********************************************************
?>
