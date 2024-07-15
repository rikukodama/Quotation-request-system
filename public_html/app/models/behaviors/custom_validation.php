<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */
/**
 * バリデーションチェック追加のBehaviorクラス
 */
class CustomValidationBehavior extends ModelBehavior
{

    /**
     * 文字の横幅（全角：1文字、半角：0.5文字）の文字数上限チェック
     * 
     * @param
     *            $model
     * @param
     *            $wordvalue
     * @param
     *            $length
     */
    function maxLengthW(&$model, $wordvalue, $length)
    {
        $value = array_shift($wordvalue);
        $width = ceil(mb_strwidth($value) / 2);
        return ($width <= $length);
    }

    /**
     * 改行数文字数判定
     * 改行の数と各行の文字の横幅（文字の横幅（全角：1文字、半角：0.5文字）の判定を行う
     *
     * @param array $wordvalue            
     * @param int $length
     *            1行あたりの文字の長さ
     * @param int $break
     *            最大行数
     * @return boolean
     */
    function maxBreakW(&$model, $wordvalue, $length, $break)
    {
        $value = array_shift($wordvalue);
        $number = preg_match_all("/[^\r\n]+/", $value, $result);
        
        if ($number > $break)
            return false;
        
        $res = 0;
        foreach ($result[0] as $val) {
            $res += ceil(mb_strwidth($val) / (2 * $length));
        }
        
        if ($res > $break)
            return false;
        
        return true;
    }


    /**
     * 消費税端数計算
     * 2023年10月01日以降は明細単位のみ許容
     *
     * @param array $value            
     * @return boolean
     */
    function taxFractionTiming(&$model, $arrayValue)
    {
        $value = array_shift($arrayValue);
        $dateString = $model->data[$model->name]['DATE'];
        if(empty($dateString)){
            return true;
        }

        $issueDate = strtotime($dateString);
        $validDate = strtotime('2023-10-01 00:00:00');
        if($issueDate >= $validDate){
            if($value == 1){
                return false;
            }
        }
        return true;
    }

}