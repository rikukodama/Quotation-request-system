<?php

/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */

/**
 * 内容:請求書関連のcontrollerクラス
 */
class TotalbillsController extends AppController
{

    var $name = "Totalbill";

    var $uses = array(
        "Totalbill",
        'Bill',
        "Mail",
        "CustomerCharge",
        "Customer",
        "Serial"
    );

    var $autoLayout = true;

    var $components = array(
        'Excel'
    );

    function beforeFilter()
    {
        parent::beforeFilter();
    }

    function index()
    {
        $authority = array();
        $this->set("authority", $authority);

        $this->set("main_title", "合計請求書管理");
        $this->set("title_text", "帳票管理");

        // セット
        $this->set("edit_stat", Configure::read('Edit_StatProtocolCode'));
        $this->set("mailstatus", Configure::read('MailStatusCode'));
        $this->set("status", Configure::read('IssuedStatCode'));
    }

    function add()
    {
        $bill_id = array();
        $data = array();
        $total = 0;
        $tax = 0;
        $subtotal = 0;
        $billfrag = 0;
        $i = 0;
        $stat = 0;

        if (isset($this->params['form']['select'])) {
            foreach ($this->data['Totalbill'] as $key => $val) {
                if (preg_match("/^[0-9]*$/", $key) && $val == 1) {
                    $data[$i] = $this->Bill->find('first', array(
                        'conditions' => array(
                            "Bill.MBL_ID" => $key
                        )
                    ));
                    $i ++;
                }
            }

            if ($data == null) {
                $i = 0;
                foreach ($this->data['Totalbill'] as $key => $val) {
                    if (preg_match("/^[0-9]*$/", $key)) {
                        $this->data[$i]['Totalbillitem']['MBL_ID'] = $key;
                    }
                    $i ++;
                }
                $this->params['form']["cancel_x"] = 1;
                $this->Session->setFlash('請求書を選択してください');
            }
        }

        if (isset($this->params['form']["cancel_x"]) | $this->params['form'] == null || isset($this->params['form']['search_x'])) {

            $user_ID = $this->Get_User_ID();
            $user_auth = $this->Get_User_Authority();

            $this->set("main_title", "合計請求書管理");
            $this->set("title_text", "帳票管理");

            if (isset($this->data)) {
                if (! isset($this->params['form']["cancel_x"])) {
                    if ($user_auth != 1) {
                        $data = $this->Totalbill->search_bill($this->data);
                    } else {
                        $data = $this->Totalbill->search_bill($this->data, $user_ID);
                    }
                } else {
                    $i = 0;
                    foreach ($this->data as $key => $val) {
                        if (preg_match("/^[0-9]*$/", $key)) {
                            $bill_id[$i] = $val['Totalbillitem']['MBL_ID'];
                            $data[$i] = $this->Bill->find('first', array(
                                'conditions' => array(
                                    "Bill.MBL_ID" => $val['Totalbillitem']['MBL_ID']
                                )
                            ));
                            if ($this->params['form']['cancel_x'] != 1) {
                                $data[$i]['Bill']['CHK'] = 1;
                            }
                            $i ++;
                        }
                    }
                    $stat = $this->data['Totalbill']['EDIT_STAT'];
                }

                if ($data != null) {
                    $billfrag = 1;
                } else {
                    if ((isset($this->data['Totalbill']['FROM']) && $this->data['Totalbill']['FROM'] != null) || (isset($this->data['Totalbill']['TO']) && $this->data['Totalbill']['TO'] != null) || (isset($this->data['Totalbill']['CST_ID']) && $this->data['Totalbill']['CST_ID'] != null)) {
                        $this->Session->setFlash('請求書がありません');
                    } else {
                        $this->Session->setFlash('条件を指定してください');
                    }
                }

                $this->set("billlist", $data);
                $this->set("cst_name", $this->data['Totalbill']['CUSTOMER_NAME']);
                $this->set("cst_id", $this->data['Totalbill']['CST_ID']);
            }

            $edit_stat = array(
                'type' => 'radio',
                'options' => Configure::read('Edit_StatProtocolCode'),
                'value' => $stat,
                'div' => false,
                'label' => false,
                'legend' => false,
                'style' => 'width:30px;',
                'class' => 'txt_mid'
            );

            // セット
            $this->set("edit_stat", $edit_stat);
            $this->set("billfrag", $billfrag);

            $this->render('search', 'default');
        } else {
            $i = 0;

            if (isset($this->params['form']["submit_x"])) {
                // トークンチェック
                $this->isCorrectToken($this->data['Security']['token']);

                if ($this->data['Totalbill']['EDIT_STAT'] == 1) {
                    $this->set("total", isset($this->data['Totalbill']['SALE']) ? $this->data['Totalbill']['SALE'] : '0');
                } elseif ($this->data['Totalbill']['EDIT_STAT'] == 0) {
                    $this->set("total", isset($this->data['Totalbill']['THISM_BILL']) ? $this->data['Totalbill']['THISM_BILL'] : '0');
                }

                $this->set("tax", isset($this->data['Totalbill']['SALE_TAX']) ? $this->data['Totalbill']['SALE_TAX'] : '0');
                if ($this->params['data']['Totalbill']['HONOR_CODE'] != 2) {
                    $this->params['data']['Totalbill']['HONOR_TITLE'] = "";
                }

                if ($this->data['Totalbill']['EDIT_STAT'] == 1) {
                    $this->Totalbill->validate['LASTM_BILL']['rule2'] = $this->Totalbill->validate['LASTM_BILL']['rule1'];
                    $this->Totalbill->validate['LASTM_BILL']['rule1'] = $this->Totalbill->validate['LASTM_BILL']['rule0'];
                    $this->Totalbill->validate['LASTM_BILL']['rule0']=array(
                        'rule' => 'notEmpty',
                        'message' => '前月請求額は必須項目です'
                    );
                    $this->Totalbill->validate['DEPOSIT']['rule2'] = $this->Totalbill->validate['DEPOSIT']['rule1'];
                    $this->Totalbill->validate['DEPOSIT']['rule1'] = $this->Totalbill->validate['DEPOSIT']['rule0'];
                    $this->Totalbill->validate['DEPOSIT']['rule0']=array(
                        'rule' => 'notEmpty',
                        'message' => '御入金額は必須項目です'
                    );
                }

                $tbl_id = $this->Totalbill->set_data($this->data, 'new');
                if ($tbl_id) {
                    // アクションログ
                    $this->History->h_reportaction($this->data['Totalbill']['USR_ID'], 11, $tbl_id);
                    $this->Session->setFlash('合計請求書を保存しました');
                    $this->Serial->serial_increment('TotalBill');
                    $this->redirect("/totalbills/check/" . $tbl_id);
                } else {


                    foreach ($this->data as $key => $val) {
                        if (preg_match("/^[0-9]*$/", $key)) {
                            $bill_id[$i] = $val['Totalbillitem']['MBL_ID'];
                            $data[$i] = $this->Bill->find('first', array(
                                'conditions' => array(
                                    "Bill.MBL_ID" => $val['Totalbillitem']['MBL_ID']
                                )
                            ));
                            $i ++;
                        }
                    }
                }
            } else {
                $i = 0;
                foreach ($this->data['Totalbill'] as $key => $val) {
                    if (preg_match("/^[0-9]*$/", $key) && $val == 1) {
                        $bill_id[$i] = $key;
                        $data[$i] = $this->Bill->find('first', array(
                            'conditions' => array(
                                "Bill.MBL_ID" => $key
                            )
                        ));
                        $subtotal += $data[$i]['Bill']['SUBTOTAL'];
                        $total += $data[$i]['Bill']['TOTAL'];
                        $tax += $data[$i]['Bill']['SALES_TAX'];
                        $i ++;
                    }
                }

                if ($this->data['Totalbill']['EDIT_STAT'] == 0) {
                    $this->data['Totalbill']['THISM_BILL'] = $total;
                    $this->data['Totalbill']['SUBTOTAL'] = $subtotal;
                    $this->data['Totalbill']['SALE_TAX'] = $tax;
                } else {
                    $this->data['Totalbill']['LASTM_BILL'] = 0;
                    $this->data['Totalbill']['DEPOSIT'] = 0;
                    $this->data['Totalbill']['SALE'] = $total;
                    $this->data['Totalbill']['SALE_TAX'] = $tax;
                }
                $this->data['Totalbill']['DATE'] = date("Y-m-d");

                $company_ID = 1;
                if ($default_honor = $this->Bill->get_honor($company_ID)) {
                    $this->data['Totalbill']['HONOR_CODE'] = $default_honor[0]['Company']['HONOR_CODE'];
                    if ($default_honor[0]['Company']['HONOR_CODE'] == 2) {
                        $this->data['Totalbill']['HONOR_TITLE'] = $default_honor[0]['Company']['HONOR_TITLE'];
                    }
                }

                if ($this->Totalbill->get_serial($company_ID) == 0) {
                    $this->data['Totalbill']['NO'] = $this->Serial->get_number('TotalBill');
                }
            }
            if ($data == null) {
                $this->redirect("#");
            }

            // セット
            $this->set("main_title", "合計請求書管理");
            $this->set("title_text", "帳票管理");
            $this->set("billlist", $data);
            $this->set('bill_id', $bill_id);
            $this->set("edit_stat", $this->data['Totalbill']['EDIT_STAT']);
        }

        $this->set("mailstatus", Configure::read('MailStatusCode'));
        $this->set("status", Configure::read('IssuedStatCode'));
        $this->set("honor", Configure::read('HonorCode'));
    }

