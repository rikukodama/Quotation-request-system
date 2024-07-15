<?php
const DISCOUNT_TYPE_PERCENT = 0;
const DISCOUNT_TYPE_NONE = 2;
/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 内容:請求書関連のcontrollerクラス
 */
class BillsController extends AppController
{

    var $name = "Bill";

    var $uses = array(
        'Bill',
        "Quote",
        "Delivery",
        "Item",
        "Receipt",
        "CustomerCharge",
        "Mail",
        "Serial",
        "Charge",
        'Customer'
    );

    var $autoLayout = true;

    var $components = array(
        'Excel'
    );

    function beforeFilter()
    {
        parent::beforeFilter();
    }

    function index()
    {
        $this->paginate['Bill']['conditions']['order'] = 'Bill.STATUS DESC';
        $authority = array();
        
        foreach ($this->data['Bill']['data'] as $key => $value) {
            $authority[$value['User']['USR_ID']] = $this->Get_Edit_Authority($value['User']['USR_ID']) ? true : false;
        }
        
        if (isset($this->params['named']['customer'])) {
            $customer = $this->Customer->find('first', array(
                'conditions' => array(
                    'Customer.CST_ID' => $this->params['named']['customer']
                )
            ));
            $this->data[$this->name]['NAME'] = $customer['Customer']['NAME'];
            $this->set("customer_id", $this->params['named']['customer']);
            
                        
            $insArray = array(
                $this->params['controller'] => array(
                    $this->name => array(
                        'NAME' => $this->data[$this->name]['NAME']
                    )
                )
            );
            $this->Session->write('session_params', $insArray);

        }
        
        $this->set("authority", $authority);
        
        $this->set("main_title", "請求書管理");
        $this->set("title_text", "帳票管理");
        
        // セット
        $this->set("mailstatus", Configure::read('MailStatusCode'));
        $this->set("status", Configure::read('IssuedStatCode'));
    }

    function add()
    {
        $this->set("main_title", "請求書登録");
        $this->set("title_text", "帳票管理");
        
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect('/bills');
        }
        //割引設定がない場合は割引の値をクリア
        if ($this->params['data']['Bill']['DISCOUNT_TYPE'] == DISCOUNT_TYPE_NONE) {
            $this->params['data']['Bill']['DISCOUNT']='';
        }
        
        // テスト用データ
        $company_ID = 1;
        
        $error = Configure::read('ItemErrorCode');
        
