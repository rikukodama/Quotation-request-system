<?php
/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */
class AppController extends Controller {

	public $autoLayout = false;
	var $uses =array('History','Company','ViewOption', 'Charge', 'Bill', 'Delivery', 'Quote', 'Totalbill');
	var $helpers = array('Session','Html', 'Form','CustomAjax','CustomHtml');
	var $components = array('Session','Common','Auth','Cookie');



	function beforeFilter(){
		if(configure::read('onlyFullGroupByDisable')){
			$this->disable_only_full_group_by();
		}
		
		//プロキシサーバー経由でアクセスした際のcacheを行わない処理を追加
		$this->header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		$this->header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->header('Pragma: no-cache');

		$user=$this->Auth->user();
		$this->set('usernavi', Configure::read('UserNavigate'));
		$this->set('user',$user['User']); // ctpで$userを使えるようにする
		$value = $this->Cookie->read('userid');
		$log=$this->History->h_getlastlog($user['User']['USR_ID']);
		$nbrowser = $this->History->browser_hash();

		if($log['History']['ACTION']==0&& $nbrowser!=$log['History']['BROWSER']){
			if(!$this->Common->matchCheck($this->action, array('pdf','contents')) // PDF出力画面はログアウトしないように
			&&!($this->Common->matchCheck($this->params['controller'], array('mails')) && $this->Common->matchCheck($this->action, array('login','customer', 'logout'))))
			{
				$this->Session->setFlash('同じユーザーIDで他PCでログインされたかセッションが切れた為、ログアウトしました','',array('auto_logout'));
				$this->redirect("/users/logout");
			}
		}
		
		//セッションに検索条件を入れる
		$session_params = $this->Session->read('session_params');
		$read_session_params = $this->Session->read('read_session_params');
		$dataArray = $this->params['url'];
		unset($dataArray['url']);
		
		if(empty($dataArray) && $this->action=='index' && empty($this->passedArgs)){
			if(!empty($session_params[$this->params['controller']])){
				$url = '/'.$this->params['controller'].'?';
				foreach($session_params[$this->params['controller']] as $key => $val){
					if ($val !== reset($session_params[$this->params['controller']])) {
						$url .='&';
					}
					$url .= $key.'='.$val;
				}
				$this->redirect($url);
			}
		} else if (SearchBoxSessionMode == SessionDeleteAlways && $this->action != 'edit' && $this->action != 'check' && $this->action != 'moveback' ){
			$this->Session->delete('session_params');
		}
		if(SearchBoxSessionMode == SessionDeleteAlways && $this->action!='index'){
			$this->Session->delete('read_session_params');
		}
		if(SearchBoxSessionMode == SessionDeleteAlways && empty($session_params[$this->params['controller']])){
			$this->Session->delete('session_params');
		}
		
		if(!empty($dataArray) && $this->action=='index'){
			$insArray = $session_params;
			$insArray[$this->params['controller']] = $dataArray;
			$this->Session->write('session_params', $insArray);
		}

		$this->Common->Authority_Check($user,$this);

		$this->Set_View_Option();

		//PDF出力時の一時画像のチェック
		if($this->action == 'pdf' && $this->name != 'Totalbill' ||
				$this->action == 'index' && $this->name == 'Coverpage') {
			$this->createTmpImage();
		}
		
		if (file_exists(APP.'plugins'. DS .'regularbill')){
			$this->set('rb_flag',true);
		}

	}


	/**
	 * ユーザー情報の取得
	 */
	function Get_User_Data(){
		return $this->Auth->user();
	}

	/**
	* 表示設定の取得
	*/
	function Set_View_Option(){
		$options = $this->ViewOption->
			find('all',array('fields' => array('ViewOption.OPTION_NAME','ViewOption.OPTION_NAME_JP','ViewOption.OPTION_VALUE')));

		for($i = 0; $i < count($options); $i++) {
			if($options[$i]['ViewOption']['OPTION_NAME'] =='logo') {
				$this->set($options[$i]['ViewOption']['OPTION_NAME'],'cms/'.$options[$i]['ViewOption']['OPTION_VALUE']);
			}else{
				$this->set($options[$i]['ViewOption']['OPTION_NAME'],$options[$i]['ViewOption']['OPTION_VALUE']);
			}
		}
	}

