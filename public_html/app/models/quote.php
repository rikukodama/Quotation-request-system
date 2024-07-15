<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 内容:見積書登録・編集のmodelクラス
 */
class Quote extends AppModel
{

    var $name = 'Quote';

    var $useTable = 'T_QUOTE';

    var $primaryKey = 'MQT_ID';

    // バリデーション
    var $actsAs = array(
        'Cakeplus.AddValidationRule',
        'CustomValidation'
    );
    // アクセス可能なカラム
    protected $accessible = array(
    		'Quote' => array(
    			'MQT_ID',
    			'NO',
    			'DATE',
    			'SUBJECT',
    			'CUSTOMER_NAME',
    			'CST_ID',
    			'CUSTOMER_CHARGE_NAME',
    			'CHRC_ID',
    			'CHARGE_NAME',
    			'CHR_ID',
    			'HONOR_CODE',
    			'HONOR_TITLE',
    			'CMP_SEAL_FLG',
    			'CHR_SEAL_FLG',
    			'DISCOUNT',
    			'DISCOUNT_TYPE',
    			'DECIMAL_QUANTITY',
    			'DECIMAL_UNITPRICE',
    			'DISCOUNT',
    			'EXCISE',
    			'TAX_FRACTION',
    			'TAX_FRACTION_TIMING',
    			'FRACTION',
    			'SUBTOTAL',
    			'SALES_TAX',
    			'TOTAL',
    			'STATUS',
    			'DEADLINE',
    			'DEAL',
    			'DELIVERY',
    			'DUE_DATE',
    			'NOTE',
    			'MEMO',
    			'USR_ID',
    			'UPDATE_USR_ID',
    			'ISSUE_DATE',
    			'INSERT_DATE',
    			'LAST_UPDATE',
          'FIVE_RATE_TAX',
          'FIVE_RATE_TOTAL',
          'EIGHT_RATE_TAX',
          'EIGHT_RATE_TOTAL',
          'REDUCED_RATE_TAX',
          'REDUCED_RATE_TOTAL',
          'TEN_RATE_TAX',
          'TEN_RATE_TOTAL',
    		),
    );
    var $validate = array(
        'SUBJECT' => array(
            "rule0" => array(
                'rule' => array(
                    'spaceOnly'
                ),
                'message' => 'スペース以外も入力してください'
            ),
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '件名は必須項目です'
            ),
            "rule2" => array(
                'rule' => array(
                    'maxLengthW',
                    40
                ),
                'message' => '件名が長すぎます'
            )
        ),
        'DELIVERY' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthW',
                    20
                ),
                'message' => '納品場所が長すぎます'
            )
        ),
        'CST_ID' => array(
            "rule0" => array(
                'rule' => 'Numberonly',
                'message' => '企業名は必須項目です'
            ),
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '企業名は必須項目です'
            )
        ),
        'CHRC_ID' => array(
            "rule2" => array(
                'rule' => 'Numberonly',
                'message' => '数字以外は入力できません'
            )
        ),
        'NOTE' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthW',
                    300
                ),
                'message' => '備考が長すぎます'
            ),
            "rule1" => array(
                'rule' => array(
                    'maxBreakW',
                    55,
                    6
                ),
                'message' => '行数または文字数が多すぎます'
            )
        ),
        'NO' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthJP',
                    20
                ),
                'message' => '納品書番号が長すぎます'
            ),
            "rule1" => array(
                'rule' => array(
                    'manageNumber'
                ),
                'message' => '使用できない文字が含まれています'
            )
        ),
        'MEMO' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthW',
                    50
                ),
                'message' => 'メモが長すぎます'
            )
        ),
        'DATE' => array(
            "rule0" => array(
                'rule' => array(
                    'date'
                ),
                'message' => '有効な日付ではありません'
            )
        ),
        'DUE_DATE' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthW',
                    20
                ),
                'message' => '有効期限が長すぎます'
            )
        ),
        'DISCOUNT' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthJP',
                    15
                ),
                'message' => '割引が長すぎます'
            ),
            "rule1" => array(
                'rule' => 'Numberonly',
                'message' => '割引は数字のみです'
            )
        ),
        'TAX_FRACTION_TIMING' => array(
            "rule0" => array(
                'rule' => array(
                    'taxFractionTiming',
                ),
                'message' => '選択できるのは明細単位のみです'
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
        'DEADLINE' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthW',
                    20
                ),
                'message' => '納入期限が長すぎます'
            )
        ),
        'DEAL' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthW',
                    20
                ),
                'message' => '取引方法が長すぎます'
            )
        )
    );

    var $belongsTo = array(
        'Customer' => array(
            'className' => 'Customer',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'CST_ID'
        ),
        'User' => array(
            'className' => 'User',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'USR_ID'
        ),
        'UpdateUser' => array(
            'className' => 'User',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'UPDATE_USR_ID'
        ),
        'Charge' => array(
            'className' => 'Charge',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'CHR_ID'
        ),
    );

    var $hasOne = array(
            'Quoteitem' => array(
            'className' => 'Quoteitem',
            'type' => '',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'MQT_ID',
        )
    );

    var $virtualFields = array(
        'MQT_ID' => 'Quote.MQT_ID',
        'CAST_TOTAL' => 'CAST(Quote.TOTAL AS SIGNED)'
    );

    // 検索条件
    var $searchColumnAry = array(
        'MQT_ID' => 'Quote.MQT_ID',
        'NO' => 'Quote.NO',
        'ACTION_DATE_FROM' => 'Quote.ACTION_DATE_FROM',
        'ACTION_DATE_TO' => 'Quote.ACTION_DATE_TO',
        'SUBJECT' => 'Quote.SUBJECT',
        'STATUS' => 'Quote.STATUS',
        'NAME' => 'Customer.NAME',
        'USR_NAME' => 'User.NAME',
        'UPD_USR_NAME' => 'UpdateUser.NAME',
        'CHR_USR_NAME' => 'Charge.CHARGE_NAME',
        'ITEM_NAME' => 'Quoteitem.ITEM',
        'ITEM_CODE' => 'Quoteitem.ITEM_CODE',
        'NOTE' => 'Quote.NOTE',
        'MEMO' => 'Quote.MEMO',
        'TOTAL_FROM' => 'TOTAL_FROM',
        'TOTAL_TO' => 'TOTAL_TO',
    );

    // order by
    var $order = array(
        'MQT_ID DESC'
    );

    var $groupBy = array(
        'MQT_ID'
    );


    /*
     * index削除用メソッド
     */
    function index_delete($_param)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Delete_Replication_Data($_param, 'Quote', 'MQT_ID');
    }

    /*
     * 複製用のチェックされている項目を選択
     */
    function reproduce_check($_param, $auto_serial = true, $model_from = 'Quote')
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        unset($_param['Quote']['STATUS_CHANGE']);

        // コピーする項目が数字でないものが一つでもある場合は処理を不許可
        foreach($_param['Quote'] as $key => $val){
            if(!preg_match("/^[0-9]+$/",$key)){
                return false;
            }
        }

        // コピーする項目のIDを整理
        $form->Sort_Replication_ID($_param, 'Quote');
        if (! $_param)
            return false;

            // コピー用データを取得
        $_param = $this->find('all', array(
            'conditions' => array(
                'Quote.MQT_ID IN (' . implode(',', $_param) . ')'
            ),
            'group' => array('Quote.MQT_ID'),
        ));
        if (! $_param)
            return false;

            // コピー用項目の情報を取得
        $form->Get_Replication_Item($_param, 'Quote', $auto_serial, $model_from);
        if (! $_param)
            return false;

        return $_param;
    }

    /*
     * 複製の登録
     */
    function insert_reproduce($_param, $_user_id)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        $Table_before = "Table";
        $Table_after = "Quote";
        $Item_before = "Item";
        $Item_after = "Quoteitem";

        // コピーする項目のデータの整理
        $form->Sort_Replication_Data($_param, $Table_before, $Table_after, $Item_before, $Item_after);
        if (! $_param)
            return false;

            // asort($_param);

        return $form->Copy_Replication_Data($_param, 'Quote', 'MQT_ID', $Item_after, $_user_id);
    }

    // 見積書の取得
    function edit_select($_quote_ID, &$count = null)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Edit_Select($_quote_ID, 'Quote', 'MQT_ID', $count);
    }

    // 顧客情報
    function get_customer($_company_id, $_condition)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Get_Customer($_company_id, $_condition);
    }

    // 小数以下情報を取得
    function get_decimal($_company_id)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Get_Decimal($_company_id);
    }

    /**
     * 顧客敬称を取得
     *
     * @param array $_company_id
     * @return array $result
     */
    function get_honor($_company_id)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Get_Honor($_company_id);
    }

    // 顧客支払い情報
    function get_payment($_company_id)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Get_Payment($_company_id);
    }

    // 顧客支払い情報
    function get_company_payment($_company_id)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Get_Company_Payment($_company_id);
    }

    // 連番設定情報
    function get_serial($_company_id)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Get_Serial($_company_id);
    }

    // 3ヶ月分の見積額 古いソース？
    public function get_amount_month()
    {

        // 先々月
        $month_before_last = date('Y-m-1', strtotime(date('Y-m-1') . ' -2 month'));
        // 先月
        $last_month = date('Y-m-1', strtotime(date('Y-m-1') . ' -1 month'));
        // 今月
        $this_month = date('Y-m-1');
        // 来月
        $next_month = date('Y-m-1', strtotime(date('Y-m-1') . ' +1 month'));

        // 先々月、先月、今月の情報を取得
        $param = array();
        $param['month_before_last'] = $this->find('all', array(
            'fields' => array(
                'MQT_ID',
                'EXCISE',
                'FRACTION'
            ),
            'conditions' => array(
                'ISSUE_DATE >=' => $month_before_last,
                'ISSUE_DATE <' => $last_month
            )
        ));
        $param['last_month'] = $this->find('all', array(
            'fields' => array(
                'MQT_ID',
                'EXCISE',
                'FRACTION'
            ),
            'conditions' => array(
                'ISSUE_DATE >=' => $last_month,
                'ISSUE_DATE <' => $this_month
            )
        ));
        $param['this_month'] = $this->find('all', array(
            'fields' => array(
                'MQT_ID',
                'EXCISE',
                'FRACTION'
            ),
            'conditions' => array(
                'ISSUE_DATE >=' => $this_month,
                'ISSUE_DATE <' => $next_month
            )
        ));

        // 見積書項目モデルの読み込み
        App::import('Model', 'Quoteitem');
        $Quoteitem = new Quoteitem();

        // データの整理
        $param1 = array();
        foreach ($param as $key => $value) {
            $param1[$key] = null;
            if (is_array($value)) {
                foreach ($value as $key1 => $value1) {
                    $param1[$key][$value1['Quote']['MQT_ID']]['EXCISE'] = $value1['Quote']['EXCISE'];
                    $param1[$key][$value1['Quote']['MQT_ID']]['FRACTION'] = $value1['Quote']['FRACTION'];
                }
            }
        }

        // 金額と件数を取得
        $quote = array();
        if (is_array($param1)) {
            foreach ($param1 as $key => $value) {
                $quote[$key]['money'] = 0;
                $quote[$key]['count'] = 0;
                if (is_array($value)) {
                    foreach ($value as $key1 => $value1) {
                        $quote[$key]['count'] ++;
                        $tmp = $Quoteitem->find('all', array(
                            'fields' => array(
                                'QUANTITY',
                                'UNIT_PRICE'
                            ),
                            'conditions' => array(
                                'QUANTITY <>' => null,
                                'UNIT_PRICE <>' => null,
                                'MQT_ID' => $key1
                            )
                        ));
                        if ($tmp) {
                            $total = 0;
                            foreach ($tmp as $key2 => $value2) {
                                $total += $value2['Quoteitem']['QUANTITY'] * $value2['Quoteitem']['UNIT_PRICE'];
                            }

                            if ($value1['EXCISE'] == 1) {
                                $total = $total * 1.05;
                            }

                            $quote[$key]['money'] += $total;
                        }
                    }
                }
            }
        }

        return $quote;
    }

    // データの登録
    function set_data($_param, $_state = null, $_error)
    {
        $this->set($_param);
        if (! $this->validates()) {
            return false;
        }

        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Set_Replication_Data($_param, 'Quote', $_state, $_error);
    }

    // プレビュー表示用データの取得
    function preview_data($_quote_ID, &$_items = null, &$_discounts = null)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Get_Preview_Data($_quote_ID, 'Quote', $_items, $_discounts);
    }

    function send_mail($_param)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Send_Mail($_param, 'Quote');
    }

    function get_for_mail_param($_quote_id, &$_customer_charge = null, &$_charge = null)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Get_Mail_Param($_quote_id, 'Quote', $_customer_charge, $_charge);
    }

    // EXCEL形式での出力用
    function export($_param, &$error, $_type = 'term', $_user_auth = null, $_user_id = null)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Export_Excel('Quote', $_param, $error, $_type, $_user_auth, $_user_id);
    }

    function get_user($_id)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Get_User_Data('Quote', $_id);
    }

    var $field = array(
        1 => "日付",
        2 => "管理番号",
        3 => "取引先",
        4 => "件名",
        5 => "自社担当者",
        6 => "小計",
        7 => "消費税",
        8 => "合計",
        9 => "納入場所",
        10 => "有効期限"
    );

    // 割引(円)チェック
    // 帳票行に複数の税率が混在している場合は
    // 割引(円)の項目をエラーにする
    function validateDiscount($data)
    {
        // TODO 定数を持ってないので、既存のものを含めどこかで定義しなおす
        $DiscountCodeYen = 1;
        $DiscountCodeNone = 2;

        $DiscountCodeError = 3;
        $TaxClassFree = 3;
        $TaxClassInclusive = 1;
        $TaxClassExclusive = 2;
        $LineAttrNormal = 0;

        // 設定なしの場合はチェックを行わない
        if ($DiscountCodeNone == $data["Quote"]["DISCOUNT_TYPE"]) {
            return;
        }

        unset($data["Quote"]);
        unset($data["Security"]);

        $prevTaxRate = null;
        foreach ($data as $key => $item) {
            if ($item["Quoteitem"]["LINE_ATTRIBUTE"] != 0)
                continue;
            if ($item["Quoteitem"]["TAX_CLASS"] == $TaxClassFree)
                continue;

            $taxRate = intval($item["Quoteitem"]["TAX_CLASS"] / 10);

            if (is_null($prevTaxRate)) {
                $prevTaxRate = $taxRate;
            } elseif ($prevTaxRate != $taxRate) {
                $this->invalidate("DISCOUNT");
                return $DiscountCodeError;
            }
        }
    }
}