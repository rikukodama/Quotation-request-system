<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * Ajax用のcontrollerクラス
 */
class AjaxController extends AppController
{

    var $name = "Ajax";

    var $uses = array(
        "Ajax",
        "Customer",
        'Post',
        "Item",
        'User',
        "Charge",
        "CustomerCharge",
        "Quote",
        "Bill",
        "Delivery",
        'Post'
    );

    var $layout = 'ajax';

    function beforeFilter()
    {
        $user = $this->Auth->user();
        $this->set('user', $user['User']); // ctpで$userを使えるようにする
                                          // インストール時に使用する為
        $this->Auth->allow('candidacy', 'search');
    }
    
    // ユーザーIDが使用可能かどうかを検索
    function searchid()
    {
        if (! isset($this->params['form']['usercode'])) {
            $this->params['form']['usercode'] = 0;
        }
        
        if (isset($this->params['form']['logincode'])) {
            $data = $this->User->SearchUserID($this->params['form']['logincode'], $this->params['form']['usercode']);
        }
        
        if ($data == 1) {
            echo "<span class='allow'>そのIDは使用可能です。</span>";
        } elseif ($data == 0) {
            echo "<span class='must'>そのIDは既に使用されています。</span>";
        } elseif ($data == 2) {
            echo "<span class='must'>文字数が足りません。</span>";
        } elseif ($data == 3) {
            echo "<span class='must'>文字数が長すぎます。</span>";
        } elseif ($data == 4) {
            echo "<span class='must'>使用できない文字が含まれています。</span>";
        }
    }
    // 郵便番号から該当する住所を検索
    function search()
    {
        if (isset($this->params['form']['postcode'])) {
            $data = $this->Post->SearchPostCord($this->params['form']['postcode']);
        }
        
        echo json_encode($data['Post']);
    }
    
    // 住所の候補を出力
    function candidacy()
    {
        parse_str($this->params['form']['params'], $param);
        
        $data = $this->Post->CandidacyPostCord($param);
        
        // コードセット
        $this->set("data", $data);
    }
    