	/**
	 * 閲覧の権限
	 */
	function Get_Check_Authority($_id){
		$user=$this->Auth->user();
		if($user['User']['AUTHORITY']==1){
			if($_id!=$user['User']['USR_ID']){
				return false;
			}
		}
		return true;
	}
	/**
	 * 編集の権限
	 */
	function Get_Edit_Authority($_id){
		$user=$this->Auth->user();
		if($user['User']['AUTHORITY']==2||$user['User']['AUTHORITY']==1){
			if($_id!=$user['User']['USR_ID']){
				return false;
			}
		}
		return true;
	}
	function Get_User_ID(){
		$user=$this->Auth->user();
		return $user['User']['USR_ID'];
	}
	function Get_User_AUTHORITY(){
		$user=$this->Auth->user();
		return $user['User']['AUTHORITY'];
	}
	/**
	 * アイテムのバリデーション
	 * @param  $_param  アイテムの入っている配列
	 * @param  $_field  検索したいフィールド名を渡す
	 * @return $error   結果データを配列で返す
	 *
	 *使用例
	 *	$error=$this->item_validation($this->params['data'],'Deliveryitem');
	 */
	function item_validation($_param,$_field){

		$_error = array(
			'ITEM'=>array(
				'NO'=>array()
				,'FLAG'=>0
			),
			'ITEM_NO'=>array(
				'NO'=>array()
				,'FLAG'=>0
			),
			'QUANTITY'=>array(
				'NO'=>array()
				,'FLAG'=>0
			),
			'UNIT'=>array(
				'NO'=>array()
				,'FLAG'=>0
			),
			'UNIT_PRICE'=>array(
				'NO'=>array()
				,'FLAG'=>0
			),
		);
			//こっからバリデーション
			for($i=0;$i<count($_param)-2;$i++){
				//商品名
				$item_value=ceil(mb_strwidth($_param[$i][$_field]['ITEM']) / 2);
				if($item_value > 40){
					$_error['ITEM']['NO'][$i]=$i;
				}
				//アイテムNO
				$no_value=mb_strlen($_param[$i][$_field]['ITEM_NO']);
				if($no_value > 2){
					$_error['ITEM_NO']['NO'][$i]=$i;
				}
				if(preg_match( "/^[0-9]+$/",$_param[$i][$_field]['ITEM_NO'])==0
							&& $_param[$i][$_field]['ITEM_NO']!=NULL){
					$_error['ITEM_NO']['NO'][$i]=$i;
				}
				//数量
				$quantity_value=0;
				$quantityf_value=0;
				$j = strpos($_param[$i][$_field]['QUANTITY'],'.');
				if($j){
					$str = substr($_param[$i][$_field]['QUANTITY'], 0, $j);
					$astr = substr($_param[$i][$_field]['QUANTITY'], $j+1);
					$quantityf_value=mb_strlen($str)+mb_strlen($astr);
				}else{
					$quantity_value=mb_strlen($_param[$i][$_field]['QUANTITY']);
				}
				if($quantity_value > 6){
					$_error['QUANTITY']['NO'][$i]=$i;
				}
				if($quantityf_value > 6){
					$_error['QUANTITY']['NO'][$i]=$i;
				}
				if(preg_match( "/^(\\|\$)?(0|-?[1-9]\d*|-?(0|[1-9]\d*)\.\d+)$/",$_param[$i][$_field]['QUANTITY'])==0
							&& $_param[$i][$_field]['QUANTITY']!=NULL){
					$_error['QUANTITY']['NO'][$i]=$i;
				}
				//単位
				$unit_value=ceil(mb_strwidth($_param[$i][$_field]['UNIT']) / 2);
				if($unit_value > 4){
					$_error['UNIT']['NO'][$i]=$i;
				}
				//単価
				$unitprice_value=0;
				$unitpricef_value=0;
				$j = strpos($_param[$i][$_field]['UNIT_PRICE'],'.');
				if($j){
					$str = substr($_param[$i][$_field]['UNIT_PRICE'], 0, $j);
					$astr = substr($_param[$i][$_field]['UNIT_PRICE'], $j+1);
					$unitpricef_value=mb_strlen($str)+mb_strlen($astr);
				}else{
					$unitprice_value=mb_strlen($_param[$i][$_field]['UNIT_PRICE']);
				}
				if($unitprice_value > 8){
					$_error['UNIT_PRICE']['NO'][$i]=$i;
				}
				if($unitpricef_value > 8){
					$_error['UNIT_PRICE']['NO'][$i]=$i;
				}
				if(preg_match( "/^(\\|\$)?(0|-?[1-9]\d*|-?(0|[1-9]\d*)\.\d+)$/",$_param[$i][$_field]['UNIT_PRICE'])==0
							&& $_param[$i][$_field]['UNIT_PRICE']!=NULL){
					$_error['UNIT_PRICE']['NO'][$i]=$i;
				}
			}
			for($i=0;$i<count($_param)-2;$i++){
				if(isset($_error['ITEM']['NO'][$i])){
					$_error['ITEM']['FLAG']=1;
				}
				if(isset($_error['ITEM_NO']['NO'][$i])){
					$_error['ITEM_NO']['FLAG']=1;
				}
				if(isset($_error['QUANTITY']['NO'][$i])){
					$_error['QUANTITY']['FLAG']=1;
				}
				if(isset($_error['UNIT']['NO'][$i])){
					$_error['UNIT']['FLAG']=1;
				}
				if(isset($_error['UNIT_PRICE']['NO'][$i])){
					$_error['UNIT_PRICE']['FLAG']=1;
				}
			}
			return $_error;
	}
	function phone_validation($_param,$_type = null){
		//電話番号のバリデーション
		if($_type == 'CustomerCharge') {
			if(!strlen($_param['PHONE_NO1']) && !strlen($_param['PHONE_NO2']) && !strlen($_param['PHONE_NO3'])){
				return 0;
			}else if(!strlen($_param['PHONE_NO1']) || !strlen($_param['PHONE_NO2']) || !strlen($_param['PHONE_NO3'])){
				return 1;
			}
		}else if($_type == 'Company') {
			if(!strlen($_param['PHONE_NO1']) || !strlen($_param['PHONE_NO2']) || !strlen($_param['PHONE_NO3'])){
				return 1;
			}
		}
		else {
			if(!strlen($_param['PHONE_NO1']) && !strlen($_param['PHONE_NO2']) && !strlen($_param['PHONE_NO3'])){
				return 0;
			}else if(!strlen($_param['PHONE_NO1']) || !strlen($_param['PHONE_NO2']) || !strlen($_param['PHONE_NO3'])){
				return 1;
			}
		}
		$phone_error=0;
		$phone_no=($_param['PHONE_NO1'].
				$_param['PHONE_NO2'].
				$_param['PHONE_NO3']);
		$mphone_no=mb_strlen($phone_no);
		if($mphone_no>11 || $mphone_no<10){
				$phone_error=1;
		}
		if(preg_match( "/^[0-9]+$/",$phone_no)==0){
				$phone_error=1;
		}
		return $phone_error;
	}
	function fax_validation($_param){
		//FAX番号のバリデーション
		if($_param['FAX_NO1'] || $_param['FAX_NO2'] || $_param['FAX_NO3']){
			if(!strlen($_param['FAX_NO1']) && !strlen($_param['FAX_NO2']) && !strlen($_param['FAX_NO3'])){
				return 1;
			}
		}
		//FAX番号のバリデーション
		$fax_error=0;
		$fax_no=($_param['FAX_NO1'].
				$_param['FAX_NO2'].
				$_param['FAX_NO3']);
		$mfax_no=mb_strlen($fax_no);
		if($mfax_no>11 || $mfax_no<10 && $mfax_no!=0){
				$fax_error=1;
		}
		if($mfax_no!=0&&preg_match( "/^[0-9]+$/",$fax_no)==0){
				$fax_error=1;
		}
		return $fax_error;
	}

