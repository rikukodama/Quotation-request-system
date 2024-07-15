<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 内容:請求書登録・編集のmodelクラス
 */
class Totalbillitem extends AppModel
{

    var $name = 'Totalbillitem';

    var $useTable = 'T_TOTAL_BILLITEM';

    var $primaryKey = 'TBLI_ID';
}