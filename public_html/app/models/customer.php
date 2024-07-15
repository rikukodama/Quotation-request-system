<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */
class Customer extends AppModel
{

    function __construct()
    {
        parent::__construct();
    }

    var $name = "Customer";

    var $useTable = 'T_CUSTOMER';

    var $primaryKey = 'CST_ID';

    // 検索用フィールド
    var $searchColumnAry = array(
        'NAME' => array( // OR 検索
            'Customer.NAME',
            'Customer.NAME_KANA'
        ),
        'ADDRESS' => 'Customer.SEARCH_ADDRESS',
        'CST_ID' => 'Customer.CST_ID'
    );

    var $joins;

    var $order = 'CST_ID DESC';

    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'USR_ID'
        )
    );

    // バリデーション
    var $actsAs = array(
        'Cakeplus.AddValidationRule',
        'CustomValidation'
    );

    protected $accessible = array(
    		'Customer' => array(
    				'CST_ID',
    				'NAME',
    				'NAME_KANA',
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
    				'HONOR_CODE',
    				'HONOR_TITLE',
    				'WEBSITE',
    				'CHR_NAME',
    				'CHR_ID',
    				'CUTOOFF_SELECT',
    				'CUTOOFF_DATE',
    				'PAYMENT_MONTH',
    				'PAYMENT_SELECT',
    				'PAYMENT_DAY',
    				'EXCISE',
    				'TAX_FRACTION',
    				'TAX_FRACTION_TIMING',
    				'FRACTION',
    				'NOTE',
    				'USR_ID',
    				'UPDATE_USR_ID',
    				'SEARCH_ADDRESS',
    				'CMP_ID',
    				'INSERT_DATE',
    				'LAST_UPDATE',
    		),
    );
    var $validate = array(
        'NAME' => array(
            "rule0" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
            ),
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '社名は必須項目です'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    30
                ),
                'message' => '社名が長すぎます'
            )
        ),
        'NAME_KANA' => array(
            "rule2" => array(
                'rule' => array(
                    'maxLengthJP',
                    100
                ),
                'message' => '社名カナが長すぎます'
            ),
            "rule3" => array(
                'rule' => array(
                    'katakanaSpace'
                ),
                'message' => '社名カナに入力できない値があります。'
            ),
            "rule4" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
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
            "rule1" => array(
                'rule' => array(
                    'maxLengthW',
                    50
                ),
                'message' => '建物名が長すぎます'
            )
        ),
        'WEBSITE' => array(
            "rule1" => array(
                'rule' => array(
                    'url',
                    true
                ),
                'message' => 'ホームページアドレスが正しくありません',
                'allowEmpty' => true
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthJP',
                    100
                ),
                'message' => 'ホームページアドレスが長すぎます'
            )
        ),
        'NOTE' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthJP',
                    1000
                ),
                'message' => '備考が長すぎます'
            )
        ),
        'CUTOOFF_DATE' => array(
            "rule0" => array(
                'rule' => array(
                    'RadioPairTxt',
                    'CUTOOFF_SELECT',
                    1
                ),
                'message' => '日付を入力してください'
            ),
            "rule1" => array(
                'rule' => 'Numberonly',
                'message' => '正しい日付を入力してください'
            ),
            "rule2" => array(
                'rule' => array(
                    'range',
                    0,
                    32
                ),
                'message' => '正しい日付を入力してください',
                'allowEmpty' => true
            )
        ),
        'PAYMENT_DAY' => array(
            "rule0" => array(
                'rule' => array(
                    'RadioPairTxt',
                    'PAYMENT_SELECT',
                    1
                ),
                'message' => '日付を入力してください'
            ),
            "rule1" => array(
                'rule' => 'Numberonly',
                'message' => '正しい日付を入力してください'
            ),
            "rule2" => array(
                'rule' => array(
                    'range',
                    0,
                    32
                ),
                'message' => '正しい日付を入力してください',
                'allowEmpty' => true
            )
        ),
        'HONOR_TITLE' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthW',
                    4
                ),
                'message' => '敬称が長すぎます'
            )
        )
    );

    // データの書き込み処理
    function set_data($_param, $_company_id, $_state = '', $_perror, $_ferror)
    {

        // 検索用住所の作成
        $county = Configure::read('PrefectureCode');
        if (! isset($_param['Customer']['BUILDING'])) {
            $_param['Customer']['BUILDING'] = "";
        }

        if ($_param['Customer']['CNT_ID'] != 0) {
            $_param['Customer']['SEARCH_ADDRESS'] = $county[$_param['Customer']['CNT_ID']] . $_param['Customer']['ADDRESS'] . $_param['Customer']['BUILDING'];
        } else {
            $_param['Customer']['SEARCH_ADDRESS'] = "";
        }

        // 会社情報のセット
        $_param['Customer']['CMP_ID'] = $_company_id;

        // 時間のセット
        if ($_state === "new")
            $_param['Customer']['INSERT_DATE'] = date("Y-m-d H:i:s");
        $_param['Customer']['LAST_UPDATE'] = date("Y-m-d H:i:s");

        // トランザクションの開始
        $dataSource = $this->getDataSource();
        $dataSource->begin($this);
        if ($param = $this->save($this->permit_params($_param['Customer']))) {
            if ($_perror != 1 && $_ferror != 1) {
            	$dataSource->commit($this);
                $param['Customer']['CST_ID'] = $this->getInsertID();
            } else {
                // エラー時の処理
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
            // エラー時の処理
            $dataSource->rollback($this);
            $param['error'] = $this->invalidFields();

            if ($_perror == 1) {
                $param['error']['PHONE'] = "正しい電話番号を入力してください";
            }
            if ($_ferror == 1) {
                $param['error']['FAX'] = "正しいfax番号を入力してください";
            }
        }
        return $param;
    }

    // 削除処理
    function index_delete($_param, &$_error)
    {
        $param = array();

        App::import('Model', 'quote');
        $Quote = new Quote();

        App::import('Model', 'bill');
        $Bill = new Bill();

        App::import('Model', 'delivery');
        $Delivery = new Delivery();

        App::import('Model', 'customer_charge');
        $CustomerCharge = new CustomerCharge();
        $id = array();

        // 削除する項目をピックアップ
        if (is_array($_param) && isset($_param['Customer'])) {
            foreach ($_param['Customer'] as $key => $value) {

                if ($value == 1) {

                    if ($Quote->find('all', array(
                        'conditions' => array(
                            'Quote.CST_ID' => $key
                        )
                    ))) {
                        $_error['Quote'] = 1;
                    } else
                        if ($Bill->find('all', array(
                            'conditions' => array(
                                'Bill.CST_ID' => $key
                            )
                        ))) {
                            $_error['Bill'] = 1;
                        } else
                            if ($Delivery->find('all', array(
                                'conditions' => array(
                                    'Delivery.CST_ID' => $key
                                )
                            ))) {
                                $_error['Delivery'] = 1;
                            } else
                                if ($CustomerCharge->find('all', array(
                                    'conditions' => array(
                                        'CustomerCharge.CST_ID' => $key
                                    )
                                ))) {
                                    $_error['CustomerCharge'] = 1;
                                } else {
                                    $data = array(
                                        'CST_ID' => $key
                                    );
                                    $param[]['Customer'] = $data;
                                    array_push($id, $key);
                                }
                }
            }
        }
        if ($_error) {
            return false;
        }

        if ($param) {
            // 削除処理
            return $this->deleteAll(array(
                'CST_ID' => $id
            ));
        } else {
            return false;
        }
    }

    // 自社担当者情報の取得
    function select_charge($_company_ID, $_condition = null)
    {

        // 担当者モデルの読み込み
        App::import('Model', 'charge');
        $Charge = new Charge();

        // 担当者情報の取得
        $result = $Charge->find('all', array(
            'fields' => array(
                'Charge.CHR_ID',
                'Charge.CHARGE_NAME'
            ),
            'conditions' => $_condition
        ));

        // 情報の整備
        $param = array();
        if ($result) {
            foreach ($result as $value) {
                $param[$value['Charge']['CHR_ID']] = $value['Charge']['CHARGE_NAME'];
            }
        }

        return $param;
    }

    // 自社担当者情報の取得
    function get_charge($chr_id)
    {

        // 担当者モデルの読み込み
        App::import('Model', 'charge');
        $Charge = new Charge();

        // 担当者情報の取得
        $result = $Charge->find('first', array(
            'fields' => array(
                'CHR_ID',
                'CHARGE_NAME'
            ),
            'conditions' => array(
                'Charge.CHR_ID' => $chr_id
            )
        ));

        // 情報の整備
        $param = "";
        if ($result) {
            $param = $result['Charge']['CHARGE_NAME'];
        }

        return $param;
    }

    // 顧客情報の取得
    function edit_select($_customer_ID)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'CST_ID' => $_customer_ID
            )
        ));

        return $result;
    }

    // 顧客情報の取得
    function select_customer($_condition = null)
    {
        $param = $this->find('all', array(
            'fields' => array(
                'CST_ID',
                'NAME'
            ),
            'conditions' => $_condition
        ));

        $result = array();

        $result[0] = "顧客名を選択してください";

        foreach ($param as $key => $value) {
            $result[$value['Customer']['CST_ID']] = $value['Customer']['NAME'];
        }

        return $result;
    }

    // 顧客情報の取得
    function get_customer($cst_id)
    {
        $result = $this->find('first', array(
            'fields' => array(
                'CST_ID',
                'NAME'
            ),
            'conditions' => array(
                'Customer.CST_ID' => $cst_id
            )
        ));

        $param = "";

        if ($result) {
            $param = $result['Customer']['NAME'];
        }

        return $param;
    }

    // 帳票との紐付け確認
    function check_pegging($_param)
    {
        $param = array();
        $id = array();

        if (is_array($_param)) {
            foreach ($_param as $key => $value) {
                $id[] = $value['Customer']["CST_ID"];
                $param[$value['Customer']["CST_ID"]] = 0;
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
                'CST_ID'
            ),
            'conditions' => array(
                'Quote.CST_ID' => $id
            )
        ));
        if (is_array($result)) {
            foreach ($result as $key => $value) {
                $param[$value['Quote']['CST_ID']] = 1;
            }
        }

        // 請求書モデルの読み込み
        App::import('Model', 'bill');
        $Bill = new Bill();

        $result = $Bill->find('all', array(
            'fields' => array(
                'CST_ID'
            ),
            'conditions' => array(
                'Bill.CST_ID' => $id
            )
        ));
        if (is_array($result)) {
            foreach ($result as $key => $value) {
                $param[$value['Bill']['CST_ID']] = 1;
            }
        }

        // 納品書モデルの読み込み
        App::import('Model', 'delivery');
        $Delivery = new Delivery();

        $result = $Delivery->find('all', array(
            'fields' => array(
                'CST_ID'
            ),
            'conditions' => array(
                'Delivery.CST_ID' => $id
            )
        ));
        if (is_array($result)) {
            foreach ($result as $key => $value) {
                $param[$value['Delivery']['CST_ID']] = 1;
            }
        }

        // 顧客担当者モデルの読み込み
        App::import('Model', 'customer_charge');
        $CustomerCharge = new CustomerCharge();

        $result = $CustomerCharge->find('all', array(
            'fields' => array(
                'CST_ID'
            ),
            'conditions' => array(
                'CustomerCharge.CST_ID' => $id
            )
        ));
        if (is_array($result)) {
            foreach ($result as $key => $value) {
                $param[$value['CustomerCharge']['CST_ID']] = 1;
            }
        }
        return $param;
    }

    /**
     * 顧客敬称を取得
     *
     * @param array $_company_id
     * @return array $result
     */
    function get_honor($_company_id)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Get_Honor($_company_id);
    }

    function get_payment($_company_ID)
    {

        // 自社モデルの読み込み
        App::import('Model', 'company');
        $Company = new Company();

        $result = $Company->find('first', array(
            'conditions' => array(
                'CMP_ID' => $_company_ID
            )
        ));

        if (! $result) {
            return false;
        }

        $param['Customer']['CUTOOFF_SELECT'] = 0;
        $param['Customer']['PAYMENT_SELECT'] = 0;
        $param['Customer']['EXCISE'] = $result['Company']['EXCISE'];
        $param['Customer']['FRACTION'] = $result['Company']['FRACTION'];
        $param['Customer']['TAX_FRACTION'] = $result['Company']['TAX_FRACTION'];
        $param['Customer']['TAX_FRACTION_TIMING'] = $result['Company']['TAX_FRACTION_TIMING'];
        
        return $param;
    }

    /**
     * 顧客ごとの帳票件数を取得
     */
    function getInvoiceNum()
    {
        // 自社モデルの読み込み
        App::import('Model', 'Bill');
        App::import('Model', 'Quote');
        App::import('Model', 'Delivery');
        $bill = new Bill();
        $quote = new Quote();
        $delivery = new Delivery();

        $res = $this->find('all', array(
            'fields' => 'DISTINCT Customer.CST_ID'
        ));
        $inv_num = array();
        for ($i = 0; $i < count($res); $i ++) {
        	$id = $res[$i]['Customer']['CST_ID'];
        	$inv_num[$id] = array();
        	$data = $quote->find('all', array(
        			'conditions' => array(
        					'Quote.CST_ID' => $id
        			),
        			'group'=> 'Quote.MQT_ID'

        	));
        	$inv_num[$id]['Quote'] = count($data);
        	$data = $bill->find('all', array(
        			'conditions' => array(
        					'Bill.CST_ID' => $id
        			),
                'group'=> 'Bill.MBL_ID'
        	));
        	$inv_num[$id]['Bill'] = count($data);
        	$data = $delivery->find('all', array(
        			'conditions' => array(
        					'Delivery.CST_ID' => $id
        			),
                'group'=> 'Delivery.MDV_ID'
        	));
        	$inv_num[$id]['Delivery'] = count($data);
        }
        return $inv_num;
    }
}
?>
