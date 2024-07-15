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
class Ajax extends AppModel
{

    var $useTable = false;
    
    // ページング
    public function paging(&$_paging, &$_nowpage, &$_number, $_count, $_page)
    {
        if ($_count > $_number) {
            // 全ページ数
            $total = floor(($_count / $_number) - 0.05) + 1;
            
            if ($_page == 0) {
                $_paging .= "<< 前へ | ";
            } else {
                $_paging .= "<a href=\"javascript:void(0)\" onclick=\"return paging(" . ($_page - 1) . ");return false;\"><< 前へ</a> | ";
            }
            
            // 10ページ以下の場合
            if ($total < 11) {
                for ($i = 0; $i < $total; $i ++) {
                    if ($_page == $i) {
                        $_paging .= ($i + 1) . " | ";
                    } else {
                        $_paging .= "<a href=\"javascript:void(0)\" onclick=\"return paging(" . $i . ");return false;\">" . ($i + 1) . "</a> | ";
                    }
                }
                
                // 11ページ以上ある場合
            } else {
                if ($_page < 5) {
                    for ($i = 0; $i < 10; $i ++) {
                        if ($_page == $i) {
                            $_paging .= ($i + 1) . " | ";
                        } else {
                            $_paging .= "<a href=\"javascript:void(0)\" onclick=\"return paging(" . $i . ");return false;\">" . ($i + 1) . "</a> | ";
                        }
                    }
                    $_paging .= " ・・・ ";
                } else 
                    if ($total <= $_page + 5) {
                        $_paging .= " ・・・ ";
                        for ($i = $total - 10; $i < $total; $i ++) {
                            if ($_page == $i) {
                                $_paging .= ($i + 1) . " | ";
                            } else {
                                $_paging .= "<a href=\"javascript:void(0)\" onclick=\"return paging(" . $i . ");return false;\">" . ($i + 1) . "</a> | ";
                            }
                        }
                    } else {
                        $_paging .= " ・・・ ";
                        for ($i = $_page - 4; $i <= $_page + 5; $i ++) {
                            if ($_page == $i) {
                                $_paging .= ($i + 1) . " | ";
                            } else {
                                $_paging .= "<a href=\"javascript:void(0)\" onclick=\"return paging(" . $i . ");return false;\">" . ($i + 1) . "</a> | ";
                            }
                        }
                        $_paging .= " ・・・ ";
                    }
            }
            
            if ($_page >= floor(($_count / $_number) - 0.05)) {
                $_paging .= "次へ >>";
            } else {
                $_paging .= "<a href=\"javascript:void(0)\" onclick=\"return paging(" . ($_page + 1) . ");return false;\">次へ >></a>";
            }
        }
        $_nowpage = "$_count 件中 " . ($_count ? $_page * $_number + 1 : 0) . " - " . ($_count > ($_page * $_number + $_number) ? ($_page * $_number + $_number) : $_count) . " 件を表示<br>";
    }
}
?>
