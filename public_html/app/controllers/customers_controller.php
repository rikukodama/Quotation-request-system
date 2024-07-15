<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 内容:顧客管理登録・編集のcontrollerクラス
 */
class CustomersController extends AppController
{

    var $name = 'Customer';

    var $uses = array(
        'Customer'
    );

    var $autoLayout = true;

    var $helpers = array(
        'Html',
        'Form',
        'Ajax',
        'Javascript'
    );
    
    // 一覧用
    function index()
    {
        $this->set("main_title", "取引先一覧");
        $this->set("title_text", "顧客管理");
        
        $company_ID = 1;
        
        // 削除
        if (isset($this->params['form']['delete_x'])) {
            // トークンチェック
            $this->isCorrectToken($this->data['Security']['token']);
            
            $error = array();
            
            // 成功
            if ($this->Customer->index_delete($this->params['data'], $error)) {
                $this->Session->setFlash('取引先を削除しました');
                $this->redirect("/customers");
            }             // 失敗
            else {
                $this->Session->setFlash('取引先の削除に失敗しました');
                $this->redirect("/customers");
            }
        } else {
            $condition = array();
            $charge = $this->Customer->select_charge($company_ID, $condition);
            
            // セット
            $this->set("page_title", "Customer");
            $this->set("charges", $charge);
            $this->set("countys", Configure::read('PrefectureCode'));
        }
    }
    
    // 一覧用
    function select()
    {
        $this->set("main_title", "顧客から絞り込み");
        $this->set("title_text", "帳票管理");
        $this->set("inv_num", $this->Customer->getInvoiceNum());
        
        $searchColumns = $this->params['url'];
        unset($searchColumns['url']);

        if (!empty($searchColumns)) {
            $searchColumnAry = array(
                'NAME' => array( // OR 検索
                    'Customer.NAME',
                    'Customer.NAME_KANA'
                ),
                'CST_ID' => 'Customer.CST_ID'
            );
            $this->Common->paginate($searchColumnAry);
        } else {
            $this->Common->paginate();
        }
        
        $company_ID = 1;
        
        $condition = array();
        $charge = $this->Customer->select_charge($company_ID, $condition);
        
        // セット
        $this->set("page_title", "Customer");
        $this->set("charges", $charge);
        $this->set("countys", Configure::read('PrefectureCode'));
    }

    function check()
    {
        $this->set("main_title", "取引先確認");
        $this->set("title_text", "顧客管理");
        
        $company_ID = 1;
        
        // IDの取得
        if (isset($this->params['pass'][0])) {
            $customer_ID = $this->params['pass'][0];
            $this->data = $this->Customer->find('first', array(
                'conditions' => array(
                    'CST_ID' => $customer_ID
                )
            ));
        }
        
        $editauth = $this->Get_Edit_Authority($this->data['Customer']['USR_ID']);
        if (! $this->Get_Check_Authority($this->data['Customer']['USR_ID'])) {
            $this->Session->setFlash('ページを開く権限がありません');
            $this->redirect("/customers");
        }
        
        $charge = $this->Customer->get_charge($this->data['Customer']['CHR_ID']);
        
        $this->set("editauth", $editauth);
        $this->set("cstID", $customer_ID);
        $this->set("payment", Configure::Read('PaymentMonth'));
        $this->set("cutooff_select", array(
            0 => '末日',
            1 => '指定'
        ));
        $this->set("payment_select", array(
            0 => '末日',
            1 => '指定'
        ));
        $this->set("countys", Configure::read('PrefectureCode'));
        $this->set("charge", $charge);
        $this->set("excises", Configure::read('ExciseCode'));
        $this->set("fractions", Configure::read('FractionCode'));
        $this->set("tax_fraction_timing", Configure::read('TaxFractionTimingCode'));        
        $this->set("honor", Configure::read('HonorCode'));
    }
    
    // 登録用
    function add()
    {
        $this->set("main_title", "取引先登録");
        $this->set("title_text", "顧客管理");
        
        // キャンセルボタンを押下すると一覧にリダイレクト
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect("/customers");
        }
        
        // テスト用データ
        $company_ID = 1;
        $phone_error = 0;
        $fax_error = 0;
        
        if (isset($this->params['form']['submit_x'])) {
            // トークンチェック
            $this->isCorrectToken($this->data['Security']['token']);
            
            // 電話番号のバリデーション
            $phone_error = $this->phone_validation($this->params['data']['Customer']);
            // FAX番号のバリデーション
            $fax_error = $this->fax_validation($this->params['data']['Customer']);
            
            // 敬称がその他の場合
            if ($this->params['data']['Customer']['HONOR_CODE'] != 2) {
                $this->params['data']['Customer']['HONOR_TITLE'] = "";
            }
            // データのインサート
            $setdata = $this->Customer->set_data($this->params['data'], $company_ID, 'new', $phone_error, $fax_error);
            if (! isset($setdata['error'])) {
                // 成功
                $customer_ID = $setdata['Customer']['CST_ID'];
                $this->Session->setFlash('取引先を保存しました');
                $this->redirect("/customers/check/" . $customer_ID);
            }
        } else {
            // 自社情報の取得
            $this->data = $this->Customer->get_payment($company_ID);
            if ($default_honor = $this->Customer->get_honor($company_ID)) {
                $this->data['Customer']['HONOR_CODE'] = $default_honor[0]['Company']['HONOR_CODE'];
                if ($default_honor[0]['Company']['HONOR_CODE'] == 2) {
                    $this->data['Customer']['HONOR_TITLE'] = $default_honor[0]['Company']['HONOR_TITLE'];
                }
            }
        }
        
        // 消費税
        $excise = array(
            'type' => 'radio',
            'options' => Configure::read('ExciseCode'),
            'div' => false,
            'label' => false,
            'legend' => false,
            'style' => 'width:30px;'
        );
        
