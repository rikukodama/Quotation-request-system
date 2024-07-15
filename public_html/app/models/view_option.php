<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 内容:表示設定画面確認・編集のmodelクラス
 */
class ViewOption extends AppModel
{

    var $useTable = 'T_OPTION';

    var $actsAs = array(
        'Cakeplus.AddValidationRule'
    );

    var $validate = array(
        'footer' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthJP',
                    100
                ),
                'message' => 'メッセージが長すぎます'
            )
        ),
        'title' => array(
            "rule0" => array(
                'rule' => array(
                    'maxLengthJP',
                    100
                ),
                'message' => 'ページタイトルが長すぎます'
            )
        )
    );

    function update_data($_param)
    {
        // DB登録
        $options = $this->find('all', array(
            'fields' => array(
                'ViewOption.OPTION_ID',
                'ViewOption.OPTION_NAME'
            )
        ));
        
        for ($i = 0; $i < count($options); $i ++) {
            $ID = $options[$i]['ViewOption']['OPTION_ID'];
            $NAME = $options[$i]['ViewOption']['OPTION_NAME'];
            // ロゴの場合
            if ($NAME === 'logo') {
                if ($_param['ViewOption'][$NAME]['error']) {
                    continue;
                } else {
                    $VALUE = $_param['ViewOption'][$NAME]['name'];
                    $this->updateAll(array(
                        'OPTION_VALUE' => "'" . mysql_escape_string($VALUE) . "'",
                        'LAST_UPDATE' => 'NOW()'
                    ), array(
                        'OPTION_ID' => $ID
                    ));
                }
                // それ以外
            } else {
                $VALUE = $_param['ViewOption'][$NAME];
                $this->updateAll(array(
                    'OPTION_VALUE' => "'" . mysql_escape_string($VALUE) . "'",
                    'LAST_UPDATE' => 'NOW()'
                ), array(
                    'OPTION_ID' => $ID
                ));
            }
        }
    }

    function get_option()
    {
        $options = $this->find('all', array(
            'fields' => array(
                'ViewOption.OPTION_NAME',
                'ViewOption.OPTION_NAME_JP',
                'ViewOption.OPTION_VALUE'
            )
        ));
        return $options;
    }
}
?>