    // 確認
    function check()
    {
        $this->set("main_title", "合計請求書確認");
        $this->set("title_text", "帳票管理");

        // IDの取得
        if (isset($this->params['pass'][0])) {
            $tbl_ID = $this->params['pass'][0];
        } else {
            // エラー処理
            $this->redirect("/totalbills/index");
        }

        // 初期データの取得
        $param = $this->Totalbill->check_select($tbl_ID);
        $param['Bill'] = $this->Totalbill->get_bill($tbl_ID);

        // データが取得できない場合
        if (! $param) {
            $this->Session->setFlash('指定の合計請求書が削除されたか、存在しない可能性があります');
            $this->redirect("/totalbills/index");
        }

        if (! $this->Get_Check_Authority($param['Totalbill']['USR_ID'])) {
            $this->Session->setFlash('帳票を閲覧する権限がありません');
            $this->redirect("/totalbills/");
        }
        $editauth = $this->Get_Edit_Authority($param['Totalbill']['USR_ID']);

        if ($customer_charge = $this->CustomerCharge->select(array(
            'CHRC_ID' => $param['Totalbill']['CHRC_ID']
        ))) {
            $param['CustomerCharge']['CHARGE_NAME'] = $customer_charge[0]['CustomerCharge']['CHARGE_NAME'];
            $param['CustomerCharge']['UNIT'] = $customer_charge[0]['CustomerCharge']['UNIT'];
        }

        // セット
        $this->set("editauth", $editauth);
        $this->set("param", $param);
        $this->set("honor", Configure::read('HonorCode'));
    }

