<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 内容:ログイン履歴のcontrollerクラス
 */
class HistoriesController extends AppController
{

    var $name = "History";

    var $uses = array(
        'History',
        'User'
    );

    var $autoLayout = true;
    
    // 一覧用
    function index()
    {
        $this->set("main_title", "操作履歴");
        $this->set("title_text", "管理者メニュー");
        $condition = array();
        $this->data['History'] = $this->Common->paginate($this->History->searchColumnAry, null, null, $condition, $this, array('ACTION_DATE', 'ACTION'));
       
        $ids = array();
        foreach($this->data['History']['data'] as $key => $val){
            $id_data = $this->History->find('all', array(
                    'fields' => 'RPT_ID',
                    'conditions' => array(
                        'ACTION_DATE' => $val['History']['ACTION_DATE'],
                        'ACTION' => $val['History']['ACTION'],
                    ),
                    'order' => 'RPT_ID asc',
                )
            );
            foreach ($id_data as $ikey => $ival){
                $ids[$key][] = $ival['History']['RPT_ID'];
            }
            
            
        }
        $this->set("ids", $ids);
        $this->set("action", Configure::read('ActionCode'));
    }
}