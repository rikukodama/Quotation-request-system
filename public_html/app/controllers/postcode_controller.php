<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 郵便番号の更新クラス
 */
class PostcodeController extends AppController
{

    var $name = 'Postcode';

    var $uses = array(
        'Post'
    );

    var $autoLayout = true;

    var $helpers = array(
        'Html',
        'Form',
        'Ajax',
        'Javascript'
    );
    
    // 一覧
    function index()
    {
        $this->set("count", $this->Post->find('count'));
        $this->set("main_title", "郵便番号の管理");
        $this->set("title_text", "管理者メニュー");
    }
    
    // 更新
    function update()
    {
        $this->set("count", $this->Post->find('count'));
        $this->set("main_title", "郵便番号の管理");
        $this->set("title_text", "管理者メニュー");
        
        // 拡張子、MIMEタイプの指定
        $ex_csv = array(
            'csv',
            'CSV'
        );
        $mime_csv = array(
            'application/vnd.ms-excel'
        );
        
        if (is_uploaded_file($this->data['Post']['Csv']['tmp_name'])) {
            // アップロードに成功
            
            if ($backup = $this->_dumpCurrentPostCode()) {
                // バックアップ
                $this->set('backup', $backup);
            } else {
                $this->Session->setFlash('バックアップファイルの作成に失敗しました');
                $this->redirect(array(
                    'action' => 'index'
                ));
            }
            
            $fileName = $this->data['Post']['Csv']['name'];
            $filePath = TMP . $fileName;
            $aryFileName = explode('.', $fileName);
            $extension = $aryFileName[count($aryFileName) - 1];
            $mime = $this->data['Post']['Csv']['type'];
            
            // 拡張子、MIME Typeのチェック
            if (! in_array($extension, $ex_csv) || ! in_array($mime, $mime_csv)) {
                // csvでない場合
                unlink($filePath);
                unlink(TMP . $backup);
                
                $this->Session->setFlash('CSV形式のファイルをアップロードしてください');
                $this->redirect(array(
                    'action' => 'index'
                ));
            }
            
            // 改行コードの変換
            $contents = file_get_contents($this->data['Post']['Csv']['tmp_name']);
            $fp = fopen($filePath, 'w');
            fwrite($fp, $this->_convertEOL($contents));
            fclose($fp);
            
            if (substr($fileName, 0, 7) == 'KEN_ALL') {
                // 全国一括版の場合
                $this->set("action", "一括更新");
                $sqlRes = $this->_getSQLFile($filePath, false, true);
            } else {
                // その他のファイル名の場合
                unlink($filePath);
                unlink(TMP . $backup);
                $this->Session->setFlash('KEN_ALL.CSV以外のファイルはアップロードできません。');
                $this->redirect(array(
                    'action' => 'index'
                ));
            }
            
            if (! $sqlRes) {
                $this->Session->setFlash('CSVの内容に誤りがあります。');
                unlink($filePath);
                unlink(TMP . $backup);
                $this->redirect(array(
                    'action' => 'index'
                ));
            }
            
            unlink($filePath);
            $this->set("sqlRes", $sqlRes);
            $this->render('confirm');
        } else {
            // アップロードに失敗
            $this->Session->setFlash('通信エラーまたはPHPの設定により、アップロードが行えませんでした。<br />PHPの設定で、アップロードできるファイルサイズが制限されている場合があるので、<br />php.iniまたは.htaccessでpost_max_size, upload_max_filesizeの設定を行ってください。');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
    }

    function reset()
    {
        $this->autoRender = false;
        App::import('Model', 'ConnectionManager');
        $db = ConnectionManager::getDataSource('default');
        
        if (! $db->isConnected()) {
            $this->Session->setFlash(__('DBに接続できません。', true));
        } else {
            $this->__executeSQLScript($db, CONFIGS . 'sql/postcode.sql');
            $this->Session->setFlash('郵便番号データを初期状態に戻しました。');
        }
        $this->redirect(array(
            'action' => 'index'
        ));
    }

    function _dumpCurrentPostCode()
    {
        $this->autoRender = false;
        App::import('Core', 'ConnectionManager');
        $db = ConnectionManager::getDataSource('default');
        
        $file_name = sprintf('%s.sql', date('YmdHis'));
        
        // mmdd単位でログを作成
        $path = TMP . $file_name;
        $cmd = sprintf("mysqldump -t -u %s --password='%s' %s %sM_POST > %s", $db->config['login'], $db->config['password'], $db->config['database'], $db->config['prefix'], $path);
        try {
            system($cmd);
        } catch (Exception $e) {
            return false;
        }
        return $file_name;
    }

    function query()
    {
        $this->autoRender = false;
        $sqlFile = $this->params['named']['sql'];
        $backupFile = $this->params['named']['backup'];
        
        App::import('Model', 'ConnectionManager');
        $db = ConnectionManager::getDataSource('default');
        
        if (! $db->isConnected()) {
            $this->Session->setFlash(__('DBに接続できません。', true));
        } else {
            if ($this->__executeSQLScript($db, TMP . $sqlFile)) {
                unlink(TMP . $sqlFile);
                unlink(TMP . $backupFile);
                $this->Session->setFlash('更新しました。');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Post->deleteAll(array(
                    '1' => '1'
                ));
                if ($this->__executeSQLScript($db, TMP . $backupFile)) {
                    $this->Session->setFlash('更新に失敗したため、前回の状態に戻しました。');
                    $this->redirect(array(
                        'action' => 'index'
                    ));
                } else {
                    $this->Session->setFlash('更新に失敗しました。「郵便番号の修復」より、初期状態に戻してください');
                    $this->redirect(array(
                        'action' => 'index'
                    ));
                }
            }
        }
    }

    /**
     * 郵便局が配布している形式から抹茶請求書用の形式に変更
     * 
     * @param $_filePath アップロードされたCSVファイルのパス            
     * @param $_del 廃止データかどうか            
     * @param $_all 全国版データで一括更新かどうか            
     */
    function _getSQLFile($_filePath, $_del = false, $_all = false)
    {
        $error = false;
        if (empty($_filePath)) {
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        
        $pref_code = array_flip(Configure::read('PrefectureCode'));
        $sqlFile = md5(time()) . '.sql';
        $sqlPath = TMP . $sqlFile;
        $fp_sql = fopen($sqlPath, "a");
        $fp_csv = fopen($_filePath, "r");
        
        if ($_del) {
            // 廃止データの場合
            fwrite($fp_sql, "DELETE FROM `M_POST` WHERE POSTCODE IN (\n");
            $line = fgetcsv($fp_csv);
            $count = 1;
            
            while (true) {
                $tmp_post_code = mb_convert_encoding(sprintf("%07d", $line[2]), 'UTF-8', 'Shift_JIS');
                $tmp_val = "'" . $tmp_post_code . "'";
                
                if ($line = fgetcsv($fp_csv)) {
                    $count ++;
                    fwrite($fp_sql, $tmp_val . ",\n");
                } else {
                    fwrite($fp_sql, $tmp_val . ");");
                    break;
                }
            }
        } else 
            if ($_all) {
                // 全国版データの一括更新の場合
                fwrite($fp_sql, "DELETE FROM `M_POST` WHERE 1=1;\n");
                fwrite($fp_sql, "INSERT INTO `M_POST` (`POSTCODE`, `CNT_ID`, `CITY`, `AREA`) VALUES\n");
                $line = fgetcsv($fp_csv);
                $count = 1;
                
                while (true) {
                    $tmp_pref = mb_convert_encoding($line[6], 'UTF-8', 'Shift_JIS');
                    $tmp_city = mb_convert_encoding($line[7], 'UTF-8', 'Shift_JIS');
                    $tmp_area = mb_convert_encoding($line[8], 'UTF-8', 'Shift_JIS');
                    $tmp_post_code = mb_convert_encoding(sprintf("%07d", $line[2]), 'UTF-8', 'Shift_JIS');
                    $tmp_pref_code = mb_convert_encoding($pref_code[$tmp_pref], 'UTF-8', 'Shift_JIS');
                    
                    $tmp_val = "('" . $tmp_post_code . "', " . $tmp_pref_code . ", '" . $tmp_city . "', '" . $tmp_area . "')";
                    
                    if (empty($pref_code[$tmp_pref]) || empty($tmp_pref) || empty($tmp_city) || empty($tmp_area) || empty($tmp_post_code) || empty($tmp_pref_code)) {
                        $error = true;
                        break;
                    }
                    
                    if ($line = fgetcsv($fp_csv)) {
                        if ($count % 2000 == 0) {
                            $count ++;
                            fwrite($fp_sql, $tmp_val . ";\n");
                            fwrite($fp_sql, "INSERT INTO `M_POST` (`POSTCODE`, `CNT_ID`, `CITY`, `AREA`) VALUES\n");
                        } else {
                            $count ++;
                            fwrite($fp_sql, $tmp_val . ",\n");
                        }
                    } else {
                        fwrite($fp_sql, $tmp_val . ";");
                        break;
                    }
                }
            } 

            else {
                // 追加データの場合
                fwrite($fp_sql, "INSERT INTO `M_POST` (`POSTCODE`, `CNT_ID`, `CITY`, `AREA`) VALUES\n");
                $line = fgetcsv($fp_csv);
                $count = 1;
                
                while (true) {
                    $tmp_pref = mb_convert_encoding($line[6], 'UTF-8', 'Shift_JIS');
                    $tmp_city = mb_convert_encoding($line[7], 'UTF-8', 'Shift_JIS');
                    $tmp_area = mb_convert_encoding($line[8], 'UTF-8', 'Shift_JIS');
                    $tmp_post_code = mb_convert_encoding(sprintf("%07d", $line[2]), 'UTF-8', 'Shift_JIS');
                    $tmp_pref_code = mb_convert_encoding($pref_code[$tmp_pref], 'UTF-8', 'Shift_JIS');
                    
                    $tmp_val = "('" . $tmp_post_code . "', " . $tmp_pref_code . ", '" . $tmp_city . "', '" . $tmp_area . "')";
                    
                    if (empty($tmp_pref) || empty($tmp_city) || empty($tmp_area) || empty($tmp_post_code) || empty($tmp_pref_code)) {
                        $error = true;
                    }
                    
                    if ($line = fgetcsv($fp_csv)) {
                        $count ++;
                        fwrite($fp_sql, $tmp_val . ",\n");
                    } else {
                        fwrite($fp_sql, $tmp_val . ";");
                        break;
                    }
                }
            }
        
        fclose($fp_sql);
        
        if ($error) {
            unlink($sqlPath);
            return false;
        } else {
            $result = array(
                'sql' => $sqlFile,
                'count' => $count,
                'del' => $_del
            );
            return $result;
        }
    }

    /**
     * 改行文字変換用
     * 
     * @param $string 対象文字列            
     * @param $to 変換後の改行文字            
     * @return 改行コードを変換した文字列
     */
    function _convertEOL($string, $to = "\n")
    {
        return preg_replace("/\r\n|\r|\n/", $to, $string);
    }

    /**
     * SQL実行用
     */
    function __executeSQLScript($db, $fileName)
    {
        Configure::write('debug', 0);
        $statements = file_get_contents($fileName);
        $statements = explode(';', $statements);
        $prefix = $db->config["prefix"];
        
        foreach ($statements as $statement) {
            if (trim($statement) != '') {
                // プレフィックス用
                $pattern = array(
                    '/(DROP TABLE IF EXISTS `)([a-z_]+)(`)/i',
                    '/(CREATE TABLE IF NOT EXISTS `)([a-z_]+)(`)/i',
                    '/(INSERT INTO `)([a-z_]+)(`)/i'
                );
                $statement = preg_replace($pattern, '$1' . $prefix . '$2$3', $statement);
                $db->query($statement);
            }
        }
        
        return empty($db->error);
    }
}