    // 編集
    function edit()
    {
        $edit = 0;
        $total = 0;
        $subtotal = 0;
        $tax = 0;
        $data = array();
        $i = 0;

        if (isset($this->params['form']['select'])) {
            foreach ($this->data['Totalbill'] as $key => $val) {
                if (preg_match("/^[0-9]*$/", $key) && $val == 1) {
                    $data[$i] = $this->Bill->find('first', array(
                        'conditions' => array(
                            "Bill.MBL_ID" => $key
                        )
                    ));
                    $i ++;
                }
            }

            if ($data == null) {
                $i = 0;
                foreach ($this->data['Totalbill'] as $key => $val) {
                    if (preg_match("/^[0-9]*$/", $key)) {
                        $this->data[$i]['Totalbillitem']['MBL_ID'] = $key;
                    }
                    $i ++;
                }
                $this->params['form']["cancel_x"] = 1;
                $this->Session->setFlash('請求書を選択してください');
            }
        }

        // 検索画面
        if (isset($this->params['form']["cancel_x"]) | $this->params['form'] == null || isset($this->params['form']['search_x']) | isset($this->params['form']['search_y']) ) {

            $user_ID = $this->Get_User_ID();
            $user_auth = $this->Get_User_Authority();
            $this->set("authority", $user_auth);

            $this->set("main_title", "合計請求書編集");
            $this->set("title_text", "帳票管理");

            // 検索
            if (isset($this->data)) {
                // 検索をかけた場合
                if (! isset($this->params['form']["cancel_x"])) {
                    if ($user_auth != 1) {
                        $data = $this->Totalbill->search_bill($this->data);
                    } else {
                        $data = $this->Totalbill->search_bill($this->data, $user_ID);
                    }
                } else {
                    // 戻ってきた場合
                    $data = array();
                    $i = 0;
                    foreach ($this->data as $key => $val) {
                        if (preg_match("/^[0-9]*$/", $key)) {
                            $bill_id[$i] = $val['Totalbillitem']['MBL_ID'];
                            $data[$i] = $this->Bill->find('first', array(
                                'conditions' => array(
                                    "Bill.MBL_ID" => $val['Totalbillitem']['MBL_ID']
                                )
                            ));
                            if ($this->params['form']['cancel_x'] != 1) {
                                $data[$i]['Bill']['CHK'] = 1;
                            }
                            $i ++;
                        }
                    }
                }

                if ($data != null) {
                    $billfrag = 1;
                } else {
                    if ((isset($this->data['Totalbill']['FROM']) && $this->data['Totalbill']['FROM'] != null) || (isset($this->data['Totalbill']['TO']) && $this->data['Totalbill']['TO'] != null) || (isset($this->data['Totalbill']['CST_ID']) && $this->data['Totalbill']['CST_ID'] != null)) {
                        $this->Session->setFlash('請求書がありません');
                    } else {
                        $this->Session->setFlash('条件を指定してください');
                    }
                    $billfrag = 0;
                }

                $this->set("billlist", $data);
                $this->set("billfrag", $billfrag);
                $this->set("cst_name", $this->data['Totalbill']['CUSTOMER_NAME']);
                $this->set("cst_id", $this->data['Totalbill']['CST_ID']);
                $this->set("tbl_id", $this->data['Totalbill']['TBL_ID']);
            }            // 初期表示
            elseif (isset($this->params['pass'][0])) {
                $tbl_ID = $this->params['pass'][0];

                $result = $this->Totalbill->find('all', array(
                    'conditions' => array(
                        'TBL_ID' => $tbl_ID
                    )
                ));

                if (! $result) {
                    $this->Session->setFlash('指定の合計請求書が削除されたか、存在しない可能性があります');
                    $this->redirect("/totalbills/index");
                }

                $data = $this->Totalbill->get_bill($tbl_ID);
                $cst = $this->Totalbill->get_cstmer($tbl_ID);
                $edit = $this->Totalbill->get_edit_stat($tbl_ID);
                $tbl_user_id = $this->Totalbill->get_user_id($tbl_ID);
                $this->set("billlist", $data);
                $this->set("billfrag", 1);

                if (isset($this->data['Customer']['NAME'])) {
                    $this->set("cst_name", $this->data['Customer']['NAME']);
                    $this->set("cst_id", $this->data['Customer']['CST_ID']);
                } else {
                    $this->set("cst_name", $cst['Customer']['NAME']);
                    $this->set("cst_id", $cst['Customer']['CST_ID']);
                }

                if (! $this->Get_Edit_Authority($tbl_user_id)) {
                    $this->Session->setFlash('帳票を編集する権限がありません');
                    $this->redirect("/totalbills/");
                }
                $this->set("tbl_id", $tbl_ID);
            }
            $edit_stat = array(
                'type' => 'radio',
                'options' => Configure::read('Edit_StatProtocolCode'),
                'value' => $edit,
                'div' => false,
                'label' => false,
                'legend' => false,
                'style' => 'width:30px;',
                'class' => 'txt_mid'
            );

            // セット
            $this->set("edit_stat", $edit_stat);
            $this->set("mailstatus", Configure::read('MailStatusCode'));
            $this->set("status", Configure::read('IssuedStatCode'));

            $this->render('edit_search', 'default');
        }         // 編集画面
        else {
            $this->set("main_title", "合計請求書編集");
            $this->set("title_text", "帳票管理");

            // IDの取得
            if (isset($this->params['pass'][0])) {
                $tbl_ID = $this->params['pass'][0];
            } elseif ($this->data['Totalbill']['TBL_ID']) {
                $tbl_ID = $this->data['Totalbill']['TBL_ID'];
            } else {
                // エラー処理
                $this->redirect("/totalbills/index");
            }

            $tbl_user_id = $this->Totalbill->get_user_id($tbl_ID);
            if (! $this->Get_Edit_Authority($tbl_user_id)) {
                $this->Session->setFlash('帳票を編集する権限がありません');
                $this->redirect("/totalbills/");
            }

            $bill_id = array();
            $i = 0;

            if (isset($this->params['form']["submit_x"])) {
                // トークンチェック
                $this->isCorrectToken($this->data['Security']['token']);

                if ($this->params['data']['Totalbill']['HONOR_CODE'] != 2) {
                    $this->params['data']['Totalbill']['HONOR_TITLE'] = "";
                }
                if ($this->data['Totalbill']['EDIT_STAT'] == 1) {
                	$this->Totalbill->validate['LASTM_BILL']['rule2'] = $this->Totalbill->validate['LASTM_BILL']['rule1'];
                	$this->Totalbill->validate['LASTM_BILL']['rule1'] = $this->Totalbill->validate['LASTM_BILL']['rule0'];
                	$this->Totalbill->validate['LASTM_BILL']['rule0']=array(
                			'rule' => 'notEmpty',
                			'message' => '前月請求額は必須項目です'
                	);
                	$this->Totalbill->validate['DEPOSIT']['rule2'] = $this->Totalbill->validate['DEPOSIT']['rule1'];
                	$this->Totalbill->validate['DEPOSIT']['rule1'] = $this->Totalbill->validate['DEPOSIT']['rule0'];
                	$this->Totalbill->validate['DEPOSIT']['rule0']=array(
                			'rule' => 'notEmpty',
                			'message' => '御入金額は必須項目です'
                	);
                }
                $tbl_id = $this->Totalbill->set_data($this->data);
                if ($tbl_id) {
                    $this->Session->setFlash('合計請求書を保存しました。');
                    $this->History->h_reportaction($this->data['Totalbill']['UPDATE_USR_ID'], 12, $tbl_id);
                    $this->redirect("/totalbills/check/" . $tbl_id);
                } else {
                    foreach ($this->data as $key => $val) {
                        if (preg_match("/^[0-9]*$/", $key)) {
                            $bill_id[$i] = $val['Totalbillitem']['MBL_ID'];
                            $data[$i] = $this->Bill->find('first', array(
                                'conditions' => array(
                                    "Bill.MBL_ID" => $val['Totalbillitem']['MBL_ID']
                                )
                            ));
                            $i ++;
                        }
                    }
                }
            } else {
                // 初期データの取得
                foreach ($this->data['Totalbill'] as $key => $val) {
                    if (preg_match("/^[0-9]*$/", $key) && $val == 1) {
                        $bill_id[$i] = $key;
                        $data[$i] = $this->Bill->find('first', array(
                            'conditions' => array(
                                "Bill.MBL_ID" => $key
                            )
                        ));
                        $subtotal += $data[$i]['Bill']['SUBTOTAL'];
                        $total += $data[$i]['Bill']['TOTAL'];
                        $tax += $data[$i]['Bill']['SALES_TAX'];
                        $i ++;
                    }
                }

                $result = $this->Totalbill->edit_select($tbl_ID);
                $this->data['Totalbill'] = array_merge($result['Totalbill'], $this->data['Totalbill']);

                if ($this->data['Totalbill']['EDIT_STAT'] == 0) {
                    $this->data['Totalbill']['THISM_BILL'] = $total;
                    $this->data['Totalbill']['SUBTOTAL'] = $subtotal;
                    $this->data['Totalbill']['SALE_TAX'] = $tax;
                } else {
                    if(empty($this->data['Totalbill']['LASTM_BILL'])){
                        $this->data['Totalbill']['LASTM_BILL']= 0;
                    }
                    if(empty($this->data['Totalbill']['DEPOSIT'])){
                        $this->data['Totalbill']['DEPOSIT'] = 0;
                    }
                    $this->data['Totalbill']['SALE'] = $total;
                    $this->data['Totalbill']['SALE_TAX'] = $tax;
                }

                if ($customer_charge = $this->CustomerCharge->select(array(
                    'CHRC_ID' => $this->data['Totalbill']['CHRC_ID']
                ))) {
                    $this->data['Totalbill']['CUSTOMER_CHARGE_NAME'] = $customer_charge[0]['CustomerCharge']['CHARGE_NAME'];
                    $this->data['Totalbill']['CUSTOMER_CHARGE_UNIT'] = $customer_charge[0]['CustomerCharge']['UNIT'];
                }
            }

            $this->set("billlist", $data);
            $this->set("tbl_id", $tbl_ID);
            $this->set('bill_id', $bill_id);
            $this->set("edit_stat", $this->data['Totalbill']['EDIT_STAT']);
            $this->set("mailstatus", Configure::read('MailStatusCode'));
            $this->set("status", Configure::read('IssuedStatCode'));
            $this->set("honor", Configure::read('HonorCode'));
        }
    }

