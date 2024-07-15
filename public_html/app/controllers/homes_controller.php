<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * ダッシュボード用のcontrollerクラス
 */
class HomesController extends AppController
{

    var $name = "Home";

    var $uses = array(
        "Home",
        "User"
    );

    var $autoLayout = true;
    
    // メイン用
    function index()
    {
        if ($this->Get_User_AUTHORITY() != 1) {
            $bill = $this->Home->get_recent_forms('Bill');
            $quote = $this->Home->get_recent_forms('Quote');
            $delivery = $this->Home->get_recent_forms('Delivery');
        } else {
            $bill = $this->Home->get_recent_forms('Bill', $this->Get_User_ID());
            $quote = $this->Home->get_recent_forms('Quote', $this->Get_User_ID());
            $delivery = $this->Home->get_recent_forms('Delivery', $this->Get_User_ID());
        }
        
        $this->User->find('all', array(
            'fields' => array(
                'USR_ID',
                'NAME'
            )
        ));
        
        // セット
        $this->set("main_title", "HOME");
        $this->set("bill", $bill);
        $this->set("quote", $quote);
        $this->set("delivery", $delivery);
    }
}