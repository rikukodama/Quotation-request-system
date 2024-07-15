<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */
/**
 * 内容:自社担当情報関連のcontrollerクラス
 */
class ChargesController extends AppController
{

    var $name = "Charge";

    var $uses = array(
        "Charge"
    );

    var $autoLayout = true;

    var $helpers = array(
        'Html',
        'Form',
        'Ajax',
        'Javascript',
        'Session'
    );

    function beforeFilter()
    {
        parent::beforeFilter();
        // pdf出力時の画像出力の為、認証チェックには入れない
        $this->Auth->allow('contents');
    }
    
    // 一覧用
    function index()
    {
        $this->set("main_title", "自社担当者一覧");
        $this->set("title_text", "自社情報設定");
        $this->set("page_title", "Company");
        $this->set("status", Configure::read('StatusCode'));
        $this->set("seal", Configure::read('SealCode'));
    }
    
    // 登録用
    function add()
    {
        $this->set("main_title", "自社担当者登録");
        $this->set("title_text", "自社情報設定");
        
        // テスト用データ
        $company_ID = 1;
        $phone_error = 0;
        $fax_error = 0;
        $image_error = 0;
        $chr_id = 0;
        
        // キャンセルボタンを押された場合に一覧にリダイレクト
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect('/charges');
        }
        
        if (isset($this->params['data'])) {
            // トークンチェック
            $this->isCorrectToken($this->data['Security']['token']);
            
            // 更新時処理
            // 電話番号のバリデーション
            $phone_error = $this->phone_validation($this->params['data']['Charge']);
            
            // FAX番号のバリデーション
            $fax_error = $this->fax_validation($this->params['data']['Charge']);
            
            // 印鑑作成時処理
            if ($this->params['data']['Charge']['SEAL_METHOD'] && ! empty($this->params['data']['Charge']['SEAL_STR'])) {
                $this->make_seal($this->params['data']['Charge']['SEAL_STR']);
            }
            
            // データのインサート
            $result = $this->Charge->set_data($this->params['data'], $company_ID, $phone_error, $fax_error, $chr_id);
            
            if ($result) {
                if ($result === 1 || $result === 2 || $result === 3) {
                    // 画像登録失敗
                    $image_error = $result;
                } else {
                    // 成功
                    $this->Session->setFlash('自社担当者を保存しました');
                    $this->redirect("/charges/check/" . $chr_id);
                }
            }
        }
        
