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
class Post extends AppModel
{

    var $name = 'Post';

    var $useTable = 'M_POST';

    function SearchPostCord($_param)
    {
        $result = array();
        if ($_param) {
            $result = $this->find('first', array(
                'conditions' => array(
                    'POSTCODE' => $_param
                )
            ));
        }
        
        if ($result) {
            $result['Post']['POSTCODE1'] = substr($result['Post']['POSTCODE'], 0, 3);
            $result['Post']['POSTCODE2'] = substr($result['Post']['POSTCODE'], 3, 4);
        }
        
        return $result;
    }

    function CandidacyPostCord($_param)
    {
        $data = $this->SearchArray($_param, array(
            'POSTCODE1',
            'POSTCODE2'
        ));
        
        $result = array();
        
        $post_length = mb_strlen($data['POSTCODE1']) + mb_strlen($data['POSTCODE2']);
        
        if ($data['POSTCODE1'] && $data['POSTCODE2'] && $post_length == 7) {
            $result = $this->find('all', array(
                'conditions' => array(
                    'POSTCODE LIKE' => $data['POSTCODE1'] . $data['POSTCODE2'] . "%"
                ),
                'limit' => '5'
            ));
        }
        if ($result) {
            $county = Configure::read('PrefectureCode');
            foreach ($result as $key => $value) {
                $result[$key]['Post']['COUNTY'] = $county[$value['Post']['CNT_ID']];
            }
            return $result;
        } else {
            return null;
        }
    }
}
?>
