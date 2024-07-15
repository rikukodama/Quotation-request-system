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
class AdministersController extends AppController
{

    var $name = "Administer";

    var $uses = array(
        'Administer'
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
        // ページ名の設定
        $this->set("main_title", "ユーザ一覧");
        $this->set("title_text", "管理者メニュー");
        
        $companyID = 1;
        
        $this->data['Administer'] = $this->Common->paginate($this->Administer->searchColumnAry, null, null, array(
            "Administer.AUTHORITY !=" => 0
        ));
        
        $this->set("status", Configure::read('StatusCode'));
    }
    
    // 登録用
    function add()
    {
        // ページ名の設定
        $this->set("main_title", "ユーザ登録");
        $this->set("title_text", "管理者メニュー");
        
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect("/administers");
        }
        $company_ID = 1;
        $error = array(
            'LOGIN_ID' => 0,
            'PASSWORD' => 0,
            'MAIL' => 0
        );
        
        if (isset($this->params['form']['submit_x'])) {
            // トークンチェック
            $this->isCorrectToken($this->data['Security']['token']);
            
            $this->params['data']['Administer']['PASSWORD'] = $this->Auth->password($this->params['data']['Administer']['EDIT_PASSWORD']);
            
            // バリデーション
            // 今までのユーザーと比較
            if (! empty($this->params['data']['Administer']['LOGIN_ID'])) {
                $usercheck = $this->Administer->find('all', array(
                    'fields' => array(
                        'LOGIN_ID',
                        'MAIL'
                    )
                ));
                foreach ($usercheck as $value) {
                    if ($value['Administer']['LOGIN_ID'] === $this->params['data']['Administer']['LOGIN_ID']) {
                        $error['LOGIN_ID'] = 1;
                    }
                }
                

            }

            if (! empty($this->params['data']['Administer']['MAIL']) && ! preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $this->params['data']['Administer']['MAIL'])) {
                    $error['MAIL'] = 2;
            }
            
            $this->params['data']['Administer']['CMP_ID'] = $company_ID;
            $savdata = $this->Administer->set_data($this->params['data'], $error, 'new');
            if (isset($savdata['Administer']['USR_ID'])) {
                // 成功
                $this->Session->setFlash('ユーザを保存しました');
                $this->redirect("/administers/check/" . $savdata['Administer']['USR_ID']);
            } else {
                // 失敗
                $this->params['data']['Administer']['EDIT_PASSWORD'] = NULL;
                $this->params['data']['Administer']['EDIT_PASSWORD1'] = NULL;
            }
        } else {}
        
        $this->set("status", Configure::read('StatusCode'));
        $this->set("error", $error);
        $this->set("authority", Configure::read('AuthorityCode'));
    }
    
    // 編集用
    function edit()
    {
        $this->set("main_title", "ユーザ編集");
        $this->set("title_text", "管理者メニュー");
        
        // キャンセルボタンを押された場合、一覧にリダイレクト
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect("/administers");
        }
        
        // テスト用データ
        $company_ID = 1;
        $error = array(
            'LOGIN_ID' => 0,
            'PASSWORD' => 0,
            'MAIL' => 0
        );
        
        if (! isset($this->params['data'])) {
            // IDの取得
            if (isset($this->params['pass'][0])) {
                $usr_ID = $this->params['pass'][0];
            } else {
                // エラー処理
                $this->redirect("/administers");
            }
            
            // 初期データの取得
            if (! $this->data = $this->Administer->edit_select($usr_ID)) {
                $this->redirect("/administers");
            }
            $this->data['Administer']['CHANGEFLG'] = 0;
        } else {
            // トークンチェック
            $this->isCorrectToken($this->data['Security']['token']);
            
            if (! empty($this->params['data']['Administer']['MAIL'])) {
                $usercheck = $this->Administer->find('all', array(
                    'fields' => array(
                        'LOGIN_ID',
                        'MAIL'
                    )
                ));
                if (! preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $this->params['data']['Administer']['MAIL'])) {
                    $error['MAIL'] = 2;
                }
            }
            
            if ($this->params['data']['Administer']['CHANGEFLG'] == 1) {
                if (empty($this->params['data']['Administer']['EDIT_PASSWORD'])) {
                    $error['PASSWORD'] = 1;
                }
                // データのインサート
                $this->params['data']['Administer']['PASSWORD'] = $this->Auth->password($this->params['data']['Administer']['EDIT_PASSWORD']);
                $this->params['data']['Administer']['PASSWORD_NOW'] = $this->Auth->password($this->params['data']['Administer']['PASSWORD_NOW']);
                // 今までのユーザーと比較
            } else {
                $pass = $this->Administer->find('first', array(
                    'fields' => array(
                        'PASSWORD'
                    ),
                    'conditions' => array(
                        'USR_ID' => $this->params['data']['Administer']['USR_ID']
                    )
                ));
                $this->params['data']['Administer']['PASSWORD_NOW'] = $pass['Administer']['PASSWORD'];
            }
            
            if ($this->Administer->set_data($this->params['data'], $error, 'edit')) {
                // 成功
                $this->Session->setFlash('ユーザを保存しました');
                $this->redirect("/administers/check/" . $this->params['data']['Administer']['USR_ID']);
            } else {
                // 失敗
                $this->params['data']['Administer']['PASSWORD_NOW'] = NULL;
                $this->params['data']['Administer']['EDIT_PASSWORD'] = NULL;
                $this->params['data']['Administer']['EDIT_PASSWORD1'] = NULL;
            }
        }
        $this->set("status", Configure::read('StatusCode'));
        $this->set("error", $error);
        $this->set("authority", Configure::read('AuthorityCode'));
    }
    
    // 編集用
    function check()
    {
        $this->set("main_title", "ユーザ確認");
        $this->set("title_text", "管理者メニュー");
        
        // IDの取得
        if (isset($this->params['pass'][0])) {
            $usr_ID = $this->params['pass'][0];
        } else {
            // エラー処理
            $this->redirect("/administers");
        }
        
        // 初期データの取得
        if (! $this->data = $this->Administer->edit_select($usr_ID)) {
            $this->redirect("/administers");
        }
        
        $this->set("status", Configure::read('StatusCode'));
        $this->set("params", $this->data);
        $this->set("authority", Configure::read('AuthorityCode'));
    }
}