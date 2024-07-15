<?php
/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */
class FormModel
{
	//帳票の共通の処理はこちらで行う


	/*
	 *
	 */
	public function Sort_Replication_ID(&$_param, $modelName)
	{
		$param = array();

		if(is_array($_param)){
			//複製する項目をピックアップ
			foreach($_param[$modelName] as $key => $value){
				if($value == 1){
					$param[] = $key;
				}
			}
		}

		$_param = $param;
	}

	/*
	 *
	 */
	public function Sort_Replication_Data(&$_param, $_Table_before, $_Table_after, $_Item_before, $_Item_after)
	{
		//情報整理
		if(is_array($_param))
		{
			foreach($_param as $key => $value)
			{
				if(is_array($value))
				{
					foreach($value as $key1 => $value1)
					{
						if($key1 === $_Table_before)
						{
							$_param[$key][$_Table_after] = $value1;
							unset($_param[$key][$_Table_before]);
						}
						if(is_array($value1))
						{
							foreach($value1 as $key2 => $value2)
							{
								if($key2 === $_Item_before)
								{
									$_param[$key][$key1][$_Item_after] = $value2;
									unset($_param[$key][$key1][$_Item_before]);
								}
							}
						}
					}
				}
			}
		}
		else
		{
			return false;
		}
	}

	/*
	 *
	 */
	public function Get_Replication_Item(&$_param, $modelName, $auto_serial = true, $model_from)
	{
		$Model = null;
		if($modelName === 'Quote'){
			App::import('Model','Quoteitem');
			$Model = new Quoteitem;
		}
		else if($modelName === 'Bill'){
			App::import('Model','Billitem');
			$Model = new Billitem;
		}
		else if($modelName === 'Delivery'){
			App::import('Model','Deliveryitem');
			$Model = new Deliveryitem;
		}

		if($auto_serial) {
			App::import('Model','Serial');

			if(empty($model_from)) {
				$model_from = $modelName;
			}
			$Serial = new Serial;

		}


		if(!$Model) return $_param = null;

		foreach($_param as $key => $value)
		{
			//ステータスの初期化
			$_param[$key][$modelName]['STATUS'] = 0;

			//時間の設定
			$_param[$key][$modelName]['INSERT_DATE'] = date("Y-m-d H:i:s");
			$_param[$key][$modelName]['LAST_UPDATE'] = date("Y-m-d H:i:s");

			//件名の追記
			$_param[$key][$modelName]['SUBJECT'] = $value[$modelName]['SUBJECT'].'のコピー';

			//連番設定
			if($auto_serial) {
				$_param[$key][$modelName]['NO'] = $Serial->get_number($model_from);
				$Serial->serial_increment($modelName);
			}

			//
			$_param[$key]['Table'] = $_param[$key][$modelName];

			if($modelName === 'Quote'){
				$items = $Model->find('all', array('conditions' => array('MQT_ID' => $_param[$key][$modelName]['MQT_ID'])));
			}
			else if($modelName === 'Bill'){
				$items = $Model->find('all', array('conditions' => array('MBL_ID' => $_param[$key][$modelName]['MBL_ID'])));
			}
			else if($modelName === 'Delivery'){
				$items = $Model->find('all', array('conditions' => array('MDV_ID' => $_param[$key][$modelName]['MDV_ID'])));
			}
			if(is_array($items))
			{
				foreach($items as $key1 => $value)
				{
					$items[$key1]['Item'] = $items[$key1][$modelName.'item'];
					unset($items[$key1][$modelName.'item']);
				}
			}

			$_param[$key] = array_merge($_param[$key], $items);

			//IDの削除
			if($modelName === 'Quote'){
				unset($_param[$key]['Table']['MQT_ID']);
			}
			if($modelName === 'Bill'){
				unset($_param[$key]['Table']['MBL_ID']);
			}
			if($modelName === 'Delivery'){
				unset($_param[$key]['Table']['MDV_ID']);
			}
			unset($_param[$key][$modelName]);
			unset($_param[$key]['Customer']);
		}
	}
	/*
	 *
	 */
	public function Copy_Replication_Data($_copy_param,$_model_Name,$_primary_key,$_Item_after,$_user_id=null)
	{

	//帳票モデルの読み込み
	$Model = null;
	$ItemModel = null;

	if($_model_Name === 'Quote'){
		App::import('Model','Quote');
		$Model = new Quote;
		App::import('Model','Quoteitem');
		$ItemModel = new Quoteitem;
	}
	else if($_model_Name === 'Bill'){
		App::import('Model','Bill');
		$Model = new Bill;
		App::import('Model','Billitem');
		$ItemModel = new Billitem;
	}
	else if($_model_Name === 'Delivery'){
		App::import('Model','Delivery');
		$Model = new Delivery;
		App::import('Model','Deliveryitem');
		$ItemModel = new Deliveryitem;
	}

	//トランザクションの開始
	$dataSource = $Model->getDataSource();
	$dataSource->begin($Model);


	foreach($_copy_param as $key => $value)
	{
		$Model->create();
		$_copy_param[$key][$_model_Name]['USR_ID']=$_user_id;
		$_copy_param[$key][$_model_Name]['UPDATE_USR_ID']=$_user_id;
		if($Model->save($_copy_param[$key], array('atomic' => false,'validate'=>false)))
		{
			//IDの取得
			$id = $Model->getInsertID();

			$item = array();
			for($i=0;$i<count($_copy_param[$key])-3;$i++)
			{
				unset($_copy_param[$key][$i][$_Item_after]['ITM_ID']);
                if(!empty($_copy_param[$key][$i][$_Item_after])){
                    $item[$i] = $_copy_param[$key][$i];
                    $item[$i][$_Item_after][$_primary_key] = $id;
                }
			}

			if(!$ItemModel->saveAll($item, array('atomic' => true,'validate' => false)))
			{
				//エラー時の処理
				$dataSource->rollback($Model);
				return false;
			}
		}
		else
		{
			//エラー時の処理
			$dataSource->rollback($Model);
			return false;
		}
	}

		//成功
		$dataSource->commit($Model);
		return $id;

	}
	/*
	 *
	 */
	public function Delete_Replication_Data($_del_param,$_model_Name, $_primary_key,$_user_id=null)
	{

		$Model = null;
		if($_model_Name === 'Quote'){
			App::import('Model','Quote');
			$Model = new Quote;
		}
		else if($_model_Name === 'Bill'){
			App::import('Model','Bill');
			$Model = new Bill;
		}
		else if($_model_Name === 'Delivery'){
			App::import('Model','Delivery');
			$Model = new Delivery;
		}

		if(!$Model) return false;

		$param = array();
		$ids = array();

        $_primary_key = $_model_Name.'.'.$_primary_key;
		//削除する項目をピックアップ
		if(is_array($_del_param)){
			foreach($_del_param[$_model_Name] as $key => $value){
				if($value == 1){
					$data = array($_primary_key => $key);
					$param[][$_model_Name] = $data;
					array_push($ids, $data[$_primary_key]);
				}
			}
		}
		if($param){
			//削除処理
			return $Model->deleteAll(array($_primary_key => $ids));
		}else{
			return false;
		}

	}