        // ビューにセット
        $this->set("ierror", $image_error);
        $this->set("perror", $phone_error);
        $this->set("ferror", $fax_error);
        $this->set("page_title", "Company");
        $this->set("status", Configure::read('StatusCode'));
        $this->set("countys", Configure::read('PrefectureCode'));
        $this->set("seal_method", Configure::read('SealMethod'));
        $this->set('seal_flg', Configure::read('SealFlg'));
    }
    
    // 削除用
    function delete()
    {
        if ($this->isCorrectToken($this->data['Security']['token']) && $this->Charge->index_delete($this->params['data'])) {
            // 成功
            $this->Session->setFlash('自社担当者を削除しました');
            $this->redirect("/charges");
        } else {
            // 失敗
            $this->Session->setFlash('自社担当者が削除できませんでした。');
            $this->redirect("/charges");
        }
    }
    
    // 編集用
    function edit()
    {
        $this->set("main_title", "自社担当者編集");
        $this->set("title_text", "自社情報設定");
        
        // テスト用データ
        $company_ID = 1;
        $phone_error = 0;
        $fax_error = 0;
        
        $image_error = 0;
        
        // キャンセルボタンを押された場合に一覧にリダイレクト
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect('/charges');
        }
        
        // IDの取得
        if (isset($this->params['pass'][0])) {
            $charge_ID = $this->params['pass'][0];
        } else {
            // エラー処理
            $this->redirect("/charges/index");
        }
        
        if (isset($this->params['data']) && $this->isCorrectToken($this->data['Security']['token'])) {
            
            // 更新時処理
            // 印鑑削除の確認
            if ($this->params['data']['Charge']['DEL_SEAL'] != 0) {
                $this->Charge->seal_delete($charge_ID);
            }
            
            // 電話番号のバリデーション
            $phone_error = $this->phone_validation($this->params['data']['Charge']);
            
            // FAX番号のバリデーション
            $fax_error = $this->fax_validation($this->params['data']['Charge']);
            
            // 印鑑作成時処理
            if ($this->params['data']['Charge']['SEAL_METHOD'] && ! empty($this->params['data']['Charge']['SEAL_STR'])) {
                $this->make_seal($this->params['data']['Charge']['SEAL_STR']);
            }
            
            // データのインサート
            $result = $this->Charge->set_data($this->params['data'], $company_ID, $phone_error, $fax_error);
            
            if ($result) {
                
                if ($result === 1 || $result === 2 || $result === 3) {
                    // 画像登録失敗
                    $image_error = $result;
                    $image = $this->Charge->get_image($charge_ID);
                    if ($image) {
                        $this->set("image", $image);
                    }
                } else {
                    // 成功
                    $msg = '自社担当者を保存しました';
                    $this->Session->setFlash($msg);
                    $this->redirect("/charges/check/" . $charge_ID);
                }
            }
        } else {
            // 通常時処理
            
            // 担当者情報の取得
            if (! $this->data = $this->Charge->edit_select($charge_ID)) {
                // エラー処理
                $this->redirect("/charges/index");
            }
        }
        
        if (! $this->Get_Edit_Authority($this->data['Charge']['USR_ID'])) {
            $this->Session->setFlash('ページを開く権限がありません');
            $this->redirect("/charges/index/");
        }
        
        // 印鑑があるかを確認しあればセット
        if (isset($this->data['Charge']['SEAL']) && $this->data['Charge']['SEAL']) {
            $image = $this->data['Charge']['SEAL'];
            $this->set("image", $image);
        }
        
        // ビューにセット
        $this->set("ierror", $image_error);
        $this->set("perror", $phone_error);
        $this->set("ferror", $fax_error);
        $this->set("id", $charge_ID);
        $this->set("page_title", "Company");
        $this->set("status", Configure::read('StatusCode'));
        $this->set("seal_method", Configure::read('SealMethod'));
        $this->set("countys", Configure::read('PrefectureCode'));
        $this->set('seal_flg', Configure::read('SealFlg'));
    }
    // 確認用
    function check()
    {
        $this->set("main_title", "自社担当者確認");
        $this->set("title_text", "自社情報設定");
        
        // テスト用データ
        $company_ID = 1;
        
        // キャンセルボタンを押された場合に一覧にリダイレクト
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect('/charges');
        }
        
        // IDの取得
        if (isset($this->params['pass'][0])) {
            $charge_ID = $this->params['pass'][0];
        } else {
            // エラー処理
            $this->redirect("/charges/index");
        }
        
        // 担当者情報の取得
        if (! $this->data = $this->Charge->edit_select($charge_ID)) {
            // エラー処理
            $this->redirect("/charges/index");
        }
        $editauth = $this->Get_Edit_Authority($this->data['Charge']['USR_ID']);
        if (! $this->Get_Check_Authority($this->data['Charge']['USR_ID'])) {
            $this->Session->setFlash('ページを開く権限がありません');
            $this->redirect("/charges");
        }
        
        // 印鑑があるかを確認しあればセット
        if (isset($this->data['Charge']['SEAL']) && $this->data['Charge']['SEAL']) {
            $image = $this->data['Charge']['SEAL'];
            $this->set("image", $image);
        }
        
        // ビューにセット
        $this->set("editauth", $editauth);
        $this->set("params", $this->data);
        $this->set("id", $charge_ID);
        $this->set("page_title", "Company");
        $this->set("status", Configure::read('StatusCode'));
        $this->set("countys", Configure::read('PrefectureCode'));
        $this->set('seal_flg', Configure::read('SealFlg'));
    }
    // 画像表示用
    function contents()
    {
        // IDの取得
        if (isset($this->params['pass'][0])) {
            $charge_ID = $this->params['pass'][0];
        } else {
            // エラー処理
            $this->redirect("/charges");
        }
        $this->layout = false;
        $this->autoRender = false;
        $this->data = $this->Charge->edit_select($charge_ID);
        
        $image = $this->data['Charge']['SEAL'];
        
        if (empty($image)) {
            $this->cakeError('error404');
        }
        header("Content-type: image/png");
        echo $image;
    }
    
    // 印鑑作成用
    function make_seal($str)
    {
        // 500x500 の画像を作成
        $font_path = CONFIGS . "font/ipam00303/ipam.ttf";
        
        $im = imagecreatetruecolor(500, 500);
        $red = imagecolorallocate($im, 0xFF, 0x00, 0x00);
        $white = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
        
        // 背景を白に設定
        imagefill($im, 0, 0, $white);
        
        // 線幅を 5 に設定
        imagesetthickness($im, 5);
        
        // 楕円を描画
        imagefilledellipse($im, 250, 250, 300, 300, $red);
        imagefilledellipse($im, 250, 250, 280, 280, $white);
        
        $tmp_name = array();
        
        // 文字数ごとに異なる描画方法
        switch (mb_strlen($str)) {
            case 1:
                ImageTTFText($im, 130, 0, 165, 310, $red, $font_path, $str);
                break;
            
            case 2:
                $tmp_name[0] = mb_substr($str, 0, 1);
                $tmp_name[1] = mb_substr($str, 1, 1);
                ImageTTFText($im, 110, 0, 175, 235, $red, $font_path, $tmp_name[0]);
                ImageTTFText($im, 110, 0, 175, 365, $red, $font_path, $tmp_name[1]);
                break;
            
            case 3:
                $tmp_name[0] = mb_substr($str, 0, 1);
                $tmp_name[1] = mb_substr($str, 1, 1);
                $tmp_name[2] = mb_substr($str, 2, 1);
                ImageTTFText($im, 75, 0, 200, 195, $red, $font_path, $tmp_name[0]);
                ImageTTFText($im, 75, 0, 200, 285, $red, $font_path, $tmp_name[1]);
                ImageTTFText($im, 75, 0, 200, 375, $red, $font_path, $tmp_name[2]);
                break;
            
            case 4:
                $tmp_name[0] = mb_substr($str, 0, 1);
                $tmp_name[1] = mb_substr($str, 1, 1);
                $tmp_name[2] = mb_substr($str, 2, 1);
                $tmp_name[3] = mb_substr($str, 3, 1);
                ImageTTFText($im, 85, 0, 245, 235, $red, $font_path, $tmp_name[0]);
                ImageTTFText($im, 85, 0, 245, 345, $red, $font_path, $tmp_name[1]);
                ImageTTFText($im, 85, 0, 135, 235, $red, $font_path, $tmp_name[2]);
                ImageTTFText($im, 85, 0, 135, 345, $red, $font_path, $tmp_name[3]);
                break;
        }
        
        // キャンバスを透過する
        imagecolortransparent($im, $white);
        
        // 出力
        imagepng($im, TMP . "tmpseal.png");
        $this->params['data']['Charge']['SEAL'] = file_get_contents(TMP . "tmpseal.png");
        imagedestroy($im);
    }
}