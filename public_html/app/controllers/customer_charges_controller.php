<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */
/**
 * 内容:顧客担当者関連のcontrollerクラス
 */
class CustomerChargesController extends AppController
{

    var $name = 'CustomerCharge';

    var $uses = array(
        'CustomerCharge',
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
        $this->set("main_title", "取引先担当者一覧");
        $this->set("title_text", "顧客管理");
        
        // 削除
        if (isset($this->params['form']['delete_x'])) {
            
            // トークンチェック
            $this->isCorrectToken($this->data['Security']['token']);
            
            // 成功
            if ($this->CustomerCharge->index_delete($this->params['data'])) {
                $this->Session->setFlash('取引先担当者を削除しました');
                $this->redirect("/customer_charges/index");
            }             // 失敗
            else {
                $this->Session->setFlash('取引先担当者を削除できませんでした');
                $this->redirect("/customer_charges/index");
            }
        }
        
        // セット
        $this->set("customer", $this->Customer->select_customer());
        $this->set("page_title", "CustomerCharge");
        $this->set("status", Configure::read('StatusCode'));
        $this->set("countys", Configure::read('PrefectureCode'));
    }
    
    // 確認画面
    function check()
    {
        $this->set("main_title", "取引先担当者確認");
        $this->set("title_text", "顧客管理");
        
        // IDの取得
        if (isset($this->params['pass'][0])) {
            $chargeID = $this->params['pass'][0];
            
            // 顧客担当者情報の取得
            $chargeAry = $this->CustomerCharge->select(array(
                'CHRC_ID' => $chargeID
            ));
            $editauth = $this->Get_Edit_Authority($chargeAry[0]['CustomerCharge']['USR_ID']);
            if (! $this->Get_Check_Authority($chargeAry[0]['CustomerCharge']['USR_ID'])) {
                $this->Session->setFlash('ページを開く権限がありません');
                $this->redirect("/customer_charges");
            }
            
            if (! $chargeAry) {
                $this->redirect("/customer_charges");
            }
            $this->data = $chargeAry[0];
            
            $customer = $this->Customer->get_customer($this->data['CustomerCharge']['CST_ID']);
            
            $this->set("editauth", $editauth);
            $this->set("chrcID", $chargeID);
            $this->set("customer", $customer);
            $this->set("countys", Configure::read('PrefectureCode'));
            $this->set("status", Configure::read('StatusCode'));
        }
    }
    
    // 登録用
    function add()
    {
        $this->set("main_title", "取引先担当者登録");
        $this->set("title_text", "顧客管理");
        
        // キャンセルボタンを押下すると一覧にリダイレクト
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect("/customer_charges");
        }
        
        // テスト用データ
        $companyID = 1;
        $phone_error = 0;
        $fax_error = 0;
        
        if (isset($this->params['data'])) {
            // トークンチェック
            $this->isCorrectToken($this->data['Security']['token']);
            
            // 電話番号のバリデーション
            $phone_error = $this->phone_validation($this->params['data']['CustomerCharge']);
            // FAX番号のバリデーション
            $fax_error = $this->fax_validation($this->params['data']['CustomerCharge']);
            // データのインサート
            $result = $this->CustomerCharge->set_data($this->params['data'], 'new', $phone_error, $fax_error);
            
            if (! isset($result['error'])) {
                // 成功
                $this->Session->setFlash('取引先担当者を保存しました');
                $this->redirect("/customer_charges/check/" . $result['CustomerCharge']['CHRC_ID']);
            }
        }
        
        // ビューにセット
        $this->set("user_id", $this->Get_User_ID());
        $this->set("perror", $phone_error);
        $this->set("ferror", $fax_error);
        $this->set("countys", Configure::read('PrefectureCode'));
        $this->set("status", Configure::read('StatusCode'));
    }
    
    // 編集用
    function edit()
    {
        $this->set("main_title", "取引先担当者編集");
        $this->set("title_text", "顧客管理");
        
        // キャンセルボタンを押下すると一覧にリダイレクト
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect("/customer_charges");
        }
        
        // テスト用データ
        $companyID = 1;
        $phone_error = 0;
        $fax_error = 0;
        
        // 担当者IDの取得
        if (isset($this->params['pass'][0])) {
            $chargeID = $this->params['pass'][0];
        } else {
            // エラー処理
            $this->redirect("/customer_charges");
        }
        
        if (! isset($this->params['data'])) {
            // 顧客担当者情報の取得
            $chargeAry = $this->CustomerCharge->select(array(
                'CHRC_ID' => $chargeID
            ));
            if (! $chargeAry) {
                $this->redirect("/customer_charges");
            }
            $this->data = $chargeAry[0];
        } else {
            // トークンチェック
            $this->isCorrectToken($this->data['Security']['token']);
            
            // 電話番号のバリデーション
            $phone_error = $this->phone_validation($this->params['data']['CustomerCharge']);
            
            // FAX番号のバリデーション
            $fax_error = $this->fax_validation($this->params['data']['CustomerCharge']);
            
            // データのインサート
            // 更新
            $this->params['data']['CustomerCharge']['CHRC_ID'] = $chargeID;
            $setdata = $this->CustomerCharge->set_data($this->params['data'], 'update', $phone_error, $fax_error);
            if (! isset($setdata['error'])) {
                // 成功
                $this->Session->setFlash('取引先担当者を保存しました');
                $this->redirect("/customer_charges/check/" . $chargeID);
            }
        }
        
        if (! $this->Get_Edit_Authority($this->data['CustomerCharge']['USR_ID'])) {
            $this->Session->setFlash('ページを開く権限がありません');
            $this->redirect("/customer_charges/index/");
        }
        
        $customer = $this->Customer->get_customer($this->data['CustomerCharge']['CST_ID']);
        
        // ビューにセット
        $this->set("page_title", "CustomerCharge");
        $this->set("status", Configure::read('StatusCode'));
        $this->set("countys", Configure::read('PrefectureCode'));
        $this->set("customer", $customer);
        $this->set("perror", $phone_error);
        $this->set("ferror", $fax_error);
    }
}