	/*
	 *
	 */
	public function Get_Decimal($_company_id)
	{
		App::import('Model','Company');
		$Company = new Company;

		$result=$Company->find('all', array('fields' => array('DECIMAL_QUANTITY','DECIMAL_UNITPRICE'), 'conditions' => array('CMP_ID' => $_company_id)));

		if(!$result) return false;

		return $result;

	}

	/*
	 * 顧客の敬称
	 *
	 */
	public function Get_Honor($_company_id)
	{
		App::import('Model','Company');
		$Company = new Company;

		$result=$Company->find('all', array('fields' => array('HONOR_CODE','HONOR_TITLE'), 'conditions' => array('CMP_ID' => $_company_id)));

		if(!$result) return false;

		return $result;

	}

	/*
	* 管理番号の連番
	*
	*/
	public function Get_Serial($_company_id)
	{
		App::import('Model','Company');
		$Company = new Company;

		$result=$Company->find('all', array('fields' => array('SERIAL_NUMBER'), 'conditions' => array('CMP_ID' => $_company_id)));

		if(!$result) return false;

		return $result[0]['Company']['SERIAL_NUMBER'];

	}
	/*
	 *
	 */
	public function Edit_Select($_model_ID,$_model_Name,$_primary_key,&$count=null)
	{
		//帳票モデルの読み込み
		$Model = null;
		$ItemModel = null;

		if($_model_Name === 'Quote'){
			App::import('Model','Quote');
			$Model = new Quote;
			App::import('Model','Quoteitem');
			$ItemModel = new Quoteitem;
		}
		else if($_model_Name === 'Bill'){
			App::import('Model','Bill');
			$Model = new Bill;
			App::import('Model','Billitem');
			$ItemModel = new Billitem;
		}
		else if($_model_Name === 'Delivery'){
			App::import('Model','Delivery');
			$Model = new Delivery;
			App::import('Model','Deliveryitem');
			$ItemModel = new Deliveryitem;
		}

		if(!is_numeric($_model_ID)){
			return false;
		}
		$result = $Model->find('first', array('conditions' => array($_primary_key => $_model_ID)));
		if(!$result){
			return false;
		}

		$result[$_model_Name]['DATE'] = $result[$_model_Name]['ISSUE_DATE'];

		// 複数の税別があるかを確認
		$every_tax_total_key = array('FIVE_RATE_TOTAL', 'EIGHT_RATE_TOTAL', 'REDUCED_RATE_TOTAL', 'TEN_RATE_TOTAL');
		$tax_kind_count = 0;
		foreach($every_tax_total_key as $key){
			if($result[$_model_Name][$key]){
				$tax_kind_count++;
			}
		}
		$result[$_model_Name]['tax_kind_count'] = $tax_kind_count;

		//請求書項目モデルの読み込み
		$item=$ItemModel->find('all', array('conditions' => array($_primary_key => $_model_ID),'order'=>array($_model_Name.'item.ITM_ID ASC')));
		$count=count($item);
		$result = array_merge($result, $item);
		return $result;

	}
	/*
	 *
	 */
	public function Get_Customer($_company_id, $_condition)
	{
		//顧客情報モデルの読み込み
		App::import('Model','Customer');
		$Customer = new Customer;

		$results = $Customer->find('all', array('fields' => array('CST_ID', 'NAME'), 'conditions' => $_condition));

		$customer['customer'] = '＋顧客追加＋';
		$customer['default']   = '＋顧客選択＋';

		if(!$results) return $customer;

		foreach($results as $key => $result){
			$customer[$result["Customer"]["CST_ID"]] = $result["Customer"]["NAME"];
		}
		return $customer;

	}
	/*
	 *
	 */
	public function Get_Payment($_company_id)
	{
		//顧客情報モデルの読み込み
		App::import('Model','Customer');
		$Customer = new Customer;

		$result = $Customer->find('all', array('fields' => array('CST_ID', 'EXCISE', 'FRACTION', 'TAX_FRACTION', 'TAX_FRACTION_TIMING'), 'conditions' => array('Customer.CMP_ID' => $_company_id)));

		if(!$result) return false;

		$param = array();
		foreach($result as $value){
			$param[$value['Customer']['CST_ID']] = $value['Customer'];
		}

		return $param;

	}