    // ポップアップウインドウの作成
    function popup()
    {
        
        // viewを使わない設定
        $this->autoRender = false;
        
        if (isset($this->params['form']['params']) && isset($this->params['form']['params']['type'])) {
            
            /*
             * 新規にポップアップウィンドウを追加したい場合、処理はここに追加する。
             */
            
            // 顧客追加の場合
            if ($this->params['form']['params']['type'] === 'customer') {
                
                // コードセット
                $this->set("countys", Configure::read('PrefectureCode'));
                
                // Viewの設定
                $this->render('customer');
            }
            
            // 顧客担当者追加の場合
            if ($this->params['form']['params']['type'] === 'add_customer_charge') {
                $this->set("cst_id", $this->params['form']['params']['no']);
                $this->set("countys", Configure::read('PrefectureCode'));
                
                // Viewの設定
                $this->render('add_customer_charge');
            }
            
            // アイテム追加の場合
            if ($this->params['form']['params']['type'] === 'item') {
                // 税区分の初期値をセット
                $company = $this->Company->find();
                $TaxClass = $company['Company']['EXCISE'] + 1;
                $this->set("TaxClass", $TaxClass);
                
                // Viewの設定
                $this->render('item');
            }
            
            // アイテム選択の場合
            if ($this->params['form']['params']['type'] === 'select_item') {
                
                $page = isset($this->params['form']['params']['page']) ? $this->params['form']['params']['page'] : 0;
                $number = 10;
                
                // 権限により取得するデータの制御
                if ($this->Get_User_AUTHORITY() != 1) {
                    $item_condition = array();
                } else {
                    $item_condition = array(
                        'Item.USR_ID' => $this->Get_User_ID()
                    );
                }
                
                // 検索
                if (isset($this->params['form']['params']['keyword']) && $this->params['form']['params']['keyword']) {
                    $q_kana = mb_convert_kana($this->params['form']['params']['keyword'], "C");
                    $q = $this->params['form']['params']['keyword'];
                    
                    $item_condition['or']['Item.ITEM LIKE'] = "%$q%";
                    $item_condition['or']['Item.ITEM_KANA LIKE'] = "%$q_kana%";
                    $item_condition['or']['Item.ITEM_CODE LIKE'] = "%$q%";
                    // $item_condition['or']['Item.TAX_CLASS LIKE'] = "%$q%";
                    
                    $this->data['ITEM_KEYWORD'] = $this->params['form']['params']['keyword'];
                }
                
                // ソート
                $item_order = "Item.LAST_UPDATE DESC";
                if (isset($this->params['form']['params']['sort'])) {
                    if ($this->params['form']['params']['sort'] == 'UNIT_PRICE') {
                        $item_order = "CAST(Item." . $this->params['form']['params']['sort'] . " AS SIGNED) " . ($this->params['form']['params']['desc'] ? "DESC" : "");
                    } else {
                        $sort_ok = false;
                        if($this->params['form']['params']['sort'] == 'ITEM_KANA') {$sort_ok = true;}
                        if($this->params['form']['params']['sort'] == 'TAX_CLASS') {$sort_ok = true;}
                        if($this->params['form']['params']['sort'] == 'ITEM_KEYWORD') {$sort_ok = true;}
                        if($sort_ok){
                            $item_order = "Item." . $this->params['form']['params']['sort'] . " " . ($this->params['form']['params']['desc'] ? "DESC" : "");
                        }
                    }
                    
                    $this->data['sort'] = $this->params['form']['params']['sort'];
                    $this->data['desc'] = $this->params['form']['params']['desc'];
                }
                
                // 表示するデータの取得と最大件数のカウント
                $count = $this->Item->find('count', array(
                    'conditions' => $item_condition
                ));
                $item = $this->Item->find('all', array(
                    'fields' => array(
                        'ITM_ID',
                        'ITEM',
                        'ITEM_CODE',
                        'UNIT',
                        'UNIT_PRICE',
                        'User.NAME',
                        'TAX_CLASS'
                    ),
                    'conditions' => $item_condition,
                    'limit' => ($page * $number) . ', ' . $number,
                    'order' => $item_order
                ));
                
                // ポップアップページング
                $this->Ajax->paging($paging, $nowpage, $number, $count, $page);
                
                // コードセット
                $this->set("excises", Configure::read('ExciseCode'));
                $this->set("taxOperationDate", Configure::read('TaxOperationDate'));
                $this->set("item", $item);
                $this->set("nowpage", $nowpage);
                $this->set("paging", $paging);
                $this->set("params", $item_condition);
                
                // Viewの設定
                $this->render('select_item');
            }
            
            // 宛先用ポップアップの場合
            if ($this->params['form']['params']['type'] === 'to' && isset($this->params['form']['params']['no'])) {
                
                $page = isset($this->params['form']['params']['page']) ? $this->params['form']['params']['page'] : 0;
                $number = 10;
                
                // 権限により取得するデータの制御
                if ($this->Get_User_AUTHORITY() != 1) {
                    $chr_condition = array();
                    $cst_condition = array(
                        'CustomerCharge.CST_ID' => $this->params['form']['params']['no']
                    );
                } else {
                    $chr_condition = array(
                        'Charge.USR_ID' => $this->Get_User_ID()
                    );
                    $cst_condition = array(
                        'CustomerCharge.CST_ID' => $this->params['form']['params']['no'],
                        'CustomerCharge.USR_ID' => $this->Get_User_ID()
                    );
                }
                
                // 表示タイプによって自社担当者か顧客担当者かを切り替える
                $charge = array();
                if (isset($this->params['form']['params']['ctype']) && $this->params['form']['params']['ctype'] == 1) {
                    $count = $this->Charge->find('count', array(
                        'conditions' => $chr_condition
                    ));
                    $result = $this->Charge->find('all', array(
                        'fields' => array(
                            'MAIL',
                            'CHARGE_NAME'
                        ),
                        'conditions' => $chr_condition,
                        'limit' => ($page * $number) . ', ' . $number
                    ));
                    foreach ($result as $key => $value) {
                        $charge[$key]['CHARGE_NAME'] = $value['Charge']['CHARGE_NAME'];
                        $charge[$key]['MAIL'] = $value['Charge']['MAIL'];
                    }
                    $this->data['type'] = 1;
                } else {
                    $count = $this->CustomerCharge->find('count', array(
                        'conditions' => $cst_condition
                    ));
                    $result = $this->CustomerCharge->find('all', array(
                        'fields' => array(
                            'MAIL',
                            'CHARGE_NAME'
                        ),
                        'conditions' => $cst_condition,
                        'limit' => ($page * $number) . ', ' . $number
                    ));
                    foreach ($result as $key => $value) {
                        $charge[$key]['CHARGE_NAME'] = $value['CustomerCharge']['CHARGE_NAME'];
                        $charge[$key]['MAIL'] = $value['CustomerCharge']['MAIL'];
                    }
                    $this->data['type'] = 0;
                }
                
                // ポップアップページング
                $this->Ajax->paging($paging, $nowpage, $number, $count, $page);
                
                // コードセット
                $this->set("charge", $charge);
                $this->set("no", $this->params['form']['params']['no']);
                $this->set("nowpage", $nowpage);
                $this->set("paging", $paging);
                
                // Viewの設定
                $this->render('to');
            }
            
            // 送り主用ポップアップの場合
            if ($this->params['form']['params']['type'] === 'from') {
                
                $page = isset($this->params['form']['params']['page']) ? $this->params['form']['params']['page'] : 0;
                $number = 10;
                
                // 権限により取得するデータの制御
                if ($this->Get_User_AUTHORITY() != 1) {
                    $chr_condition = array();
                } else {
                    $chr_condition = array(
                        'Charge.USR_ID' => $this->Get_User_ID()
                    );
                }
                
                // 表示するデータの取得と最大件数のカウント
                $count = $this->Charge->find('count', array(
                    'conditions' => $chr_condition
                ));
                $charge = $this->Charge->find('all', array(
                    'fields' => array(
                        'MAIL',
                        'CHARGE_NAME'
                    ),
                    'conditions' => $chr_condition,
                    'limit' => ($page * $number) . ', ' . $number
                ));
                
                // ポップアップページング
                $this->Ajax->paging($paging, $nowpage, $number, $count, $page);
                
                // コードセット
                $this->set("charge", $charge);
                $this->set("nowpage", $nowpage);
                $this->set("paging", $paging);
                
                // Viewの設定
                $this->render('from');
            }
            
            // 自社担当者選択ポップアップの場合
            if ($this->params['form']['params']['type'] === 'charge') {
                
                $page = isset($this->params['form']['params']['page']) ? $this->params['form']['params']['page'] : 0;
                $number = 10;
                
                // 権限により取得するデータの制御
                if ($this->Get_User_AUTHORITY() != 1) {
                    $chr_condition = array();
                } else {
                    $chr_condition = array(
                        'Charge.USR_ID' => $this->Get_User_ID()
                    );
                }
                
                // 検索
                if (isset($this->params['form']['params']['keyword'])) {
                    $q_kana = mb_convert_kana($this->params['form']['params']['keyword'], "C");
                    $q = $this->params['form']['params']['keyword'];
                    
                    $chr_condition['or']['Charge.CHARGE_NAME LIKE'] = "%$q%";
                    $chr_condition['or']['Charge.CHARGE_NAME_KANA LIKE'] = "%$q_kana%";
                    
                    $this->data['CHR_KEYWORD'] = $this->params['form']['params']['keyword'];
                }
                
                // ソート
                $chr_order = "Charge.LAST_UPDATE DESC";
                if (isset($this->params['form']['params']['sort'])) {
                    $chr_order = "Charge." . $this->params['form']['params']['sort'] . " " . ($this->params['form']['params']['desc'] ? "DESC" : "");
                    
                    $this->data['sort'] = $this->params['form']['params']['sort'];
                    $this->data['desc'] = $this->params['form']['params']['desc'];
                }
                
                // 表示するデータの取得と最大件数のカウント
                $count = $this->Charge->find('count', array(
                    'conditions' => $chr_condition
                ));
                $charge = $this->Charge->find('all', array(
                    'fields' => array(
                        'CHR_ID',
                        'CHARGE_NAME',
                        'UNIT',
                        'User.NAME',
                        'CHR_SEAL_FLG'
                    ),
                    'conditions' => $chr_condition,
                    'limit' => ($page * $number) . ', ' . $number,
                    'order' => $chr_order
                ));
                
                // ポップアップページング
                $this->Ajax->paging($paging, $nowpage, $number, $count, $page);
                
                // コードセット
                $this->set("charge", $charge);
                $this->set("nowpage", $nowpage);
                $this->set("paging", $paging);
                
                // Viewの設定
                $this->render('charge');
            }
            
            // 顧客選択ポップアップの場合
            if ($this->params['form']['params']['type'] === 'select_customer') {
                
                $page = isset($this->params['form']['params']['page']) ? $this->params['form']['params']['page'] : 0;
                $number = 10;
                
                // 権限により取得するデータの制御
                if ($this->Get_User_AUTHORITY() != 1) {
                    $cst_condition = array();
                } else {
                    $cst_condition = array(
                        'Customer.USR_ID' => $this->Get_User_ID()
                    );
                }
                
                // 検索
                if (isset($this->params['form']['params']['keyword'])) {
                    $q_kana = mb_convert_kana($this->params['form']['params']['keyword'], "C");
                    $q = $this->params['form']['params']['keyword'];
                    
                    $cst_condition['or']['Customer.NAME LIKE'] = "%$q%";
                    $cst_condition['or']['Customer.NAME_KANA LIKE'] = "%$q_kana%";
                    $this->data['CST_KEYWORD'] = $this->params['form']['params']['keyword'];
                }
                
                // ソート
                $cst_order = "Customer.LAST_UPDATE DESC";
                if (isset($this->params['form']['params']['sort'])) {
                    $cst_order = "Customer." . $this->params['form']['params']['sort'] . " " . ($this->params['form']['params']['desc'] ? "DESC" : "");
                    
                    $this->set('sort', $this->params['form']['params']['sort']);
                    $this->set('desc', $this->params['form']['params']['desc']);
                    
                    $this->data['sort'] = $this->params['form']['params']['sort'];
                    $this->data['desc'] = $this->params['form']['params']['desc'];
                }
                
                // 表示するデータの取得と最大件数のカウント
                $count = $this->Customer->find('count', array(
                    'conditions' => $cst_condition
                ));
                $customer = $this->Customer->find('all', array(
                    'fields' => array(
                        'Customer.CST_ID',
                        'Customer.CHR_ID',
                        'Customer.NAME',
                        'Customer.EXCISE',
                        'Customer.HONOR_CODE',
                        'Customer.HONOR_TITLE',
                        'Customer.FRACTION',
                        'Customer.TAX_FRACTION',
                        'Customer.TAX_FRACTION_TIMING',
                    	'Customer.SEARCH_ADDRESS',
                        'Customer.ADDRESS',
                        'Customer.POSTCODE1',
                        'Customer.POSTCODE2',
                        'Customer.CNT_ID',
                        'Customer.BUILDING',
                        'User.NAME'
                    ),
                    'conditions' => $cst_condition,
                    'limit' => ($page * $number) . ', ' . $number,
                    'order' => $cst_order
                ));
                
                for ($i = 0; $i < $number; $i ++) {
                    if (empty($customer[$i])) {
                        break;
                    }
                    $charge[$i] = $this->Charge->find('first', array(
                        'fields' => array(
                            'CHARGE_NAME',
                            'CHR_ID'
                        ),
                        'conditions' => array(
                            'Charge.CHR_ID' => $customer[$i]['Customer']['CHR_ID']
                        )
                    ));
                    if (empty($charge[$i]['Charge']['CHR_ID'])) {
                        break;
                    }
                    $customer[$i]['Charge']['CHARGE_NAME'] = $charge[$i]['Charge']['CHARGE_NAME'];
                    $customer[$i]['Charge']['CHR_ID'] = $charge[$i]['Charge']['CHR_ID'];
                }
                
                // ポップアップページング
                $this->Ajax->paging($paging, $nowpage, $number, $count, $page);
                
                // コードセット
                $this->set("s", $cst_order);
                $this->set("customer", $customer);
                $this->set("nowpage", $nowpage);
                $this->set("paging", $paging);
                $this->set("desc", isset($this->params['form']['params']['desc']) ? $this->params['form']['params']['desc'] : 0);
                
                // Viewの設定
                $this->render('select_customer');
            }
            
            // 顧客担当者選択ポップアップの場合
            if ($this->params['form']['params']['type'] === 'customer_charge') {
                $page = isset($this->params['form']['params']['page']) ? $this->params['form']['params']['page'] : 0;
                $number = 10;
                
                // 権限により取得するデータの制御
                if ($this->Get_User_AUTHORITY() != 1) {
                    $cst_condition = array();
                } else {
                    $cst_condition = array(
                        'CustomerCharge.USR_ID' => $this->Get_User_ID()
                    );
                }
                
                // 顧客で絞込み
                if (isset($this->params['form']['params']['id']) && $this->params['form']['params']['id'] != 'default' && $this->params['form']['params']['id'] != '') {
                    $cst_condition['CustomerCharge.CST_ID'] = $this->params['form']['params']['id'];
                }
                
                // 検索
                if (isset($this->params['form']['params']['keyword'])) {
                    $q_kana = mb_convert_kana($this->params['form']['params']['keyword'], "C");
                    $q = $this->params['form']['params']['keyword'];
                    
                    $cst_condition['or']['CustomerCharge.CHARGE_NAME LIKE'] = "%$q%";
                    $cst_condition['or']['Customer.NAME LIKE'] = "%$q%";
                    $cst_condition['or']['CustomerCharge.CHARGE_NAME_KANA LIKE'] = "%$q_kana%";
                    $cst_condition['or']['Customer.NAME_KANA LIKE'] = "%$q_kana%";
                    
                    $this->data['CHRC_KEYWORD'] = $this->params['form']['params']['keyword'];
                }
                
                // ソート
                $cst_order = "CustomerCharge.LAST_UPDATE DESC";
                if (isset($this->params['form']['params']['sort'])) {
                    $cst_order = "CustomerCharge." . $this->params['form']['params']['sort'] . " " . ($this->params['form']['params']['desc'] ? "DESC" : "");
                    
                    if ($this->params['form']['params']['sort'] == 'CUSTOMER_NAME') {
                        $cst_order = "Customer.NAME_KANA " . ($this->params['form']['params']['desc'] ? "DESC" : "");
                    }
                    
                    $this->data['sort'] = $this->params['form']['params']['sort'];
                    $this->data['desc'] = $this->params['form']['params']['desc'];
                }
                
                // 表示するデータの取得と最大件数のカウント
                $count = $this->CustomerCharge->find('count', array(
                    'conditions' => $cst_condition
                ));
                $customer_charge = $this->CustomerCharge->find('all', array(
                    'fields' => array(
                        'CustomerCharge.CHRC_ID',
                        'CustomerCharge.CST_ID',
                        'CustomerCharge.CHARGE_NAME',
                        'CustomerCharge.USR_ID',
                        'CustomerCharge.UNIT',
                        'User.NAME',
                        'Customer.NAME',
                        'Customer.CST_ID'
                    ),
                    'conditions' => $cst_condition,
                    'limit' => ($page * $number) . ', ' . $number,
                    'order' => $cst_order
                ));
                
                // ポップアップページング
                $this->Ajax->paging($paging, $nowpage, $number, $count, $page);
                
                // コードセット
                $this->set("customer_charge", $customer_charge);
                $this->set("nowpage", $nowpage);
                $this->set("paging", $paging);
                
                // 顧客IDのセット
                $cst_id = $this->params['form']['params']['id'];
                if ($cst_id === 'default') {
                    $cst_id = "undefined";
                }
                $this->set("cst_id", $cst_id);
                
                // Viewの設定
                $this->render('customercharge');
            }
        } else {
            // エラー
            echo 'error';
        }
    }
    
