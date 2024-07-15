<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */
/**
 * 内容:ログインのcontrollerクラス
 */
class UsersController extends AppController
{

    var $name = "Users";

    var $uses = array(
        'User'
    );

    var $components = array(
        'Qdmail'
    );

    var $autoLayout = true;

    var $layout = 'login_layout';

    function beforeFilter()
    {
        $this->Auth->autoRedirect = false;
        $this->Auth->fields = array(
            'username' => 'LOGIN_ID',
            'password' => 'PASSWORD'
        );
        if ($this->action == 'regist_user') {
            $this->Auth->authenticate = $this->User;
        }
        
        $this->Auth->allow('reset', 'reset_end', 'pass_edit');
        $this->Set_View_Option();
    }
    
    // ログイン画面用
    function login()
    {
        $this->set("page_title", "ログイン");
        $value = $this->Cookie->read('userid');
        if ($value != NULL) {
            $user = $this->Auth->user();
            $this->History->h_logout($value[0]);
            $this->Cookie->delete('userid');
        }
        
        // 認証している場合はマイページにリダイレクト
        if ($this->Auth->user()) {
            $user = $this->Auth->user();
            if ($user['User']['STATUS'] != 1) {
                $this->History->h_login($user['User']['USR_ID']);
                
                // セッションID書き換え
                App::import('Helper', 'Session');
                $sessionHelper = new SessionHelper();
                $sessionHelper->__regenerateId();
                $this->Session->delete('session_params');
                
                
                // ログイン成功
                $this->redirect(array(
                    'controller' => 'homes',
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash('IDまたはパスワードが不正です。', '');
                $this->redirect($this->Auth->logout());
            }
        } elseif (! empty($this->data)) {
            // ログイン失敗
            $this->Session->setFlash('IDまたはパスワードが不正です。', '');
        }
    }
    
    // ログアウト用
    function logout()
    {
        // ログアウトのログを投入
        $user = $this->Auth->user();
        $this->History->h_logout($user['User']['USR_ID']);
        $this->Session->destroy();
        $this->redirect($this->Auth->logout());
    }
    
    // パスワード再設定
    function reset()
    {
        if (isset($this->data)) {
            $condition = array(
                'conditions' => array(
                    'MAIL' => $this->data['User']['MAIL']
                ),
                'order' => array(
                    'ADD_DATE' => 'DESC'
                ),
                'recursive' => 0
            );
            
            $row = $this->User->find('first', $condition);
            if (! $row) {
                $this->Session->setFlash('入力されたメールアドレスは登録されていません', '');
                $this->redirect('/users/reset');
            }
            
            // メール送信
            $key = Security::hash(uniqid() . mt_rand());
            $this->data['User']['RANDOM_KEY'] = $key;
            $this->User->id = $row['User']['USR_ID'];
            if ($this->User->save($this->data)) {
                $url = Router::url('/users/pass_edit', true);
                $body = Configure::read('Mail.Txt.PassEdit') . "\n" . $url . '?k=' . $key;
                if (! $this->Common->send_mail($this->data['User']['MAIL'], Configure::read('Mail.Subject.PassEdit'), $body)) {
                    $this->Session->setFlash('メールの送信に失敗しました', '');
                    $this->redirect('/users/reset');
                }
            }
            $this->render('reset_end');
        }
    }
    
    // パスワード作成用
    function pass_edit()
    {
        $key = isset($this->params['url']['k']) ? $this->params['url']['k'] : $this->data['User']['key'];
        
        // keyパラメータがない場合は
        if (! isset($key)) {
            $this->redirect('/');
        }
        
        $condition = array(
            'conditions' => array(
                'RANDOM_KEY' => $key
            ),
            'order' => array(
                'ADD_DATE' => 'DESC'
            ),
            'recursive' => 0
        );
        
        $row = $this->User->find('first', $condition);
        if (! $row) {
            $this->redirect('/');
        }
        
        $this->set('key', $key);
        if (isset($this->data)) {
            // パスワード・ランダムキー削除
            $this->User->id = $row['User']['USR_ID'];
            $this->data['User']['RANDOM_KEY'] = null;
            $this->data['User']['PASSWORD'] = $this->Auth->password($this->data['User']['EDIT_PASSWORD']);
            $this->data['User']['LAST_UPDATE'] = $datetime = date("Y-m-d H:i:s");
            
            if ($this->User->save($this->data)) {
                $this->render('pass_edit_end');
            }
        }
    }
}