    function action()
    {
        // トークンチェック
        $this->isCorrectToken($this->data['Security']['token']);

        $user_ID = $this->Get_User_ID();

        if (isset($this->params['form']['delete_x'])) {
            if (empty($this->params['data']['Totalbill'])) {
                $this->Session->setFlash('合計請求書が選択されていません');
                $this->redirect("/totalbills/index");
            }

            // 削除
            foreach ($this->params['data']['Totalbill'] as $key => $val) {
                if ($val == 1) {
                    $id = $this->Totalbill->find('first', array(
                        'conditions' => array(
                            'Totalbill.TBL_ID' => $key
                        ),
                        'fields' => array(
                            'Totalbill.USR_ID'
                        )
                    ));
                    if (! $this->Get_Edit_Authority($id['Totalbill']['USR_ID'])) {
                        $this->Session->setFlash('削除できない合計請求書が含まれていました');
                        $this->redirect("/totalbills/index");
                    }
                }
            }

            if ($this->Totalbill->index_delete($this->params['data'])) {
                // 成功
                foreach ($this->params['data']['Totalbill'] as $key => $value) {
                    if ($value == 1) {
                        $this->History->h_reportaction($user_ID, 13, $key);
                    }
                }
                $this->Session->setFlash('合計請求書を削除しました');
                $this->redirect("/totalbills/index");
            } else {
                // 失敗
                $this->redirect("/totalbills/index");
            }
        }
    }

