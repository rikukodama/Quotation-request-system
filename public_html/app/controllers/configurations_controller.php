<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 内容:メールサーバ設定のcontrollerクラス
 */
class ConfigurationsController extends AppController
{

    var $name = "Configuration";

    var $uses = array(
        'Configuration',
        'User'
    );

    var $autoLayout = true;
    
    // 一覧用
    function index()
    {
        $this->set("main_title", "環境設定");
        $this->set("title_text", "管理者メニュー");
        
        $this->set('params', $this->Configuration->index_select(1));
        
        $this->set("security", Configure::read('SmtpSecurityCode'));
        $this->set("status", array(
            0 => '無効',
            1 => '有効'
        ));
        $this->set("protocol", Configure::read('MailProtocolCode'));
    }
    // 編集用
    function edit()
    {
        $this->set("main_title", "環境設定");
        $this->set("title_text", "管理者メニュー");
        
        $error = array();
        
        // キャンセルボタンを押された場合、一覧にリダイレクト
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect("/configurations");
        }
        
        if (isset($this->params['data'])) {
            // トークンチェック
            $this->isCorrectToken($this->data['Security']['token']);
            
            // データのインサート
            if ($this->params['data']['Configuration']['STATUS'] == 0) {
                $this->params['data']['Configuration']['SECURITY'] = array();
                $this->params['data']['Configuration']['PROTOCOL'] = array();
            }
            
            $result = $this->Configuration->index_set_data($this->params['data'], $error);
            $this->Configuration->validationErrors = $error;
            if ($error == null) {
                // 成功
                $this->Session->setFlash('自社情報設定を保存しました');
                $this->redirect("/configurations");
            } else {
                // 失敗
                if (isset($error['PROTOCOL']) || isset($error['SECURITY'])) {
                    $this->Session->setFlash('値が不正に入力されました');
                }
            }
        } else {
            // 通常時処理
            
            // メールサーバ情報の取得
            $this->data = $this->Configuration->index_select(1);
        }
        
        $protocol = array(
            'type' => 'radio',
            'options' => Configure::read('MailProtocolCode'),
            'div' => false,
            'label' => false,
            'legend' => false,
            'style' => 'width:30px;',
            'class' => "txt_mid"
        );
        
        $security = array(
            'type' => 'radio',
            'options' => Configure::read('SmtpSecurityCode'),
            'div' => false,
            'label' => false,
            'legend' => false,
            'style' => 'width:30px;',
            'class' => 'txt_mid'
        );
        
        $this->set("security", $security);
        $this->set("status", array(
            0 => '無効',
            1 => '有効'
        ));
        $this->set("protocol", $protocol);
    }
}