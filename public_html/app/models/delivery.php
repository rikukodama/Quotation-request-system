<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 内容:納品書登録・編集のmodelクラス
 */
class Delivery extends AppModel
{

    var $name = 'Delivery';

    var $useTable = 'T_DELIVERY';

    var $primaryKey = 'MDV_ID';

    // アクセス可能なカラム
    protected $accessible = array(
    	'Delivery' => array(
    		'MDV_ID',
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
    		'EXCISE',
    		'TAX_FRACTION',
    		'TAX_FRACTION_TIMING',
    		'FRACTION',
    		'SUBTOTAL',
    		'SALES_TAX',
    		'TOTAL',
    		'STATUS',
    		'DELIVERY',
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


    // バリデーション
    var $actsAs = array(
        'Cakeplus.AddValidationRule',
        'CustomValidation'
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
            'Deliveryitem' => array(
            'className' => 'Deliveryitem',
            'type' => '',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'MDV_ID',
        )
    );

    var $virtualFields = array(
        'MDV_ID' => 'Delivery.MDV_ID',
        'CAST_TOTAL' => 'CAST(Delivery.TOTAL AS SIGNED)'
    );

    // 検索条件
    var $searchColumnAry = array(
        'MDV_ID' => 'Delivery.MDV_ID',
        'NO' => 'Delivery.NO',
        'ACTION_DATE_FROM' => 'Delivery.ACTION_DATE_FROM',
        'ACTION_DATE_TO' => 'Delivery.ACTION_DATE_TO',
        'SUBJECT' => 'Delivery.SUBJECT',
        'STATUS' => 'Delivery.STATUS',
        'NAME' => 'Customer.NAME',
        'USR_NAME' => 'User.NAME',
        'UPD_USR_NAME' => 'UpdateUser.NAME',
        'CHR_USR_NAME' => 'Charge.CHARGE_NAME',
        'ITEM_NAME' => 'Deliveryitem.ITEM',
        'ITEM_CODE' => 'Deliveryitem.ITEM_CODE',
        'NOTE' => 'Delivery.NOTE',
        'MEMO' => 'Delivery.MEMO',
        'TOTAL_FROM' => 'TOTAL_FROM',
        'TOTAL_TO' => 'TOTAL_TO',
    );
    // order by
    var $order = array(
        'MDV_ID DESC'
    );
    var $groupBy = array(
        'MDV_ID'
    );
    // index削除用メソッド
    function index_delete($_param)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Delete_Replication_Data($_param, 'Delivery', 'MDV_ID');
    }

    // チェックされている項目を取得
    function reproduce_check($_param, $auto_serial = true, $model_from = 'Delivery')
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();
        unset($_param['Delivery']['STATUS_CHANGE']);
        // コピーする項目が数字でないものが一つでもある場合は処理を不許可
        foreach($_param['Delivery'] as $key => $val){
            if(!preg_match("/^[0-9]+$/",$key)){
                return false;
            }
        }


        // コピーする項目のIDを整理
        $form->Sort_Replication_ID($_param, 'Delivery');
        if (! $_param)
            return false;

            // コピー用データを取得
        $_param = $this->find('all', array(
            'conditions' => array(
                'Delivery.MDV_ID IN (' . implode(',', $_param) . ')'
            ),
            'group' => array('Delivery.MDV_ID'),
        ));
        if (! $_param)
            return false;

            // コピー用項目の情報を取得
        $form->Get_Replication_Item($_param, 'Delivery', $auto_serial, $model_from);
        if (! $_param)
            return false;

        return $_param;
    }

    function insert_reproduce($_param, $_user_id)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        $Table_before = "Table";
        $Table_after = "Delivery";
        $Item_before = "Item";
        $Item_after = "Deliveryitem";

        // コピーする項目のデータの整理
        $form->Sort_Replication_Data($_param, $Table_before, $Table_after, $Item_before, $Item_after);
        if (! $_param)
            return false;

            // asort($_param);

        return $form->Copy_Replication_Data($_param, 'Delivery', 'MDV_ID', $Item_after, $_user_id);
    }

    // 納品書の取得
    function edit_select($_delivery_ID, &$count = null)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Edit_Select($_delivery_ID, 'Delivery', 'MDV_ID', $count);
    }

    // 顧客情報
    function get_customer($_company_id, $_condition)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Get_Customer($_company_id, $_condition);
    }

    // 顧客支払い情報
    function get_payment($_company_id)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Get_Payment($_company_id);
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

    // データの登録
    function set_data($_param, $_state = null, $_error)
    {
        $this->set($_param);
        if (! $this->validates()) {
            return false;
        }

        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Set_Replication_Data($_param, 'Delivery', $_state, $_error);
    }

    // プレビュー表示用データの取得
    function preview_data($_delivery_ID, &$_items = null, &$_discounts = null)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Get_Preview_Data($_delivery_ID, 'Delivery', $_items, $_discounts);
    }

    function send_mail($_param)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Send_Mail($_param, 'Delivery');
    }

    function get_for_mail_param($_delivery_id, &$_customer_charge = null, &$_charge = null)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Get_Mail_Param($_delivery_id, 'Delivery', $_customer_charge, $_charge);
    }

    // EXCEL形式での出力用
    function export($_param, &$error, $_type = 'term', $_user_auth = null, $_user_id = null)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Export_Excel('Delivery', $_param, $error, $_type, $_user_auth, $_user_id);
    }

    function get_user($_id)
    {
        App::import('Vendor', 'model/form');
        $form = new FormModel();

        return $form->Get_User_Data('Delivery', $_id);
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
        9 => "納入場所"
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
        if ($DiscountCodeNone == $data["Delivery"]["DISCOUNT_TYPE"]) {
            return;
        }

        unset($data["Delivery"]);
        unset($data["Security"]);

        $prevTaxRate = null;
        foreach ($data as $key => $item) {
            if ($item["Deliveryitem"]["LINE_ATTRIBUTE"] != 0)
                continue;
            if ($item["Deliveryitem"]["TAX_CLASS"] == $TaxClassFree)
                continue;

            $taxRate = intval($item["Deliveryitem"]["TAX_CLASS"] / 10);

            if (is_null($prevTaxRate)) {
                $prevTaxRate = $taxRate;
            } elseif ($prevTaxRate != $taxRate) {
                $this->invalidate("DISCOUNT");
                return $DiscountCodeError;
            }
        }
    }
}