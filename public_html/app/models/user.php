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
class User extends AppModel
{

    var $name = 'User';

    var $useTable = 'T_USER';

    var $primaryKey = 'USR_ID';

    var $components = array(
        'Auth'
    );
    
    // バリデーション
    var $actsAs = array(
        'Cakeplus.AddValidationRule'
    );

    var $validate = array(
        'EDIT_PASSWORD' => array(
            'rule0' => array(
                'rule' => array(
                    'password_valid',
                    'EDIT_PASSWORD',
                    4,
                    20
                ),
                'message' => 'パスワードは4～20文字で入力してください。'
            ),
            'rule1' => array(
                'rule' => array(
                    'compare2fields',
                    'EDIT_PASSWORD1'
                ),
                'message' => 'パスワードとパスワード確認が一致しません'
            )
        )
        ,
        'EDIT_PASSWORD1' => array(
            'rule0' => array(
                'rule' => array(
                    'password_valid',
                    'EDIT_PASSWORD1',
                    4,
                    20
                ),
                'message' => 'パスワード確認は4～20文字で入力してください。'
            ),
            'rule1' => array(
                'rule' => array(
                    'compare2fields',
                    'EDIT_PASSWORD'
                ),
                'message' => 'パスワードとパスワード確認が一致しません'
            )
        )
    );

    function hashPasswords($data, $enforce = false)
    {
        if ($enforce && isset($this->data[$this->alias]['password'])) {
            if (! empty($this->data[$this->alias]['password'])) {
                $this->data[$this->alias]['password'] = Security::hash($this->data[$this->alias]['password'], null, true);
            }
        }
        return $data;
    }

    function beforeSave()
    {
        $this->hashPasswords(null, true);
        return true;
    }

    function regist_user($param, $pass)
    {
        $datetime = date("Y-m-d H:i:s");
        $data['User']['LOGIN_ID'] = 'admin'; // admin固定
        $data['User']['NAME'] = '管理者'; // 管理者固定
        $data['User']['MAIL'] = $param['mail'];
        $data['User']['PASSWORD'] = $pass;
        $data['User']['CMP_ID'] = 1;
        $data['User']['ADD_DATE'] = $datetime;
        $data['User']['LAST_UPDATE'] = $datetime;
        // トランザクションの開始
        $dataSource = $this->getDataSource();
        $dataSource->begin($this);
        // DB登録
        if ($this->save($data)) {
            $dataSource->commit($this);
            return true;
        } else {
            $dataSource->rollback($this);
            return false;
        }
    }

    function SearchUserID($_LoginID, $_usrID)
    {
        if (mb_strlen($_LoginID) > 0) {
            if (! preg_match("/^[a-zA-Z0-9-]*$/", $_LoginID)) {
                return 4;
            }
            if (mb_strlen($_LoginID) < 5) {
                return 2;
            }
            if (mb_strlen($_LoginID) > 10) {
                return 3;
            }
            if ($_usrID == NULL) {
                $result = $this->find('first', array(
                    'conditions' => array(
                        'LOGIN_ID' => $_LoginID
                    )
                ));
            } else {
                $result = $this->find('first', array(
                    'conditions' => array(
                        'LOGIN_ID' => $_LoginID,
                        'NOT' => array(
                            'USR_ID' => $_usrID
                        )
                    )
                ));
            }
            if ($result) {
                return 0;
            }
            return 1;
        }
        return 5;
    }
}
