<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 内容:ログインのmodel
 */
class Administer extends AppModel
{

    var $name = 'Administer';

    var $useTable = 'T_USER';

    var $primaryKey = 'USR_ID';
    
    // 検索条件
    var $searchColumnAry = array(
        'LOGIN_ID' => 'Administer.LOGIN_ID',
        'NAME' => 'Administer.NAME'
    );
    // order by
    var $order = array(
        'USR_ID DESC'
    );
    
    // プラグイン読み込み
    var $actsAs = array(
        'Cakeplus.AddValidationRule',
        'CustomValidation'
    );
    
	// アクセス可能なカラム
    protected $accessible = array(
    		'Administer' => array(
    				'USR_ID',
    				'NAME',
    				'NAME_KANA',
    				'UNIT',
    				'MAIL',
    				'LOGIN_ID',
    				'EDIT_PASSWORD',
    				'EDIT_PASSWORD1',
    				'STATUS',
    				'AUTHORITY',
    				'PASSWORD',
    				'CMP_ID',
    				'ADD_DATE',
    				'LAST_UPDATE',
    		),
    );
    
    // バリデーション（入力チェック）の設定
    var $validate = array(
        'NAME' => array(
            "rule0" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
            ),
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '名前は必須項目です'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    30
                ),
                'message' => '名前が長すぎます'
            )
        ),
        'NAME_KANA' => array(
            "rule2" => array(
                'rule' => array(
                    'maxLengthJP',
                    60
                ),
                'message' => '名前カナが長すぎます'
            ),
            "rule3" => array(
                'rule' => array(
                    'katakanaSpace'
                ),
                'message' => '名前カナに入力できない値があります。'
            ),
            "rule4" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
            )
        ),
        'UNIT' => array(
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    30
                ),
                'message' => '部署名が長すぎます'
            )
        ),
        'MAIL' => array(
            "rule3" => array(
                'rule' => array(
                    'maxLengthJP',
                    255
                ),
                'message' => 'メールアドレスが長すぎます'
            )
        ),
        'LOGIN_ID' => array(
            "rule0" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
            ),
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => 'ログインIDは必須項目です'
            ),
            "rule2" => array(
                'rule' => array(
                    'betweenJP',
                    5,
                    10
                ),
                'message' => 'ログインIDは5～10文字で入力してください'
            ),
            "rule3" => array(
                'rule' => array(
                    'idNumber'
                ),
                'message' => '使用できな文字が含まれています。'
            )
        ),
        'EDIT_PASSWORD' => array(
            'rule1' => array(
                'rule' => array(
                    'password_valid',
                    'EDIT_PASSWORD',
                    4,
                    20
                ),
                'message' => 'パスワードは4～20文字で入力してください。'
            ),
            'rule2' => array(
                'rule' => array(
                    'compare2fields',
                    'EDIT_PASSWORD1'
                ),
                'message' => 'パスワードとパスワード確認が一致しません'
            )
        ),
        'EDIT_PASSWORD1' => array(
            'rule1' => array(
                'rule' => array(
                    'password_valid',
                    'EDIT_PASSWORD1',
                    4,
                    20
                ),
                'message' => 'パスワード確認は4～20文字で入力してください。'
            ),
            'rule2' => array(
                'rule' => array(
                    'compare2fields',
                    'EDIT_PASSWORD'
                ),
                'message' => 'パスワードとパスワード確認が一致しません'
            )
        )
    );

    /*
     * データの書き込み処理 @param array $_param @param array $_perror @param char $_state @return $result
     */
    function set_data($_param, $_error, $_state = '')
    {
        if (isset($_param['Administer']['USR_ID'])) {
            $this->id = $_param['Administer']['USR_ID'];
        } else {
            // 時間のセット
            $_param['Administer']['ADD_DATE'] = date("Y-m-d H:i:s");
        }
        $_param['Administer']['LAST_UPDATE'] = date("Y-m-d H:i:s");
        
        if ($_state == 'edit') {
            if ($_param['Administer']['EDIT_PASSWORD'] == NULL) {
                $_param['Administer']['PASSWORD'] = $_param['Administer']['PASSWORD_NOW'];
                unset($_param['Administer']['EDIT_PASSWORD']);
                unset($_param['Administer']['EDIT_PASSWORD1']);
            }
        }
        
        $this->set($_param['Administer']);
        if ($this->validates() && $_error['LOGIN_ID'] == 0 && $_error['PASSWORD'] == 0 && $_error['MAIL'] == 0) {
            if ($this->save($this->permit_params($_param['Administer']))) {
                $_param['Administer']['USR_ID'] = $this->getInsertID();
                return $_param;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /*
     * ユーザ情報の取得 @param array $_usr_ID @return array $result
     */
    function edit_select($_usr_ID)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'USR_ID' => $_usr_ID
            )
        ));
        
        $result['Administer']['PASSWORD'] = NULL;
        
        return $result;
    }
    
    /*
     * index削除用メソッド @param array $_param @return boolean
     */
    function index_delete($_param)
    {
        $param = array();
        
        // 削除する項目をピックアップ
        if (is_array($_param)) {
            foreach ($_param['Administer'] as $key => $value) {
                if ($value == 1) {
                    $data = array(
                        'USR_ID' => $key
                    );
                    $param[]['Administer'] = $data;
                }
            }
        }
        if ($param) {
            // 削除処理
            return $this->saveAll($param);
        } else {
            return false;
        }
    }
}
