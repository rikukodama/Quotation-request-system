<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 内容:商品登録・編集のcontrollerクラス
 */
class ItemsController extends AppController
{

    var $name = "Item";

    var $uses = array(
        "Item",
        "Company"
    );

    var $autoLayout = true;

    var $helpers = array(
        'Html',
        'Form',
        'Ajax',
        'Javascript'
    );

    var $components = array(
        'Auth'
    );
    
    // 一覧用
    function index()
    {
        
        $list = 5;
        
        $this->set("excises", Configure::read('ExciseCode'));
        $this->set("main_title", "商品管理");
        $this->set("title_text", "自社情報設定");
        $this->set("page_title", "Company");
    }
    
    // 登録用
    function add()
    {
        $this->set("main_title", "商品登録");
        $this->set("title_text", "自社情報設定");
        
        // キャンセルボタンを押された場合、一覧にリダイレクト
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect("/items");
        }
        
        if (isset($this->params['form']['submit_x'])) {
            // トークンチェック
            $this->isCorrectToken($this->data['Security']['token']);
            
            // データのインサート
            $setdata = $this->Item->set_data($this->params['data']);
            if (! $setdata['error']) {
                // 成功
                $this->Session->setFlash('商品を保存しました');
                $this->redirect("/items/check/" . $setdata['Item']['ITM_ID']);
            }
        }
        
        $user = $this->Auth->user();
        $company = $this->Company->find();
        $this->data['Item']['TAX_CLASS'] = $company['Company']['EXCISE'] + 1;
        
        $ExciseCode = Configure::read('ExciseCode');
        
        // ビューにセット
        $this->set("excises", $ExciseCode);
        $this->set("page_title", "Company");
    }
    
    // 編集用
    function check()
    {
        $this->set("main_title", "商品確認");
        $this->set("title_text", "自社情報設定");
        
        if (! isset($this->params['data'])) {
            
            // IDの取得
            if (isset($this->params['pass'][0])) {
                $item_ID = $this->params['pass'][0];
            } else {
                // エラー処理
                $this->redirect("/items");
            }
            // 初期データの取得
            if (! $this->data = $this->Item->edit_select($item_ID)) {
                $this->redirect("/items");
            }
        }
        
        $editauth = $this->Get_Edit_Authority($this->data['Item']['USR_ID']);
        if (! $this->Get_Check_Authority($this->data['Item']['USR_ID'])) {
            $this->Session->setFlash('ページを開く権限がありません');
            $this->redirect("/items");
        }
        
        $this->set("excises", Configure::read('ExciseCode'));
        $this->set("editauth", $editauth);
        $this->set("params", $this->data);
    }
    
    // 編集用
    function edit()
    {
        $this->set("main_title", "商品編集");
        $this->set("title_text", "自社情報設定");
        
        // キャンセルボタンを押された場合、一覧にリダイレクト
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect("/items");
        }
        
        if (! isset($this->params['data'])) {
            
            // IDの取得
            if (isset($this->params['pass'][0])) {
                $item_ID = $this->params['pass'][0];
            } else {
                // エラー処理
                $this->redirect("/items");
            }
            
            // 初期データの取得
            if (! $this->data = $this->Item->edit_select($item_ID)) {
                $this->redirect("/items");
            }
            if (! $this->Get_Edit_Authority($this->data['Item']['USR_ID'])) {
                $this->Session->setFlash('ページを開く権限がありません');
                $this->redirect("/items/index/");
            }
        } else {
            // トークンチェック
            $this->isCorrectToken($this->data['Security']['token']);
            
            // データのインサート
            $setdata = $this->Item->set_data($this->params['data']);
            if (! $setdata['error']) {
                // 成功
                $this->Session->setFlash('商品を保存しました');
                $this->redirect("/items/check/" . $setdata['Item']['ITM_ID']);
            }
        }
        $ExciseCode = Configure::read('ExciseCode');
        
        // ビューにセット
        $this->set("excises", $ExciseCode);
    }
    
    // 削除用
    function delete()
    {
        
        // トークンチェック
        $this->isCorrectToken($this->data['Security']['token']);
        
        // 削除
        if ($this->Item->index_delete($this->params['data'])) {
            // 成功
            $this->Session->setFlash('商品を削除しました');
            $this->redirect("/items/index");
        } else {
            // 失敗
            $this->redirect("/items/index");
            $this->Session->setFlash('商品が削除できませんでした');
        }
    }
}