	/*
	 *
	 */
	public function Get_Company_Payment($_company_id)
	{
		//自社情報モデルの読み込み
		App::import('Model','Company');
		$Company = new Company;
		$result = $Company->find('first', array('fields' => array('EXCISE', 'FRACTION', 'TAX_FRACTION', 'TAX_FRACTION_TIMING'), 'conditions' => array('CMP_ID' => $_company_id)));
		if(!$result) return false;

		return $result['Company'];

	}

	/*
	 *
	 */
	public function Set_Replication_Data($_set_param,$_model_Name ,$_state,$_error)
	{
// 		print_r($_set_param);
// 		exit;

		//帳票モデルの読み込み
		$Model = null;
		$ItemModel = null;
		$itemModel_Name = null;
		$_primary_key = null;

		if($_model_Name === 'Quote'){
			App::import('Model','Quote');
			$Model = new Quote;
			App::import('Model','Quoteitem');
			$ItemModel = new Quoteitem;
			$itemModel_Name = 'Quoteitem';
			$_primary_key = 'MQT_ID';
		}
		else if($_model_Name === 'Bill'){
			App::import('Model','Bill');
			$Model = new Bill;
			App::import('Model','Billitem');
			$ItemModel = new Billitem;
			$itemModel_Name = 'Billitem';
			$_primary_key = 'MBL_ID';
		}
		else if($_model_Name === 'Delivery'){
			App::import('Model','Delivery');
			$Model = new Delivery;
			App::import('Model','Deliveryitem');
			$ItemModel = new Deliveryitem;
			$itemModel_Name = 'Deliveryitem';
			$_primary_key = 'MDV_ID';
		}

		$_set_param[$_model_Name]['ISSUE_DATE'] = $_set_param[$_model_Name]['DATE'];

		if($_state === "new"){
			$_set_param[$_model_Name]['INSERT_DATE'] = date("Y-m-d H:i:s");
		}
		$_set_param[$_model_Name]['LAST_UPDATE'] = date("Y-m-d H:i:s");

		//トランザクションの開始
		$dataSource = $Model->getDataSource();
		$dataSource->begin($Model);

		$Model->permit_params($_set_param);
		if($Model->save($_set_param)){
			//成功

			//登録されたプライマリキーを取得
			if($_state === "new"){
				$_set_param[$_model_Name][$_primary_key] = $Model->getInsertID();
			}

			if(!$ItemModel->deleteAll(array($_primary_key => $_set_param[$_model_Name][$_primary_key]))){
				//エラー時の処理
				$dataSource->rollback($Model);
				return false;
			}

			//アイテムの長さを定義
			$item = array();
			for($i = 0 ; ; $i++){
				if(isset($_set_param[$i])){
					$item[$i] = $_set_param[$i];
					$item[$i][$itemModel_Name][$_primary_key]      = $_set_param[$_model_Name][$_primary_key];
					$item[$i][$itemModel_Name]['INSERT_DATE'] = date("Y-m-d H:i:s");
					$item[$i][$itemModel_Name]['LAST_UPDATE'] = date("Y-m-d H:i:s");
				}
				if(!isset($_set_param[$i+1])){
					break;
				}
			}
			//ここにバリデーション
			if($_error['ITEM']['FLAG']==0
					&& $_error['ITEM_NO']['FLAG']==0
					&& $_error['QUANTITY']['FLAG']==0
					&& $_error['UNIT']['FLAG']==0
					&& $_error['UNIT_PRICE']['FLAG']==0
					&& $_error['DISCOUNT']==0
			){
				if($ItemModel->saveAll($item, array('atomic' => false))){
					//成功
					$dataSource->commit($Model);
					return $_set_param[$_model_Name][$_primary_key];
				}else{
					//エラー時の処理
					$dataSource->rollback($Model);
					return false;
				}
			}else{
				//エラー時の処理
				$dataSource->rollback($Model);
				return false;
			}
		}else{
			//エラー時の処理
			$dataSource->rollback($Model);
			return false;
		}

	}
	/*
	 *
	 */
	public function Get_Preview_Data($_model_id,$_model_Name,&$_items=null,&$_discounts=null)
	{
		//帳票モデルの読み込み
		$Model = null;
		$itemModel_Name = null;

		if($_model_Name === 'Quote'){
			App::import('Model','Quote');
			$Model = new Quote;
			$itemModel_Name = 'Quoteitem';
		}
		else if($_model_Name === 'Bill'){
			App::import('Model','Bill');
			$Model = new Bill;
			$itemModel_Name = 'Billitem';
		}
		else if($_model_Name === 'Delivery'){
			App::import('Model','Delivery');
			$Model = new Delivery;
			$itemModel_Name = 'Deliveryitem';
		}

		$_company_ID = 1;

		$result = $Model->edit_select($_model_id);



		if(!$result) return false;

		$subtotal = 0;

		$count=0;
		$_discounts = 0;

		//項目ごとの合計を求める
		foreach($result as $key => $value){
			if(isset($value[$itemModel_Name]) && is_array($value[$itemModel_Name])){

				$subtotal += $result[$key][$itemModel_Name]['AMOUNT'];
				// = $value[$itemModel_Name]['QUANTITY'] * $value[$itemModel_Name]['UNIT_PRICE'];
				if($value[$itemModel_Name]['DISCOUNT']){
					$subtotal -= $value[$itemModel_Name]['DISCOUNT_TYPE']==0?$value[$itemModel_Name]['QUANTITY'] * $value[$itemModel_Name]['UNIT_PRICE'] * $value[$itemModel_Name]['DISCOUNT']*0.01:$value[$itemModel_Name]['DISCOUNT'];
					$_discounts++;
				}
				$count++;
			}
		}


		$_items = $count;

		//自社情報の取得
		App::import('Model','Company');
		$Company = new Company;

		$result = array_merge($result, $Company->index_select($_company_ID));


		if(!empty($result[$_model_Name]['CHR_ID'])) {
			//自社情報の取得
			App::import('Model','Charge');
			$Charge = new Charge;
			$charge = $Charge->find("first", array('conditions' => array('CHR_ID' => $result[$_model_Name]['CHR_ID'])));
			$result = array_merge($result, $charge);
		} else if($result['Customer']["CHR_ID"]){

			//自社情報の取得
			App::import('Model','Charge');
			$Charge = new Charge;
			$charge = $Charge->find("first", array('conditions' => array('CHR_ID' => $result['Customer']["CHR_ID"])));
			$result = array_merge($result, $charge);

		}

		return $result;

	}


