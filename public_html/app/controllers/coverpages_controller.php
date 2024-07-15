<?php

/**
* @copyright ICZ Corporation (http://www.icz.co.jp/)
* @license See the LICENCE file
* @author <matcha@icz.co.jp>
* @version $Id$
*/

/**
 * 内容:送付状作成のcontrollerクラス
 */
class CoverpagesController extends Appcontroller
{

    var $name = 'Coverpage';

    var $uses = array(
        'charge',
        'Company',
        'Customer',
        'CustomerCharge',
        'Coverpage'
    );

    var $autoLayout = true;

    function beforeFilter()
    {
        parent::beforeFilter();
    }

    function index()
    {
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect('/homes');
        }
        
        $this->set("main_title", "送付状作成");
        $this->set("title_text", "帳票管理");
        $this->set("maxline", Configure::read('CoverpageMaxFormLine'));
        $this->set("SendMethod", Configure::read('SendMethod'));
        
        $errors = null;
        
        if (isset($this->params['data']['Coverpages'])) {
            // バリデーション
            $this->Coverpage->set($this->params['data']['Coverpages']);
            $errors = $this->Coverpage->invalidFields();
            $document_error = $this->document_validation($this->params['data'], 'Reports');
            $this->set('document_error', $document_error);
            
            // データ数の取得
            $count = 0;
            for ($i = 0; $i < $this->params['data']['Coverpages']['maxformline']; $i ++) {
                if (! empty($this->params['data'][$i]['Reports']['DOCUMENT_TITLE']) || ! empty($this->params['data'][$i]['Reports']['DOCUMENT_NUMBER']))
                    $count = $i + 1;
            }
            
            if ($this->Coverpage->validates() && $document_error['FLG'] == 0) {
                // 空白行をつめる
                $this->params['data'] = $this->document_shift($this->params['data']);
                $count = 0;
                for ($i = 0; $i < $this->params['data']['Coverpages']['maxformline']; $i ++) {
                    if (! empty($this->params['data'][$i]['Reports']['DOCUMENT_TITLE']))
                        $this->params['data']['Coverpages']['dataformline'] = ($i + 1);
                }
                
                $this->autoLayout = false;
                $param['Data'] = $this->params['data'];
                
                $Color = Configure::read('ColorCode');
                $this->set("dataline", $count);
                
                // 自社情報の取得
                $company_ID = 1;
                if (! $param = array_merge($param, $this->Company->index_select($company_ID))) {
                    $this->cakeError('error404', array(
                        array(
                            'url' => '/'
                        )
                    ));
                }
                
                // 不正に入力された場合
                if (! isset($this->params['data']['Coverpages']['CST_ID'])) {
                    $this->Session->setFlash('値が不正に入力されました');
                    $this->redirect('/coverpages');
                }
                if (! isset($this->params['data']['Coverpages']['SEND_METHOD'])) {
                    $this->Session->setFlash('値が不正に入力されました');
                    $this->redirect('/coverpages');
                }
                
                // 顧客情報の取得
                if (! $param = array_merge($param, $this->Customer->edit_select($param['Data']['Coverpages']['CST_ID']))) {
                    $this->Session->setFlash('顧客IDが不正に入力されました');
                    $this->redirect('/coverpages');
                }
                
                $param['Company']['COLOR'] = $Color[$param['Company']['COLOR']]['code'];
                
                // 社版URLのセット
                if ($param['Company']['SEAL']) {
                    $param['Company']['SEAL_IMAGE'] = $this->getTmpImagePath(null, true);
                }
                
                // 都道府県情報取得
                $county = Configure::read('PrefectureCode');
                $accounttype = Configure::read('AccountTypeCode');
                
                App::import('Vendor', 'pdf/coverpagepdf');
                
                // ブラウザの識別
                $browser = getenv("HTTP_USER_AGENT");
                
                // インスタンス化
                $pdf = new COVERPAGEPDF();
                
                $pdf->SetLeftMargin(19.0);
                
                // 送付状は縦固定
                $direction = 0;
                
                // ページの作成
                $pdf->AddPage();
                
                // 本文用情報付加
                $pdf->main($param, $county);
                
                $title = htmlspecialchars($param['Data']['Coverpages']['TITLE']);
                
                // アウトプット
                $str = mb_convert_encoding("送付状_" . $title . ".pdf", "SJIS-win", "UTF-8");
                ob_end_clean();
                
                if (ereg("MSIE", $browser) || ereg("Trident", $browser)) {
                    $str = strip_tags($str);
                    $pdf->Output($str, 'D');
                } else {
                    $pdf->Output("送付状_" . $title . ".pdf", 'D');
                }
            } else {
                $this->set("error", $errors);
                $this->set("dataline", $count ? $count : 1);
            }
        } else {
            $this->data['Coverpages']['DATE'] = date("Y-m-d");
            $this->set("dataline", 1);
        }
    }

    /**
     * 送付書類のバリデーション
     * 
     * @param $_param アイテムの入っている配列            
     * @param $_field 検索したいフィールド名を渡す            
     * @return $error 結果データを配列で返す
     *        
     *         使用例
     *         $error=$this->item_validation($this->params['data'],'Deliveryitem');
     */
    function document_validation($_param, $_field)
    {
        $_error = array(
            'DOCUMENT_TITLE' => array(
                'NO' => array(),
                'FLAG' => 0
            ),
            'DOCUMENT_NUMBER' => array(
                'NO' => array(),
                'FLAG' => 0
            ),
            'FLG' => 0
        );
        
        // ここからバリデーション
        $empty = 0;
        for ($i = 0; $i < count($_param) - 1; $i ++) {
            // 書類名
            $document_value = ceil(mb_strwidth($_param[$i][$_field]['DOCUMENT_TITLE']) / 2);
            if ($document_value > 15) {
                $_error['DOCUMENT_TITLE']['NO'][$i] = 'over';
            }
            
            if (empty($document_value) && empty($_param[$i][$_field]['DOCUMENT_NUMBER'])) {
                $empty ++;
            } else 
                if (empty($document_value) && ! empty($_param[$i][$_field]['DOCUMENT_NUMBER'])) {
                    $_error['DOCUMENT_TITLE']['NO'][$i] = 'empty';
                }
            
            // 部数
            if (preg_match("/^[0-9]*$/", $_param[$i][$_field]['DOCUMENT_NUMBER']) == 0 && $_param[$i][$_field]['DOCUMENT_NUMBER'] != NULL) {
                $_error['DOCUMENT_NUMBER']['NO'][$i] = $i;
            } else 
                if ($_param[$i][$_field]['DOCUMENT_NUMBER'] > 9999999) {
                    $_error['DOCUMENT_NUMBER']['NO'][$i] = $i;
                } else 
                    if ((! empty($_param[$i][$_field]['DOCUMENT_TITLE'])) && empty($_param[$i][$_field]['DOCUMENT_NUMBER'])) {
                        $_error['DOCUMENT_NUMBER']['NO'][$i] = $i;
                    }
        }
        
        if ($empty == count($_param) - 1) {
            $_error['EMP_FLG'] = 1;
            $_error['FLG'] = 1;
        }
        
        for ($i = 0; $i < count($_param); $i ++) {
            if (isset($_error['DOCUMENT_TITLE']['NO'][$i])) {
                if ($_error['DOCUMENT_TITLE']['NO'][$i] == 'over')
                    $_error['DOCUMENT_TITLE']['OVER_FLAG'] = 1;
                if ($_error['DOCUMENT_TITLE']['NO'][$i] == 'empty')
                    $_error['DOCUMENT_TITLE']['EMP_FLAG'] = 1;
                $_error['FLG'] = 1;
            }
            if (isset($_error['DOCUMENT_NUMBER']['NO'][$i])) {
                $_error['DOCUMENT_NUMBER']['FLAG'] = 1;
                $_error['FLG'] = 1;
            }
        }
        return $_error;
    }
    
    // 送付書類上詰め
    function document_shift($_param)
    {
        $max = $_param['Coverpages']['maxformline']; // 最大行数
        $fill_line = 0; // 上から埋まっている行数
        $blank_line = 0; // 空白行数
        
        for ($i = 0; $i < $max; $i ++) {
            if (empty($_param[$i]['Reports']['DOCUMENT_TITLE'])) {
                $fill_line = $i;
                break;
            }
        }
        
        for ($i = 0; $i < $max; $i ++) {
            $blank_line = 0;
            for ($j = $fill_line; $j < $max; $j ++) {
                if (! empty($_param[$j]['Reports']['DOCUMENT_TITLE'])) {
                    $_param[$fill_line]['Reports']['DOCUMENT_TITLE'] = $_param[$j]['Reports']['DOCUMENT_TITLE'];
                    $_param[$fill_line]['Reports']['DOCUMENT_NUMBER'] = $_param[$j]['Reports']['DOCUMENT_NUMBER'];
                    $_param[$j]['Reports']['DOCUMENT_TITLE'] = '';
                    $_param[$j]['Reports']['DOCUMENT_NUMBER'] = '';
                    $fill_line ++;
                }
            }
        }
        return $_param;
    }
}