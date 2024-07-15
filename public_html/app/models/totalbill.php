<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 内容:請求書登録・編集のmodelクラス
 */
class Totalbill extends AppModel
{

    var $name = 'Totalbill';

    var $useTable = 'T_TOTAL_BILL';

    var $primaryKey = 'TBL_ID';
    
    // プラグイン読み込み
    var $actsAs = array(
        'Cakeplus.AddValidationRule',
        'CustomValidation'
    );

    // アクセス可能なカラム
    protected $accessible = array(
    	'Totalbill' => array(
    		'TBL_ID',
    		'NO',
    		'DATE',
    		'SUBJECT',
    		'CUSTOMER_NAME',
    		'CST_ID',
    		'CUSTOMER_CHARGE_NAME',
    		'CHRC_ID',
    		'CUSTOMER_CHARGE_UNIT',
    		'CHR_ID',
    		'HONOR_CODE',
    		'HONOR_TITLE',
    		'DUE_DATE',
    		'SUBTOTAL',
    		'SALE_TAX',
    		'THISM_BILL',
    		'EDIT_STAT',
    		'TOTAL',
    		'LASTM_BILL',
    		'DEPOSIT',
    		'CARRY_BILL',
    		'SALE',
    		'USR_ID',
    		'UPDATE_USR_ID',
    		'ISSUE_DATE',
    		'INSERT_DATE',
    		'LAST_UPDATE',
    	),
    );
    // バリデーション（入力チェック）の設定
    var $validate = array(
        'SUBJECT' => array(
            "rule0" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
            ),
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '件名は必須項目です'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    40
                ),
                'message' => '件名が長すぎます'
            )
        ),
        'FEE' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthJP',
                    20
                ),
                'message' => '振込手数料が長すぎます'
            )
        ),
        'CST_ID' => array(
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '企業名は必須項目です'
            ),
            "rule2" => array(
                'rule' => 'Numberonly',
                'message' => '企業名は必須項目です'
            )
        ),
        'NO' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthJP',
                    20
                ),
                'message' => '納品書番号が長すぎます'
            ),
            "rule1" => array(
                'rule' => array(
                    'manageNumber'
                ),
                'message' => '使用できない文字が含まれています'
            )
        ),
        'DATE' => array(
            "rule0" => array(
                'rule' => array(
                    'date'
                ),
                'message' => '有効な日付ではありません'
            )
        ),
        'DUE_DATE' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthJP',
                    20
                ),
                'message' => '支払い期日が長すぎます',
                'allowEmpty' => true
            )
        ),
        'LASTM_BILL' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthJP',
                    15
                ),
                'message' => '前月請求額が長すぎます'
            ),
            "rule1" => array(
                'rule' => array(
                    'NumberonlyF'
                ),
                'message' => '半角数字以外使用できません',
                'allowEmpty' => true
            )
        ),
        'DEPOSIT' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthJP',
                    15
                ),
                'message' => '御入金額が長すぎます'
            ),
            "rule1" => array(
                'rule' => array(
                    'NumberonlyF'
                ),
                'message' => '半角数字以外使用できません',
                'allowEmpty' => true
            )
        ),
        'CARRY_BILL' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthJP',
                    15
                ),
                'message' => '振込手数料が長すぎます'
            ),
            "rule1" => array(
                'rule' => array(
                    'NumberonlyF'
                ),
                'message' => '半角数字以外使用できません',
                'allowEmpty' => true
            )
        ),
        'SALE' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthJP',
                    15
                ),
                'message' => '振込手数料が長すぎます'
            ),
            "rule1" => array(
                'rule' => array(
                    'NumberonlyF'
                ),
                'message' => '半角数字以外使用できません',
                'allowEmpty' => true
            )
        ),
        'SALE_TAX' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthJP',
                    15
                ),
                'message' => '振込手数料が長すぎます'
            ),
            "rule1" => array(
                'rule' => array(
                    'NumberonlyF'
                ),
                'message' => '半角数字以外使用できません',
                'allowEmpty' => true
            )
        ),
        'THISM_BILL' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthJP',
                    15
                ),
                'message' => '振込手数料が長すぎます'
            ),
            "rule1" => array(
                'rule' => array(
                    'NumberonlyF'
                ),
                'message' => '半角数字以外使用できません',
                'allowEmpty' => true
            )
        ),
        'SUBTOTAL' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthJP',
                    15
                ),
                'message' => '振込手数料が長すぎます'
            ),
            "rule1" => array(
                'rule' => array(
                    'NumberonlyF'
                ),
                'message' => '半角数字以外使用できません',
                'allowEmpty' => true
            )
        )
    );

    var $belongsTo = array(
        'Customer' => array(
            'className' => 'Customer',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'CST_ID'
        ),
        'User' => array(
            'className' => 'User',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'USR_ID'
        ),
        'UpdateUser' => array(
            'className' => 'User',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'UPDATE_USR_ID'
        )
    );

    var $virtualFields = array(
        'CAST_THISM_BILL' => 'CAST(Totalbill.THISM_BILL AS SIGNED)'
    );
    
    // 検索条件
    var $searchColumnAry = array(
        'ACTION_DATE_FROM' => 'Totalbill.ACTION_DATE_FROM',
        'ACTION_DATE_TO' => 'Totalbill.ACTION_DATE_TO',
        'SUBJECT' => 'Totalbill.SUBJECT',
        'STATUS' => 'Totalbill.STATUS',
        'NAME' => 'Customer.NAME',
        'USR_NAME' => 'User.NAME'
    );
    // order by
    var $order = array(
        'TBL_ID DESC'
    );

    var $field = array(
        1 => "日付",
        2 => "番号",
        3 => "取引先",
        4 => "件名",
        5 => "自社担当者",
        6 => "小計",
        7 => "消費税",
        8 => "合計",
        9 => "振込手数料",
        10 => "振込期限"
    );
    
    // データの書き込み処理
    function set_data($_param, $state = null)
    {
        
        // 時間の追加
        if ($state == 'new') {
            // 初回更新時のみ
            $_param['Totalbill']['INSERT_DATE'] = date("Y-m-d H:i:s");
        }
        $_param['Totalbill']['LAST_UPDATE'] = date("Y-m-d H:i:s");
        
        $_param['Totalbill']['ISSUE_DATE'] = $_param['Totalbill']['DATE'];
        
        App::import('Model', 'totalbillitem');
        $totalbill_item = new Totalbillitem();
        
        if ($_param['Totalbill']["EDIT_STAT"] == 0) {
            $_param['Totalbill']['LASTM_BILL'] = null;
            $_param['Totalbill']['DEPOSIT'] = null;
            $_param['Totalbill']['CARRY_BILL'] = null;
            $_param['Totalbill']['SALE'] = null;
        }
        if ($_param['Totalbill']["EDIT_STAT"] == 1) {
            $_param['Totalbill']['SUBTOTAL'] = null;
        }
        
        // トランザクションの開始
        $dataSource = $this->getDataSource();
        $dataSource->begin($this);
        
        // DB登録
        if ($this->save($this->permit_params($_param['Totalbill']))) {
            if (! isset($_param['Totalbill']['TBL_ID'])) {
                $_param['Totalbill']['TBL_ID'] = $this->getInsertID();
            }
            
            if (! $totalbill_item->deleteAll(array(
                'TBL_ID' => $_param['Totalbill']['TBL_ID']
            ))) {
                // エラー時の処理
                $dataSource->rollback($Model);
                return false;
            }
            
            // アイテムの長さを定義
            $item = array();
            $i = 0;
            foreach ($_param as $key => $val) {
                if (preg_match("/^[0-9]*$/", $key)) {
                    $item[$i]['Totalbillitem']['TBL_ID'] = $_param['Totalbill']['TBL_ID'];
                    $item[$i]['Totalbillitem']['MBL_ID'] = $val['Totalbillitem']['MBL_ID'];
                    if ($state == 'new') {
                        // 初回更新時のみ
                        $item[$i]['Totalbillitem']['INSERT_DATE'] = date("Y-m-d H:i:s");
                    }
                    $item[$i]['Totalbillitem']['LAST_UPDATE'] = date("Y-m-d H:i:s");
                    $i ++;
                }
            }
            if ($totalbill_item->saveAll($item)) {
                $dataSource->commit($this);
                return $_param['Totalbill']['TBL_ID'];
            } else {
                $dataSource->rollback($this);
                return false;
            }
        } else {
            $dataSource->rollback($this);
            
            return false;
        }
    }

    function index_delete($_param)
    {
        $param = array();
        $ids = array();
        
        // 削除する項目をピックアップ
        if (is_array($_param)) {
            foreach ($_param['Totalbill'] as $key => $value) {
                if ($value == 1) {
                    $data = array(
                        'TBL_ID' => $key
                    );
                    $param[]['Totalbill'] = $data;
                    array_push($ids, $data['TBL_ID']);
                }
            }
        }
        
        if ($param) {
            // 削除処理
            return $this->deleteAll(array(
                'TBL_ID' => $ids
            ));
        } else {
            return false;
        }
    }

    function edit_select($_tbl_ID)
    {
        $result = $this->find('first', array(
            'fields' => array(
                'SUBJECT',
                'NO',
                'DUE_DATE',
                'ISSUE_DATE',
                'HONOR_CODE',
                'HONOR_TITLE',
                'CHRC_ID',
                'LASTM_BILL',
                'DEPOSIT'
            ),
            'conditions' => array(
                'TBL_ID' => $_tbl_ID
            )
        ));
        $result['Totalbill']['DATE'] = $result['Totalbill']['ISSUE_DATE'];
        return $result;
    }

    function check_select($_tbl_ID)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'TBL_ID' => $_tbl_ID
            )
        ));
        
        return $result;
    }

    function get_bill($_tbl_ID)
    {
        App::import('Model', 'totalbillitem');
        $totalbill_item = new Totalbillitem();
        
        App::import('Model', 'Bill');
        $bill = new Bill();
        
        $item_result = $totalbill_item->find('all', array(
            'conditions' => array(
                'TBL_ID' => $_tbl_ID
            )
        ));
        
        $i = 0;
        $result = array();
        foreach ($item_result as $key => $val) {
            $result[$i] = $bill->find('first', array(
                'conditions' => array(
                    'MBL_ID' => $val['Totalbillitem']['MBL_ID']
                )
            ));
            if ($result[$i] != null) {
                $result[$i]['Bill']['CHK'] = 1;
                $i ++;
            }
        }
        return $result;
    }

    function get_bill_id($_tbl_ID)
    {
        App::import('Model', 'totalbillitem');
        $totalbill_item = new Totalbillitem();
        
        App::import('Model', 'Bill');
        $bill = new Bill();
        
        $result = $totalbill_item->find('all', array(
            'conditions' => array(
                'TBL_ID' => $_tbl_ID
            )
        ));
        
        return $result;
    }
    
    // 連番設定情報
    function get_serial($_company_id)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();
        
        return $form->Get_Serial($_company_id);
    }

    function preview_data($_tbl_ID)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'TBL_ID' => $_tbl_ID
            )
        ));
        
        App::import('Model', 'Company');
        $Company = new Company();
        
        $result = array_merge($result, $Company->index_select(1));
        
        App::import('Model', 'Charge');
        $Charge = new Charge();
        
        $result['Charge']['SEAL'] = $Charge->get_image($result['Customer']['CHR_ID']);
        
        return $result;
    }

    function get_cstmer($_tbl_ID)
    {
        $result = $this->find('first', array(
            'fields' => array(
                'Customer.CST_ID',
                'Customer.NAME'
            ),
            'conditions' => array(
                'TBL_ID' => $_tbl_ID
            )
        ));
        
        return $result;
    }

    function get_edit_stat($_tbl_ID)
    {
        $result = $this->find('first', array(
            'fields' => array(
                'EDIT_STAT'
            ),
            'conditions' => array(
                'TBL_ID' => $_tbl_ID
            )
        ));
        return $result['Totalbill']['EDIT_STAT'];
    }

    function get_user_id($_tbl_ID)
    {
        $result = $this->find('first', array(
            'fields' => array(
                'USR_ID'
            ),
            'conditions' => array(
                'TBL_ID' => $_tbl_ID
            )
        ));
        return $result['Totalbill']['USR_ID'];
    }

    function search_bill($_param, $_userid = null)
    {
        App::import('Model', 'Bill');
        $Bill = new Bill();
        
        $data = array();
        
        if ((isset($_param['Totalbill']['FROM']) && $_param['Totalbill']['FROM']) || (isset($_param['Totalbill']['TO']) && $_param['Totalbill']['TO'])) {
            if (isset($_param['Totalbill']['CST_ID']) && $_param['Totalbill']['CST_ID'] != 0) {
                if ($_param['Totalbill']['TO'] != null) {
                    if ($_userid == null) {
                        $data = $Bill->find('all', array(
                            'conditions' => array(
                                "Bill.CST_ID" => $_param['Totalbill']['CST_ID'],
                                "Bill.ISSUE_DATE >= " => $_param['Totalbill']['FROM'],
                                "Bill.ISSUE_DATE <= " => $_param['Totalbill']['TO'],
                                'Bill.STATUS' => 1
                            ),
                            'group' => 'Bill.MBL_ID',
                        ));
                    } else {
                        $data = $Bill->find('all', array(
                            'conditions' => array(
                                "Bill.CST_ID" => $_param['Totalbill']['CST_ID'],
                                "Bill.ISSUE_DATE >= " => $_param['Totalbill']['FROM'],
                                "Bill.ISSUE_DATE <= " => $_param['Totalbill']['TO'],
                                "Bill.USR_ID" => $_userid,
                                'Bill.STATUS' => 1
                            ),
                            'group' => 'Bill.MBL_ID',
                        ));
                    }
                } else {
                    if ($_userid == null) {
                        $data = $Bill->find('all', array(
                            'conditions' => array(
                                "Bill.CST_ID" => $_param['Totalbill']['CST_ID'],
                                "Bill.ISSUE_DATE >= " => $_param['Totalbill']['FROM'],
                                'Bill.STATUS' => 1
                            ),
                            'group' => 'Bill.MBL_ID',
                        ));
                    } else {
                        $data = $Bill->find('all', array(
                            'conditions' => array(
                                "Bill.CST_ID" => $_param['Totalbill']['CST_ID'],
                                "Bill.ISSUE_DATE >= " => $_param['Totalbill']['FROM'],
                                "Bill.USR_ID" => $_userid,
                                'Bill.STATUS' => 1
                            ),
                            'group' => 'Bill.MBL_ID',
                        ));
                    }
                }
            } else {
                if ($_param['Totalbill']['TO'] != null) {
                    if ($_userid == null) {
                        $data = $Bill->find('all', array(
                            'conditions' => array(
                                "Bill.ISSUE_DATE >= " => $_param['Totalbill']['FROM'],
                                "Bill.ISSUE_DATE <= " => $_param['Totalbill']['TO'],
                                'Bill.STATUS' => 1
                            ),
                            'group' => 'Bill.MBL_ID',
                        ));
                    } else {
                        $data = $Bill->find('all', array(
                            'conditions' => array(
                                "Bill.ISSUE_DATE >= " => $_param['Totalbill']['FROM'],
                                "Bill.ISSUE_DATE <= " => $_param['Totalbill']['TO'],
                                "Bill.USR_ID" => $_userid,
                                'Bill.STATUS' => 1
                            ),
                            'group' => 'Bill.MBL_ID',
                        ));
                    }
                } else {
                    if ($_userid == null) {
                        $data = $Bill->find('all', array(
                            'conditions' => array(
                                "Bill.ISSUE_DATE >= " => $_param['Totalbill']['FROM'],
                                'Bill.STATUS' => 1
                            ),
                            'group' => 'Bill.MBL_ID',
                        ));
                    } else {
                        $data = $Bill->find('all', array(
                            'conditions' => array(
                                "Bill.ISSUE_DATE >= " => $_param['Totalbill']['FROM'],
                                "Bill.USR_ID" => $_userid,
                                'Bill.STATUS' => 1
                            ),
                            'group' => 'Bill.MBL_ID',
                        ));
                    }
                }
            }
        } elseif (isset($_param['Totalbill']['CST_ID']) && $_param['Totalbill']['CST_ID'] != 0) {
            if ($_userid == null) {
                        echo 'asdf<BR><BR><BR>';
                $data = $Bill->find('all', array(
                    'conditions' => array(
                        "Bill.CST_ID" => $_param['Totalbill']['CST_ID'],
                        'Bill.STATUS' => 1
                    ),
                    'group' => 'Bill.MBL_ID',
                ));
            } else {
                $data = $Bill->find('all', array(
                    'conditions' => array(
                        "Bill.CST_ID" => $_param['Totalbill']['CST_ID'],
                        "Bill.USR_ID" => $_userid,
                        'Bill.STATUS' => 1
                    ),
                    'group' => 'Bill.MBL_ID',
                ));
            }
        }
        return $data;
    }
}