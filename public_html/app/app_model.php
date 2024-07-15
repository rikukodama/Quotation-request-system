<?php
/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Application model for Cake.
 *
 * This is a placeholder class.
 * Create the same file in app/app_model.php
 * Add your application-wide methods to the class, your models will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.model
 */
class AppModel extends Model {
	/**
	 * 住所検索で使用
	 * 配列内検索
	 * @param  $_param  検索対象の配列または多重配列
	 * @param  $_option 検索したいキーを配列で渡す
	 * @return $data    結果データを配列で返す
	 *
	 */
	function SearchArray($_param, $_option){
		//初期化
		$data = array();
		if(is_array($_option)){
			foreach($_option as $value){
				if(!is_array($value)){
					$data[$value] = NULL;
				}
			}
		}
		if(is_array($_param)){
			$this->_SearchArray($_param, $_option, $data);
		}

		return $data;
	}

	function _SearchArray($_param, $_option, &$_data){
		foreach($_param as $Pkey => $Pvalue){
			foreach($_option as $Ovalue){
				if($Pkey == $Ovalue){
					$_data[$Pkey] = $Pvalue;
				}
			}
			if(is_array($Pvalue)){
				$this->_SearchArray($Pvalue, $_option, $_data);
			}
		}
	}
	
	function paginateCount($conditions = null, $recursive = 0, $extra = array())
	{
		$parameters = compact('conditions');
		$this->recursive = $recursive;
		$count = $this->find('count', array_merge($parameters, $extra));
		if (isset($extra['group'])) {
			$count = $this->getAffectedRows();
		}
	
		return $count;
	}
	
	public function change_status($_param, $value, $user){
		if($this->name == 'Quote'){
			App::import('Model', 'Quote');
			$model = new Quote;
			$primaryKey = 'MQT_ID';
		} else if($this->name == 'Bill'){
			App::import('Model', 'Bill');
			$model = new Bill;
			$primaryKey = 'MBL_ID';
		} else if($this->name == 'Delivery'){
			App::import('Model', 'Delivery');
			$model = new Delivery;
			$primaryKey = 'MDV_ID';
		}
		$change_ids = array();
		foreach($_param as $key => $val){
			if($val == 1  && is_numeric($key)){
				$change_ids[] = $key;
			}
		}
        $dataSource = $this->getDataSource();
        $dataSource->begin($model);

        try{
        	$model->updateAll(
        		array('STATUS' => $value, 'UPDATE_USR_ID' => $user['User']['USR_ID']),
        		array($this->name.'.'.$primaryKey => $change_ids)
			);
	
			$dataSource->commit($model);

			return true;
		} catch(Exception $e){
			$dataSource->rollback($model);
			return false;
		}
	
	}	
	
	public function permit_params($param){
		$result = array();
		if(isset($param[$this->name])){
			foreach($param as $key => $val){
				if(isset($this->accessible[$key])){
					foreach($val as $skey => $sval){
						if(in_array($skey, $this->accessible[$key])){
							$result[$key][$skey] = $sval;
						}
					}
				}
			}
		} else {
			foreach($param as $key => $val){
				if(in_array($key, $this->accessible[$this->name])){
					$result[$key] = $val;
				}
			}
		}

		return $result;
	}
	
	
	
	
}
