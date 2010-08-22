<?PHP
class Airia{
	
	private $CONFIG;
	
	private $currentGroup = ''; //現在選択中のグループ(ディレクトリ)
	
	public  $aryGroups = array(); //グループ。サブディレクトリのこと。
	public  $aryFiles  = array(); //ファイル一覧
	
	private $strDebug = '';
	
	private $fileName = ''; //読み込み中のファイル名
	private $fileContents = ''; //読み込み中のファイル本文
	
	private $requireReloadMenu = false; //メニュー再読み込みの必要があるか
	
	/**
	 * コンストラクタ
	 */
	function __construct($CONFIG){
		$this->writeDebug(__METHOD__,'');
		$this->CONFIG = $CONFIG;
		$this->targetDir = $this->CONFIG['dataDir'];
		$this->makeAryGroups();
	}
	
	/**
	 * ディレクトリ一覧(グループ一覧)を取得
	 * 現在のグループが何であれ、ルート以下1階層のみ取得
	 */
	public function makeAryGroups(){
		$this->writeDebug(__METHOD__,'');
		$encodedDir = $this->createEncodedDirPath('');
		$bulkDirs = scandir($encodedDir);
		foreach($bulkDirs as $i => $f){
			if(is_dir($encodedDir.DIRECTORY_SEPARATOR.$f)){
				if($f == '..') continue;
				if($f == '.') continue;
				$this->aryGroups[] = mb_convert_encoding($f,mb_internal_encoding(),$this->CONFIG['encoding_filename']);
			}
		}
		natcasesort($this->aryGroups);
		return $this->aryGroups;
	}
	
	
	/**
	 * ディレクトリ内ファイル一覧を取得
	 */
	public function makeAryFiles(){
		$this->writeDebug(__METHOD__,$this->targetDir);
		
		$encodedDir = $this->createEncodedDirPath($this->currentGroup);
		$bulkFiles = scandir($encodedDir);
		foreach($bulkFiles as $i => $f){
			
			//1文字目が'.'の場合、無視
			if(substr($f,0,1)==='.'){
				continue;
			}
			
			if(is_file($encodedDir.DIRECTORY_SEPARATOR.$f)){
				$this->aryFiles[] = mb_convert_encoding($f,mb_internal_encoding(),$this->CONFIG['encoding_filename']);
			}
		}
		natcasesort($this->aryFiles);
		return $this->aryFiles;
	}
	
	
	/**
	 * グループをセット
	 */
	public function setGroup($strArg){
		$this->writeDebug(__METHOD__,$strArg);
		if(in_array($strArg,$this->aryGroups)){
			$this->currentGroup = $strArg;
		}else{
			$this->writeDebug(__METHOD__,'setGroup error.');
		}
	}
	/**
	 * グループをゲット
	 */
	public function getGroup(){
		return $this->currentGroup;
	}
	
	/**
	 * デバッグ情報書き込み
	 */
	private function writeDebug($method,$message = ''){
		$this->strDebug .= '['.$method.'] '.$message."\n";
	}
	
	/**
	 * デバッグ情報取得
	 */
	public function getDebugMessage(){
		return $this->strDebug;
	}
	
	
	/**
	 * ファイルを読み込む
	 */
	public function readFile($fileName){
		$this->writeDebug(__METHOD__,$fileName);
		
		$this->fileName = $this->convertFilenameSafe($fileName);
		
		list($encodedFileFullName,$encodedDir) = $this->createEncodedFilePath($this->fileName,$this->currentGroup);
		
		if(is_file($encodedFileFullName)){ //ファイルが実在する場合は
			if(!is_writable($encodedFileFullName)){ //権限チェック
				 die(__METHOD__.' have no permission ('.$encodedFileFullName.').');
			}
			$this->fileContents = file_get_contents($encodedFileFullName);
			$this->writeDebug(__METHOD__,'read OK.');
		}
	}
	
	/**
	 * ファイル名をゲット
	 */
	public function getFileName(){
		return $this->fileName;
	}
	
	/**
	 * ファイル本文をゲット
	 */
	public function getFileContents(){
		return $this->fileContents;
	}
	
	
	/**
	 * セルフテストして、エラーメッセージを返す
	 */
	public function selfTest(){
		
		if(!is_dir($this->CONFIG['dataDir'])){
			return 'データ保存ディレクトリ('.$this->CONFIG['dataDir'].')が存在しません。設定ファイル(config/config.php)を確認してください。';
		}
		if(!is_writable($this->CONFIG['dataDir'])){
			return 'データ保存ディレクトリ('.$this->CONFIG['dataDir'].')への書き込み権限がありません。ディレクトリへの書き込み権限を設定してください。';
		}
		return '';
	}
	
