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
class Serial extends AppModel
{

    var $name = 'Serial';

    var $useTable = 'T_SERIAL';

    var $primaryKey = 'FORM_ID';
    
    // バリデーション（入力チェック）の設定
    var $validate = array(
        'NUMBERING_FORMAT' => array(
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '付番書式は必須項目です'
            )
        )
        ,
        'PREFIX' => array(
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '接頭文字は必須項目です'
            ),
            "rule2" => array(
                'rule' => array(
                    'manageNumber'
                ),
                'message' => '使用できない文字が含まれています'
            )
        ),
        'NEXT' => array(
            "rule1" => array(
                'rule' => 'notEmpty',
                'message' => '次回番号は必須項目です'
            ),
            "rule2" => array(
                'rule' => 'Numberonly',
                'message' => '半角数字で入力してください'
            )
        )
    );

    /**
     * データのインサート処理
     *
     * @param array $_param            
     *
     */
    function set_data($_param)
    {
        for ($i = 0; $i < 6; $i ++) {
            // ループ内対策
            $this->create();
            // 変更があれば更新
            if ($_param[$i]['CHANGED']) {
                if (empty($_param[$i]['NEXT'])) {
                    $_param[$i]['NEXT'] = 1;
                }
                // 更新日時の取得
                $_param[$i]['LAST_UPDATE'] = date("Y-m-d H:i:s");
                $this->updateAll(array(
                    'NUMBERING_FORMAT' => $_param[$i]['NUMBERING_FORMAT'],
                    'NEXT' => "'" . $_param[$i]['NEXT'] . "'",
                    'PREFIX' => "'" . $_param[$i]['PREFIX'] . "'",
                    'LAST_UPDATE' => 'NOW()'
                ), array(
                    'FORM_ID' => $i
                ));
            }
        }
    }

    /**
     * データ取得
     *
     * @param array $_param            
     *
     */
    function get_data()
    {
        $result = array();
        
        for ($i = 0; $i < 6; $i ++) {
            $temp = $this->find('first', array(
                'conditions' => array(
                    'FORM_ID' => $i
                ),
                'fields' => array(
                    'FORM_ID',
                    'NUMBERING_FORMAT',
                    'PREFIX',
                    'NEXT',
                    'LAST_UPDATE'
                )
            ));
            
            $result[$i] = $temp['Serial'];
        }
        
        return $result;
    }

    /**
     * 連番取得
     *
     * @param array $_form            
     *
     */
    function get_number($_form)
    {
        // 連番データ取得
        $data = $this->get_data();
        $id = Configure::read('FormID');
        
        $numbering_format = $data[$id[$_form]]['NUMBERING_FORMAT'];
        $prefix = $data[$id[$_form]]['PREFIX'];
        $next = $data[$id[$_form]]['NEXT'];
        $last = $data[$id[$_form]]['LAST_UPDATE'];
        
        $number = '';
        
        // 通し番号
        if ($numbering_format == 0) {
            if (isset($prefix)) {
                $number .= $prefix;
            }
            $number .= sprintf('%05d', $next);
            // 日付形式
        } else {
            // 日付が変わっていれば数値を1に戻す
            $this->reset_next($id[$_form], $last);
            
            if (isset($prefix)) {
                $number .= $prefix;
            }
            $number .= date("ymd");
            $number .= sprintf('%02d', $next);
        }
        
        return $number;
    }

    /**
     * 連番をインクリメント
     *
     * @param array $_form            
     *
     */
    function serial_increment($_form)
    {
        // 連番データ取得
        $data = $this->get_data();
        $id = Configure::read('FormID');
        $next = $data[$id[$_form]]['NEXT'];
        
        $this->updateAll(array(
            'LAST_UPDATE' => 'NOW()',
            'NEXT' => ++ $next
        ), array(
            'FORM_ID' => $id[$_form]
        ));
    }

    /**
     * 日付が変わっていれば次回番号をリセット
     *
     * @param array $last            
     */
    function reset_next($id, $last)
    {
        // 連番データ取得
        $data = $this->get_data();
        $next = $data[$id]['LAST_UPDATE'];
        
        if (strtotime(date("Y-m-d")) > strtotime($last)) {
            $this->updateAll(array(
                'LAST_UPDATE' => 'NOW()',
                'NEXT' => 1
            ), array(
                'FORM_ID' => $id
            ));
        }
    }

    /**
     * 設定情報を取得
     */
    function getSerialConf()
    {
        App::import('Model', 'Company');
        $company = new Company();
        $res = $company->find('first', array(
            'fileds' => array(
                'SERIAL_NUMBER'
            ),
            'condtions' => array(
                'CMP_ID' => 1
            )
        ));
        return 1 - $res['Company']['SERIAL_NUMBER'];
    }
}

?>
