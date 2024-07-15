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
class Receipt extends AppModel
{

    var $name = 'Receipt';

    var $useTable = false;

    var $_schema = array(
        'CST_ID' => array(
            'type' => 'string',
            'length' => 50
        ),
        'TOTAL' => array(
            'type' => 'int',
            'length' => 20
        ),
        'DATE' => array(
            'type' => 'date'
        ),
        'RECEIPT_NUMBER' => array(
            'type' => 'string'
        ),
        'PROVISO' => array(
            'type' => 'string'
        )
    );
    
    // バリデーション
    var $actsAs = array(
        'Cakeplus.AddValidationRule',
        'CustomValidation'
    );

    var $validate = array(
        'CST_ID' => array(
            "rule0" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
            ),
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '企業名は必須項目です'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    30
                ),
                'message' => '企業名が長すぎます'
            )
        ),
        'TOTAL' => array(
            "rule0" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
            ),
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '金額は必須項目です'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthJP',
                    16
                ),
                'message' => '金額が長すぎます'
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
        'RECEIPT_NUMBER' => array(
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '領収書番号は必須項目です'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthJP',
                    20
                ),
                'message' => '領収書番号は20桁までです'
            ),
            "rule3" => array(
                'rule' => array(
                    'manageNumber'
                ),
                'message' => '使用できない文字が含まれています'
            )
        ),
        'PROVISO' => array(
            "rule0" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
            ),
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '但書きは必須項目です'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    20
                ),
                'message' => '但書きが長すぎます'
            )
        )
    );
}