	/**
	 * ファイルを保存する
	 */
	public function saveFile($group,$fileName,$contents){
		$this->writeDebug(__METHOD__,$fileName);
		
		$group = $this->convertFilenameSafe($group);
				
		//ファイル名が無ければ自動生成
		if(!$fileName){
			$tempTitle = ltrim($contents);
			$nrPosition = strpos($tempTitle,"\n");
			if($nrPosition>0){
				$tempTitle = substr($tempTitle,0,$nrPosition);
			}
			$tempTitle = rtrim($tempTitle);
			$tempTitle = $this->convertFilenameSafe($tempTitle);
			$tempTitle = mb_strimwidth($tempTitle,0,$this->CONFIG['autoFilenameLength']);
			if(!$tempTitle){
				$this->writeDebug(__METHOD__,'Cannot create filename.');
				return;
			}
			$tempTitle .= $this->CONFIG['autoFilenameSuffix'];
			$fileName = $tempTitle;
			$this->fileName = $fileName; //リダイレクトで使う用
			$this->writeDebug(__METHOD__,'Filename auto create. ='.$fileName);
		
		}else{
			$fileName = $this->convertFilenameSafe($fileName);
		}	
		
		//ディレクトリ文字列をエンコード
		list($encodedFileFullName,$encodedDir) = $this->createEncodedFilePath($fileName ,$group);
		
		//ディレクトリが存在しないなら作る
		if(!is_dir($encodedDir)){
			$this->writeDebug(__METHOD__,' mkdir '.$group);
			mkdir($encodedDir) or die(__METHOD__.' mkdir Error.');
		}
		
		if(!is_file($encodedFileFullName)){
			#ファイルが存在しない場合はリロードが必要
			$this->requireReloadMenu = true;
		}
		//ファイル保存
		file_put_contents($encodedFileFullName,$contents);
	}
	
	/**
	 * ファイルを削除する
	 */
	public function deleteFile($group,$fileName){
		$this->writeDebug(__METHOD__,$fileName);
		
		$group    = $this->convertFilenameSafe($group);
		$fileName = $this->convertFilenameSafe($fileName);
		
		list($encodedFileFullName,$encodedDir) = $this->createEncodedFilePath($fileName,$group);
		
		if(is_file($encodedFileFullName)){
			$this->writeDebug(__METHOD__,' unlink '.$fileName);
			unlink($encodedFileFullName) or die(__METHOD__.' unlink failed. ');
		}
		
		//ディレクトリ内ファイルが空ならディレクトリも削除
		if($group){
			$bulkFiles = scandir($encodedDir);
			if(count($bulkFiles) <= 2){
				rmdir($encodedDir) or die(__METHOD__.' rmdir failed.');
			}
		}
		$this->requireReloadMenu = true;
	}
	
	/**
	 * ファイルシステム用にエンコードしたディレクトリ名を返す
	 * データディレクトリ設定を読んで、グループ名を実際に保存に使うディレクトリ名にする。
	 */
	function createEncodedDirPath($group){
		if($group){
			$targetDir = $this->CONFIG['dataDir'].DIRECTORY_SEPARATOR.$group;
		}else{
			$targetDir = $this->CONFIG['dataDir'];
		}		
		$encodedDir = mb_convert_encoding($targetDir,$this->CONFIG['encoding_filename'],mb_internal_encoding());
		return $encodedDir;
	}
	
	/**
	 * ファイルシステム用にエンコードしたディレクトリ名とファイル名を返す
	 * データディレクトリ設定を読んで、グループ名を実際に保存に使うディレクトリ名にする。
	 */
	function createEncodedFilePath($fileName,$group){
		$encodedDir      = $this->createEncodedDirPath($group);
		$encodedFileName = mb_convert_encoding($fileName ,$this->CONFIG['encoding_filename'],mb_internal_encoding());
		
		$encodedFileFullName = $encodedDir.DIRECTORY_SEPARATOR.$encodedFileName;
		return array($encodedFileFullName,$encodedDir);
	}
	
	/**
	 * 本文を自動リンクありHTMLで取得
	 * htmlspecialchars() はこの中でかかるので注意。
	 */
	function getLinkedFileContents(){
		$text = $this->fileContents;
		$text = htmlspecialchars($text);
		$text = nl2br($text);
		$text = mbereg_replace(
			'(https?)'."(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)",
			"<a href=\'\\1\\2\'>\\1\\2</a>",
			$text
		);
		return $text;
	}
	
	/**
	 * 本文をそのままHTMLで取得
	 */
	function getHtmlText(){
		$text = $this->fileContents;
		return $text;
	}
	
	
	/**
	 * HTTP入力されたものをエンコーディング変換
	 */
	function httpInputConvertEncoding($s){
		if(isset($this->CONFIG['encoding_input_http']) && $this->CONFIG['encoding_input_http']){
			return mb_convert_encoding($s,mb_internal_encoding(),$this->CONFIG['encoding_input_http']);
		}else{
			return $s;
		}
	}
	
	/**
	 * ファイル名の禁則文字を置換
	 */
	function convertFilenameSafe($s){
		$s = str_replace($this->CONFIG['filenameConvertBefore'],$this->CONFIG['filenameConvertAfter'],$s);
		return $s;
	}
	
	
	/**
	 * メニューのリロードの必要をチェック
	 */
	function isRequireReloadMenu(){
		return $this->requireReloadMenu;
	}
	
	/**
	 * 文頭に文字を追加
	 * (メモリ上の処理のみ)
	 */
	function addTextBeforeContent($s){
		$this->fileContents = $s . $this->fileContents;
	}
		
	/**
	 * 文末に文字を追加
	 * (メモリ上の処理のみ)
	 */
	function addTextAfterContent($s){
		$this->fileContents .= $s;
	}
}
?>
