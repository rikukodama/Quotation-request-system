<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 内容:ログインのmodel
 */
class History extends AppModel
{

    var $name = 'History';

    var $useTable = 'T_HISTORY';

    var $primaryKey = 'HST_ID';

    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'USR_ID'
        )
    );
    
    // 検索条件
    var $searchColumnAry = array(
        'ACTION_DATE_FROM' => 'History.ACTION_DATE_FROM',
        'ACTION_DATE_TO' => 'History.ACTION_DATE_TO',
        'NAME' => 'User.NAME',
        'ACTION' => 'History.ACTION'
    );

    var $order = array(
        'HST_ID DESC'
    );

    function h_login($_user)
    {
        $result = array();
        
        $result['History']['USR_ID'] = $_user;
        $result['History']['ACTION_DATE'] = date("Y-m-d  H:i:s");
        $result['History']['ACTION'] = 0;
        $result['History']['DEL_FLAG'] = 0;
        
        $result['History']['BROWSER'] = $this->browser_hash();
        
        if ($this->saveAll($result['History'])) {
            return true;
        }
        return false;
    }

    function h_logout($_user, $_browser = null)
    {
        $result = array();
        
        $result['History']['USR_ID'] = $_user;
        $result['History']['ACTION_DATE'] = date("Y-m-d H:i:s");
        $result['History']['ACTION'] = 1;
        $result['History']['DEL_FLAG'] = 0;
        if ($_browser) {
            $result['History']['BROWSER'] = $_browser;
        } else {
            $result['History']['BROWSER'] = $this->browser_hash();
        }
        
        if ($this->saveAll($result['History'])) {
            return true;
        }
        return false;
    }
    
    // 見積書・請求書・納品書の作成、更新、削除アクションログ
    function h_reportaction($_user, $a_num, $r_num)
    {
        $result = array();
        $result['History']['USR_ID'] = $_user;
        $result['History']['ACTION'] = $a_num;
        $result['History']['RPT_ID'] = $r_num;
        $result['History']['ACTION_DATE'] = date("Y-m-d H:i:s");
        $result['History']['DEL_FLAG'] = 0;
        
        if ($this->saveAll($result['History'])) {
            return true;
        }
        return false;
    }

    function h_getlastlog($_user)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'History.USR_ID' => $_user
            ),
            'order' => array(
                'History.HST_ID' => 'desc'
            ),
            'fields' => array(
                'ACTION',
                'BROWSER'
            )
        ));
        return $result;
    }

    function browser_log($_user)
    {
        // ブラウザの識別
        $browser = getenv("HTTP_USER_AGENT");
        $result = $this->find('first', array(
            'conditions' => array(
                'History.USR_ID' => $_user,
                'History.BROWSER' => $browser
            ),
            'order' => array(
                'History.HST_ID' => 'desc'
            ),
            'fields' => array(
                'ACTION'
            )
        ));
        return $result;
    }

    function browser_hash()
    {
        // ブラウザの識別
        $browser = getenv("HTTP_USER_AGENT");
        Security::hash($browser, null, true);
        return Security::hash($browser, null, true);
    }
}
