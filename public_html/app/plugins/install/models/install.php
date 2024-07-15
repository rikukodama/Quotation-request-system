<?php
/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */
class Install extends InstallAppModel {
	var $name = 'Install';
	var $useTable = false;
	//バリデーション
	var $actsAs = array('Cakeplus.AddValidationRule','CustomValidation');

    var $validate = array(
			'mail' =>  array(
				'rule0' => array('rule' => array('email'),'message'=>'メールアドレスの形式をご確認ください'),
				'rule1' => array('rule' => 'notEmpty','message' => 'メールアドレスは必須項目です')
			),
			'user_password' => array(
    			'rule0' => array('rule' => array('password_valid','user_password', 4, 20),'message' => 'パスワードは4～20文字で入力してください'),
				'rule1' => array('rule' => array('compare2fields','user_password1'),'message' => 'パスワードとパスワード確認が一致しません')

			),
			'user_password1' => array(
				'rule0' => array('rule' => array('password_valid','user_password1', 4, 20),'message' => 'パスワード確認は4～20文字で入力してください'),
				'rule1' => array('rule' => array('compare2fields','user_password'),'message' => 'パスワードとパスワード確認が一致しません')
			),
		'NAME' => array(
			"rule0" => array('rule' => array('spaceOnly'),
				'message' => 'スペース以外も入力してください'
			),
			"rule1" => array('rule' => 'notEmpty',
				'message' => '自社名は必須項目です'
			),
			"rule2" => array('rule' => array('maxLengthW', 30),
				'message' => '自社名が長すぎます'
			),
		),
		'REPRESENTATIVE' => array(
			"rule1" => array('rule' => array('maxLengthW', 30),
				'message' => '代表者名が長すぎます'
			),
		),
		'ADDRESS' => array(
			"rule0" => array('rule' => array('spaceOnly'),
				'message' => 'スペース以外も入力してください'
			),
			"rule1" => array('rule' => 'notEmpty',
				'message' => '住所は必須項目です'
			),
			"rule2" => array('rule' => array('maxLengthW', 50),
				'message' => '住所が長すぎます'
			),
		),
		'BUILDING' => array(
			"rule2" => array('rule' => array('maxLengthW', 50),
				'message' => '建物名が長すぎます'
			),
			),
			'POSTCODE1' => array(
				'rule0' => array('rule' => array('spaceOnly'),
					'message' => '正しい郵便番号を入力してください'
				),
				'rule1' => array('rule' => 'notEmpty',
					'message' => '郵便番号は必須項目です'
				),
				'rule2' => array('rule' => array('maxLengthJP', 3),
					'message' => '正しい郵便番号を入力してください'
				),
				'rule3' => array('rule' => 'Numberonly',
					'message' => '正しい郵便番号を入力してください'
				),
			),
			'POSTCODE2' => array(
				'rule0' => array('rule' => array('spaceOnly'),
					'message' => 'スペース以外も入力してください'
				),
				'rule1' => array('rule' => 'notEmpty',
					'message' => '郵便番号は必須項目です'
				),
				'rule2' => array('rule' => array('maxLengthJP', 4),
					'message' => '正しい郵便番号を入力してください'
				),
				'rule3' => array('rule' => 'Numberonly',
					'message' => '正しい郵便番号を入力してください'
				),
			),
		);

	function regsit($params , $pass){
		App::import('Model','User');
		App::import('Model','Company');
		$user = new User();
		if($user->regist_user($params,$pass)){
			$company = new Company();
			$datetime = date("Y-m-d H:i:s");
			//初期登録
			$cmp_params['Company'] = $company->permit_params($params);
			$cmp_params['Company']['COLOR'] = 0;
			$cmp_params['Company']['EXCISE'] = 2;
			$cmp_params['Company']['FRACTION'] = 1;

			$county = Configure::read('PrefectureCode');
			$cmp_params['Company']['SEARCH_ADDRESS'] = $county[$cmp_params['Company']['CNT_ID']].$cmp_params['Company']['ADDRESS'].$cmp_params['Company']['BUILDING'];
			$cmp_params['Company']['INSERT_DATE'] = $datetime;
			$cmp_params['Company']['LAST_UPDATE'] = $datetime;
			//トランザクションの開始
			$dataSource = $company->getDataSource();
			$dataSource->begin($company);
			//DB登録
			if($company->save($cmp_params)){
				$dataSource->commit($company);
				return true;
			}else{
				$dataSource->rollback($company);
				return false;
			}
		}else{
			return false;
		}
	}

	function mail_set($_params,&$_error){
		App::import('Model','Configuration');
		$config = new Configuration();

		$param["Configuration"] = $_params;
		return $config->index_set_data($param,$_error,'new');
	}
}