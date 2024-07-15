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
 * 内容:見積書関連のcontrollerクラス
 */
class QuotesController extends AppController
{

    var $name = "Quote";

    var $uses = array(
        "Quote",
        "Bill",
        "Delivery",
        "Item",
        "Mail",
        "CustomerCharge",
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
    
    // 一覧用
    function index()
    {
        if (isset($this->params['named']['customer'])) {

            $customer = $this->Customer->find('first', array(
                'conditions' => array(
                    'Customer.CST_ID' => $this->params['named']['customer']
                )
            ));
            $this->data[$this->name]['NAME'] = $customer['Customer']['NAME'];
            $this->set('customer_id', $this->params['named']['customer']);

            $insArray = array(
                $this->params['controller'] => array(
                    $this->name => array(
                        'NAME' => $this->data[$this->name]['NAME']
                    )
                )
            );
            $this->Session->write('session_params', $insArray);
        }
        
        $this->set("main_title", "見積書管理");
        $this->set("title_text", "帳票管理");
        $this->set("mailstatus", Configure::read('MailStatusCode'));
        $this->set("status", Configure::read('IssuedStatCode'));
    }
    
    // 登録用
    function add()
    {
        $this->set("main_title", "見積書登録");
        $this->set("title_text", "帳票管理");
        
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect('/quotes');
        }
        
        //割引設定がない場合は割引の値をクリア
        if ($this->params['data']['Quote']['DISCOUNT_TYPE'] == DISCOUNT_TYPE_NONE) {
            $this->params['data']['Quote']['DISCOUNT']='';
        }
        
        $company_ID = 1;
        
        $error = Configure::read('ItemErrorCode');
        
        $count = 1;
        
        if (isset($this->params['data'])) {
            // トークンチェック
            $this->isCorrectToken($this->data['Security']['token']);
            
            // バリデーション
            $error = $this->item_validation($this->params['data'], 'Quoteitem');
            
            // 割引のバリデーション
            $error['DISCOUNT'] = $this->Quote->validateDiscount($this->data);

            
            if ($this->params['data']['Quote']['DISCOUNT_TYPE'] == DISCOUNT_TYPE_PERCENT) {
                $discount = mb_strlen($this->params['data']['Quote']['DISCOUNT']);
                if ($discount > 2) {
                    $error['DISCOUNT'] = 1;
                }
                if ($this->params['data']['Quote']['DISCOUNT'] == '100') {
                    $error['DISCOUNT'] = 0;
                }
                if (preg_match("/^[0-9]+$/", $this->params['data']['Quote']['DISCOUNT']) == 0 && $this->params['data']['Quote']['DISCOUNT'] != NULL) {
                    $error['DISCOUNT'] = 2;
                }
            }
            
            if ($this->params['data']['Quote']['HONOR_CODE'] != 2) {
                $this->params['data']['Quote']['HONOR_TITLE'] = "";
            }
            
            // データのインサート
            if ($MQT_ID = $this->Quote->set_data($this->params['data'], 'new', $error)) {
                // アクションログ
                $this->History->h_reportaction($this->params['data']['Quote']['USR_ID'], 2, $MQT_ID);
                // 成功
                $this->Session->setFlash('見積書を保存しました');
                
                // 連番をインクリメント
                $this->Serial->serial_increment('Quote');
                
                $this->redirect("/quotes/check/" . $MQT_ID);
            } else {
                // 失敗
                $count = count($this->params['data']) - 2 > 1 ? count($this->params['data']) - 2 : 1;
                
                // その他情報に何も入力されていなければ非表示
                if (empty($this->data['Quote']['DEADLINE']) && empty($this->data['Quote']['DEAL']) && empty($this->data['Quote']['DELIVERY']) && empty($this->data['Quote']['DUE_DATE'])) {
                    $collaspe['other'] = 1;
                } else {
                    $collaspe['other'] = 0;
                }
                
                // 管理情報に何も入力されていなければ非表示
                if (empty($this->data['Quote']['MEMO'])) {
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
            if ($this->Quote->get_serial($company_ID) == 0) {
                $this->data['Quote']['NO'] = $this->Serial->get_number('Quote');
            }
            
            $this->data['Quote']['CST_ID'] = 'default';
            $this->data['Quote']['item'] = 'default';
            if ($defult_cmp = $this->Quote->get_company_payment($company_ID)) {
                $this->data['Quote']['EXCISE'] = $defult_cmp['EXCISE'];
                $this->data['Quote']['FRACTION'] = $defult_cmp['FRACTION'];
                $this->data['Quote']['TAX_FRACTION'] = $defult_cmp['TAX_FRACTION'];
                $this->data['Quote']['TAX_FRACTION_TIMING'] = $defult_cmp['TAX_FRACTION_TIMING'];
            } else {
                $this->data['Quote']['EXCISE'] = 1;
                $this->data['Quote']['FRACTION'] = 1;
                $this->data['Quote']['TAX_FRACTION'] = 1;
                $this->data['Quote']['TAX_FRACTION_TIMING'] = 0;
            }
            
            if ($default_dec = $this->Quote->get_decimal($company_ID)) {
                $this->data['Quote']['DECIMAL_QUANTITY'] = $default_dec[0]['Company']['DECIMAL_QUANTITY'];
                $this->data['Quote']['DECIMAL_UNITPRICE'] = $default_dec[0]['Company']['DECIMAL_UNITPRICE'];
            } else {
                $this->data['Quote']['DECIMAL_QUANTITY'] = 0;
                $this->data['Quote']['DECIMAL_UNITPRICE'] = 0;
            }
            
            $this->data['Quote']['DISCOUNT_TYPE'] = 2;
            
            $this->data['Quote']['DATE'] = date("Y-m-d");
            
            // 押印設定
            $this->data['Quote']['CMP_SEAL_FLG'] = $this->Company->getSealFlg();
            $this->data['Quote']['CHR_SEAL_FLG'] = 0;
            
            if ($default_honor = $this->Quote->get_honor($company_ID)) {
                $this->data['Quote']['HONOR_CODE'] = $default_honor[0]['Company']['HONOR_CODE'];
                if ($default_honor[0]['Company']['HONOR_CODE'] == 2) {
                    $this->data['Quote']['HONOR_TITLE'] = $default_honor[0]['Company']['HONOR_TITLE'];
                }
            }
         // 年月日で帳票の税区分の初期表示を行っている
            $taxOperationDate = Configure::read('TaxOperationDate');
            foreach ($taxOperationDate as $key => $value) {
            
                if ($this->data['Quote']['DATE'] >= $value['start']) {
            
                    if ($this->data['Quote']['DATE'] <= $value['end']) {
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
        
        // 企業情報の取得
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
        $company = $this->Quote->get_customer($company_ID, $cst_condition);
        
        $hidden = $this->Quote->get_payment($company_ID);
        if ($defult_cmp = $this->Quote->get_company_payment($company_ID)) {
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
                $itemlist[$value['Item']['ITM_ID']]['ITEM_CODE'] = $value['Item']['ITEM_CODE'];
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
        $this->set("error", $error);
        $this->set("dataline", $count);
        $this->set("item", $items);
        $this->set("companys", $company);
        $this->set("honor", Configure::read('HonorCode'));
        $this->set("hidden", $hidden);
        $this->set('collapse_other', $collaspe['other']);
        $this->set('collapse_management', $collaspe['management']);
        $this->set('lineAttribute', Configure::read('LineAttribute'));
        $this->set('taxClass', Configure::read('TaxClass'));
        $this->set('taxRates', Configure::read('TaxRates'));
        $this->set('taxOperationDate', Configure::read('TaxOperationDate'));
        $this->set('seal_flg', Configure::read('SealFlg'));
    }
    
    // 確認用
    function check()
    {
        $this->set("main_title", "見積書確認");
        $this->set("title_text", "帳票管理");
        
        // IDの取得
        if (isset($this->params['pass'][0])) {
            $quote_ID = $this->params['pass'][0];
        } else {
            // エラー処理
            $this->Session->setFlash('指定の見積書が存在しません');
            $this->redirect("/quotes/index");
        }
        
        // 初期データの取得
        $param = $this->Quote->edit_select($quote_ID, $count);
        
        // 顧客に紐付けられた自社担当者を取得
        $param['Charge']['NAME'] = $this->Charge->get_charge($param['Quote']['CHR_ID']);
        
        // IDが取得できない場合
        if (! $param) {
            $this->Session->setFlash('指定の見積書が削除されたか、存在しない可能性があります');
            $this->redirect("/quotes/index");
        }
        
        if (! $this->Get_Check_Authority($param['Quote']['USR_ID'])) {
            $this->Session->setFlash('帳票を閲覧する権限がありません');
            $this->redirect("/quotes/");
        }
        
        // バージョン2.3.0追加 、割引の変換
        $param = $this->getCompatibleItems($param);
        $count = $param['count'];
        
        if ($customer_charge = $this->CustomerCharge->select(array(
            'CHRC_ID' => $param['Quote']['CHRC_ID']
        ))) {
            $param['CustomerCharge'] = $customer_charge[0]['CustomerCharge'];
        }
        
        $editauth = $this->Get_Edit_Authority($param['Quote']['USR_ID']);
        
        // セット
        $this->set("decimals", Configure::read('DecimalCode'));
        $this->set("excises", Configure::read('ExciseCode'));
        $this->set("fractions", Configure::read('FractionCode'));
        $this->set("tax_fraction_timing", Configure::read('TaxFractionTimingCode'));
        $this->set("status", Configure::read('IssuedStatCode'));
        $this->set("editauth", $editauth);
        $this->set("param", $param);
        $this->set("dataline", $count);
        $this->set("honor", Configure::read('HonorCode'));
        $this->set('seal_flg', Configure::read('SealFlg'));
    }
    
    // 編集用
    function edit()
    {
        $this->set("main_title", "見積書編集");
        $this->set("title_text", "帳票管理");
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect('/quotes');
        }
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
                $quote_ID = $this->params['pass'][0];
            } else {
                // エラー処理
                $this->Session->setFlash('指定の見積書が存在しません');
                $this->redirect("/quotes/check");
            }
            
            // 初期データの取得
            $this->data = $this->Quote->edit_select($quote_ID, $count);
            
            // バージョン2.3.0追加 、割引の変換
            $this->data = $this->getCompatibleItems($this->data);
            $count = $this->data['count'];
            
            // データが取得できない場合
            if (! $this->data) {
                $this->Session->setFlash('指定の見積書が削除されたか、存在しない可能性があります');
                $this->redirect("/quotes/index");
            }
            $this->data['Quote']['item'] = 'default';
            
            if ($customer_charge = $this->CustomerCharge->select(array(
                'CHRC_ID' => $this->data['Quote']['CHRC_ID']
            ))) {
                $this->data['Quote']['CUSTOMER_CHARGE_NAME'] = $customer_charge[0]['CustomerCharge']['CHARGE_NAME'];
                $this->data['Quote']['CUSTOMER_CHARGE_UNIT'] = $customer_charge[0]['CustomerCharge']['UNIT'];
            }
            
            if (! $this->Get_Edit_Authority($this->data['Quote']['USR_ID'])) {
                $this->Session->setFlash('帳票を編集する権限がありません');
                $this->redirect("/quotes/");
            }
        } else {
            
            // トークンチェック
            $this->isCorrectToken($this->data['Security']['token']);
            
            if (isset($this->params['form']['del_x']) && $this->params['form']['del_x']) {
                $this->Quote->delete($this->data['Quote']['MQT_ID']);
                $this->Session->setFlash('削除しました。');
                $this->redirect(array(
                    'controller' => 'quotes',
                    'action' => 'index'
                ));
            }
            
            $user = $this->Auth->user();
            
            // バリデーション
            $error = $this->item_validation($this->params['data'], 'Quoteitem');
            // 割引のバリデーション
            $error['DISCOUNT'] = $this->Quote->validateDiscount($this->data);
            if ($this->params['data']['Quote']['DISCOUNT_TYPE'] == DISCOUNT_TYPE_PERCENT) {
                $discount = mb_strlen($this->params['data']['Quote']['DISCOUNT']);
                if ($discount > 2) {
                    $error['DISCOUNT'] = 1;
                }
                if ($this->params['data']['Quote']['DISCOUNT'] == '100') {
                    $error['DISCOUNT'] = 0;
                }
                if (preg_match("/^[0-9]+$/", $this->params['data']['Quote']['DISCOUNT']) == 0 && $this->params['data']['Quote']['DISCOUNT'] != NULL) {
                    $error['DISCOUNT'] = 2;
                }
            }
            
            if ($this->params['data']['Quote']['HONOR_CODE'] != 2) {
                $this->params['data']['Quote']['HONOR_TITLE'] = "";
            }
            
            // データのインサート
            if ($MQT_ID = $this->Quote->set_data($this->params['data'], 'update', $error)) {
                // アクションログ
                $this->History->h_reportaction($user['User']['USR_ID'], 3, $this->params['data']['Quote']['MQT_ID']);
                // 成功
                $this->Session->setFlash('見積書を保存しました');
                $this->redirect("/quotes/check/" . $MQT_ID);
            } else {
                // 失敗
                $count = count($this->params['data']) - 2 > 1 ? count($this->params['data']) - 2 : 1;
                
                // その他情報に何も入力されていなければ非表示
                if (empty($this->data['Quote']['DEADLINE']) && empty($this->data['Quote']['DEAL']) && empty($this->data['Quote']['DELIVERY']) && empty($this->data['Quote']['DUE_DATE'])) {
                    $collaspe['other'] = 1;
                } else {
                    $collaspe['other'] = 0;
                }
                
                // 管理情報に何も入力されていなければ非表示
                if (empty($this->data['Quote']['MEMO'])) {
                    $collaspe['management'] = 1;
                } else {
                    $collaspe['management'] = 0;
                }
            }
        }
        
        // 企業情報の取得
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
        
        $hidden = $this->Quote->get_payment($company_ID);
        if ($defult_cmp = $this->Quote->get_company_payment($company_ID)) {
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
        
        if (! isset($this->data['Quote']['DECIMAL_QUANTITY']) && ! isset($this->data['Quote']['DECIMAL_UNITPRICE'])) {
            if ($default_dec = $this->Quote->get_decimal($company_ID)) {
                $this->data['Quote']['DECIMAL_QUANTITY'] = $default_dec[0]['Company']['DECIMAL_QUANTITY'];
                $this->data['Quote']['DECIMAL_UNITPRICE'] = $default_dec[0]['Company']['DECIMAL_UNITPRICE'];
            } else {
                $this->data['Quote']['DECIMAL_QUANTITY'] = 0;
                $this->data['Quote']['DECIMAL_UNITPRICE'] = 0;
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
            $this->data['Quote']['CUSTOMER_NAME'] = $this->data['Customer']['NAME'];
            $this->data['Quote']['CHARGE_NAME'] = $this->Charge->get_charge($this->data['Quote']['CHR_ID']);
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
        $this->set("dataline", $count - 0);
        $this->set("item", $items);
        $this->set("honor", Configure::read('HonorCode'));
        $this->set("hidden", $hidden);
        $this->set('collapse_other', $collaspe['other']);
        $this->set('collapse_management', $collaspe['management']);
        $this->set('lineAttribute', Configure::read('LineAttribute'));
        $this->set('taxClass', Configure::read('TaxClass'));
        $this->set('taxRates', Configure::read('TaxRates'));
        $this->set('taxOperationDate', Configure::read('TaxOperationDate'));
        $this->set('seal_flg', Configure::read('SealFlg'));
    }
    
    // 削除・複製用
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
            if (empty($this->params['data']['Quote'])) {
                $this->Session->setFlash('見積書が選択されていません');
                $this->redirect(array(
                    'controller' => 'quotes',
                    'action' => 'index',
                    'customer' => $customer_id
                ));
            }
            
            // 削除
            foreach ($this->params['data']['Quote'] as $key => $val) {
                if ($val == 1) {
                    $id = $this->Quote->find('first', array(
                        'conditions' => array(
                            'Quote.MQT_ID' => $key
                        ),
                        'fields' => array(
                            'Quote.USR_ID'
                        )
                    ));
                    if (! $this->Get_Edit_Authority($id['Quote']['USR_ID'])) {
                        $this->Session->setFlash('削除できない見積書が含まれていました');
                        $this->redirect(array(
                            'controller' => 'quotes',
                            'action' => 'index',
                            'customer' => $customer_id
                        ));
                    }
                }
            }
            
            if ($this->Quote->index_delete($this->params['data'])) {
                // アクションログ
                $user = $this->Auth->user();
                foreach ($this->params['data']['Quote'] as $key => $value) {
                    if ($value == 1) {
                        $this->History->h_reportaction($user['User']['USR_ID'], 4, $key);
                    }
                }
                // 成功
                $this->Session->setFlash('見積書を削除しました');
                $this->redirect(array(
                    'controller' => 'quotes',
                    'action' => 'index',
                    'customer' => $customer_id
                ));
            } else {
                // 失敗
                $this->redirect(array(
                    'controller' => 'quotes',
                    'action' => 'index',
                    'customer' => $customer_id
                ));
            }
        }        

        // 見積書へ複製
        elseif (isset($this->params['form']['reproduce_quote_x'])) {
            if ($result = $this->Quote->reproduce_check($this->params['data'], $this->Serial->getSerialConf(), 'Quote')) {
                
                if ($inv_id = $this->Quote->insert_reproduce($result, $user_ID)) {
                    $this->Session->setFlash('見積書に転記しました');
                    if (isset($form_check) && $form_check) {
                        $this->redirect("/quotes/edit/$inv_id");
                    } else {
                        $this->redirect(array(
                            'controller' => "quotes",
                            'action' => 'index',
                            'customer' => $customer_id
                        ));
                    }
                }                 // 失敗
                else {
                    $this->redirect(array(
                        'controller' => "quotes",
                        'action' => 'index',
                        'customer' => $customer_id
                    ));
                }
            } else {
                // 失敗
                $this->redirect(array(
                    'controller' => "quotes",
                    'action' => 'index',
                    'customer' => $customer_id
                ));
            }
        }        

        // 請求書へ複製
        elseif (isset($this->params['form']['reproduce_bill_x'])) {
            if ($result = $this->Quote->reproduce_check($this->params['data'], false)) {
                
                // 成功
                if ($this->Bill->insert_reproduce($result, $user_ID)) {
                    $this->Session->setFlash('請求書に転記しました');
                    $this->redirect(array(
                        'controller' => 'bills',
                        'action' => 'index',
                        'customer' => $customer_id
                    ));
                }                 // 失敗
                else {
                    $this->redirect(array(
                        'controller' => "quotes",
                        'action' => "index",
                        'customer' => $customer_id
                    ));
                }
            } else {
                // 失敗
                $this->redirect(array(
                    'controller' => "quotes",
                    'action' => 'index',
                    'customer' => $customer_id
                ));
            }
        }        

        // 納品書へ複製
        elseif (isset($this->params['form']['reproduce_delivery_x'])) {
            if ($result = $this->Quote->reproduce_check($this->params['data'], false)) {
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
                        'controller' => "quotes",
                        'action' => 'index',
                        'customer' => $customer_id
                    ));
                }
            } else {
                // 失敗
                $this->redirect(array(
                    'controller' => "quotes",
                    'action' => 'index',
                    'customer' => $customer_id
                ));
            }
        }
        
        
        // 発行ステータス一括変更
        elseif (isset($this->params['form']['status_change_x'])){
        	$redirect_uri = array(
        			'controller' => 'quotes',
        			'action' => 'index',
        			'customer' => $customer_id
        	);
        	return $this->status_change($this->params['data']['Quote'], $redirect_uri);
        }
        
        
        
        
    }
    
    // excel形式の一覧を抽出用
    function export()
    {
        // ブラウザの識別
        $browser = getenv("HTTP_USER_AGENT");
        
        if (isset($this->params['form']['download_x'])) {
            if ($this->params['data']['Quote']) {
                $error = "";
                $data = $this->Quote->export($this->params['data']['Quote'], $error, 'term', $this->Get_User_AUTHORITY(), $this->Get_User_ID());
                if ($data) {
                    $str = mb_convert_encoding("見積書", "SJIS-win", "UTF-8");
                    if (preg_match("/MSIE/", $browser) || preg_match('/Trident\/[0-9]\.[0-9]/', $browser)) {
                        $this->Excel->outputXls($this, $this->Quote->field, $data, $str);
                    } else {
                        $this->Excel->outputXls($this, $this->Quote->field, $data, "見積書");
                    }
                } else {
                    $this->Session->setFlash($error);
                    $this->redirect("/quotes/export");
                }
            }
        }
        
        $this->set("main_title", "見積書Excel出力");
        $this->set("title_text", "帳票管理");
    }
    
    // pdf用
    function pdf()
    {
        
        // デザイン無効
        $this->autoLayout = false;
        
        // 見積書IDの取得
        $quote_ID = null;
        if (isset($this->params['pass'][0])) {
            $quote_ID = $this->params['pass'][0];
        }
        
        if (! $quote_ID) {
            $this->cakeError('error404', array(
                array(
                    'url' => '/'
                )
            ));
        }
        
        $items = 0;
        $discounts = 0;
        
        // 記載項目
        if (! $param = $this->Quote->preview_data($quote_ID, $items, $discounts)) {
            $this->cakeError('error404', array(
                array(
                    'url' => '/'
                )
            ));
        }
        
        if (! $this->Get_Check_Authority($param['Quote']['USR_ID'])) {
            $this->Session->setFlash('帳票を閲覧する権限がありません');
            $this->redirect("/quotes/");
        }
        
        if ($customer_charge = $this->CustomerCharge->select(array(
            'CHRC_ID' => $param['Quote']['CHRC_ID']
        ))) {
            $param['CustomerCharge'] = $customer_charge[0]['CustomerCharge'];
        }
        $Color = Configure::read('ColorCode');
        
        // $param['Company']['COLOR'] = $Color[$param['Company']['COLOR']]['code'];
        
        // 社版URLのセット
        if ($param['Company']['SEAL']) {
            $param['Company']['SEAL_IMAGE'] = $this->getTmpImagePath(null, true);
        }
        
        // 社員版URLのセット
        if ($param['Quote']['CHR_ID'] && $param['Charge']['SEAL']) {
            $param['Charge']['SEAL_IMAGE'] = $this->getTmpImagePath();
        }
        
        // バージョン2.3.0追加 、割引の変換
        $param = $this->getCompatibleItems($param);
        $item_count = $param['count'];
        
        // 都道府県情報取得
        $county = Configure::read('PrefectureCode');
        
        // ブラウザの識別
        $browser = getenv("HTTP_USER_AGENT");
        
        $pages = 1;
        
        // $item_count = $items + $discounts;
        
        // 方向の指定
        $direction = $param['Company']['DIRECTION'];
        
        // ページ数計測
        for ($i = 0, $item_count_per_page = 0; $i < $item_count; $i++) {
            $fbreak = isset($param[$i]['Quoteitem']['LINE_ATTRIBUTE']) 
                && intval($param[$i]['Quoteitem']['LINE_ATTRIBUTE']) == 8;
            
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
        
        // インスタンス化
        if ($direction == 1) {
            App::import('Vendor', 'pdf/quotepdf_side');
            $pdf = new QUOTEPDF_SIDE();
        } else {
            App::import('Vendor', 'pdf/quotepdf');
            $pdf = new QUOTEPDF();
        }
        $pdf->AddMBFont(MINCHO, 'SJIS');
        $pdf->Total_Page = $pages;
        $pdf->Direction = $direction;
        
        // 送付状もダウンロードする場合
        if (isset($this->params['pass'][1]) && $this->params['pass'][1] === 'download_with_coverpage') {
            $pdf->cover = 1;
            $pdf->AddPage();
            $pdf->coverpage($param, $county, 'Quote');
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
        $pdf->main($param, $county, $direction, $items, $pages);
        
        if (isset($this->params['pass'][1]) && $this->params['pass'][1] === 'download') {
            // ダウンロード
            $str = mb_convert_encoding("見積書_{$param['Quote']['SUBJECT']}.pdf", "SJIS-win", "UTF-8");
            if (ereg("MSIE", $browser) || ereg("Trident", $browser)) {
                $str = strip_tags($str);
                $pdf->Output($str, 'D');
            } else {
                $pdf->Output("見積書_{$param['Quote']['SUBJECT']}.pdf", 'D');
            }
        } elseif (isset($this->params['pass'][1]) && $this->params['pass'][1] === 'download_with_coverpage') {
            // 送付状とともにダウンロード
            $str = mb_convert_encoding("送付状_{$param['Quote']['SUBJECT']}.pdf", "SJIS-win", "UTF-8");
            if (ereg("MSIE", $browser) || ereg("Trident", $browser)) {
                $str = strip_tags($str);
                $pdf->Output($str, 'D');
            } else {
                $pdf->Output("見積書_{$param['Quote']['SUBJECT']}.pdf", 'D');
            }
        } else {
            // アウトプット
            $str = mb_convert_encoding("見積書_{$param['Quote']['SUBJECT']}.pdf", "SJIS-win", "UTF-8");
            if (ereg("MSIE", $browser) || ereg("Trident", $browser)) {
                $str = strip_tags($str);
                $pdf->Output($str, 'I');
            } else {
                $pdf->Output("見積書_{$param['Quote']['SUBJECT']}.pdf", 'I');
            }
        }
    }
    /*
    function moveback(){
        $this->Session->write('read_session_params',true);
        $this->redirect(array(
            'controller' => "quotes",
            'action' => 'index',
        ));        
    }
    */
}