	/**
	 * 連番情報のバリデーション
	 *
	 */
	function serial_validation($_param){
		$serial_error = array();
		$serial_error['ERROR'] = 0;

		for($i = 0; $i < 5; $i++) {
			//付番書式のバリデーション
			if($_param[$i]['NUMBERING_FORMAT'] == 0 || $_param[$i]['NUMBERING_FORMAT'] == 1){
				$serial_error[$i]['NUMBERING_FORMAT'] = 0;
			}else {
				$serial_error[$i]['NUMBERING_FORMAT'] = 1;
				$serial_error['ERROR'] = 1;
			}

			//接頭文字のバリデーション
			if(mb_strlen($_param[$i]['PREFIX']) > 12){
				$serial_error[$i]['PREFIX'] = 1;
				$serial_error['ERROR'] = 1;
			}else if(preg_match("/^[a-zA-Z0-9\/_\.-]*$/", $_param[$i]['PREFIX']) == 0){
				$serial_error[$i]['PREFIX'] = 2;
				$serial_error['ERROR'] = 1;
			}else {
				$serial_error[$i]['PREFIX'] = 0;
			}

			//次回番号のバリデーション
			if(mb_strlen($_param[$i]['NEXT']) > 8 ){
				$serial_error[$i]['NEXT'] = 1;
				$serial_error['ERROR'] = 1;
			}else if(!is_numeric($_param[$i]['NEXT'])){
				$serial_error[$i]['NEXT'] = 2;
				$serial_error['ERROR'] = 1;
			}else {
				$serial_error[$i]['NEXT'] = 0;
			}


		}


		return $serial_error;
	}