    // pdf
    function pdf()
    {

        // デザイン無効
        $this->autoLayout = false;

        // 見積書IDの取得
        $tbl_ID = null;
        if (isset($this->params['pass'][0])) {
            $tbl_ID = $this->params['pass'][0];
        }
        if (! $tbl_ID) {
            $this->cakeError('error404', array(
                array(
                    'url' => '/'
                )
            ));
        }

        $param = $this->Totalbill->preview_data($tbl_ID);
        $direction = 0;

        // 請求書の準備
        $bill = $this->Totalbill->get_bill_id($tbl_ID);
        $count = count($bill);

        $Color = Configure::read('ColorCode');

        // $param['Company']['COLOR'] = $Color[$param['Company']['COLOR']]['code'];

        $items_a = array();
        $discounts_a = array();
        $item_count_a = array();
        $page_a = array();
        // 記載項目
        $i = 0;
        foreach ($bill as $key => $val) {
            if (! $billparam[$i] = $this->Bill->preview_data($val['Totalbillitem']['MBL_ID'], $items_a[$i], $discounts_a[$i])) {
                // $this->cakeError('error404',array(array('url'=>'/')));
            } else {

                $billparam[$i]['pages'] = 1;
                $item_count[$i] = $items_a[$i] + $discounts_a[$i];

                // ページ数計測
                {
                    $pages = 1;
                    $item_count = count($billparam[$i]);

                    for ($j = 0, $item_count_per_page = 0; $j < $item_count; $j++) {
                        $fbreak = isset($billparam[$i][$j]['Billitem']['LINE_ATTRIBUTE'])
                            && intval($billparam[$i][$j]['Billitem']['LINE_ATTRIBUTE']) == 8;

                        if ($direction == 0) {
                            // 縦
                            if ($pages == 1) {
                                if ($fbreak) {
                                    $pages++;
                                    $item_count_per_page = 0;
                                } else if ($item_count_per_page >= 20) {
                                    $pages++;
                                    $item_count_per_page = -(10-1); // 前のページの10個からカウントを始める
                                } else {
                                    $item_count_per_page++;
                                }
                            } else {
                                if ($fbreak) {
                                    $pages++;
                                    $item_count_per_page = 0;
                                } else if ($item_count_per_page >= 30) {
                                    $pages++;
                                    $item_count_per_page = -(10-1); // 前のページの10個からカウントを始める
                                } else {
                                    $item_count_per_page++;
                                }
                            }
                        }
                    }
                    $billparam[$i]['pages'] = $pages;
                }

                $billparam[$i]['items'] = $items_a[$i];

                // $billparam[$i]['Company']['COLOR'] = $Color[$billparam[$i]['Company']['COLOR']]['code'];
                $this->createTmpImage($billparam[$i]['Bill']['MBL_ID']);
                // 社版URLのセット
                if ($billparam[$i]['Company']['SEAL']) {
                    $billparam[$i]['Company']['SEAL_IMAGE'] = $this->getTmpImagePath(null, true);
                }
                // 社員版URLのセット
                if ($billparam[$i]['Bill']['CHR_ID'] && $billparam[$i]['Charge']['SEAL']) {
                    $billparam[$i]['Charge']['SEAL_IMAGE'] = $this->getTmpImagePath($billparam[$i]['Bill']['MBL_ID']);
                }
                $page_a[$i] = $billparam[$i]['pages'];
                $i ++;
            }
        }
        $count = $i;

        if ($customer_charge = $this->CustomerCharge->select(array(
            'CHRC_ID' => $param['Totalbill']['CHRC_ID']
        ))) {
            $param['CustomerCharge'] = $customer_charge[0]['CustomerCharge'];
        }

        // 社版URLのセット
        if ($param['Company']['SEAL']) {
            $param['Company']['SEAL_IMAGE'] = $this->getTmpImagePath(null, true);
        }
        // 社員版URLのセット
        if ($param['Customer']['CHR_ID'] && $param['Charge']['SEAL']) {
            $param['Charge']['SEAL_IMAGE'] = $this->getTmpImagePath();
        }

        // 役職名のセット
        if (empty($param['CustomerCharge']['UNIT'])) {
            $param['CustomerCharge']['UNIT'] = '';
        }

        // 担当者名のセット
        if (empty($param['CustomerCharge']['CHARGE_NAME'])) {
            $param['CustomerCharge']['CHARGE_NAME'] = '';
        }

        // 都道府県情報取得
        $county = Configure::read('PrefectureCode');
        $accounttype = Configure::read('AccountTypeCode');

        if ($param['Totalbill']['EDIT_STAT'] == 0) {
            App::import('Vendor', 'pdf/totalbillpdf_s');
            // インスタンス化
            $pdf = new TOTALBILLPDF_S();
        } elseif ($param['Totalbill']['EDIT_STAT'] == 1) {
            App::import('Vendor', 'pdf/totalbillpdf_d');
            // インスタンス化
            $pdf = new TOTALBILLPDF_D();
        }

        $pdf->AddMBFont(MINCHO, 'SJIS');

        $pdf->Direction = $direction;
        $pdf->TotalPage = $page_a;

        // 都道府県情報取得
        $county = Configure::read('PrefectureCode');

        // ブラウザの識別
        $browser = getenv("HTTP_USER_AGENT");

        // ページの作成
        $pdf->AddPage();

        // 本文用情報付加
        $pdf->main($param, $county, $count, $accounttype, $direction, $billparam);

        if (isset($this->params['pass'][1]) && $this->params['pass'][1] === 'download') {
            // アウトプット
            $str = mb_convert_encoding("合計請求書_{$param['Totalbill']['SUBJECT']}.pdf", "SJIS-win", "UTF-8");
            if (ereg("MSIE", $browser) || ereg("Trident", $browser)) {
                $str = strip_tags($str);
                $pdf->Output($str, 'D');
            } else {
                $pdf->Output("合計請求書_{$param['Totalbill']['SUBJECT']}.pdf", 'D');
            }
        } else {
            // アウトプット
            $param['Bill']['SUBJECT'] = "aa";
            $str = mb_convert_encoding("合計請求書_{$param['Totalbill']['SUBJECT']}.pdf", "SJIS-win", "UTF-8");

            if (ereg("MSIE", $browser) || ereg("Trident", $browser)) {
                $str = strip_tags($str);
                $pdf->Output($str, 'I');
            } else {
                $pdf->Output('合計請求書.pdf', 'I');
            }
        }
    }
}