	/*
	 *
	 */
	public function Export_Excel($_model_Name, $_excel_param, &$error, $_type,$_user_auth=null,$_user_id=null)
	{
		//帳票モデルの読み込み
		$Model = null;
		$_primary_key = null;

		if($_model_Name === 'Quote'){
			App::import('Model','Quote');
			$Model = new Quote;
			$_primary_key = 'MQT_ID';
		}
		else if($_model_Name === 'Bill'){
			App::import('Model','Bill');
			$Model = new Bill;
			$_primary_key = 'MBL_ID';
		}
		else if($_model_Name === 'Delivery'){
			App::import('Model','Delivery');
			$Model = new Delivery;
			$_primary_key = 'MDV_ID';
		}

		$excel_param = array();

		//期間での取得
		if($_type === 'term')
		{
			if(!$_excel_param)
			{
				return false;
			}

			$date1 = $_excel_param['DATE1']['year']."-".$_excel_param['DATE1']['month']."-".$_excel_param['DATE1']['day'];
			$date2 = $_excel_param['DATE2']['year']."-".$_excel_param['DATE2']['month']."-".$_excel_param['DATE2']['day'];

			if(Validation::date($date1, 'ymd', null) && Validation::date($date2, 'ymd', null))
			{
				if($_user_auth!=1){
					$data = $Model->find('all', array('conditions' => array($_model_Name.".ISSUE_DATE >= " => $date1, $_model_Name.".ISSUE_DATE <= " => $date2),'group'=> $Model->groupBy,));
				}
				else{
					$data = $Model->find('all', array('conditions' => array($_model_Name.".USR_ID" => $_user_id,$_model_Name.".ISSUE_DATE >= " => $date1, $_model_Name.".ISSUE_DATE <= " => $date2),'group'=> $Model->groupBy,));
				}
				if(empty($data))
				{
				$error = "データがありません";
					return false;
				}

				//顧客情報モデルの読み込み
				App::import('Model','Charge');
				$Charge = new Charge;

				$count=0;


				foreach($data as $key1 => $value1):
					if($count<1000){
						if(is_array($value1)):
							foreach($value1 as $key2 => $value2):
								if($key2 === $_model_Name):
									$excel_param[$key1][1]  = date('Y年m月d日', strtotime($value2['ISSUE_DATE']));
									$excel_param[$key1][2]  = $value2['NO'];//
									$excel_param[$key1][4]  = $value2['SUBJECT'];//
									$excel_param[$key1][5]  = $value2['CHR_ID']==0?null:$Charge->get_charge($value2['CHR_ID']);
									$excel_param[$key1][6]  = $value2['SUBTOTAL'];//
									$excel_param[$key1][7]  = $value2['SALES_TAX'];//
									$excel_param[$key1][8]  = $value2['TOTAL'];//
									if($_model_Name === 'Quote'){
										$excel_param[$key1][9]  = $value2['DELIVERY'];//
										$excel_param[$key1][10] = $value2['DUE_DATE'];//
									}
									else if($_model_Name === 'Bill'){
										$excel_param[$key1][9]  = $value2['FEE'];//
										$excel_param[$key1][10] = $value2['DUE_DATE'];//
									}
									else if($_model_Name === 'Delivery'){
										$excel_param[$key1][9]  = $value2['DELIVERY'];//
									}
								endif;
								if($key2 === "Customer"):
									$excel_param[$key1][3] = $value2['NAME'];//
								endif;
								ksort($excel_param[$key1]);
							endforeach;
						endif;
						$count++;
					}
				endforeach;

			}
			else
			{
				//日付が正しくない
				$error = "日付が正しくありません";
				return false;
			}
		}
		//IDでの取得予定
		else
		{
				$error = "データが空";
			return false;
		}

		return $excel_param;

	}
	public 	function Get_User_Data($_model_Name,$_id){

		if($_model_Name === 'Quote'){
			App::import('Model','Quote');
			$Model = new Quote;
			$_primary_key = 'MQT_ID';
		}
		else if($_model_Name === 'Bill'){
			App::import('Model','Bill');
			$Model = new Bill;
			$_primary_key = 'MBL_ID';
		}
		else if($_model_Name === 'Delivery'){
			App::import('Model','Delivery');
			$Model = new Delivery;
			$_primary_key = 'MDV_ID';
		}

		$result=$Model->find('all',array('fields' => array('USR_ID'),'conditions' => array($_primary_key => $_id)));
		if(!$result) return false;

		return $result[0][$_model_Name ]['USR_ID'];
	}
}


