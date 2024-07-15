<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */
/**
 * 内容:自社情報登録・編集のcontrollerクラス
 */
class CompaniesController extends AppController
{

    var $name = "Company";

    var $uses = array(
        'Company',
        'Serial'
    );

    var $autoLayout = true;

    var $helpers = array(
        'Html',
        'Form',
        'Ajax',
        'Javascript'
    );

    function beforeFilter()
    {
        parent::beforeFilter();
        // pdf出力時の画像出力の為、認証チェックには入れない
        $this->Auth->allow('contents');
    }
    
    // メイン用
    function index()
    {
        $this->set("main_title", "自社情報設定確認");
        $this->set("title_text", "自社情報設定");
        
        $company_ID = 1;
        
        // 自社情報の取得
        $this->data = $this->Company->index_select($company_ID);
        
        // 画像をセット
        if (isset($this->data['Company']['SEAL']) && $this->data['Company']['SEAL']) {
            $image = $this->data['Company']['SEAL'];
            $this->set("image", $image);
        }
        
        // 色設定
        $color = array();
        foreach (Configure::read('ColorCode') as $key => $value) {
            $color[$key] = $value['name'];
        }
        
        // ビューにセット
        $this->set("account_type", Configure::read('AccountTypeCode'));
        $this->set("countys", Configure::read('PrefectureCode'));
        $this->set("payment", Configure::Read('PaymentMonth'));
        $this->set("direction", Configure::read('DirectionCode'));
        $this->set("cutooff_select", array(
            0 => '末日',
            1 => '指定'
        ));
        $this->set("payment_select", array(
            0 => '末日',
            1 => '指定'
        ));
        $this->set("decimals", Configure::read('DecimalCode'));
        $this->set("excises", Configure::read('ExciseCode'));
        $this->set("fractions", Configure::read('FractionCode'));
        $this->set("tax_fraction_timing", Configure::read('TaxFractionTimingCode'));
        $this->set("colors", $color);
        $this->set("numbering_format", Configure::read('NumberingFormat'));
        $this->set("honor", Configure::read('HonorCode'));
        $this->set("serial_option", Configure::read('Serial'));
        $this->set("param", $this->data);
        $this->set('seal_flg', Configure::read('SealFlg'));
    }

    function edit()
    {
        $this->set("main_title", "自社情報設定");
        $this->set("title_text", "自社情報設定");
        
        $company_ID = 1;
        $phone_error = 0;
        $fax_error = 0;
        $image_error = 0;
        $serial_error = 0;
        
        // キャンセルボタンを押された場合、一覧にリダイレクト
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect("/companies");
        }
        
        if (isset($this->params['data'])) {
            // トークンチェック
            $this->isCorrectToken($this->data['Security']['token']);
            
            // 更新時処理
            if ($this->params['data']['Company']['DEL_SEAL'] != 0) {
                $this->Company->seal_delete($company_ID);
            }
            
            // 電話番号のバリデーション
            $phone_error = $this->phone_validation($this->params['data']['Company'], 'Company');
            
            // FAX番号のバリデーション
            $fax_error = $this->fax_validation($this->params['data']['Company']);
            
            // 連番情報のバリデーション
            $serial_error = $this->serial_validation($this->data['SERIAL']);
            
            if ($this->params['data']['Company']['HONOR_CODE'] != 2) {
                $this->params['data']['Company']['HONOR_TITLE'] = "";
            }
            
            // 連番情報のインサート
            $this->Serial->set_data($this->params['data']['SERIAL']);
            
            // データのインサート
            $result = $this->Company->index_set_data($this->params['data'], $phone_error, $fax_error, $serial_error);
            
            if ($result) {
                if ($result === 1 || $result === 2 || $result === 3) {
                    // 画像登録失敗
                    $image_error = $result;
                    $image = $this->Company->get_image($company_ID);
                    if ($image) {
                        $this->set("image", $image);
                    }
                } else {
                    // 成功
                    $this->Session->setFlash('自社情報設定を保存しました');
                    $this->redirect("/companies");
                }
            } else {
                // 失敗
            }
        } else {
            // 通常時処理
            // 自社情報の取得
            $this->data = $this->Company->index_select($company_ID);
            $this->data['SERIAL'] = $this->Serial->get_data();
        }
        
