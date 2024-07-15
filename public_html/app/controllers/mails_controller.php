<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */
/**
 * 内容:メール送信機能
 */
class MailsController extends AppController
{

    var $name = "Mail";

    var $uses = array(
        "Mail",
        "Quote",
        "Bill",
        "Delivery"
    );

    var $autoLayout = true;

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('login', 'customer', 'logout');
    }
    
    // 一覧用
    public function index()
    {
        $type = array(
            1 => '見積書',
            2 => '請求書',
            3 => '納品書'
        );
        
        // セット
        $this->set("mailstatus", Configure::read('MailStatusCode'));
        $this->set("type", $type);
        $this->set("main_title", "確認メール");
        $this->set("title_text", "帳票管理");
    }
    
    // コメントの確認用
    function check()
    {
        // IDの取得
        if (! isset($this->params['pass'][0])) {
            // エラー処理
            $this->redirect("/mails");
        }
        
        $tml_id = $this->params['pass'][0];
        
        if (! $result = $this->Mail->find('first', array(
            'conditions' => array(
                'TML_ID' => $tml_id
            )
        ))) {
            // エラー処理
            $this->redirect("/mails");
        }
        
        if (! $this->Get_Edit_Authority($result['Mail']['USR_ID'])) {
            $this->Session->setFlash('不正な操作が行われました');
            $this->redirect("/mails");
        }
        
        $status = Configure::read('MailStatusCode');
        switch ($result['Mail']['TYPE']) {
            case 1:
                $tilte = '見積書';
                break;
            case 2:
                $tilte = '請求書';
                break;
            case 3:
                $tilte = '納品書';
                break;
        }
        
        $this->set("reptime", $result['Mail']["RCV_DATE"]);
        $this->set("mail_status", $status[$result['Mail']['STATUS']]);
        $this->set("sender", $result['Mail']['SENDER']);
        $this->set("receiver", $result['Mail']['RECEIVER']);
        $this->set("comment", str_replace("\n", "<br>", $result['Mail']['RCV_MESSAGE']));
        $this->set("rcv_name", $result['Mail']['RCV_NAME']);
        $this->set("snd_name", $result['Mail']['SND_NAME']);
        $this->set("subject", $result['Mail']['SUBJECT']);
        $this->set("main_title", "{$tilte}確認メール");
        $this->set("title_text", "帳票管理");
    }
    
    // お客様用
    public function customer()
    {
        $type = "";
        
        // ページタイプの抜き出し
        if (is_array($this->params['form'])) {
            foreach ($this->params['form'] as $key => $value) {
                if (preg_match("/^(.*)_x$/", $key, $result)) {
                    $type = $result[1];
                }
            }
        }
        
        // ページ切り替え処理
        switch ($type) {
            
            // 送信処理
            case 'send':
                
                // トークンの確認
                if (! $this->Common->checkOneTimeToken('_cml', $this->params['data']['Mail']['tkn'])) {
                    $this->cakeError('error404', array(
                        array(
                            'url' => '/'
                        )
                    ));
                }
                
                // トークンの削除
                $this->Common->deleteOneTimeToken('_cml', $this->params['data']['Mail']['tkn']);
                
                $param['Mail'] = $this->params['data']['Mail'];
                $param['Mail']['RCV_MESSAGE'] = $param['Mail']['COMMENT'];
                
                $time_limit = date('Y-m-d H:i:s', mktime(date("H"), date("i"), date("s"), date("n"), date("j") - Configure::read('MailLoginTerm'), date("Y")));
                
                $check = $this->Mail->find('first', array(
                    'conditions' => array(
                        'TOKEN' => $param['Mail']['TOKEN'],
                        'SND_DATE >=' => $time_limit
                    )
                ));
                
                if (empty($check)) {
                    $this->cakeError('error404', array(
                        array(
                            'url' => '/'
                        )
                    ));
                }
                
                $this->set("token", $param['Mail']['TOKEN']);
                
                $tml_id = $check['Mail']['TML_ID'];
                $frm_id = $check['Mail']['FRM_ID'];
                if ($this->Mail->res_mail($check, $param['Mail'])) {
                    $param['Mail']['TOKEN'] = null;
                    $param['Mail']['RCV_DATE'] = date("Y-m-d H:i:s");
                    
                    if ($this->Mail->save($param)) {
                        // リダイレクト
                        $this->redirect('/mails/logout');
                    }
                }
                
                break;
            
            // 確認処理
            case 'reaffirmation':
                
                // トークンの確認
                if (! $this->Common->checkOneTimeToken('_cml', $this->params['data']['Mail']['tkn'])) {
                    $this->cakeError('error404', array(
                        array(
                            'url' => '/'
                        )
                    ));
                }
                
                // バリデーション
                $this->Mail->set($this->params['data']['Mail']);
                $errors = $this->Mail->invalidFields();
                
                // エラーでなければ確認画面を表示
                if (! $errors) {
                    $sta = array(
                        1 => '確認済み',
                        2 => '修正願い'
                    );
                    
                    $this->set("status", $sta[$this->params['data']['Mail']['STATUS']]);
                    $this->set("comment", str_replace("\n", "<br>", $this->params['data']['Mail']['COMMENT']));
                    
                    $this->render('c_reaffirmation', 'check_layout');
                    break;
                }
                
                // エラー時の処理 エラー発行後に入力処理へ移行
                $this->set("error", $errors);
            
            // 入力処理
            default:
                
                $time_limit = date('Y-m-d H:i:s', mktime(date("H"), date("i"), date("s"), date("n"), date("j") - Configure::read('MailLoginTerm'), date("Y")));
                
                // 通常処理
                if (isset($this->data['Mail']['TOKEN']) && isset($this->data['Mail']['PASSWORD'])) {
                    $token = $this->data['Mail']['TOKEN'];
                    $pass = $this->data['Mail']['PASSWORD'];
                    $check = $this->Mail->find('first', array(
                        'conditions' => array(
                            'TOKEN' => $token,
                            'PASSWORD' => $this->Auth->password($pass),
                            'SND_DATE >=' => $time_limit
                        )
                    ));
                    if (! $check) {
                        $this->Session->setFlash('パスワードが違います');
                        $this->redirect("login/" . $token);
                    }
                    
                    // 入力エラー時処理とバック時処理
                } else 
                    if (isset($this->data['Mail']['TOKEN'])) {
                        // トークンの確認
                        if (! $this->Common->checkOneTimeToken('_cml', $this->params['data']['Mail']['tkn'])) {
                            $this->cakeError('error404', array(
                                array(
                                    'url' => '/'
                                )
                            ));
                        }
                        
                        $token = $this->data['Mail']['TOKEN'];
                        $check = $this->Mail->find('first', array(
                            'conditions' => array(
                                'TOKEN' => $token,
                                'SND_DATE >=' => $time_limit
                            )
                        ));
                        
                        // エラー
                    } else {
                        $this->cakeError('error404', array(
                            array(
                                'url' => '/'
                            )
                        ));
                    }
                
                // 不正操作検出
                if (! $check) {
                    $this->Session->setFlash('不正操作がありました');
                    $this->redirect("login/" . $token);
                }
                
                // ログイン時のみトークン発行
                if (isset($this->data['Mail']['TOKEN']) && isset($this->data['Mail']['PASSWORD'])) {
                    $this->data['Mail']['STATUS'] = 1;
                    // ワンタイムトークンの作成
                    $this->data['Mail']['tkn'] = $this->Common->setOneTimeToken('_cml');
                }
                
                $type = "";
                if ($check['Mail']['TYPE'] == 1) {
                    $type = "quotes";
                } else 
                    if ($check['Mail']['TYPE'] == 2) {
                        $type = "bills";
                    } else 
                        if ($check['Mail']['TYPE'] == 3) {
                            $type = "deliveries";
                        }
                
                $this->data['Mail']['TML_ID'] = $check['Mail']['TML_ID'];
                $this->data['Mail']['TOKEN'] = $check['Mail']['TOKEN'];
                
                $this->set("frm_id", $check['Mail']['FRM_ID']);
                $this->set("type", $type);
                $this->set("token", $token);
                $this->set("subject", $check['Mail']['SUBJECT']);
                $this->set("snd_name", $check['Mail']['SND_NAME']);
                $this->set("rcv_name", $check['Mail']['RCV_NAME']);
                $this->set("subject", $check['Mail']['SUBJECT']);
                $this->render('customer', 'check_layout');
                
                break;
        }
    }
    
    // ログイン用
    public function login()
    {
        $token = '';
        if (isset($this->params['pass'][0])) {
            $token = $this->params['pass'][0];
            
            $time_limit = date('Y-m-d H:i:s', mktime(date("H"), date("i"), date("s"), date("n"), date("j") - Configure::read('MailLoginTerm'), date("Y")));
            
            $check = $this->Mail->find('first', array(
                'conditions' => array(
                    'TOKEN' => $token
                ),
                'SND_DATE >=' => $time_limit
            ));
            
            if (empty($check)) {
                $this->cakeError('error404', array(
                    array(
                        'url' => '/'
                    )
                ));
            }
        }
        
        $this->set("customer_charge", $check['Mail']['RCV_NAME']);
        $this->set("charge", $check['Mail']['SND_NAME']);
        $this->set("token", $token);
        $this->render('login', 'check_layout');
    }
    
    // ログアウト用
    function logout()
    {
        $this->render('logout', 'check_layout');
    }
    
    // 送信基本情報
    function sendmail()
    {
        $type = "";
        
        // ページタイプの抜き出し
        if (is_array($this->params['form'])) {
            foreach ($this->params['form'] as $key => $value) {
                if (preg_match("/^(.*)_x$/", $key, $result)) {
                    $type = $result[1];
                }
            }
        }
        
        // ページ切り替え処理
        switch ($type) {
            
            // 送信処理
            case 'send':
                
                // トークンの確認
                if (! $this->Common->checkOneTimeToken('_ml', $this->params['data']['Mail']['tkn'])) {
                    $this->Session->setFlash('初めから登録してください');
                    $this->redirect("/mails");
                }
                
                // トークンの削除
                $this->Common->deleteOneTimeToken('_ml', $this->params['data']['Mail']['tkn']);
                
                $this->params['data']['Mail']['PASSWORD'] = $this->Auth->password($this->params['data']['Mail']['PASSWORD1']);
                
                // メールの送付
                if ($this->Mail->Send_Mail($this->params['data']['Mail'])) {
                    $pass = $this->params['data']['Mail']['PASSWORD1'];
                    
                    // ビュー設定
                    $this->set("main_title", "確認依頼");
                    $this->set("title_text", "帳票管理");
                    $this->set("pass", $pass);
                    $this->render('completion');
                } else {
                    // リダイレクト
                    $this->Session->setFlash('メールの送信に失敗しました');
                    $this->redirect("/mails");
                }
                
                break;
            
            // 再確認
            case 'reaffirmation':
                
                // トークンの確認
                if (! $this->Common->checkOneTimeToken('_ml', $this->params['data']['Mail']['tkn'])) {
                    $this->Session->setFlash('初めから登録してください');
                    $this->redirect("/mails");
                }
                
                $param = $this->params['data']['Mail'];
                
                // バリデーション
                $this->Mail->set($param);
                $errors = $this->Mail->invalidFields();
                
                // エラーがなければ表示
                if (! $errors) {
                    // ビュー設定
                    $this->set("main_title", "確認依頼");
                    $this->set("title_text", "帳票管理");
                    $this->set("to", $param['TO']);
                    $this->set("from", $param['FROM']);
                    $this->set("param", $param);
                    $this->set("body", str_replace("\n", "<br>", $param['BODY']));
                    $this->render('reaffirmation');
                    break;
                }
                
                // エラー時の処理 エラー発行後に本文入力処理へ移行
                $this->set("error", $errors);
            
            // 本文入力
            case 'body':
                
                // トークンの確認
                if (! $this->Common->checkOneTimeToken('_ml', $this->params['data']['Mail']['tkn'])) {
                    $this->Session->setFlash('初めから登録してください');
                    $this->redirect("/mails");
                }
                
                // バリデーション
                $this->Mail->set($this->params['data']['Mail']);
                $errors = $this->Mail->invalidFields();
                
                // エラーがなければ表示
                if (! $errors) {
                    $seed = "K4O9dg7d";
                    $hash = $this->Auth->password($seed . microtime());
                    $url = h(Router::url('/', true)) . "mails/login/" . $hash;
                    
                    $default = $this->params['data']['Mail']['CUSTOMER_CHARGE'] . " 様\n";
                    $default .= "\n\n";
                    $default .= $this->params['data']['Mail']['CHARGE'] . "様より" . $this->params['data']['Mail']['CUSTOMER_CHARGE'] . "様宛てに以下データが送付されています。\n";
                    $default .= "\n\n";
                    $default .= "------------------------------------------------------------\n";
                    $default .= "送信者：" . $this->params['data']['Mail']['CHARGE'] . "様\n";
                    $default .= "\n";
                    $default .= "件名：" . $this->params['data']['Mail']['SUBJECT'] . "\n";
                    $default .= "ダウンロードURL：" . $url . "\n";
                    $default .= "\n";
                    $default .= "※データのお預かり期間は7日間となりますので、ご注意ください。\n";
                    $default .= "------------------------------------------------------------\n";
                    $default .= "\n";
                    $default .= "※上記URLを押下すると、ダウンロードページへ遷移します。\n";
                    $default .= "※ダウンロードページにはセキュリティのため、パスワードが設定されていますので、\n";
                    $default .= "　パスワード入力画面ではあらかじめ送信者様から送られたパスワードをご入力ください。\n";
                    $default .= "なお、パスワードが送信されていない場合は、送信者様にご連絡ください。\n";
                    $default .= "\n\n";
                    $default .= "本メールは「抹茶請求書」による自動送信メールです。本メールにお心当たりの\n";
                    $default .= "ない場合は、恐れ入りますが、破棄くださいますようお願い申し上げます。";
                    
                    $this->set("main_title", "確認依頼");
                    $this->set("title_text", "帳票管理");
                    $this->set("hash", $hash);
                    $this->set("body", $default);
                    
                    // Viewの設定
                    $this->render('bodyinfo');
                    
                    break;
                }
                
                // エラー時の処理 エラー発行後に基本情報入力処理へ移行
                $this->set("error", $errors);
            
            // 基本情報入力
            default:
                
                // 通常処理
                if (isset($this->params['pass'][0]) && isset($this->params['pass'][1])) {
                    $type = $this->params['pass'][0];
                    $frm_id = $this->params['pass'][1];
                    if ($type === 'quote')
                        $type = 1;
                    else 
                        if ($type === 'bill')
                            $type = 2;
                        else 
                            if ($type === 'delivery')
                                $type = 3;
                            else
                                $this->redirect("/mails");
                    
                    // エラー時及びバック時の処理
                } else 
                    if (isset($this->params['data']['Mail']['TYPE']) && isset($this->params['data']['Mail']['FRM_ID'])) {
                        
                        // トークンの確認
                        if (! $this->Common->checkOneTimeToken('_ml', $this->params['data']['Mail']['tkn'])) {
                            $this->Session->setFlash('初めから登録してください');
                            $this->redirect("/mails");
                        }
                        $type = $this->params['data']['Mail']['TYPE'];
                        $frm_id = $this->params['data']['Mail']['FRM_ID'];
                    }
                
                // データの取得
                if (isset($type) && isset($frm_id)) {
                    $param = array();
                    if ($type == 1) {
                        if ($this->Get_User_Authority() != 1 && $this->Get_User_Authority() != 2) {
                            $param = $this->Mail->mail_data('Quote', $this->Quote->find('first', array(
                                'conditions' => array(
                                    'Quote.MQT_ID' => $frm_id
                                )
                            )));
                        } else {
                            $param = $this->Mail->mail_data('Quote', $this->Quote->find('first', array(
                                'conditions' => array(
                                    'Quote.MQT_ID' => $frm_id,
                                    'Quote.USR_ID' => $this->Get_User_ID()
                                )
                            )));
                        }
                    } else 
                        if ($type == 2) {
                            if ($this->Get_User_Authority() != 1 && $this->Get_User_Authority() != 2) {
                                $param = $this->Mail->mail_data('Bill', $this->Bill->find('first', array(
                                    'conditions' => array(
                                        'Bill.MBL_ID' => $frm_id
                                    )
                                )));
                            } else {
                                $param = $this->Mail->mail_data('Bill', $this->Bill->find('first', array(
                                    'conditions' => array(
                                        'Bill.MBL_ID' => $frm_id,
                                        'Bill.USR_ID' => $this->Get_User_ID()
                                    )
                                )));
                            }
                        } else 
                            if ($type == 3) {
                                if ($this->Get_User_Authority() != 1 && $this->Get_User_Authority() != 2) {
                                    $param = $this->Mail->mail_data('Delivery', $this->Delivery->find('first', array(
                                        'conditions' => array(
                                            'Delivery.MDV_ID' => $frm_id
                                        )
                                    )));
                                } else {
                                    $param = $this->Mail->mail_data('Delivery', $this->Delivery->find('first', array(
                                        'conditions' => array(
                                            'Delivery.MDV_ID' => $frm_id,
                                            'Delivery.USR_ID' => $this->Get_User_ID()
                                        )
                                    )));
                                }
                            } else {
                                $this->redirect("/mails");
                            }
                } else {
                    $this->redirect("/mails");
                }
                
                // データがなければエラー処理
                if (! $param) {
                    $this->Session->setFlash('不正なアクセス');
                    $this->redirect("/mails");
                }
                
                // 通常処理の場合はワンタイムトークンを発行
                if (isset($this->params['pass'][0]) && isset($this->params['pass'][1])) {
                    // ワンタイムトークンの作成
                    $this->data['Mail']['tkn'] = $this->Common->setOneTimeToken('_ml');
                }
                
                $this->data['Mail']['TYPE'] = $type;
                $this->data['Mail']['FRM_ID'] = $frm_id;
                $this->data['Mail']['USR_ID'] = $this->Get_User_ID();
                
                $this->set("customer", $param['Customer']);
                $this->set("cstid", $param['CST_ID']);
                $this->set("company", $param['Company']);
                $this->set("subject", $param['SUBJECT']);
                $this->set("main_title", "確認依頼");
                $this->set("title_text", "帳票管理");
                $this->render('basicinfo');
                break;
        }
    }
}