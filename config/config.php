<?php
/*

Airia用 設定ファイル

認証設定(ユーザー/パスワード設定)は、auth.phpに記述されています。
*/

define('BASE_ENCODING','UTF-8');

mb_http_output(BASE_ENCODING);
mb_internal_encoding(BASE_ENCODING);
mb_regex_encoding(BASE_ENCODING);

$CONFIG = array(
	
	//▼データ保存用ディレクトリ
	//フルパス指定可能。公開ディレクトリ以外に設定することを推奨。
	'dataDir' => 'data',
	
	//▼ファイル名のエンコーディング。
	//Windowsの場合はSJIS-winを推奨
	'encoding_filename' => strncasecmp(PHP_OS,'WIN',3)==0 ? 'SJIS-win' : BASE_ENCODING ,  
	
	//▼アプリケーションタイトル
	'appricationTitle' => 'Airia, my note',
	
	'airiaClassFile'  => 'include'.DIRECTORY_SEPARATOR.'Airia.class.php',
	'authScriptFile'  => 'config' .DIRECTORY_SEPARATOR.'auth.php',
	'authUtilityFile' => 'include'.DIRECTORY_SEPARATOR.'AuthUtility.class.php',
	
	//▼HTTP-GET,HTTP-POSTで入力されるパラメータの文字エンコーディング。
	//空文字やfalseの場合、エンコーディング変換を行わない
	//'encoding_input_http' => BASE_ENCODING,
	'encoding_input_http' => '',
	
	//▼タイトルが無い場合、1行目からこの文字数抽出し、タイトルとする
	//(mb_strimwidthの第3引数となる)
	'autoFilenameLength' => 40,
	
	//▼タイトルが無い場合、抽出したファイル名の末尾にこれを付加する
	'autoFilenameSuffix' => '.txt',
	
	//▼ファイル名の変換配列
	'filenameConvertBefore' => array("\r","\n","\t",'..','/',"\\"),
	'filenameConvertAfter'  => array(''  ,''  ,' ' ,'_' ,'_','_'),
	
	//▼デフォルトグループ文字列
	'defaultGroup' => '(DEFAULT)',
	
	//▼grep有効
	'grepEnable' => true,
	
);
?><?php

//追加設定
require('globalConfig.php');
$CONFIG['dataDir'] = $GLOBAL_CONFIG['commonDataDir'].'/mynote';

?>