        // 画像をセット
        if (isset($this->data['Company']['SEAL']) && $this->data['Company']['SEAL']) {
            $image = $this->data['Company']['SEAL'];
            $this->set("image", $image);
        }
        
        // 小数点処理
        $decimal = array(
            'type' => 'radio',
            'options' => Configure::read('DecimalCode'),
            'div' => false,
            'label' => false,
            'legend' => false,
            'style' => 'margin-right: 5px; margin-left: 10px;',
            'class' => 'txt_mid'
        );
        
        // 消費税
        $excise = array(
            'type' => 'radio',
            'options' => Configure::read('ExciseCode'),
            'div' => false,
            'label' => false,
            'legend' => false,
            'style' => 'margin-right: 5px; margin-left: 10px;',
            'class' => 'txt_mid'
        );
        
        // 端数処理
        $fraction = array(
            'type' => 'radio',
            'options' => Configure::read('FractionCode'),
            'div' => false,
            'label' => false,
            'legend' => false,
            'style' => 'margin-right: 5px; margin-left: 10px;',
            'class' => 'txt_mid'
        );
        
        // 消費税端数計算
        $tax_fraction_timing = array(
        		'type' => 'radio',
        		'options' => Configure::read('TaxFractionTimingCode'),
        		'div' => false,
        		'label' => false,
        		'legend' => false,
        		'style' => 'margin-right: 10px; margin-left: 8px;',
        		'class' => 'txt_mid'
        );
        
        
        // 色設定
        $color = array();
        foreach (Configure::read('ColorCode') as $key => $value) {
            $color[$key] = $value['name'];
        }
        
        // 締日処理
        $cutooff_select = array(
            'type' => 'radio',
            'options' => array(
                0 => '末日',
                1 => '指定'
            ),
            'div' => false,
            'label' => false,
            'legend' => false,
            'style' => 'margin-right: 5px; margin-left: 10px;',
            'class' => 'txt_mid'
        );
        
        // 支払い日処理
        $payment_select = array(
            'type' => 'radio',
            'options' => array(
                0 => '末日',
                1 => '指定'
            ),
            'div' => false,
            'label' => false,
            'legend' => false,
            'style' => 'margin-right: 5px; margin-left: 10px;',
            'class' => 'txt_mid'
        );
        
        // ビューにセット
        $this->set("numbering_format", Configure::read('NumberingFormat'));
        $this->set("serial", Configure::read('Serial'));
        $this->set("account_type", Configure::read('AccountTypeCode'));
        $this->set("countys", Configure::read('PrefectureCode'));
        $this->set("payment", Configure::Read('PaymentMonth'));
        $this->set("direction", Configure::read('DirectionCode'));
        $this->set("cutooff_select", $cutooff_select);
        $this->set("payment_select", $payment_select);
        $this->set("perror", $phone_error);
        $this->set("ierror", $image_error);
        $this->set("ferror", $fax_error);
        $this->set("serror", $serial_error);
        $this->set("decimals", $decimal);
        $this->set("excises", $excise);
        $this->set("fractions", $fraction);
        $this->set("tax_fraction_timing", $tax_fraction_timing);
        $this->set("colors", $color);
        $this->set("honor", Configure::read('HonorCode'));
        $this->set('seal_flg', Configure::read('SealFlg'));
    }
    
    // 画像表示用
    function contents()
    {
        $company_ID = 1;
        $this->layout = false;
        $this->autoRender = false;
        $this->data = $this->Company->index_select($company_ID);
        
        $image = $this->data['Company']['SEAL'];
        
        if (empty($image)) {
            $this->cakeError('error404');
        }
        
        header("Content-type: image/png");
        echo $image;
    }
}