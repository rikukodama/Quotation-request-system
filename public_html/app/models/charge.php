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
class Charge extends AppModel
{

    var $name = 'Charge';

    var $primaryKey = 'CHR_ID';

    var $useTable = 'T_CHARGE';

    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'USR_ID'
        )
    );
    
    // プラグイン読み込み
    var $actsAs = array(
        'Cakeplus.AddValidationRule',
        'CustomValidation'
    );
    protected $accessible = array(
    		'Charge' => array(
    			'CHR_ID',
    			'STATUS',
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
    			'SEAL_METHOD',
    			'SEAL_STR',
    			'SEAL',
    			'DEL_SEAL',
    			'CHR_SEAL_FLG',
    			'USR_ID',
    			'UPDATE_USR_ID',
    			'SEARCH_ADDRESS',
    			'INSERT_DATE',
    			'CMP_ID',
    			'LAST_UPDATE',
    		),
    );
    // バリデーション（入力チェック）の設定
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
                'message' => '担当者名（カナ）が長すぎます'
            ),
            "rule3" => array(
                'rule' => array(
                    'katakanaSpace'
                ),
                'message' => '担当者名（カナ）に入力できない値があります。'
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
                'message' => '正しい郵便番号を入力してください'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthJP',
                    4
                ),
                'message' => '正しい郵便番号を入力してください'
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
        ),
        'SEAL_STR' => array(
            "rule1" => array(
                'rule' => array(
                    'maxLengthJP',
                    4
                ),
                'message' => '印鑑の名前が長すぎます'
            )
        ),
        'SEAL_METHOD' => array(
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '印鑑作成方法は必須です。'
            )
        )
    );
    
    // 一覧取得処理
    function index_select($_company_ID)
    {
        $result = $this->find('all', array(
            'fields' => array(
                'CHR_ID',
                'CHARGE_NAME',
                'UNIT',
                'PHONE_NO1',
                'PHONE_NO2',
                'PHONE_NO3',
                'STATUS'
            ),
            'conditions' => array(
                'CMP_ID' => $_company_ID
            )
        ));
        
        return $result;
    }
    
    // 削除処理
    function index_delete($_param)
    {
        $param = array();
        $cus = array();
        
        // 削除する項目をピックアップ
        if (is_array($_param)) {
            
            foreach ($_param['Charge'] as $key => $value) {
                if ($value == 1) {
                    $param[] = $key;
                    $cus[] = $key;
                }
            }
        }
        
        // 紐付けられている顧客をすべて抽出
        App::import('Model', 'customer');
        $Customer = new Customer();
        
        $result = $Customer->find('all', array(
            'fields' => array(
                'CST_ID',
                'CHR_ID'
            ),
            'conditions' => array(
                'CHR_ID' => $cus
            )
        ));
        
        // トランザクションの開始
        $dataSource = $this->getDataSource();
        $dataSource->begin($this);
        
        if (! empty($result)) {
            
            foreach ($result as $key => $value) {
                $result[$key]['Customer']['CHR_ID'] = 0;
            }
            
            if (! $Customer->saveAll($result, array(
                'atomic' => false
            ))) {
                // エラー時の処理
                $dataSource->rollback($this);
                return false;
            }
        }
        
        if ($param) {
            // 削除処理
            if ($this->deleteAll(array(
                'CHR_ID' => $param
            ))) {
                // 成功
                $dataSource->commit($this);
                return true;
            } else {
                // エラー時の処理
                $dataSource->rollback($this);
                return false;
            }
        } else {
            // エラー時の処理
            $dataSource->rollback($this);
            return false;
        }
    }
    
    // 顧客取得処理
    function get_charge($_chr_id)
    {
        $result = $this->find('first', array(
            'fields' => array(
                'CHARGE_NAME'
            ),
            'conditions' => array(
                'CHR_ID' => $_chr_id
            )
        ));
        
        if (! $result) {
            return null;
        }
        return $result['Charge']['CHARGE_NAME'];
    }
    
    // データの書き込み処理
    function set_data($_param, $_company_ID, $_perror, $_ferror, &$_chr_id = null)
    {
        
        // 検索用住所の作成
        $county = Configure::read('PrefectureCode');
        
        $_param['Charge']['SEARCH_ADDRESS'] = "";
        
        if ($_param['Charge']['CNT_ID']) {
            $_param['Charge']['SEARCH_ADDRESS'] .= $county[$_param['Charge']['CNT_ID']];
        }
        
        $_param['Charge']['SEARCH_ADDRESS'] .= $_param['Charge']['ADDRESS'] . $_param['Charge']['BUILDING'];
        
        $imageerror = 0;
        
        // 時間の追加
        if (! isset($_param['Charge']['CHR_ID'])) {
            // 初回更新時のみ
            $_param['Charge']['INSERT_DATE'] = date("Y-m-d H:i:s");
        }
        $_param['Charge']['LAST_UPDATE'] = date("Y-m-d H:i:s");
        $_param['Charge']['CMP_ID'] = $_company_ID;
        
        App::import('Vendor', 'model/other');
        $other = new OtherModel();
        
        // 印鑑の登録
        if ($_param['Charge']['SEAL_METHOD'] == 0) {
            $other->Image_Create($_param, 'Charge', $imageerror);
        } else {}
        // トランザクションの開始
        $dataSource = $this->getDataSource();
        $dataSource->begin($this);
        
        // DB登録
        if ($this->save($this->permit_params($_param))) {
            if ($_perror != 1 && $_ferror != 1 && empty($imageerror)) {
                $dataSource->commit($this);
                $_chr_id = $this->getInsertID();
                return true;
            } else {
                // 失敗
                $dataSource->rollback($this);
                if (! empty($imageerror)) {
                    return $imageerror;
                }
                return false;
            }
        } else {
            $dataSource->rollback($this);
            
            if (! empty($imageerror)) {
                return $imageerror;
            }
            return false;
        }
    }
    
    // 編集担当者情報
    function edit_select($_charge_ID)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'CHR_ID' => $_charge_ID
            )
        ));
        
        return $result;
    }

    /**
     * 画像の取得処理
     *
     * @param array $_companyID            
     * @return $result
     */
    function get_image($_charge_ID)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'CHR_ID' => $_charge_ID
            ),
            'fields' => 'Charge.SEAL'
        ));
        
        // 存在しない場合
        if (! $result)
            return null;
        
        return $result['Charge']['SEAL'];
    }

    /**
     * 印鑑の削除
     *
     * @param array $_chargeID            
     * @return bool
     */
    function seal_delete($_chargeID)
    {
        $result = $this->read(null, $_chargeID);
        $result['Charge']['SEAL'] = NULL;
        if ($result) {
            // 削除処理
            return $this->saveAll($result);
        } else {
            return false;
        }
    }
    
    // 押印設定の取得
    function getSealFlg($_chargeID)
    {
        $result = $this->find('first', array(
            'fields' => array(
                'Charge.CHR_SEAL_FLG'
            ),
            'conditions' => array(
                'Charge.CHR_ID' => $_chargeID
            )
        ));
        
        return $result['Charge']['CHR_SEAL_FLG'];
    }
    
    /*
     * 検索
     */
    // field
    var $searchColumnAry = array(
        'CHARGE_NAME' => array( // OR 検索
            'Charge.CHARGE_NAME',
            'Charge.CHARGE_NAME_KANA'
        ),
        'UNIT' => array(
            'Charge.UNIT'
        ),
        'STATUS' => 'Charge.STATUS'
    );
    
    // order by
    var $order = array(
        'CHR_ID DESC'
    );
    
    // 帳票との紐付け確認
    function check_pegging($_param)
    {
        $param = array();
        $id = array();
        
        if (is_array($_param)) {
            foreach ($_param as $key => $value) {
                $id[] = $value['Charge']["CHR_ID"];
                $param[$value['Charge']["CHR_ID"]] = 0;
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
                'CHR_ID'
            ),
            'conditions' => array(
                'Quote.CHR_ID' => $id
            )
        ));
        if (is_array($result)) {
            foreach ($result as $key => $value) {
                $param[$value['Quote']['CHR_ID']] = 1;
            }
        }
        
        // 請求書モデルの読み込み
        App::import('Model', 'bill');
        $Bill = new Bill();
        
        $result = $Bill->find('all', array(
            'fields' => array(
                'CHR_ID'
            ),
            'conditions' => array(
                'Bill.CHR_ID' => $id
            )
        ));
        if (is_array($result)) {
            foreach ($result as $key => $value) {
                $param[$value['Bill']['CHR_ID']] = 1;
            }
        }
        
        // 納品書モデルの読み込み
        App::import('Model', 'delivery');
        $Delivery = new Delivery();
        
        $result = $Delivery->find('all', array(
            'fields' => array(
                'CHR_ID'
            ),
            'conditions' => array(
                'Delivery.CHR_ID' => $id
            )
        ));
        if (is_array($result)) {
            foreach ($result as $key => $value) {
                $param[$value['Delivery']['CHR_ID']] = 1;
            }
        }
        return $param;
    }
}
?>
