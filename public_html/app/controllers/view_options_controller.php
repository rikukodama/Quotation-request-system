<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */
/**
 * 表示設定クラス
 */
class ViewOptionsController extends AppController
{

    var $name = "ViewOptions";

    var $autoLayout = true;

    var $uses = array(
        'ViewOption'
    );

    var $helpers = array(
        'Html',
        'Form',
        'Ajax',
        'Javascript'
    );
    
    // メイン用
    function index()
    {
        $this->set("main_title", "デザイン設定確認");
        $this->set("title_text", "管理者メニュー");
        // 全オプション取得
        $options = $this->ViewOption->get_option();
        $this->set("options", $options);
    }
    
    // 編集用
    function edit()
    {
        $this->set("main_title", "デザイン設定編集");
        $this->set("title_text", "管理者メニュー");
        $options = $this->ViewOption->get_option();
        $this->set("options", $options);
        
        $errors = $this->ViewOption->invalidFields();
        
        // キャンセルボタンを押された場合、一覧にリダイレクト
        if (isset($this->params['form']['cancel_x'])) {
            $this->redirect("/view_options");
        }
        
        if (isset($this->params['data'])) {
            // トークンチェック
            $this->isCorrectToken($this->data['Security']['token']);
            
            $this->ViewOption->set($this->params['data']);
            if ($this->ViewOption->validates()) {
                if ($this->data['ViewOption']['logo_default']) {
                    $this->data['ViewOption']['logo'] = array(
                        'name' => 'i_logo.jpg',
                        'error' => 0
                    );
                    $result = $this->ViewOption->update_data($this->params['data']);
                    $this->Session->setFlash('設定を保存しました');
                    $this->redirect("/view_options");
                }
                
                // ファイルのアップロード処理
                if (is_uploaded_file($this->data['ViewOption']['logo']['tmp_name'])) {
                    $logo_error = $this->logo_validation($this->data['ViewOption']['logo']);
                    if (! $logo_error) {
                        
                        $upload_dir = Configure::read('ImgUploadDir'); // アップロードディレクトリ
                                                                       
                        // ファイルがすでに存在している場合はリネーム
                        $filename = $this->data['ViewOption']['logo']['name'];
                        $i = 1;
                        $tmpname = substr($filename, 0, strpos($filename, '.'));
                        $extension = substr($filename, strpos($filename, '.'), strlen($filename));
                        
                        while (file_exists($upload_dir . $tmpname . $extension)) {
                            $tmpname = substr($filename, 0, strpos($filename, '.')) . '_' . $i;
                            $i ++;
                        }
                        
                        $filename = $tmpname . $extension;
                        $this->data['ViewOption']['logo']['name'] = $filename;
                        
                        if (move_uploaded_file($this->data['ViewOption']['logo']['tmp_name'], $upload_dir . $filename)) {
                            // アップロードファイル移動成功
                            $result = $this->ViewOption->update_data($this->params['data']);
                            $this->Session->setFlash('設定を保存しました');
                            $this->redirect("/view_options");
                        } else {
                            // アップロードファイル移動失敗
                            $this->Session->setFlash('アップロード画像の移動に失敗しました');
                            $this->redirect("/view_options");
                        }
                    } else {
                        $this->set('logo_error', $logo_error);
                    }
                } else 
                    if (empty($hits->data['ViewOption']['logo'])) {
                        // 画像を更新しないとき
                        $result = $this->ViewOption->update_data($this->params['data']);
                        $this->Session->setFlash('設定を保存しました');
                        $this->redirect("/view_options");
                    } else {
                        // アップロード失敗
                        $this->Session->setFlash('画像のアップロードに失敗しました');
                        $this->redirect("/view_options");
                    }
            } else {
                $this->set('error', $errors);
            }
        } else {}
    }
    // ロゴ画像のバリデーション
    function logo_validation($logo)
    {
        $error = 0;
        if ($logo['type'] === 'image/jpeg' || $logo['type'] === 'image/pjpeg' || $logo['type'] === 'image/png' || $logo['type'] === 'image/x-png') {
            if ($logo['type'] === 'image/pjpeg') {
                $logo['type'] = 'image/jpeg';
            }
            if ($logo['type'] === 'image/x-png') {
                $logo['type'] = 'image/png';
            }
        }
        $info = getimagesize($logo['tmp_name']);
        
        // リクエスト改変対応
        $ex_type = pathinfo($logo['name'], PATHINFO_EXTENSION);
        if(!strstr(strtolower($info['mime']), strtolower($ex_type))){
            return 4;
        }
        
        // 正しい画像ファイルであるかを確認
        if ($info['mime'] != $logo['type']) {
            return 4;
        }
        if (! ($logo['type'] == 'image/jpeg' || $logo['type'] == 'image/png')) {
            $error = $error + 1;
        }
        if ($logo['size'] > 1000000) {
            $error = $error + 2;
        }
      
        // リクエスト改変対応で対応できなかったphpコードが含まれてるファイルを排除
        $data_check = file_get_contents($logo['tmp_name']);

        if (mb_eregi('<\\?php', $data_check)) {
          $error = 1;
        }
        if (mb_eregi('^.*<\\?php.*$', $data_check)) {
          $error = 1;
        }
        if (preg_match('/<\\?php./i', $logo)) {
          $error = 1;
        }

        return $error;
    }
}