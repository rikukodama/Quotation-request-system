<?php
/**
 * Install Controller
 *
 * PHP version 5
 *
 * @category Controller
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class InstallController extends InstallAppController {
/**
 * Controller name
 *
 * @var string
 * @access public
 */
	var $name = 'Install';
/**
 * No models required
 *
 * @var array
 * @access public
 */
	var $uses = 'Install.Install';
/**
 * No components required
 *
 * @var array
 * @access public
 */
	var $components = null;

	var $autoLayout = true;

	var $helpers = array('Ajax');

/**
 * beforeFilter
 *
 * @return void
 */
	function beforeFilter() {
		parent::beforeFilter();

		$this->layout = 'install';
		App::import('Component', 'Session');
		$this->Session = new SessionComponent;
	}
/**
 * Step 0: welcome
 *
 * A simple welcome message for the installer.
 *
 * @return void
 */
	function index() {
		$this->set("main_title", "インストール：環境チェック");
	}
/**
 * Step 1: database
 *
 * @return void
 */
	function database() {
		$this->set("main_title", "インストール：データベース設定");

		if (!empty($this->data)) {
		}
	}
/**
 * Step 2: insert required data
 *
 * @return void
 */
	function data() {
		if (isset($this->params['named']['run'])) {
			App::import('Core', 'File');
			App::import('Model', 'ConnectionManager');
			$db = ConnectionManager::getDataSource('default');

			if(!$db->isConnected()) {
				$this->Session->setFlash(__('DBに接続できません。', true));
			} else {
				$this->__executeSQLScript($db, CONFIGS.'sql'.DS.'dump.sql');
				$this->__executeSQLScript($db, CONFIGS.'sql'.DS.'postcode.sql');

				$this->redirect(array('action' => 'add'));
				exit();
			}
		}else{
			//前処理
			// test database connection
			if (mysql_connect($this->data['Install']['host'], $this->data['Install']['login'], $this->data['Install']['password']) &&
				mysql_select_db($this->data['Install']['database'])) {
				//mysqlのバージョン確認
				if(mysql_get_server_info() < 5){
					$this->Session->setFlash('MYSQLのバージョンが5以上でない為、インストールを行えません');
					$this->redirect(array('action' => 'database'));
                }elseif(!$this->check_db_valid($this->data['Install'])){
					$this->Session->setFlash('DB情報に禁止文字が含まれています');
					$this->redirect(array('action' => 'database'));
				}else{
					// rename database.php.install
					copy(APP.'config'.DS.'installed.php.default', APP.'config'.DS.'installed.php');
					// open database.php file
					App::import('Core', 'File');
					$file = new File(APP.'config'.DS.'installed.php', true);
					$content = $file->read();
					// write database.php file
					$content = str_replace('{default_host}', $this->data['Install']['host'], $content);
					$content = str_replace('{default_login}', $this->data['Install']['login'], $content);
					$content = str_replace('{default_password}', $this->data['Install']['password'], $content);
					$content = str_replace('{default_database}', $this->data['Install']['database'], $content);
					// The database import script does not support prefixes at this point
					$content = str_replace('{default_prefix}', $this->data['Install']['prefix'], $content);

					if(!$file->write($content) ) {
						$this->Session->setFlash(__('設定ファイル生成に失敗しました', true));
					}

				}
			} else {
				$this->Session->setFlash('DBに接続できません');
				$this->redirect(array('action' => 'database'));
			}
		}

		$this->set("main_title", "インストール：テーブル・初期データ作成");
	}
    
    // db情報に禁止文字が含まれていないかバリデーションチェックを行う
    private function check_db_valid($data){
        foreach($data as $key => $val){
            if(preg_match('/[\'\"();]/',$val)){
                return false;
            }
        }
        return true;
    }    

/**
 * Step 3: ユーザ登録
 *
 *
 *
 * @return void
 */
    function add(){
    	$this->set("main_title", "インストール：基本情報設定");

    	$this->set('countys',Configure::read('PrefectureCode'));
		$db = ConnectionManager::getDataSource('default');
		$phone_error = 0;
		if(isset($this->data['Install'])){
			//バリデーション
			$this->Install->set($this->data['Install']);
			$errors=$this->Install->invalidFields();
			$phone_error = $this->phone_validation($this->data['Install']);
			$this->set("perror",$phone_error);
			if($this->Install->validates() && $phone_error === 0){
				App::import('Component', 'Auth');
				$auth = new AuthComponent();
				$pass = $auth->password($this->data['Install']['user_password']);
				if($this->Install->regsit($this->params['data']['Install'],$pass)){
					//完了
//					touch(APP.'config'.DS.'finish', true);
					$this->redirect(array('action'=>'mail'));
				}
			}
		}
		$this->set("perror",$phone_error);
    }
	function mail() {
		$this->set("main_title", "インストール：メール設定");

		$error=array();
		$this->set("FROM_NAME",'抹茶請求書');
		if(isset($this->data['Install'])){
			$this->set("FROM_NAME",$this->data['Install']['FROM_NAME']);
			$result = $this->Install->mail_set($this->data['Install'],$error);
			$this->Install->validationErrors=$error;
			if($result['Configuration']['CON_ID'] !=null&& $error==null){
				touch(APP.'config'.DS.'finish', true);
				$this->redirect(array('action'=>'finish'));
			}
		}
		$protocol = array(
			'type'		=> 'radio',
			'options'	=> Configure::read('MailProtocolCode'),
			'div'		=> false,
			'label'		=> false,
			'legend'	=> false,
			'value'=>'0',
			'style'		=>'width:30px;',
			'class'		=>'txt_mid'
		);
		$security = array(
			'type'		=> 'radio',
			'options'	=> Configure::read('SmtpSecurityCode'),
			'div'		=> false,
			'label'		=> false,
			'legend'	=> false,
			'style'		=>'width:30px;',
			'class'		=>'txt_mid'
		);
		$this->set("error",$error);
		$this->set("security",$security);
		$this->set("status",array(	0	=> '無効',	1	=> '有効'));
		$this->set("protocol",$protocol);
	}
/**
 * Step 4: finish
 *
 * Remind the user to delete 'install' plugin.
 *
 * @return void
 */
	function finish() {
		$this->set("main_title", "インストール：完了");
	}
/**
 * Execute SQL file
 *
 * @link http://cakebaker.42dh.com/2007/04/16/writing-an-installer-for-your-cakephp-application/
 * @param object $db Database
 * @param string $fileName sql file
 * @return void
 */
    function __executeSQLScript($db, $fileName) {
		$statements = file_get_contents($fileName);
		$statements = explode(';', $statements);
		$prefix = $db->config["prefix"];

		foreach ($statements as $statement) {
			if (trim($statement) != '') {
				//プレフィックス用
				$pattern = array(
					'/(DROP TABLE IF EXISTS `)([a-z_]+)(`)/i',
					'/(CREATE TABLE IF NOT EXISTS `)([a-z_]+)(`)/i',
					'/(INSERT INTO `)([a-z_]+)(`)/i',
				);
				$statement = preg_replace($pattern, '$1'.$prefix.'$2$3', $statement);
				$db->query($statement);
			}
		}
	}
}