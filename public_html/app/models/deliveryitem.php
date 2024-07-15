<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 内容:DB属性名変換のmodelクラス
 */
class Deliveryitem extends AppModel
{

    var $name = 'Deliveryitem';

    var $primaryKey = 'ITM_ID';

    var $useTable = 'T_DELIVERY_ITEM';
}