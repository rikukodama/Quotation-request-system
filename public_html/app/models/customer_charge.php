<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */
class CustomerCharge extends AppModel
{

    var $name = "CustomerCharge";

    var $useTable = 'T_CUSTOMER_CHARGE';

    var $primaryKey = 'CHRC_ID';

    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'USR_ID'
        ),
        'Customer' => array(
            'className' => 'Customer',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'CST_ID'
        )
    );
    
    // バリデーション
    var $actsAs = array(
        'Cakeplus.AddValidationRule',
        'CustomValidation'
    );

    protected $accessible = array(
    		'CustomerCharge' => array(
    				'CHRC_ID',
    				'STATUS',
    				'CUSTOMER_NAME',
    				'CST_ID',
    				'CHARGE_NAME',
    				'CHARGE_NAME_KANA',
    				'UNIT',
    				'POST',
    				'MAIL',
    				'POSTCODE1',
    				'POSTCODE2',
    				'CNT_ID',
    				'ADDRESS',
    				'BUILDING',
    				'PHONE_NO1',
    				'PHONE_NO2',
    				'PHONE_NO3',
    				'FAX_NO1',
    				'FAX_NO2',
    				'FAX_NO3',
    				'USR_ID',
    				'UPDATE_USR_ID',
    				'SEARCH_ADDRESS',
    				'INSERT_DATE',
    				'LAST_UPDATE',
    		),
    );
    var $validate = array(
        'UNIT' => array(
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    30
                ),
                'message' => '部署名が長すぎます'
            )
        ),
        'POST' => array(
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    30
                ),
                'message' => '役職名が長すぎます'
            )
        ),
        'CHARGE_NAME' => array(
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '担当者名は必須項目です'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    30
                ),
                'message' => '担当者名が長すぎます'
            )
        ),
        'CHARGE_NAME_KANA' => array(
            "rule2" => array(
                'rule' => array(
                    'maxLengthJP',
                    60
                ),
                'message' => '担当者名カナが長すぎます'
            ),
            "rule3" => array(
                'rule' => array(
                    'katakanaSpace'
                ),
                'message' => '担当者名カナに入力できない値があります。'
            ),
            "rule4" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
            )
        ),
        'MAIL' => array(
            "rule1" => array(
                'rule' => array(
                    'maxLengthJP',
                    256
                ),
                'message' => 'メールアドレスが長すぎます'
            ),
            "rule2" => array(
                'rule' => array(
                    'email'
                ),
                'message' => '有効なメールアドレスではありません',
                'allowEmpty' => true
            )
        ),
        'POSTCODE1' => array(
            "rule0" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => '正しい郵便番号を入力してください'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthJP',
                    3
                ),
                'message' => '正しい郵便番号を入力してください'
            ),
            "rule3" => array(
                'rule' => 'Numberonly',
                'message' => '正しい郵便番号を入力してください'
            )
        ),
        'POSTCODE2' => array(
            "rule0" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthJP',
                    4
                ),
                'message' => '郵便番号が長すぎます'
            ),
            "rule3" => array(
                'rule' => 'Numberonly',
                'message' => '正しい郵便番号を入力してください'
            )
        ),
        'ADDRESS' => array(
            "rule0" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    50
                ),
                'message' => '住所が長すぎます'
            )
        ),
        'BUILDING' => array(
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    50
                ),
                'message' => '建物名が長すぎます'
            )
        )
    );
    
    // データの書き込み処理
    function set_data($_param, $_state = '', $_perror, $_ferror, $_cst_id = null)
    {
        if (isset($_param['CustomerCharge']['CHRC_ID'])) {
            $this->id = $_param['CustomerCharge']['CHRC_ID'];
        }
        
        // 検索用住所の作成
        $county = Configure::read('PrefectureCode');
        
        $building = isset($_param['CustomerCharge']['BUILDING']) ? $_param['CustomerCharge']['BUILDING'] : '';
        
        $_param['CustomerCharge']['SEARCH_ADDRESS'] = "";
        
        if ($_param['CustomerCharge']['CNT_ID']) {
            $_param['CustomerCharge']['SEARCH_ADDRESS'] .= $county[$_param['CustomerCharge']['CNT_ID']];
        }
        
        $_param['CustomerCharge']['SEARCH_ADDRESS'] .= $_param['CustomerCharge']['ADDRESS'] . $building;
        
        // 取引先の置換
        if (isset($_cst_id)) {
            $_param['CustomerCharge']['CST_ID'] = $_cst_id;
        }
        
        // 時間のセット
        if ($_state === "new")
            $_param['CustomerCharge']['INSERT_DATE'] = date("Y-m-d H:i:s");
        $_param['CustomerCharge']['LAST_UPDATE'] = date("Y-m-d H:i:s");
        
        // トランザクションの開始
        $dataSource = $this->getDataSource();
        $dataSource->begin($this);
        
        // DB登録
        if ($param = $this->save($this->permit_params($_param['CustomerCharge']))) {
            if ($_perror != 1 && $_ferror != 1) {
                $dataSource->commit($this);
                $param['CustomerCharge']['CHRC_ID'] = $this->getInsertID();
            } else {
                // 失敗
                $dataSource->rollback($this);
                if ($_perror == 1) {
                    $param['error']['PHONE'] = "正しい電話番号を入力してください";
                }
                if ($_ferror == 1) {
                    $param['error']['FAX'] = "正しいfax番号を入力してください";
                }
                return $param;
            }
        } else {
            $dataSource->rollback($this);
            $param['error'] = $this->invalidFields();
            if ($_perror == 1) {
                $param['error']['PHONE'] = "正しい電話番号を入力してください";
            }
            if ($_ferror == 1) {
                $param['error']['FAX'] = "正しいfax番号を入力してください";
            }
            return $param;
        }
        return $param;
    }
    
    // 削除処理
    function index_delete($_param)
    {
        $param = array();
        
        // 削除する項目をピックアップ
        if (is_array($_param)) {
            foreach ($_param['CustomerCharge'] as $key => $value) {
                if ($value == 1) {
                    $param[] = $key;
                }
            }
        }
        
        if ($param) {
            // 削除処理
            return $this->deleteAll(array(
                'CHRC_ID' => $param
            ));
        } else {
            return false;
        }
    }
    
    // 自社担当者情報の取得
    function select_charge($_company_ID)
    {
        
        // 担当者モデルの読み込み
        App::import('Model', 'charge');
        $Charge = new Charge();
        
        // 担当者情報の取得
        $result = $Charge->find('all', array(
            'fields' => array(
                'CHRC_ID',
                'CHARGE_NAME'
            ),
            'conditions' => array(
                'CMP_ID' => $_company_ID
            )
        ));
        
        // 情報の整備
        $param = array();
        if ($result) {
            foreach ($result as $value) {
                $param[$value['Charge']['CHRC_ID']] = $value['Charge']['CHARGE_NAME'];
            }
        }
        return $param;
    }
    
    // 顧客情報の取得
    function edit_select($_customer_ID)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'CHRC_ID' => $_customer_ID
            )
        ));
        
        return $result;
    }
    
    // select
    function select($id = null)
    {
        // パラメータを条件に追加
        $conditions = array();
        if ($id) {
            $conditions[] = array(
                'CHRC_ID' => $id
            );
        }
        $conditions[] = array();
        
        $options = array(
            'fields' => '*',
            'conditions' => $conditions
        );
        
        $ret = $this->find('all', $options);
        
        return $ret;
    }
    
    /*
     * 検索
     */
    // field
    var $searchColumnAry = array(
        'CHARGE_NAME' => array( // OR 検索
            'CHARGE_NAME',
            'CHARGE_NAME_KANA'
        ),
        'COMPANY_NAME' => 'Customer.NAME',
        'STATUS' => 'CustomerCharge.STATUS'
    );
    
    // order by
    var $order = array(
        'CHRC_ID DESC'
    );
    
    // 帳票との紐付け確認
    function check_pegging($_param)
    {
        $param = array();
        $id = array();
        
        if (is_array($_param)) {
            foreach ($_param as $key => $value) {
                $id[] = $value['CustomerCharge']["CHRC_ID"];
                $param[$value['CustomerCharge']["CHRC_ID"]] = 0;
            }
        }
        
        if (empty($id)) {
            return false;
        }
        
        // 見積書モデルの読み込み
        App::import('Model', 'quote');
        $Quote = new Quote();
        
        $result = $Quote->find('all', array(
            'fields' => array(
                'CHRC_ID'
            ),
            'conditions' => array(
                'Quote.CHRC_ID' => $id
            )
        ));
        if (is_array($result)) {
            foreach ($result as $key => $value) {
                $param[$value['Quote']['CHRC_ID']] = 1;
            }
        }
        
        // 請求書モデルの読み込み
        App::import('Model', 'bill');
        $Bill = new Bill();
        
        $result = $Bill->find('all', array(
            'fields' => array(
                'CHRC_ID'
            ),
            'conditions' => array(
                'Bill.CHRC_ID' => $id
            )
        ));
        if (is_array($result)) {
            foreach ($result as $key => $value) {
                $param[$value['Bill']['CHRC_ID']] = 1;
            }
        }
        
        // 納品書モデルの読み込み
        App::import('Model', 'delivery');
        $Delivery = new Delivery();
        
        $result = $Delivery->find('all', array(
            'fields' => array(
                'CHRC_ID'
            ),
            'conditions' => array(
                'Delivery.CHRC_ID' => $id
            )
        ));
        if (is_array($result)) {
            foreach ($result as $key => $value) {
                $param[$value['Delivery']['CHRC_ID']] = 1;
            }
        }
        return $param;
    }
}

?>
