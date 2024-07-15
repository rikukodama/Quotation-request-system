<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 内容:ユーザ管理のcontrollerクラス
 */
class PersonalsController extends AppController
{

    var $name = "Personal";

    var $uses = array(
        'Personal'
    );

    var $autoLayout = true;

    var $helpers = array(
        'Html',
        'Form',
        'Ajax',
        'Javascript'
    );
    
    // 編集用
    function pass_edit()
    {
        $this->set("main_title", "パスワード変更");
        $user = $this->Auth->user();
        
        if ($user['User']['AUTHORITY'] == '1') {
            $this->set("title_text", "マイメニュー");
        } else {
            $this->set("title_text", "管理者メニュー");
        }
        
        // キャンセルボタンを押された場合、ホームにリダイレクト
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect("/homes");
        }
        
        if (isset($this->data)) {
            // トークンチェック
            $this->isCorrectToken($this->data['Security']['token']);
            
            $this->data['Personal']['USR_ID'] = $user['User']['USR_ID'];
            $this->data['Personal']['RANDOM_KEY'] = null;
            $this->data['Personal']['PASSWORD'] = $this->Auth->password($this->data['Personal']['EDIT_PASSWORD']);
            $this->data['Personal']['LAST_UPDATE'] = $datetime = date("Y-m-d H:i:s");
            
            if ($this->Personal->save($this->Personal->permit_params($this->data))) {
                $this->Session->setFlash('パスワードを変更しました。');
            }
            
            $this->params['data']['Personal']['PASSWORD_NOW'] = NULL;
            $this->params['data']['Personal']['EDIT_PASSWORD'] = NULL;
            $this->params['data']['Personal']['EDIT_PASSWORD1'] = NULL;
        }
    }
}
