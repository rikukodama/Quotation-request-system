<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 確認メール用modelクラス
 */
class Mail extends AppModel
{

    var $name = 'Mail';

    var $useTable = 'T_MAIL';

    var $primaryKey = 'TML_ID';
    
    // 検索条件
    var $searchColumnAry = array(
        'SUBJECT' => 'Mail.SUBJECT',
        'CUSTOMER_CHARGE' => 'Mail.RCV_NAME',
        'STATUS' => 'Mail.STATUS',
        'TYPE' => 'Mail.TYPE'
    );
    
    // order by
    var $order = array(
        'TML_ID DESC'
    );
    
    // バリデーション
    var $actsAs = array(
        'Cakeplus.AddValidationRule',
        'CustomValidation'
    );

    var $validate = array(
        'TO' => array(
            "rule0" => array(
                'rule' => 'notEmpty',
                'message' => 'メールアドレスは必須項目です'
            ),
            "rule1" => array(
                'rule' => array(
                    'email'
                ),
                'message' => '有効なメールアドレスではありません',
                'allowEmpty' => true
            )
        ),
        'FROM' => array(
            "rule0" => array(
                'rule' => 'notEmpty',
                'message' => 'メールアドレスは必須項目です'
            ),
            "rule1" => array(
                'rule' => array(
                    'email'
                ),
                'message' => '有効なメールアドレスではありません',
                'allowEmpty' => true
            )
        ),
        'CUSTOMER' => array(
            "rule0" => array(
                'rule' => 'notEmpty',
                'message' => '顧客名は必須項目です'
            ),
            "rule1" => array(
                'rule' => array(
                    'maxLengthJP',
                    30
                ),
                'message' => '顧客名が長すぎます'
            )
        ),
        'CHARGE' => array(
            "rule0" => array(
                'rule' => 'notEmpty',
                'message' => '自社担当者名は必須項目です'
            ),
            "rule1" => array(
                'rule' => array(
                    'maxLengthW',
                    30
                ),
                'message' => '自社担当者名が長すぎます'
            )
        ),
        'CUSTOMER_CHARGE' => array(
            "rule0" => array(
                'rule' => 'notEmpty',
                'message' => '顧客担当者名は必須項目です'
            ),
            "rule1" => array(
                'rule' => array(
                    'maxLengthW',
                    30
                ),
                'message' => '顧客担当者名が長すぎます'
            )
        ),
        'PASSWORD1' => array(
            "rule0" => array(
                'rule' => 'idNumber',
                'message' => '半角英数字以外が含まれます'
            ),
            'rule1' => array(
                'rule' => array(
                    'betweenJP',
                    6,
                    12
                ),
                'message' => 'パスワードは6～12文字で入力してください。'
            ),
            "rule2" => array(
                'rule' => 'notEmpty',
                'message' => 'パスワードは必須項目です'
            )
        ),
        'COMMENT' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthJP',
                    200
                ),
                'message' => 'コメントが長すぎます'
            )
        )
    );

    function mail_data($_type, $_param)
    {
        if (! $_param) {
            return false;
        }
        
        $result = array();
        
        if ($_type === 'Quote') {
            $result['FRM_ID'] = $_param[$_type]['MQT_ID'];
        } else 
            if ($_type === 'Bill') {
                $result['FRM_ID'] = $_param[$_type]['MBL_ID'];
            } else 
                if ($_type === 'Delivery') {
                    $result['FRM_ID'] = $_param[$_type]['MDV_ID'];
                }
        $result['SUBJECT'] = $_param[$_type]['SUBJECT'];
        $result['Customer'] = $_param['Customer']['NAME'];
        $result['CST_ID'] = $_param['Customer']['CST_ID'];
        
        App::import('Model', 'Company');
        $Company = new Company();
        $company = $Company->find('first', array(
            'conditions' => array(
                'Company.CMP_ID' => 1
            )
        ));
        
        $result['Company'] = $company['Company']['NAME'];
        
        return $result;
    }

    function res_mail($_param, $_mail)
    {
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");
        
        // 件名
        $subject = $_param['Mail']['RCV_NAME'] . "様から確認メールが届きました";
        
        // 送信元アドレス
        $from = "From: " . $_param['Mail']['RECEIVER'];
        
        // 返信先アドレス
        $to = $_param['Mail']['SENDER'];
        
        // メール本文
        $body = "";
        $body .= $_param['Mail']['SND_NAME'] . " 様\n";
        $body .= "\n";
        $body .= "\n";
        $body .= $_param['Mail']['RCV_NAME'] . " 様より以下の内容が届いています。\n";
        $body .= "詳細は、確認メールページで、ご確認ください。\n";
        $body .= "\n";
        $body .= "------------------------------------------------------------\n";
        $body .= "件名　　　　　：" . $_param['Mail']['SUBJECT'] . "\n";
        $body .= "ステータス　　：" . ($_mail['STATUS'] == 1 ? "確認済み" : "修正願い") . "\n";
        $body .= "\n";
        $body .= "コメント\n";
        $body .= $_mail['COMMENT'] . "\n";
        $body .= "\n";
        $body .= "------------------------------------------------------------\n";
        $body .= "\n";
        $body .= "\n";
        $body .= "本メールは「抹茶請求書」による自動送信メールです。本メールにお心当たりの\n";
        $body .= "ない場合は、恐れ入りますが、破棄くださいますようお願い申し上げます。\n";
        
        App::import('Component', 'Common');
        $common = new CommonComponent();
        if ($common->send_mail($to, $subject, $body)) {
            return true;
        } else {
            return false;
        }
    }

    public function Send_Mail($_send_param)
    {
        // メールの送信
        // mb_language("Japanese");
        // mb_internal_encoding("UTF-8");
        if (Validation::email($_send_param['MAIL'])) {
            // 宛先
            $to = $_send_param['MAIL'];
            
            // 差出人
            $header = "From: " . $_send_param['FROM'];
            
            // 件名
            $subject = $_send_param['CHARGE'] . "様よりデータ受領の確認";
            
            // 本文
            $body = str_replace(array(
                '<br />',
                '<br>'
            ), "", $_send_param['BODY']);
            
            // データベース保存データの作成
            $data['Mail']['FRM_ID'] = $_send_param['FRM_ID'];
            $data['Mail']['USR_ID'] = $_send_param['USR_ID'];
            $data['Mail']['RECEIVER'] = $_send_param['MAIL'];
            $data['Mail']['SENDER'] = $_send_param['FROM'];
            $data['Mail']['CUSTOMER'] = $_send_param['CUSTOMER'];
            $data['Mail']['RCV_NAME'] = $_send_param['CUSTOMER_CHARGE'];
            $data['Mail']['SUBJECT'] = $_send_param['SUBJECT'];
            $data['Mail']['SND_NAME'] = $_send_param['CHARGE'];
            $data['Mail']['STATUS'] = 0;
            $data['Mail']['TOKEN'] = $_send_param['CORD'];
            $data['Mail']['TYPE'] = $_send_param['TYPE'];
            $data['Mail']['PASSWORD'] = $_send_param['PASSWORD'];
            $data['Mail']['SND_MESSAGE'] = $body;
            $data['Mail']['SND_DATE'] = date('Y-m-d H:i:s');
            
            App::import('Component', 'Common');
            
            $common = new CommonComponent();
            $status = $common->send_mail($to, $subject, $body);
            
            // トランザクションの開始
            $dataSource = $this->getDataSource();
            $dataSource->begin($data);
            
            $this->save($data);
            
            // メール送信
            if ($status) {
                $dataSource->commit($data);
                return true;
            } else {
                $dataSource->rollback($data);
                return false;
            }
        } else {
            $dataSource->rollback($data);
            // メールアドレスエラー
            return false;
        }
    }
}