        // 端数処理
        $fraction = array(
            'type' => 'radio',
            'options' => Configure::read('FractionCode'),
            'div' => false,
            'label' => false,
            'legend' => false,
            'style' => 'width:30px;'
        );
        // 消費税端数計算
        $tax_fraction_timing = array(
        		'type' => 'radio',
        		'options' => Configure::read('TaxFractionTimingCode'),
        		'div' => false,
        		'label' => false,
        		'legend' => false,
        		'style' => 'margin-right: 10px; margin-left: 8px;',
        		'class' => 'txt_mid'
        );
        // 締日処理
        $cutooff_select = array(
            'type' => 'radio',
            'options' => array(
                0 => '末日',
                1 => '指定'
            ),
            'div' => false,
            'label' => false,
            'legend' => false,
            'class' => 'cutooff_select',
            'style' => 'width:30px;'
        );
        
        // 締日処理
        $payment_select = array(
            'type' => 'radio',
            'options' => array(
                0 => '末日',
                1 => '指定'
            ),
            'div' => false,
            'label' => false,
            'legend' => false,
            'class' => 'payment_select',
            'style' => 'width:30px;'
        );
        
        // ビューにセット
        $this->set("user_id", $this->Get_User_ID());
        $this->set("countys", Configure::read('PrefectureCode'));
        $this->set("payment", Configure::Read('PaymentMonth'));
        $this->set("cutooff_select", $cutooff_select);
        $this->set("payment_select", $payment_select);
        $this->set("perror", $phone_error);
        $this->set("ferror", $fax_error);
        $this->set("excises", $excise);
        $this->set("fractions", $fraction);
        $this->set("tax_fraction_timing", $tax_fraction_timing);
        $this->set("honor", Configure::read('HonorCode'));
    }
    
    // 編集用
    function edit()
    {
        $this->set("main_title", "取引先編集");
        $this->set("title_text", "顧客管理");
        
        // キャンセルボタンを押下すると一覧にリダイレクト
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect("/customers");
        }
        
        // テスト用データ
        $company_ID = 1;
        $phone_error = 0;
        $fax_error = 0;
        
        if (! isset($this->params['data'])) {
            // IDの取得
            if (isset($this->params['pass'][0])) {
                $customer_ID = $this->params['pass'][0];
            } else {
                // エラー処理
                $this->redirect("/customers");
            }
            
            // 顧客情報の取得
            if (! $this->data = $this->Customer->edit_select($customer_ID)) {
                // エラー処理
                $this->redirect("/customers");
            }
        } else {
            // 電話番号のバリデーション
            $phone_error = $this->phone_validation($this->params['data']['Customer']);
            
            // FAX番号のバリデーション
            $fax_error = $this->fax_validation($this->params['data']['Customer']);
            if ($this->params['data']['Customer']['HONOR_CODE'] != 2) {
                $this->params['data']['Customer']['HONOR_TITLE'] = "";
            }
            
            // 更新
            $customer_ID = $this->params['data']['Customer']['CST_ID'];
            $setdata = $this->Customer->set_data($this->params['data'], $company_ID, 'update', $phone_error, $fax_error);
            
            if (! isset($setdata['error'])) {
                // 成功
                $this->Session->setFlash('取引先を保存しました');
                $this->redirect("/customers/check/" . $customer_ID);
            }
        }
        
        if (! $this->Get_Edit_Authority($this->data['Customer']['USR_ID'])) {
            $this->Session->setFlash('ページを開く権限がありません');
            $this->redirect("/customers/index/");
        }
        
        // 消費税
        $excise = array(
            'type' => 'radio',
            'options' => Configure::read('ExciseCode'),
            'div' => false,
            'label' => false,
            'legend' => false,
            'style' => 'width:30px;',
            'class' => 'txt_mid'
        );
        
        // 端数処理
        $fraction = array(
            'type' => 'radio',
            'options' => Configure::read('FractionCode'),
            'div' => false,
            'label' => false,
            'legend' => false,
            'style' => 'width:30px;',
            'class' => 'txt_mid'
        );
        // 消費税端数計算単位
        $tax_fraction_timing = array(
        		'type' => 'radio',
        		'options' => Configure::read('TaxFractionTimingCode'),
        		'div' => false,
        		'label' => false,
        		'legend' => false,
        		'style' => 'margin-right: 10px; margin-left: 8px;',
        		'class' => 'txt_mid'
        );        
        // 締日処理
        $cutooff_select = array(
            'type' => 'radio',
            'options' => array(
                0 => '末日',
                1 => '指定'
            ),
            'div' => false,
            'label' => false,
            'legend' => false,
            'style' => 'width:30px;',
            'class' => 'txt_mid'
        );
        
        // 締日処理
        $payment_select = array(
            'type' => 'radio',
            'options' => array(
                0 => '末日',
                1 => '指定'
            ),
            'div' => false,
            'label' => false,
            'legend' => false,
            'style' => 'width:30px;',
            'class' => 'txt_mid'
        );
        
        $charge = $this->Customer->get_charge($this->data['Customer']['CHR_ID']);
        
        // ビューにセット
        $this->set("payment", Configure::Read('PaymentMonth'));
        $this->set("countys", Configure::read('PrefectureCode'));
        $this->set("charge", $charge);
        $this->set("cutooff_select", $cutooff_select);
        $this->set("payment_select", $payment_select);
        $this->set("perror", $phone_error);
        $this->set("ferror", $fax_error);
        $this->set("excises", $excise);
        $this->set("fractions", $fraction);
        $this->set("tax_fraction_timing", $tax_fraction_timing);
        $this->set("honor", Configure::read('HonorCode'));
    }
}