	/**
	* 商品の割引の方法の変換
	* (バージョン2.2と2.3で割引方法が違うので互換性のため)
	*
	*/
	function getCompatibleItems($_param){

		$formType = $this->name.'item';
		$compatibleItems = $_param;
		$count = 0;


		//アイテム数のカウント
		for($i = 0; $i < count($_param); $i++) {
			if(!empty($_param[$i])) {
				$count = $i;
			}else {
				$count++;
				break;
			}
		}

		for($i = 0, $j = 0; $i < $count; $i++, $j++) {
			$compatibleItems[$j] = $_param[$i];

			//通常行に税区分設定
			if($_param[$i][$formType]['LINE_ATTRIBUTE'] == 0 && $_param[$i][$formType]['TAX_CLASS'] == 0) {
				$compatibleItems[$j][$formType]['ITEM_CODE'] = '';
				$compatibleItems[$j][$formType]['TAX_CLASS'] = $_param[$this->name]['EXCISE'] + 1;
			}

			//割引のバグを修正
			if(isset($_param[$i][$formType]['DISCOUNT_TYPE']) && $_param[$i][$formType]['DISCOUNT_TYPE'] == 0 && empty($_param[$i][$formType]['DISCOUNT'])) {
				$_param[$i][$formType]['DISCOUNT_TYPE'] = null;
				$_param[$i][$formType]['DISCOUNT'] = null;
			}

			//割引(％)
			if(isset($_param[$i][$formType]['DISCOUNT_TYPE']) && $_param[$i][$formType]['DISCOUNT_TYPE'] == 0) {

				$compatibleItems[$j + 1][$formType] = array();
				$compatibleItems[$j + 1][$formType]['ITEM'] = '　(割引)';
				$compatibleItems[$j + 1][$formType]['UNIT']  = '％';
				$compatibleItems[$j + 1][$formType]['QUANTITY'] = $_param[$i][$formType]['DISCOUNT'];
				$compatibleItems[$j + 1][$formType]['LINE_ATTRIBUTE'] = 4;
				$compatibleItems[$j + 1][$formType]['TAX_CLASS'] = 0;
				$compatibleItems[$j][$formType]['DISCOUNT'] = '';
				$compatibleItems[$j][$formType]['DISCOUNT_TYPE'] = '';
				$compatibleItems[$j][$formType]['TAX_CLASS'] = $_param[$this->name]['EXCISE'] + 1;
				$compatibleItems[$j][$formType]['AMOUNT'] = $_param[$i][$formType]['UNIT_PRICE'] * $_param[$i][$formType]['QUANTITY'];

				$j++;


			//割引(円)
			}else if(isset($_param[$i][$formType]['DISCOUNT_TYPE']) && $_param[$i][$formType]['DISCOUNT_TYPE'] == 1) {
				$compatibleItems[$j + 1][$formType] = array();
				$compatibleItems[$j + 1][$formType]['ITEM'] = '　(割引)';
				$compatibleItems[$j + 1][$formType]['AMOUNT'] = -$_param[$i][$formType]['DISCOUNT'];
				$compatibleItems[$j + 1][$formType]['LINE_ATTRIBUTE'] = 3;
				$compatibleItems[$j + 1][$formType]['TAX_CLASS'] = 0;
				$compatibleItems[$j][$formType]['DISCOUNT'] = '';
				$compatibleItems[$j][$formType]['DISCOUNT_TYPE'] = '';
				$compatibleItems[$j][$formType]['TAX_CLASS'] = $_param[$this->name]['EXCISE'] + 1;
				$compatibleItems[$j][$formType]['AMOUNT'] = $_param[$i][$formType]['UNIT_PRICE'] * $_param[$i][$formType]['QUANTITY'];

				$j++;

			}else {

			}

			//アイテム数の更新
			$compatibleItems['count'] = $j + 1;
		}
		return $compatibleItems;
	}


