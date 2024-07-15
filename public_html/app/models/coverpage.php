<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 */
class Coverpage extends AppModel
{

    var $name = 'Coverpage';

    var $useTable = false;

    var $_schema = array(
        'CUSTOMER_NAME' => array(
            'type' => 'string',
            'length' => 255
        ),
        'CUSTOMER_CHARGE_NAME' => array(
            'type' => 'string',
            'length' => 255
        ),
        'CUSTOMER_CHARGE_UNIT' => array(
            'type' => 'string',
            'length' => 255
        ),
        'CHARGE_NAME' => array(
            'type' => 'string',
            'length' => 255
        ),
        'DATE' => array(
            'type' => 'date'
        ),
        'UNIT' => array(
            'type' => 'string',
            'length' => 250
        )
    );
    
    // バリデーション
    var $actsAs = array(
        'Cakeplus.AddValidationRule',
        'CustomValidation'
    );

    var $validate = array(
        'CUSTOMER_NAME' => array(
            "rule0" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
            ),
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '顧客名は必須項目です'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    31
                ),
                'message' => '顧客名は30文字以内で入力してください'
            )
        ),
        'CUSTOMER_CHARGE_NAME' => array(
            "rule0" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    31
                ),
                'message' => '顧客担当者名は30文字以内で入力してください'
            )
        ),
        'CUSTOMER_CHARGE_UNIT' => array(
            "rule0" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    30
                ),
                'message' => '担当者部署名は30文字以内で入力してください'
            )
        ),
        'CHARGE_NAME' => array(
            "rule0" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    31
                ),
                'message' => '自社担当者名は30文字以内で入力してください'
            )
        ),
        'DATE' => array(
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '発行日は必須項目です'
            ),
            "rule0" => array(
                'rule' => array(
                    'date'
                ),
                'message' => '有効な日付ではありません'
            )
        ),
        'TITLE' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthW',
                    20
                ),
                'message' => '件名は20文字以内で入力してください'
            )
        ),
        'CONTACT' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthW',
                    300
                ),
                'message' => '連絡事項は300文字以内で入力してください'
            ),
            "rule1" => array(
                'rule' => array(
                    'maxBreakW',
                    55,
                    6
                ),
                'message' => '行数または文字数が多すぎます'
            )
        )
    );
}