    // ポップアップインサート
    function popupinsert()
    {
        
        // viewを使わない設定
        $this->autoRender = false;
        
        $phone_error = 0;
        
        $user_ID = $this->Get_User_ID();
        
        parse_str($this->params['form']['params'], $param);
        
        // トークンチェック
        if (isset($param['data']['type']) && $param['data']['type']) {
            $this->isCorrectToken($param['data']['Security']['token']);
        }
        
        if ($param['data']['type'] === 'customer') {
            $data['Customer'] = $param['data'];
            $data['Customer']['USR_ID'] = $user_ID;
            $data = array_merge_recursive($data, $this->Customer->get_payment(1));
            
            // 電話番号のバリデーション
            $phone_error = $this->phone_validation($data['Customer']);
            $json = $this->Customer->set_data($data, 1, 'new', $phone_error, 0);
            if ($json) {
                echo json_encode($json);
            }
        } else 
            if ($param['data']['type'] === 'item') {
                $data['Item'] = $param['data'];
                $data['Item']['USR_ID'] = $user_ID;
                if ($json = $this->Item->set_data($data)) {
                    echo json_encode($json);
                }
            } else 
                if ($param['data']['type'] === 'customer_charge') {
                    $data['CustomerCharge'] = $param['data'];
                    $data['CustomerCharge']['USR_ID'] = $user_ID;
                    $cst_id = $data['CustomerCharge']['CST_ID'];
                    
                    // 電話番号のバリデーション
                    $phone_error = $this->phone_validation($data['CustomerCharge'], 'CustomerCharge');
                    $json = $this->CustomerCharge->set_data($data, 'new', $phone_error, 0, $cst_id);
                    $json['pe'] = $phone_error;
                    if ($json) {
                        echo json_encode($json);
                    }
                }
    }
    