        $count = 1;
        if (isset($this->params['data']) && $this->isCorrectToken($this->data['Security']['token'])) {
            
            // バリデーション
            $error = $this->item_validation($this->params['data'], 'Billitem');
            
            // 割引のバリデーション
            $error['DISCOUNT'] = $this->Bill->validateDiscount($this->data);

            if ($this->params['data']['Bill']['DISCOUNT_TYPE'] == DISCOUNT_TYPE_PERCENT ) {
                $discount = mb_strlen($this->params['data']['Bill']['DISCOUNT']);
                if ($discount > 2) {
                    $error['DISCOUNT'] = 1;
                }
                if ($this->params['data']['Bill']['DISCOUNT'] == '100') {
                    $error['DISCOUNT'] = 0;
                }
                if (preg_match("/^[0-9]+$/", $this->params['data']['Bill']['DISCOUNT']) == 0 && $this->params['data']['Bill']['DISCOUNT'] != NULL) {
                    $error['DISCOUNT'] = 2;
                }
            }
            
            if ($this->params['data']['Bill']['HONOR_CODE'] != 2) {
                $this->params['data']['Bill']['HONOR_TITLE'] = "";
            }
            
            // データのインサート
            if ($MBL_ID = $this->Bill->set_data($this->params['data'], 'new', $error)) {
                // アクションログ
                $this->History->h_reportaction($this->params['data']['Bill']['USR_ID'], 5, $MBL_ID);
                // 成功
                $this->Session->setFlash('請求書を保存しました');
                
                // 連番をインクリメント
                $this->Serial->serial_increment('Bill');
                
                $this->redirect("/bills/check/" . $MBL_ID);
            } else {
                // 失敗
                $count = count($this->params['data']) - 2 > 1 ? count($this->params['data']) - 2 : 1;
                
                // その他情報に何も入力されていなければ非表示
                if (empty($this->data['Bill']['FEE']) && empty($this->data['Bill']['DUE_DATE'])) {
                    $collaspe['other'] = 1;
                } else {
                    $collaspe['other'] = 0;
                }
                
                // 管理情報に何も入力されていなければ非表示
                if (empty($this->data['Bill']['MEMO'])) {
                    $collaspe['management'] = 1;
                } else {
                    $collaspe['management'] = 0;
                }
            }
        } else {
            // 折りたたみ設定
            $collaspe['management'] = 1;
            $collaspe['other'] = 1;
            
            // 連番設定がされていれば設定
            if ($this->Bill->get_serial($company_ID) == 0) {
                $this->data['Bill']['NO'] = $this->Serial->get_number('Bill');
            }
            
            $this->data['Bill']['CST_ID'] = 'default';
            $this->data['Bill']['item'] = 'default';
            if ($defult_cmp = $this->Bill->get_company_payment($company_ID)) {
                $this->data['Bill']['EXCISE'] = $defult_cmp['EXCISE'];
                $this->data['Bill']['FRACTION'] = $defult_cmp['FRACTION'];
                $this->data['Bill']['TAX_FRACTION'] = $defult_cmp['TAX_FRACTION'];
                $this->data['Bill']['TAX_FRACTION_TIMING'] = $defult_cmp['TAX_FRACTION_TIMING'];
            } else {
                $this->data['Bill']['EXCISE'] = 1;
                $this->data['Bill']['FRACTION'] = 1;
                $this->data['Bill']['TAX_FRACTION'] = 1;
                $this->data['Bill']['TAX_FRACTION_TIMING'] = 0;
            }
            
            if ($default_dec = $this->Bill->get_decimal($company_ID)) {
                $this->data['Bill']['DECIMAL_QUANTITY'] = $default_dec[0]['Company']['DECIMAL_QUANTITY'];
                $this->data['Bill']['DECIMAL_UNITPRICE'] = $default_dec[0]['Company']['DECIMAL_UNITPRICE'];
            } else {
                $this->data['Bill']['DECIMAL_QUANTITY'] = 0;
                $this->data['Bill']['DECIMAL_UNITPRICE'] = 0;
            }
            
            $this->data['Bill']['DISCOUNT_TYPE'] = 2;
            
            $this->data['Bill']['DATE'] = date("Y-m-d");
            
            // 押印設定
            $this->data['Bill']['CMP_SEAL_FLG'] = $this->Company->getSealFlg();
            $this->data['Bill']['CHR_SEAL_FLG'] = 0;
            
            if ($default_honor = $this->Bill->get_honor($company_ID)) {
                $this->data['Bill']['HONOR_CODE'] = $default_honor[0]['Company']['HONOR_CODE'];
                if ($default_honor[0]['Company']['HONOR_CODE'] == 2) {
                    $this->data['Bill']['HONOR_TITLE'] = $default_honor[0]['Company']['HONOR_TITLE'];
                }
            }
            // 年月日で帳票の税区分の初期表示を行っている
            $taxOperationDate = Configure::read('TaxOperationDate');
            foreach ($taxOperationDate as $key => $value) {
            
                if ($this->data['Bill']['DATE'] >= $value['start']) {
            
                    if ($this->data['Bill']['DATE'] <= $value['end']) {
                        if ($key == 8) {
                            $tax_index = $key;
                            $defult_cmp['EXCISE'] = $tax_index . $defult_cmp['EXCISE'];
                        } elseif($key == 5) {
                            $defult_cmp['EXCISE'];
                        }
                    } elseif ($key >= 10) { //税区分コードの10%のendがnullで不確定のため、今後税率が増えてendにnullが入った時の対応のため
                        $tax_index = $key;
                        $defult_cmp['EXCISE'] = $tax_index . $defult_cmp['EXCISE'];
                        break;
                    }
                }
            }
            
            $this->set('defaultExcise', $defult_cmp['EXCISE']);
        }
        
        // 企業アイテム情報の取得
        if ($this->Get_User_Authority() == 1) {
            $cst_condition = array(
                'Customer.CMP_ID' => $company_ID,
                'Customer.USR_ID' => $this->Get_User_ID()
            );
            $item = $this->Item->find('all', array(
                'conditions' => array(
                    'Item.USR_ID' => $this->Get_User_ID()
                )
            ));
        } else {
            $cst_condition = array(
                'Customer.CMP_ID' => $company_ID
            );
            $item = $this->Item->find('all', array());
        }
        $company = $this->Bill->get_customer($company_ID, $cst_condition);
        
        $hidden = $this->Bill->get_payment($company_ID);
        
        if ($defult_cmp = $this->Bill->get_company_payment($company_ID)) {
            $hidden['default']['EXCISE'] = $defult_cmp['EXCISE'];
            $hidden['default']['FRACTION'] = $defult_cmp['FRACTION'];
            $hidden['default']['TAX_FRACTION'] = $defult_cmp['TAX_FRACTION'];
            $hidden['default']['TAX_FRACTION_TIMING'] = $defult_cmp['TAX_FRACTION_TIMING'];
        } else {
            $hidden['default']['EXCISE'] = 1;
            $hidden['default']['FRACTION'] = 1;
            $hidden['default']['TAX_FRACTION'] = 1;
            $hidden['default']['TAX_FRACTION_TIMING'] = 0;
        }
        
