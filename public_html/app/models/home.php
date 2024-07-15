<?php
/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 内容:DB属性名変換のmodelクラス
 */
class Home extends AppModel{

	var $name	=	'Home';
	var $useTable	=	false;


	function get_recent_forms($_model_name=null,$user_ID=null){
		$result=array();
		if($_model_name=='Bill'){
			App::import('Model','Bill');
			$Model = new Bill;
			$primary_id='MBL_ID';
		}
		if($_model_name=='Quote'){
			App::import('Model','Quote');
			$Model = new Quote;
			$primary_id='MQT_ID';
        }
        if ($_model_name == 'Delivery') {
            App::import('Model', 'Delivery');
            $Model = new Delivery();
            $primary_id = 'MDV_ID';
        }

        if ($user_ID == null) {
			$condition=array();
		}
		else{
			$condition=array($_model_name.'.USR_ID'=>$user_ID);
		}
		$result = $Model->find('all', array(
			'conditions' =>$condition,
			'order'=>array($_model_name.'.LAST_UPDATE'=>'desc',$_model_name.'.'.$primary_id=>'desc'),
			'limit'=>'3',
			'group'=> $_model_name.'.'.$primary_id
			)
		);

		//ユーザー情報の取得
		App::import('Model','User');
		$User = new User;
		$u_result = $User->find('all', array('fields' => array('USR_ID', 'NAME')));

		$user=array();
		foreach($u_result as $value){
			$user[$value['User']['USR_ID']] = $value['User']['NAME'];
		}

		if($result){
			foreach($result as $key => $value){
				$result[$key][$_model_name]['USR_NAME']=$user[$value[$_model_name]['USR_ID']];
			}
		}

		return $result;
	}
}