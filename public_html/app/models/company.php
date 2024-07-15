<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 内容:自社情報登録・編集のmodelクラス
 */
class Company extends AppModel
{

    var $name = 'Company';

    var $useTable = 'T_COMPANY';

    var $primaryKey = 'CMP_ID';
    
    // プラグイン読み込み
    var $actsAs = array(
        'Cakeplus.AddValidationRule',
        'CustomValidation'
    );
    
    // アクセス可能なカラム
    protected $accessible = array(
    	'Company' => array(
    		'CMP_ID',
    		'NAME',
    		'REPRESENTATIVE',
    		'POSTCODE1',
    		'POSTCODE2',
    		'CNT_ID',
    		'ADDRESS',
    		'BUILDING',
    		'PHONE_NO1',
    		'PHONE_NO2',
    		'PHONE_NO3',
    		'FAX_NO1',
    		'FAX_NO2',
    		'FAX_NO3',
    		'INVOICE_NUMBER',
    		'HONOR_CODE',
    		'HONOR_TITLE',
    		'SEAL',
    		'DEL_SEAL',
    		'CMP_SEAL_FLG',
    		'CUTOOFF_SELECT',
    		'CUTOOFF_DATE',
    		'PAYMENT_MONTH',
    		'PAYMENT_SELECT',
    		'PAYMENT_DAY',
    		'DECIMAL_QUANTITY',
    		'DECIMAL_UNITPRICE',
    		'EXCISE',
    		'TAX_FRACTION',
    		'TAX_FRACTION_TIMING',
    		'FRACTION',
    		'ACCOUNT_HOLDER',
    		'BANK_NAME',
    		'BANK_BRANCH',
    		'ACCOUNT_TYPE',
    		'ACCOUNT_NUMBER',
    		'COLOR',
    		'DIRECTION',
    		'SERIAL_NUMBER',
    		'CMP_ID',
    		'SEARCH_ADDRESS',
    		'INSERT_DATE',
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
                'message' => '自社名は必須項目です'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    30
                ),
                'message' => '自社名が長すぎます'
            )
        ),
        
        'CNT_ID' => array(
            "rule1" => array(
                'rule' => array(
                    'range',
                    0,
                    48
                ),
                'message' => '都道府県を選択してください'
            )
        ),
        
        'REPRESENTATIVE' => array(
            "rule1" => array(
                'rule' => array(
                    'maxLengthW',
                    30
                ),
                'message' => '代表者名が長すぎます'
            )
        ),
        'ADDRESS' => array(
            "rule0" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
            ),
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '住所は必須項目です'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    50
                ),
                'message' => '住所が長すぎます'
            )
        ),
        'BUILDING' => array(
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    50
                ),
                'message' => '建物名が長すぎます'
            )
        ),
        'POSTCODE1' => array(
            "rule0" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => '正しい郵便番号を入力してください'
            ),
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '郵便番号は必須項目です'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthJP',
                    3
                ),
                'message' => '正しい郵便番号を入力してください'
            ),
            "rule3" => array(
                'rule' => 'Numberonly',
                'message' => '正しい郵便番号を入力してください'
            )
        ),
        'POSTCODE2' => array(
            "rule0" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
            ),
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '郵便番号は必須項目です'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthJP',
                    4
                ),
                'message' => '正しい郵便番号を入力してください'
            ),
            "rule3" => array(
                'rule' => 'Numberonly',
                'message' => '正しい郵便番号を入力してください'
            )
        ),
        'CUTOOFF_DATE' => array(
            "rule1" => array(
                'rule' => array(
                    'RadioPairTxt',
                    'CUTOOFF_SELECT',
                    1
                ),
                'message' => '日付を入力してください'
            ),
            "rule2" => array(
                'rule' => 'Numberonly',
                'message' => '正しい日付を入力してください'
            ),
            "rule3" => array(
                'rule' => array(
                    'range',
                    0,
                    32
                ),
                'message' => '正しい日付を入力してください',
                'allowEmpty' => true
            )
        ),
        'PAYMENT_DAY' => array(
            "rule1" => array(
                'rule' => array(
                    'RadioPairTxt',
                    'PAYMENT_SELECT',
                    1
                ),
                'message' => '日付を入力してください'
            ),
            "rule2" => array(
                'rule' => 'Numberonly',
                'message' => '正しい日付を入力してください'
            ),
            "rule3" => array(
                'rule' => array(
                    'range',
                    0,
                    32
                ),
                'message' => '正しい日付を入力してください',
                'allowEmpty' => true
            )
        ),
        'ACCOUNT_NUMBER' => array(
            "rule0" => array(
                'rule' => 'Numberonly',
                'message' => '正しい口座番号を入力してください'
            ),
            "rule1" => array(
                'rule' => array(
                    'maxLengthJP',
                    7
                ),
                'message' => '正しい口座番号を入力してください'
            )
        ),
        'ACCOUNT_HOLDER' => array(
            "rule1" => array(
                'rule' => array(
                    'maxLengthW',
                    100
                ),
                'message' => '口座名義は100文字までです'
            )
        ),
        'BANK_NAME' => array(
            "rule1" => array(
                'rule' => array(
                    'maxLengthW',
                    100
                ),
                'message' => '銀行名は100文字までです'
            )
        ),
        'BANK_BRANCH' => array(
            "rule1" => array(
                'rule' => array(
                    'maxLengthW',
                    100
                ),
                'message' => '支店名は100文字までです'
            )
        ),
        'HONOR_TITLE' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthW',
                    4
                ),
                'message' => '敬称が長すぎます'
            )
        ),
        'INVOICE_NUMBER' => array(
            "rule0" => array(
                'rule' => array('custom', '/^(^T[1-9]{1}[0-9]{12})?$/i'),
                'message' => '登録番号の形式が違います'
            )
        )
    );

    /**
     * データのインサート処理
     *
     * @param array $_param            
     * @param array $_perror            
     * @param array $_ferror            
     * @return boolean
     */
    function index_set_data($_param, $_perror, $_ferror, $_serror)
    {
        if ($_serror['ERROR']) {
            return false;
        }
        // 検索用住所の作成
        $county = Configure::read('PrefectureCode');
        
        $_param['Company']['SEARCH_ADDRESS'] = "";
        
        if ($_param['Company']['CNT_ID']) {
            $_param['Company']['SEARCH_ADDRESS'] .= $county[$_param['Company']['CNT_ID']];
        }
        
        $_param['Company']['SEARCH_ADDRESS'] .= $_param['Company']['ADDRESS'] . $_param['Company']['BUILDING'];
        
        // 印鑑の最大サイズ
        $limit = Configure::read('ImageSize');
        
        // 時間の追加
        if (! isset($_param['Company']['CMP_ID'])) {
            // 初回更新時のみ
            $_param['Company']['INSERT_DATE'] = date("Y-m-d H:i:s");
        }
        $_param['Company']['LAST_UPDATE'] = date("Y-m-d H:i:s");
        
        // 印鑑の登録
        $imageerror = 0;
        
        App::import('Vendor', 'model/other');
        $other = new OtherModel();
        
        $other->Image_Create($_param, 'Company', $imageerror);
        
        // トランザクションの開始
        $dataSource = $this->getDataSource();
        $dataSource->begin($this);
        
        // DB登録
        if ($this->save($this->permit_params($_param))) {
            if ($_perror != 1 && $_ferror != 1 && empty($imageerror)) {
                $dataSource->commit($this);
                return true;
            } else {
                // 失敗
                $dataSource->rollback($this);
                if (! empty($imageerror)) {
                    return $imageerror;
                }
                return false;
            }
        } else {
            $dataSource->rollback($this);
            if (! empty($imageerror)) {
                return $imageerror;
            }
            return false;
        }
    }

    /**
     * データの取得処理
     *
     * @param array $_companyID            
     * @return $result
     */
    function index_select($_companyID)
    {
        $result = $this->read(null, $_companyID);
        
        // 存在しない場合
        if (! $result)
            return null;
        
        return $result;
    }

    /**
     * 画像の取得処理
     *
     * @param array $_companyID            
     * @return $result
     */
    function get_image($_companyID)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'CMP_ID' => $_companyID
            ),
            'fields' => 'Company.SEAL'
        ));
        
        // 存在しない場合
        if (! $result)
            return null;
        
        return $result['Company']['SEAL'];
    }

    /**
     * 印鑑の削除
     *
     * @param array $_companyID            
     * @return bool
     */
    function seal_delete($_companyID)
    {
        $result = $this->read(null, $_companyID);
        $result['Company']['SEAL'] = NULL;
        if ($result) {
            // 削除処理
            return $this->saveAll($result);
        } else {
            return false;
        }
    }
    
    // 押印設定の取得
    function getSealFlg($_companyID = 1)
    {
        $result = $this->find('first', array(
            'fields' => array(
                'Company.CMP_SEAL_FLG'
            ),
            'conditions' => array(
                'Company.CMP_ID' => $_companyID
            )
        ));
        
        return $result['Company']['CMP_SEAL_FLG'];
    }
}
?>
