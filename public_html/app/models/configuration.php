<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 内容:DB属性名変換のmodelクラス
 */
class Configuration extends AppModel
{

    var $name = 'Configuration';

    var $primaryKey = 'CON_ID';

    var $useTable = 'T_CONFIG';
    
    // バリデーション
    var $actsAs = array(
        'Cakeplus.AddValidationRule',
        'CustomValidation'
    );
    
    protected $accessible = array(
    		'Configuration' => array(
    				'CON_ID',
    				'FROM_NAME',
    				'FROM',
    				'STATUS',
    				'PROTOCOL',
    				'SECURITY',
    				'HOST',
    				'PORT',
    				'USER',
    				'PASS',
    				'CON_ID',
    				'LAST_UPDATE',
    		),
    );
    
    var $validate = array(
        'FROM' => array(
            "rule1" => array(
                'rule' => array(
                    'maxLengthJP',
                    256
                ),
                'message' => 'メールアドレスが長すぎます'
            ),
            "rule2" => array(
                'rule' => array(
                    'email'
                ),
                'message' => '有効なメールアドレスではありません'
            ),
            "rule3" => array(
                'rule' => 'notEmpty',
                'message' => 'メールアドレスは必須です'
            )
        ),
        'FROM_NAME' => array(
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '送信者名は必須です'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthJP',
                    256
                ),
                'message' => '送信者名が長すぎます'
            )
        ),
        'PORT' => array(
            "rule1" => array(
                'rule' => array(
                    'betweenJP',
                    0,
                    5
                ),
                'message' => '有効なポート番号ではありません'
            ),
            "rule2" => array(
                'rule' => 'Numberonly',
                'message' => '有効なポート番号ではありません'
            ),
            "rule3" => array(
                'rule' => array(
                    'Max_number',
                    65536
                ),
                'message' => '有効なポート番号ではありません'
            )
        ),
        'USER' => array(
            "rule1" => array(
                'rule' => array(
                    'maxLengthW',
                    30
                ),
                'message' => 'ユーザ名が長すぎます'
            )
        ),
        'PASS' => array(
            "rule0" => array(
                'rule' => 'idNumber',
                'message' => '半角英数字以外が含まれます'
            )
        ),
        'HOST' => array(
            "rule1" => array(
                'rule' => array(
                    'maxLengthJP',
                    100
                ),
                'message' => 'SMTPサーバが長すぎます'
            ),
            "rule2" => array(
                'rule' => array(
                    'domain'
                ),
                'message' => '有効なドメインではありません'
            )
        )
    );

    function index_select($_con_ID = null)
    {
        $_con_ID = null ? $_con_ID = 1 : $_con_ID = $_con_ID;
        
        $result = $this->read(null, $_con_ID);
        
        // 存在しない場合
        if (! $result)
            return null;
        
        return $result;
    }
    
    // データの書き込み処理
    function index_set_data($_param, &$_error = null, $_state = null)
    {
        // 時間のセット
        if ($_state == 'new') {
            $_param['Configuration']['INSERT_DATE'] = date("Y-m-d H:i:s");
            $_param['Configuration']['CON_ID'] = 1;
        }
        $_param['Configuration']['LAST_UPDATE'] = date("Y-m-d H:i:s");
        
        // トランザクションの開始
        $dataSource = $this->getDataSource();
        $dataSource->begin($this);
        
        $this->set($_param);
        $_error = $this->invalidFields();
        $error = 0;
        if ($_param['Configuration']['STATUS'] != 0) {
            if ($_param['Configuration']['HOST'] == null) {
                $_error['HOST'] = "SMTPサーバは必須です。";
                $this->invalidate('HOST');
                $error = 1;
            }
            
            if ($_param['Configuration']['PORT'] == null) {
                $_error['PORT'] = "ポート番号は必須です。";
                $this->invalidate('PORT');
                $error = 1;
            }
            
            if (! isset($_param['Configuration']['PROTOCOL']) || $_param['Configuration']['PROTOCOL'] == '') {
                $_error['PROTOCOL'] = "プロトコルが選択されていません。";
                $this->invalidate('PROTOCOL');
                $error = 1;
            } else {
                if ($_param['Configuration']['PROTOCOL'] == 1) {
                    if ($_param['Configuration']['USER'] == null) {
                        $_error['USER'] = "SMTPユーザは必須です。";
                        $this->invalidate('USER');
                        $error = 1;
                    }
                    if ($_param['Configuration']['PASS'] == null) {
                        $_error['PASS'] = "SMTPパスワードは必須です。";
                        $this->invalidate('PASS');
                        $error = 1;
                    }
                }
            }
            
            if (! isset($_param['Configuration']['SECURITY']) || $_param['Configuration']['SECURITY'] == '') {
                $_error['SECURITY'] = "SMTPセキュリティが選択されていません。";
                $this->invalidate('SECURITY');
                $error = 1;
            }
        }
        
        // DB登録
        if ($this->save($this->permit_params($_param)) && $error == 0) {
            $dataSource->commit($this);
            $_param['Configuration']['CON_ID'] = $this->getInsertID();
        } else {
            $dataSource->rollback($this);
            return $_param;
        }
        return $_param;
    }
}
?>