    // 自社担当者取得
    function charge()
    {
        // viewを使わない設定
        $this->autoRender = false;
        
        $charge = $this->Charge->find('first', array(
            'fields' => array(
                'MAIL',
                'UNIT',
                'CHARGE_NAME'
            ),
            'conditions' => array(
                'Charge.CHR_ID' => $this->params['pass'][0]
            )
        ));
        
        if (! $charge) {
            echo null;
        }
        
        echo json_encode($charge["Charge"]);
    }
    
    // 顧客担当者取得
    function customer_charge()
    {
        // viewを使わない設定
        $this->autoRender = false;
        
        $c_charge = $this->CustomerCharge->find('first', array(
            'fields' => array(
                'MAIL',
                'UNIT',
                'CHARGE_NAME'
            ),
            'conditions' => array(
                'CustomerCharge.CHRC_ID' => $this->params['pass'][0]
            )
        ));
        
        if (! $c_charge) {
            echo null;
        }
        
        echo json_encode($c_charge["CustomerCharge"]);
    }
    
    // excel 件数取得
    function excel()
    {
        // viewを使わない設定
        $this->autoRender = false;
        
        $param = $this->params['form']['params'];
        
        $date1 = $param['year1'] . "-" . $param['month1'] . "-" . $param['day1'];
        $date2 = $param['year2'] . "-" . $param['month2'] . "-" . $param['day2'];
        
        $_model_Name = "Quote";
        
        $user = $this->Auth->user();
        $_user_id = $user['User']['USR_ID'];
        
        $count = 0;
        
        if (Validation::date($date1, 'ymd', null) && Validation::date($date2, 'ymd', null)) {
            $count = $this->$_model_Name->find('count', array(
                'conditions' => array(
                    $_model_Name . ".ISSUE_DATE >= " => $date1,
                    $_model_Name . ".ISSUE_DATE <= " => $date2
                )
            ));
        }
        
        echo $count . "件";
    }
}