	//CSRF対策、トークンチェック
	function isCorrectToken($_token) {
		if($_token === session_id()) {
			return true;
		}else {
			$this->data = null;
			$this->Session->setFlash('正規の画面からご利用ください。', '');
			$this->redirect(array('controller'=>'users','action'=>'logout'));
			return false;
		}
	}

	//一時画像ファイルの作成
	function createTmpImage($id = null) {
		if($this->name=='Coverpage'){
			$this->params['pass'][0]=1;
		}
		if($id == null && isset($this->params['pass'][0])) {
			$id = $this->params['pass'][0];
		}else if(!isset($this->params['pass'][0])){
			return false;
		}

		if($this->name == 'Totalbill') {
			$_name = 'Bill';
		} else {
			$_name = $this->name;
		}
		$_path = TMP.'img/';
		$_user = $this->Auth->user();

		//各テーブルの主キー名
		$_primary_key;
		switch($this->name) {
			case 'Quote':
				$_primary_key = 'MQT_ID';
				break;
			case 'Bill':
				$_primary_key = 'MBL_ID';
				break;
			case 'Delivery':
				$_primary_key = 'MDV_ID';
				break;
 			case 'Totalbill':
				$_primary_key = 'MBL_ID';
				break;
		}

		//自社印
		$_company = $this->Company->find('first', array('fields' => array('Company.LAST_UPDATE', 'Company.SEAL')));
		$_last_update = str_replace(array('-', ':', ' '), '',$_company['Company']['LAST_UPDATE']);


		//一時画像のファイル名
		$tmp_company_name_user = $_user['User']['LOGIN_ID'].'_company';
		$tmp_company_name = $tmp_company_name_user.'_'.$_last_update.'_.png';

		//すでにファイルが存在しているか
		$company_file = glob($_path.$tmp_company_name_user."*");

		if(empty($company_file) && !empty($_company['Company']['SEAL'])) {
			//ファイルが存在しない場合は作成
			$image = fopen($_path.$tmp_company_name, 'w');
			fwrite($image, $_company['Company']['SEAL']);
			fclose($image);

		}else if(!empty($_company['Company']['SEAL'])){
			//ファイルが存在している場合

			//存在するファイルの最終更新日を取得
			$exist_file = spliti('_' ,$company_file[0]);
			$exist_file_last_update = $exist_file[count($exist_file) - 2];

			//古いものであれば更新
			if($exist_file_last_update != $_last_update) {
				$image = fopen($_path.$tmp_company_name, 'w');
				fwrite($image, $_company['Company']['SEAL']);
				fclose($image);

				unlink($company_file[0]);
			}
		}

		//送付状作成のみの場合は担当者印は不要
		if($this->name!='Coverpage'){
			//担当者印
			//帳票の担当のIDを取得
			$_chr_id =  $this->{$_name}->find('first', array('fields' => array($_name.'.CHR_ID'),'conditions' => array($_name.'.'.$_primary_key => $id)));

			//担当者の最終更新日と画像バイナリを取得
			$_params = $this->Charge->find('first', array('fields' => array('LAST_UPDATE', 'SEAL'), 'conditions' => array('Charge.CHR_ID' => $_chr_id[$_name]['CHR_ID'])));

			//最終更新日の区切り文字を削除
			$_last_update = str_replace(array('-', ':', ' '), '',$_params['Charge']['LAST_UPDATE']);

			//一時画像のファイル名
			$tmp_charge_name_user = $_user['User']['LOGIN_ID'].'_charge'.$_chr_id[$_name]['CHR_ID'];
			$tmp_charge_name = $tmp_charge_name_user.'_'.$_last_update.'_.png';

			//すでにファイルが存在しているか
			$filenames = glob($_path.$tmp_charge_name_user."*");

			if(empty($filenames) && !empty($_params['Charge']['SEAL'])) {
				//ファイルが存在しない場合は作成
				$image = fopen($_path.$tmp_charge_name, 'w');
				fwrite($image, $_params['Charge']['SEAL']);
				fclose($image);

			}else if(!empty($_params['Charge']['SEAL'])){
				//ファイルが存在している場合

				//存在するファイルの最終更新日を取得
				$exist_file = spliti('_' ,$filenames[0]);
				$exist_file_last_update = $exist_file[count($exist_file) - 2];

				//古いものであれば更新
				if($exist_file_last_update != $_last_update) {
					$image = fopen($_path.$tmp_charge_name, 'w');
					fwrite($image, $_params['Charge']['SEAL']);
					fclose($image);

					unlink($filenames[0]);
				}
			}
		}
	}

