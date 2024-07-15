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
class Personal extends AppModel
{

    var $name = 'Personal';

    var $useTable = 'T_USER';

    var $primaryKey = 'USR_ID';
    
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
    
    protected $accessible = array(
    	'Personal' => array(
    		'USR_ID',
	    	'EDIT_PASSWORD',
	    	'EDIT_PASSWORD1',
    		'PASSWORD',
    		'RANDOM_KEY',
    		'LAST_UPDATE',
    	),
    );
    
    
}