        $items['item'] = '＋アイテム追加＋';
        $items['default'] = '＋アイテム選択＋';
        $itemlist = null;
        if ($item) {
            foreach ($item as $key => $value) {
                $items[$value['Item']['ITM_ID']] = $value['Item']['ITEM'];
                $itemlist[$value['Item']['ITM_ID']]['ITEM'] = $value['Item']['ITEM'];
                $itemlist[$value['Item']['ITM_ID']]['UNIT'] = $value['Item']['UNIT'];
                $itemlist[$value['Item']['ITM_ID']]['UNIT_PRICE'] = $value['Item']['UNIT_PRICE'];
            }
        }
        
        // セット
        $this->set("excises", Configure::read('ExciseCode'));
        $this->set("fractions", Configure::read('FractionCode'));
        $this->set("tax_fraction_timing", Configure::read('TaxFractionTimingCode'));
        $this->set("discount", Configure::read('DiscountCode'));
        $this->set("status", Configure::read('IssuedStatCode'));
        $this->set("decimal", Configure::read('DecimalCode'));
        $this->set("itemlist", $itemlist ? json_encode($itemlist) : false);
        $this->set("companys", $company);
        $this->set("error", $error);
        $this->set("dataline", $count);
        $this->set("item", $items);
        $this->set("hidden", $hidden);
        $this->set("honor", Configure::read('HonorCode'));
        $this->set("page_title", "Bill");
        $this->set('collapse_other', $collaspe['other']);
        $this->set('collapse_management', $collaspe['management']);
        $this->set('lineAttribute', Configure::read('LineAttribute'));
        $this->set('taxClass', Configure::read('TaxClass'));
        $this->set('taxRates', Configure::read('TaxRates'));
        $this->set('taxOperationDate', Configure::read('TaxOperationDate'));
        $this->set('seal_flg', Configure::read('SealFlg'));
    }
    
    // 確認
    function check()
    {
        $this->set("main_title", "請求書確認");
        $this->set("title_text", "帳票管理");
        
        // IDの取得
        if (isset($this->params['pass'][0])) {
            $bill_ID = $this->params['pass'][0];
        } else {
            // エラー処理
            $this->Session->setFlash('指定の請求書が存在しません');
            $this->redirect("/bills/index");
        }
        
        // 初期データの取得
        $param = $this->Bill->edit_select($bill_ID, $count);
        
        // 顧客に紐付けられた自社担当者を取得
        $param['Charge']['NAME'] = $this->Charge->get_charge($param['Bill']['CHR_ID']);
        
        if ($customer_charge = $this->CustomerCharge->select(array(
            'CHRC_ID' => $param['Bill']['CHRC_ID']
        ))) {
            $param['CustomerCharge'] = $customer_charge[0]['CustomerCharge'];
        }
        
        // データが取得できない場合
        if (! $param) {
            $this->Session->setFlash('指定の請求書が削除されたか、存在しない可能性があります');
            $this->redirect("/bills/index");
        }
        if (! $this->Get_Check_Authority($param['Bill']['USR_ID'])) {
            $this->Session->setFlash('帳票を閲覧する権限がありません');
            $this->redirect("/bills/");
        }
        
        // バージョン2.3.0追加 、割引の変換
        $param = $this->getCompatibleItems($param);
        $count = $param['count'];
        
        $editauth = $this->Get_Edit_Authority($param['Bill']['USR_ID']);
        
        // セット
        $this->set("status", Configure::read('IssuedStatCode'));
        $this->set("decimals", Configure::read('DecimalCode'));
        $this->set("excises", Configure::read('ExciseCode'));
        $this->set("fractions", Configure::read('FractionCode'));
        $this->set("tax_fraction_timing", Configure::read('TaxFractionTimingCode'));
        $this->set("honor", Configure::read('HonorCode'));
        $this->set("param", $param);
        $this->set("dataline", $count);
        $this->set("editauth", $editauth);
        $this->set('seal_flg', Configure::read('SealFlg'));
    }
    
    // 編集
    function edit()
    {
        $this->set("main_title", "請求書編集");
        $this->set("title_text", "帳票管理");
        
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect('/bills');
        }
        
        $user_ID = $this->Get_User_ID();
        $user_auth = $this->Get_User_Authority();
        
        // テスト用データ
        $company_ID = 1;
        
        $error = Configure::read('ItemErrorCode');
        
        $count = 1;
        
        if (! isset($this->params['data'])) {
            
            // 折りたたみ設定
            $collaspe['management'] = 1;
            $collaspe['other'] = 1;
            
            // IDの取得
            if (isset($this->params['pass'][0])) {
                $bill_ID = $this->params['pass'][0];
            } else {
                // エラー処理
                $this->Session->setFlash('指定の請求書が存在しません');
                $this->redirect("/bills/index");
            }
            
            // 初期データの取得
            $this->data = $this->Bill->edit_select($bill_ID, $count);
            
            // バージョン2.3.0追加 、割引の変換
            $this->data = $this->getCompatibleItems($this->data);
            $count = $this->data['count'];
            
            // データが取得できない場合
            if (! $this->data) {
                $this->Session->setFlash('指定の請求書が削除されたか、存在しない可能性があります');
                $this->redirect("/bills/index");
            }
            
            if ($customer_charge = $this->CustomerCharge->select(array(
                'CHRC_ID' => $this->data['Bill']['CHRC_ID']
            ))) {
                $this->data['Bill']['CUSTOMER_CHARGE_NAME'] = $customer_charge[0]['CustomerCharge']['CHARGE_NAME'];
                $this->data['Bill']['CUSTOMER_CHARGE_UNIT'] = $customer_charge[0]['CustomerCharge']['UNIT'];
            }
            $this->data['Bill']['item'] = 'default';
            
            if (! $this->Get_Edit_Authority($this->data['Bill']['USR_ID'])) {
                $this->Session->setFlash('帳票を編集する権限がありません');
                $this->redirect("/bills/");
            }
        } else {
            // トークンチェック
            $this->isCorrectToken($this->data['Security']['token']);
            
            if (isset($this->params['form']['del_x']) && $this->params['form']['del_x']) {
                $this->Bill->delete($this->data['Bill']['MBL_ID']);
                $this->Session->setFlash('削除しました。');
                $this->redirect(array(
                    'controller' => 'bills',
                    'action' => 'index'
                ));
            }
            
            $user = $this->Auth->user();
            // バリデーション
            $error = $this->item_validation($this->params['data'], 'Billitem');
            
            // 割引のバリデーション
            $error['DISCOUNT'] = $this->Bill->validateDiscount($this->data);
            
            if ($this->params['data']['Bill']['DISCOUNT_TYPE'] == DISCOUNT_TYPE_PERCENT ) {
                $discount = mb_strlen($this->params['data']['Bill']['DISCOUNT']);
                
                if ($discount > 2) {
                    $error['DISCOUNT'] = 1;
                }
                if ($this->params['data']['Bill']['DISCOUNT'] == '100') {
                    $error['DISCOUNT'] = 0;
                }
                if (preg_match("/^[0-9]+$/", $this->params['data']['Bill']['DISCOUNT']) == 0 && $this->params['data']['Bill']['DISCOUNT'] != NULL) {
                    $error['DISCOUNT'] = 2;
                }
            }
            if ($this->params['data']['Bill']['HONOR_CODE'] != 2) {
                $this->params['data']['Bill']['HONOR_TITLE'] = "";
            }
            // データのインサート
            if ($MBL_ID = $this->Bill->set_data($this->params['data'], 'update', $error)) {
                // アクションログ
                $this->History->h_reportaction($user['User']['USR_ID'], 6, $this->params['data']['Bill']['MBL_ID']);
                
                // 成功
                $this->Session->setFlash('請求書を保存しました');
                $this->redirect("/bills/check/" . $MBL_ID);
            } else {
                // 失敗
                $count = count($this->params['data']) - 2 > 1 ? count($this->params['data']) - 2 : 1;
                
                // その他情報に何も入力されていなければ非表示
                if (empty($this->data['Bill']['FEE']) && empty($this->data['Bill']['DUE_DATE'])) {
                    $collaspe['other'] = 1;
                } else {
                    $collaspe['other'] = 0;
                }
                
                // 管理情報に何も入力されていなければ非表示
                if (empty($this->data['Bill']['MEMO'])) {
                    $collaspe['management'] = 1;
                } else {
                    $collaspe['management'] = 0;
                }
            }
        }
        
        // 企業アイテム情報の取得
        if ($this->Get_User_Authority() == 1) {
            $cst_condition = array(
                'CMP_ID' => $company_ID,
                'USR_ID' => $this->Get_User_ID()
            );
            $item = $this->Item->find('all', array(
                'conditions' => array(
                    'Item.USR_ID' => $this->Get_User_ID()
                )
            ));
        } else {
            $cst_condition = array(
                'CMP_ID' => $company_ID
            );
            $item = $this->Item->find('all', array());
        }
        
        $hidden = $this->Bill->get_payment($company_ID);
        if ($defult_cmp = $this->Bill->get_company_payment($company_ID)) {
            $hidden['default']['EXCISE'] = $defult_cmp['EXCISE'];
            $hidden['default']['FRACTION'] = $defult_cmp['FRACTION'];
            $hidden['default']['TAX_FRACTION'] = $defult_cmp['TAX_FRACTION'];
            $hidden['default']['TAX_FRACTION_TIMING'] = $defult_cmp['TAX_FRACTION_TIMING'];
        } else {
            $hidden['default']['EXCISE'] = 1;
            $hidden['default']['FRACTION'] = 1;
            $hidden['default']['TAX_FRACTION'] = 1;
            $hidden['default']['TAX_FRACTION_TIMING'] = 0;
        }
        if (! isset($this->data['Bill']['DECIMAL_QUANTITY']) && ! isset($this->data['Bill']['DECIMAL_UNITPRICE'])) {
            if ($default_dec = $this->Bill->get_decimal($company_ID)) {
                $this->data['Bill']['DECIMAL_QUANTITY'] = $default_dec[0]['Company']['DECIMAL_QUANTITY'];
                $this->data['Bill']['DECIMAL_UNITPRICE'] = $default_dec[0]['Company']['DECIMAL_UNITPRICE'];
            } else {
                $this->data['Bill']['DECIMAL_QUANTITY'] = 0;
                $this->data['Bill']['DECIMAL_UNITPRICE'] = 0;
            }
        }
        
        $items['item'] = '＋アイテム追加＋';
        $items['default'] = '＋アイテム選択＋';
        $itemlist = null;
        if ($item) {
            foreach ($item as $key => $value) {
                $items[$value['Item']['ITM_ID']] = $value['Item']['ITEM'];
                $itemlist[$value['Item']['ITM_ID']]['ITEM'] = $value['Item']['ITEM'];
                $itemlist[$value['Item']['ITM_ID']]['UNIT'] = $value['Item']['UNIT'];
                $itemlist[$value['Item']['ITM_ID']]['UNIT_PRICE'] = $value['Item']['UNIT_PRICE'];
            }
        }
        
        if (isset($this->data['Customer']['NAME'])) {
            $this->data['Bill']['CUSTOMER_NAME'] = $this->data['Customer']['NAME'];
            $this->data['Bill']['CHARGE_NAME'] = $this->Charge->get_charge($this->data['Bill']['CHR_ID']);
        }
        
        // セット
        $this->set("excises", Configure::read('ExciseCode'));
        $this->set("fractions", Configure::read('FractionCode'));
        $this->set("tax_fraction_timing", Configure::read('TaxFractionTimingCode'));
        $this->set("discount", Configure::read('DiscountCode'));
        $this->set("decimal", Configure::read('DecimalCode'));
        $this->set("status", Configure::read('IssuedStatCode'));
        $this->set("itemlist", $itemlist ? json_encode($itemlist) : false);
        $this->set("error", $error);
        $this->set("dataline", $count);
        $this->set("item", $items);
        $this->set("hidden", $hidden);
        $this->set("honor", Configure::read('HonorCode'));
        $this->set('collapse_other', $collaspe['other']);
        $this->set('collapse_management', $collaspe['management']);
        $this->set('lineAttribute', Configure::read('LineAttribute'));
        $this->set('seal_flg', Configure::read('SealFlg'));
        $this->set('taxClass', Configure::read('TaxClass'));
        $this->set('taxRates', Configure::read('TaxRates'));
        $this->set('taxOperationDate', Configure::read('TaxOperationDate'));
    }

    function action()
    {
        // 絞り込みした場合の顧客IDを取得
        if (isset($this->data['Customer']['id'])) {
            $customer_id = $this->data['Customer']['id'];
        }
        
        if (isset($this->data['Action']['type'])) {
            $this->params['form']['reproduce_' . $this->data['Action']['type'] . '_x'] = 1;
            $form_check = true; // 詳細から転記したかどうか
        } else {
            $form_check = false;
        }
        
        // トークンチェック
        $this->isCorrectToken($this->data['Security']['token']);
        
        $user_ID = $this->Get_User_ID();
        
        if (isset($this->params['form']['delete_x'])) {
            if (empty($this->params['data']['Bill'])) {
                $this->Session->setFlash('請求書が選択されていません');
                $this->redirect(array(
                    'controller' => 'bills',
                    'action' => 'index',
                    'customer' => $customer_id
                ));
            }
            
            // 削除
            foreach ($this->params['data']['Bill'] as $key => $val) {
                
                if ($val == 1) {
                    $id = $this->Bill->find('first', array(
                        'conditions' => array(
                            'Bill.MBL_ID' => $key
                        ),
                        'fields' => array(
                            'Bill.USR_ID'
                        )
                    ));
                    if (! $this->Get_Edit_Authority($id['Bill']['USR_ID'])) {
                        $this->Session->setFlash('削除できない請求書が含まれていました');
                        $this->redirect(array(
                            'controller' => 'bills',
                            'action' => 'index',
                            'customer' => $customer_id
                        ));
                    }
                }
            }
            
            if ($this->Bill->index_delete($this->params['data'])) {
                // アクションログ
                $user = $this->Auth->user();
                foreach ($this->params['data']['Bill'] as $key => $value) {
                    if ($value == 1) {
                        $this->History->h_reportaction($user['User']['USR_ID'], 7, $key);
                    }
                }
                // 成功
                $this->Session->setFlash('請求書を削除しました');
                $this->redirect(array(
                    'controller' => 'bills',
                    'action' => 'index',
                    'costomer' => $customer_id
                ));
            } else {
                // 失敗
                $this->redirect(array(
                    'controller' => 'bills',
                    'action' => 'index',
                    'costomer' => $customer_id
                ));
            }
        }        

        // 見積書へ複製
        elseif (isset($this->params['form']['reproduce_quote_x'])) {
            if ($result = $this->Bill->reproduce_check($this->params['data'], false)) {
                // 成功
                if ($this->Quote->insert_reproduce($result, $user_ID)) {
                    $this->Session->setFlash('見積書に転記しました');
                    $this->redirect(array(
                        'controller' => 'quotes',
                        'action' => 'index',
                        'customer' => $customer_id
                    ));
                }                 // 失敗
                else {
                    $this->redirect(array(
                        'controller' => 'bills',
                        'action' => 'index',
                        'customer' => $customer_id
                    ));
                }
            } else {
                // 失敗
                $this->redirect(array(
                    'controller' => 'bills',
                    'action' => 'index',
                    'customer' => $customer_id
                ));
            }
        }        

        // 請求書へ複製
        elseif (isset($this->params['form']['reproduce_bill_x'])) {
            
            if ($result = $this->Bill->reproduce_check($this->params['data'], $this->Serial->getSerialConf(), 'Bill')) {
                // 成功
                if ($inv_id = $this->Bill->insert_reproduce($result, $user_ID)) {
                    $this->Session->setFlash('請求書に転記しました');
                    if (isset($form_check) && $form_check) {
                        $this->redirect("/bills/edit/$inv_id");
                    } else {
                        $this->redirect(array(
                            'controller' => 'bills',
                            'action' => 'index',
                            'customer' => $customer_id
                        ));
                    }
                }                 // 失敗
                else {
                    $this->redirect(array(
                        'controller' => 'bills',
                        'action' => 'index',
                        'customer' => $customer_id
                    ));
                }
            } else {
                // 失敗
                $this->redirect(array(
                    'controller' => 'bills',
                    'action' => 'index',
                    'customer' => $customer_id
                ));
            }
        }        

        // 納品書へ複製
        elseif (isset($this->params['form']['reproduce_delivery_x'])) {
            if ($result = $this->Bill->reproduce_check($this->params['data'], false)) {
                // 成功
                if ($this->Delivery->insert_reproduce($result, $user_ID)) {
                    $this->Session->setFlash('納品書に転記しました');
                    $this->redirect(array(
                        'controller' => 'deliveries',
                        'action' => 'index',
                        'customer' => $customer_id
                    ));
                }                 // 失敗
                else {
                    $this->redirect(array(
                        'controller' => 'bills',
                        'action' => 'index',
                        'customer' => $customer_id
                    ));
                }
            } else {
                // 失敗
                $this->redirect(array(
                    'controller' => 'bills',
                    'action' => 'index',
                    'customer' => $customer_id
                ));
            }
        }
        // 発行ステータス一括変更
        elseif (isset($this->params['form']['status_change_x'])){
        	$redirect_uri = array(
        			'controller' => 'bills',
        			'action' => 'index',
        			'customer' => $customer_id
        	);
        	return $this->status_change($this->params['data']['Bill'], $redirect_uri);
        }
        
    }
    
    // excel形式の一覧を抽出します
    function export()
    {
        // ブラウザの識別
        $browser = getenv("HTTP_USER_AGENT");
        if (isset($this->params['form']['download_x'])) {
            if ($this->params['data']['Bill']) {
                $error = "";
                $data = $this->Bill->export($this->params['data']['Bill'], $error, 'term', $this->Get_User_AUTHORITY(), $this->Get_User_ID());
                if ($data) {
                    $str = mb_convert_encoding("請求書", "SJIS-win", "UTF-8");
                    if (preg_match("/MSIE/", $browser) || preg_match('/Trident\/[0-9]\.[0-9]/', $browser)) {
                        $this->Excel->outputXls($this, $this->Bill->field, $data, $str);
                    } else {
                        $this->Excel->outputXls($this, $this->Bill->field, $data, "請求書");
                    }
                } else {
                    $this->Session->setFlash($error);
                    $this->redirect("/bills/export");
                }
            }
        }
        
        $this->set("main_title", "請求書Excel出力");
        $this->set("title_text", "帳票管理");
    }
    
    // pdf
    function pdf()
    {
        
        // デザイン無効
        $this->autoLayout = false;
        
        // 見積書IDの取得
        $bill_ID = null;
        if (isset($this->params['pass'][0])) {
            $bill_ID = $this->params['pass'][0];
        }
        
        if (! $bill_ID) {
            $this->cakeError('error404', array(
                array(
                    'url' => '/'
                )
            ));
        }
        
        $items = 0;
        $discounts = 0;
        
        // 記載項目
        if (! $param = $this->Bill->preview_data($bill_ID, $items, $discounts)) {
            $this->cakeError('error404', array(
                array(
                    'url' => '/'
                )
            ));
        }
        
        if (! $this->Get_Check_Authority($param['Bill']['USR_ID'])) {
            $this->Session->setFlash('帳票を閲覧する権限がありません');
            $this->redirect("/bills/");
        }
        $Color = Configure::read('ColorCode');
        if ($customer_charge = $this->CustomerCharge->select(array(
            'CHRC_ID' => $param['Bill']['CHRC_ID']
        ))) {
            $param['CustomerCharge'] = $customer_charge[0]['CustomerCharge'];
        }
        // $param['Company']['COLOR'] = $Color[$param['Company']['COLOR']]['code'];
        
        // 社版URLのセット
        if ($param['Company']['SEAL']) {
            $param['Company']['SEAL_IMAGE'] = $this->getTmpImagePath(null, true);
        }
        
        // 社員版URLのセット
        if ($param['Bill']['CHR_ID'] && $param['Charge']['SEAL']) {
            $param['Charge']['SEAL_IMAGE'] = $this->getTmpImagePath();
        }
        
        // バージョン2.3.0追加 、割引の変換
        $param = $this->getCompatibleItems($param);
        $item_count = $param['count'];
        
        $direction = $param['Company']['DIRECTION'];
        
        $pages = 1;
        // $item_count = $items+$discounts;
        
        // 都道府県情報取得
        $county = Configure::read('PrefectureCode');
        $accounttype = Configure::read('AccountTypeCode');
        
        // ページ数計測
        for ($i = 0, $item_count_per_page = 0; $i < $item_count; $i++) {
            $fbreak = isset($param[$i]['Billitem']['LINE_ATTRIBUTE']) 
                && intval($param[$i]['Billitem']['LINE_ATTRIBUTE']) == 8;
            
            if ($direction == 0) {
                // 縦
                if ($pages == 1) {
                    if ($fbreak) {
                        $pages++;
                        $item_count_per_page = 0;
                    } else if ($item_count_per_page >= 20) {
                        $pages++;
                        $item_count_per_page = -(10-1); // 前のページの10個からカウントを始める
                    } else {
                        $item_count_per_page++;
                    }
                } else {
                    if ($fbreak) {
                        $pages++;
                        $item_count_per_page = 0;
                    } else if ($item_count_per_page >= 30) {
                        $pages++;
                        $item_count_per_page = -(10-1); // 前のページの10個からカウントを始める
                    } else {
                        $item_count_per_page++;
                    }
                }
            } else {
                // 横
                if ($pages == 1) {
                    if ($fbreak) {
                        $pages++;
                        $item_count_per_page = 0;
                    } else if ($item_count_per_page >= 14) {
                        $pages++;
                        $item_count_per_page = -(6-1); // 前のページの6個からカウントを始める
                    } else {
                        $item_count_per_page++;
                    }
                } else {
                    if ($fbreak) {
                        $pages++;
                        $item_count_per_page = 0;
                    } else if ($item_count_per_page >= 24) {
                        $pages++;
                        $item_count_per_page = -(6-1); // 前のページの6個からカウントを始める
                    } else {
                        $item_count_per_page++;
                    }
                }
            }
        }
        
        // ブラウザの識別
        $browser = getenv("HTTP_USER_AGENT");
        
        // インスタンス化
        
        // ページの作成
        if ($direction == 1) {
            App::import('Vendor', 'pdf/billpdf_side');
            $pdf = new BILLPDF_SIDE();
        } else {
            App::import('Vendor', 'pdf/billpdf');
            $pdf = new BILLPDF();
        }
        
        $pdf->AddMBFont(MINCHO, 'SJIS');
        $pdf->Total_Page = $pages;
        $pdf->Direction = $direction;
        
        // 送付状もダウンロードする場合
        if (isset($this->params['pass'][1]) && $this->params['pass'][1] === 'download_with_coverpage') {
            $pdf->cover = 1;
            $pdf->AddPage();
            $pdf->coverpage($param, $county, 'Bill');
            $pdf->Total_Page = $pages + 1;
        }
        
        // ページの作成
        if ($direction == 1) {
            $pdf->AddPage('L');
        } else {
            $pdf->AddPage();
        }
        
        $pdf->cover = 0;
        // 本文用情報付加
        $pdf->main($param, $county, $accounttype, $direction, $items, $pages);
        
        if (isset($this->params['pass'][1]) && $this->params['pass'][1] === 'download') {
            // アウトプット
            $str = mb_convert_encoding("請求書_{$param['Bill']['SUBJECT']}.pdf", "SJIS-win", "UTF-8");
            if (ereg("MSIE", $browser) || ereg("Trident", $browser)) {
                $str = strip_tags($str);
                $pdf->Output($str, 'D');
            } else {
                $pdf->Output("請求書_{$param['Bill']['SUBJECT']}.pdf", 'D');
            }
        } elseif (isset($this->params['pass'][1]) && $this->params['pass'][1] === 'download_with_coverpage') {
            // アウトプット
            $str = mb_convert_encoding("送付状_{$param['Bill']['SUBJECT']}.pdf", "SJIS-win", "UTF-8");
            
            if (ereg("MSIE", $browser) || ereg("Trident", $browser)) {
                $str = strip_tags($str);
                $pdf->Output($str, 'D');
            } else {
                $pdf->Output("送付状_{$param['Bill']['SUBJECT']}.pdf", 'D');
            }
        } else {
            // アウトプット
            $param['Bill']['SUBJECT'] = "aa";
            $str = mb_convert_encoding("請求書_{$param['Bill']['SUBJECT']}.pdf", "SJIS-win", "UTF-8");
            
            if (ereg("MSIE", $browser) || ereg("Trident", $browser)) {
                $str = strip_tags($str);
                $pdf->Output($str, 'I');
            } else {
                $pdf->Output('請求書.pdf', 'I');
            }
        }
    }
    // 領収書
    function receipt()
    {
        $this->set("main_title", "領収書発行");
        $this->set("title_text", "帳票管理");
        
        // テスト用データ
        $company_ID = 1;
        $errors = 0;
        
        // 戻るボタンを押された場合
        if (isset($this->params['form']['cancel_x']) && $this->params['form']['cancel_x']) {
            $this->redirect(array(
                'controller' => 'bills',
                'action' => 'index'
            ));
        }
        
        if (isset($this->params['form']) && $this->params['form']) {
            // バリデーション
            $this->Receipt->set($this->params['data']['Bill']);
            $errors = $this->Receipt->invalidFields();
            
            if ($this->Receipt->validates()) {
                // デザイン無効
                $this->autoLayout = false;
                // 見積書IDの取得
                $bill_ID = null;
                if (isset($this->params['pass'][0])) {
                    $bill_ID = $this->params['pass'][0];
                }
                // 記載項目
                $param = $this->Bill->preview_data($bill_ID);
                
                // 領収書番号等の追加
                $param = array_merge($param, $this->data);
                
                $Color = Configure::read('ColorCode');
                
                // $param['Company']['COLOR'] = $Color[$param['Company']['COLOR']]['code'];
                
                // 都道府県情報取得
                $county = Configure::read('PrefectureCode');
                
                // ブラウザの識別
                $browser = getenv("HTTP_USER_AGENT");
                
                App::import('Vendor', 'pdf/receiptpdf');
                
                // インスタンス化
                $pdf = new RECEIPTPDF();
                
                $pdf->AddMBFont(MINCHO, 'SJIS');
                
                // ページの作成
                $pdf->AddPage();
                
                // 本文用情報付加
                $pdf->main($param, $county);
                
                // 連番をインクリメント
                $this->Serial->serial_increment('Receipt');
                
                $str = mb_convert_encoding("領収書_.pdf", "SJIS-win", "UTF-8");
                if (ereg("MSIE", $browser) || ereg("Trident", $browser)) {
                    $pdf->Output($str, 'D');
                } else {
                    // アウトプット
                    $pdf->Output('領収書.pdf', 'D');
                }
            }
        }
        
        if (! isset($this->params['data'])) {
            // IDの取得
            if (isset($this->params['pass'][0])) {
                $bill_ID = $this->params['pass'][0];
            } else {
                // エラー処理
                $this->redirect("/bills/index");
            }
            // 初期データの取得
            $this->data = $this->Bill->preview_data($bill_ID);
            // 企業情報の取得
            $company = $this->Bill->get_customer($company_ID);
            $company = $company[$this->data['Bill']['CST_ID']];
        } else {
            $company = $this->data['Bill']['CST_ID'];
        }
        
        $bill_ID = $this->data['Bill']['MBL_ID'];
        $this->data = array_merge($this->Bill->preview_data($bill_ID), $this->data);
        
        if (isset($this->data['Bill']['STATUS']) && $this->data['Bill']['STATUS'] != 1) {
            $this->Session->setFlash('領収書を作成できません');
            $this->redirect("/bills/");
        }
        if (! $this->Get_Edit_Authority($this->data['Bill']['USR_ID'])) {
            $this->Session->setFlash('帳票を閲覧する権限がありません');
            $this->redirect("/bills/");
        }
        
        // 端数処理
        if ($this->data['Customer']['FRACTION'] == 0) {
            $this->data['Customer']['TOTAL'] = ceil($this->data['Bill']['TOTAL']);
        }
        if ($this->data['Customer']['FRACTION'] == 1) {
            $this->data['Customer']['TOTAL'] = floor($this->data['Bill']['TOTAL']);
        }
        if ($this->data['Customer']['FRACTION'] == 2) {
            $this->data['Customer']['TOTAL'] = round($this->data['Bill']['TOTAL']);
        }
        $this->set("error", $errors);
        $this->set("companys", $company);
        
        // 連番取得
        if ($this->Bill->get_serial($company_ID) == 0) {
            $this->data['Bill']['RECEIPT_NUMBER'] = $this->Serial->get_number('Receipt');
        }
    }
}