	//一時画像ファイルのパスを取得
	function getTmpImagePath($id = null, $isCompany = false) {
		if($this->name=='Coverpage'){
			$this->params['pass'][0]=1;
		}
		if($id == null && isset($this->params['pass'][0])) {
			$id = $this->params['pass'][0];
		}else if(!isset($this->params['pass'][0])){
			return false;
		}

		if($this->name == 'Totalbill'){
			$_name = 'Bill';
		}else {
			$_name = $this->name;
		}

		$_path = TMP.'img/';
		$_user = $this->Auth->user();
		$_primary_key;
		switch($_name) {
			case 'Quote':
				$_primary_key = 'MQT_ID';
				break;
			case 'Bill':
				$_primary_key = 'MBL_ID';
				break;
			case 'Delivery':
				$_primary_key = 'MDV_ID';
				break;
 			case 'Totalbill':
 				$_primary_key = 'MBL_ID';
 				break;
		}

		//自社印
		if($isCompany) {
			$filenames = glob($_path.$_user['User']['LOGIN_ID'].'_company*');
		}

		//自社担当者印
		else {
			$_chr_id =  $this->{$_name}->find('first', array('fields' => array($_name.'.CHR_ID'),'conditions' => array($_name.'.'.$_primary_key => $id)));
			$tmp_charge_name_user = $_user['User']['LOGIN_ID'].'_charge'.$_chr_id[$_name]['CHR_ID'];
			$filenames = glob($_path.$tmp_charge_name_user."*");

		}

		return $filenames[0];
	}


	function moveback(){
		$this->Session->write('read_session_params',true);
		$this->redirect(array(
			'action' => 'index',
		));
	}
	function movetoindex(){
		if(SearchBoxSessionMode != SessionDeleteNever){
			$this->Session->delete('session_params');
		}
		$this->redirect(array(
				'action' => 'index',
		));
	}

	function status_change($data, $redirect_uri){
		if($this->params['controller'] == 'quotes'){
			$controller_name = '見積書';
			$primary_key = 'MQT_ID';
			App::import('Model', 'Quote');
			$model = new Quote;

		} else if($this->params['controller']== 'bills'){
			$controller_name = '請求書';
			$primary_key = 'MBL_ID';
			App::import('Model', 'Bill');
			$model = new Bill();
		} else if($this->params['controller'] == 'deliveries'){
			$controller_name = '納品書';
			$primary_key = 'MDV_ID';
			App::import('Model', 'Delivery');
			$model = new Delivery();
		}

		$status_value = $data['STATUS_CHANGE'];
		$status_request = $data;
		
		if (empty($status_request)) {
			$this->Session->setFlash($controller_name.'が選択されていません');
			return $this->redirect($redirect_uri);
		}

		foreach ($status_request as $key => $val) {
			if ($val == 1) {
				$id = $model->find('first', array(
						'conditions' => array(
								$primary_key => $key
						),
						'fields' => array(
								'USR_ID'
						)
				));
				
				if (! $this->Get_Edit_Authority($id[$this->name]['USR_ID'])) {
					$this->Session->setFlash('変更できない'.$controller_name.'が含まれていました');
					return $this->redirect($redirect_uri);
				}
			}
		}

		 
		$user = $this->Auth->user();

		if($model->change_status($status_request, $status_value, $user)){
			// 成功
			$this->Session->setFlash($controller_name.'のステータスを一括変更しました');
			return $this->redirect($redirect_uri);
		} else {
			// 失敗
			$this->Session->setFlash($controller_name.'のステータスの一括変更に失敗しました');
			return $this->redirect($redirect_uri);
		}
	}
	
	private function disable_only_full_group_by(){
		App::import('Model', 'ConnectionManager');
		$db = ConnectionManager::getDataSource('default');$result = $db->query("SELECT @@SESSION.sql_mode as result;");

		$setting = $result[0][0]['result'];
 
		if(strchr($setting, 'ONLY_FULL_GROUP_BY')){
			$setting = str_replace('ONLY_FULL_GROUP_BY,','',$setting);
			$query = "SET SESSION sql_mode = '$setting'";
			$db->query($query);
		}
	}
}