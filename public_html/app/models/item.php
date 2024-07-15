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
class Item extends AppModel
{

    var $name = 'Item';

    var $primaryKey = 'ITM_ID';

    var $useTable = 'T_ITEM';
    
    var $virtualFields = array(
        'UNIT_PRICE' => 'CAST(Item.UNIT_PRICE AS SIGNED)'
    );

    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'USR_ID'
        )
    );

    var $actsAs = array(
        'Cakeplus.AddValidationRule',
        'CustomValidation'
    );
    protected $accessible = array(
            'Item' => array(
                'ITM_ID',
                'ITEM',
                'ITEM_KANA',
                'ITEM_CODE',
                'UNIT',
                'UNIT_PRICE',
                'TAX_CLASS',
                'USR_ID',
                'UPDATE_USR_ID',
                'INSERT_DATE',
                'LAST_UPDATE',
            ),
    );
    var $validate = array(
        'ITEM' => array(
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '商品名は必須項目です'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    40
                ),
                'message' => '商品名が長すぎます'
            )
        ),
        'ITEM_CODE' => array(
            "rule1" => array(
                'rule' => array(
                    'maxLengthJP',
                    8
                ),
                'message' => '商品コードが長すぎます'
            )
        ),
        'ITEM_KANA' => array(
            "rule1" => array(
                'rule' => array(
                    'maxLengthJP',
                    50
                ),
                'message' => '商品名カナが長すぎます'
            ),
            "rule2" => array(
                'rule' => array(
                    'katakanaSpace'
                ),
                'message' => '商品名カナに入力できない値があります。'
            ),
            "rule3" => array(
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
                    4
                ),
                'message' => '単位が長すぎます'
            )
        ),
        'UNIT_PRICE' => array(
            "rule2" => array(
                'rule' => array(
                    'maxLengthFJP',
                    8
                ),
                'message' => '価格が長すぎます'
            ),
            "rule3" => array(
                'rule' => array(
                    'Numberonly'
                ),
                'message' => '半角数字以外使用できません'
            )
        )
    );
    
    // 検索条件
    var $searchColumnAry = array(
        'ITEM' => array(
            'Item.ITEM',
            'Item.ITEM_KANA'
        ),
    );
    // order by
    var $order = array(
        'ITM_ID DESC'
    );
    
    // データの書き込み処理
    function set_data($_param)
    {
        if (isset($_param['Item']['ITM_ID'])) {
            $this->id = $_param['Item']['ITM_ID'];
        } else {
            // 時間のセット
            $_param['Item']['INSERT_DATE'] = date("Y-m-d H:i:s");
        }
        $_param['Item']['LAST_UPDATE'] = date("Y-m-d H:i:s");
        
        $param = $this->save($this->permit_params($_param['Item']));
        
        if (! $param) {
            $param['error'] = $this->invalidFields();
        }
        
        if (! isset($param['Item']['ITM_ID'])) {
            $param['Item']['ITM_ID'] = $this->getInsertID();
        }
        
        return $param;
    }
    // アイテム情報の取得
    function edit_select($_item_ID)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'ITM_ID' => $_item_ID
            )
        ));
        
        return $result;
    }
    
    // 小数以下情報を取得
    function get_decimal($_company_id)
    {
        App::import('Model', 'Company');
        $Company = new Company();
        
        $result = $Company->find('all', array(
            'fields' => array(
                'DECIMAL_UNITPRICE'
            ),
            'conditions' => array(
                'CMP_ID' => $_company_id
            )
        ));
        
        if (! $result)
            return false;
        
        return $result[0]['Company'];
    }
    
    // 削除処理
    function index_delete($_param)
    {
        $param = array();
        
        // 削除する項目をピックアップ
        if (is_array($_param)) {
            foreach ($_param['Item'] as $key => $value) {
                if ($value == 1) {
                    $param[] = $key;
                }
            }
        }
        
        if ($param) {
            
            // 削除処理
            return $this->deleteAll(array(
                'ITM_ID' => $param
            ));
        } else {
            return false;
        }
    }
}
?>
