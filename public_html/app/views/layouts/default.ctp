<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="imagetoolbar" content="no" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="copyright" content="" />
    <meta name="robots" content="index,follow" />
    <meta http-equiv="imagetoolbar" content="no" />
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-store">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <link rel="icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
    <?php $controller = $this->params['controller']; ?>
    <?php $action = $this->params['action']; ?>
    <?php if(preg_match('/^home/', $controller)){?>
    <title><?php echo $main_title ?>｜<?php echo $title ?></title>
    <?php }else{ ?>
    <title><?php echo $main_title ?>｜<?php echo $title_text ?>｜<?php echo $customHtml->ht2br($title); ?></title>
    <?php }?>
    <script>
    var controller_name <?php if(isset($this->name)){?> = '<?php echo $this->name;?>'
    <?php }?>;
    </script>
    <?php echo $html->script("jquery-1.4.4.min")."\n"; ?>
    <?php 
			if(isset($rb_flag) && $rb_flag ){
				echo $html->script("/regularbill/js/jkl-calendar")."\n";
			} else {
				echo $html->script("jkl-calendar")."\n";
			}
		?>
    <?php echo $html->script("mathcontext")."\n"; ?>
    <?php echo $html->script("bigdecimal")."\n"; ?>
    <?php echo $html->script("forms")."\n"; ?>
    <?php echo $html->script("dropdown")."\n"; ?>
    <?php echo $html->script("alphafilter",array('defer'=>'defer'))."\n"; ?>
    <?php echo $customAjax->postcode(); ?>
    <?php echo $customAjax->usercode(); ?>
    <?php echo $customAjax->popup(); ?>
    <?php echo $html->script("ready")."\n"; ?>
    <?php echo $html->script("rollover")."\n"; ?>
    <?php echo $html->script("rollover-table")."\n"; ?>
    <?php echo $html->css("import")."\n"; ?>
</head>

<body>

    <!-- wrapper_Start -->
    <div id="wrapper">

        <!-- header_Start -->
        <div id="header" class="clearfix">
            <h1><?php echo $html->image($logo, array('height' => '40','alt' => '抹茶請求書','url' =>'/')); ?></h1>
            <div id="logout">
                <?php echo $html->image('bt_logout.jpg', array('alt' => 'ログアウト', 'url' => array('controller' => 'users', 'action' => 'logout'), 'class' => 'imgover')); ?>
            </div>
            <br class="clear" />
            <div>
                <ul id="menu" class="menu"">
<?php if(preg_match('/^home/', $controller)){ ?>
			<li><?php echo $html->image('bt_menu01_on.jpg', array('alt' => 'HOME')); ?></li>
<?php }else{ ?>
			<li><?php echo $html->image('bt_menu01.jpg', array('alt' => 'HOME', 'url' => array('controller' => 'homes', 'action' => 'index'), 'class' => 'imgover')); ?></li>
<?php } ?>
		<li>
<?php
	if(preg_match('/^quotes|^bills|^totalbills|^regularbill|^deliveries|^mails|^customers\/select/', $controller."/".$action)){ 
		echo $html->image('bt_menu02_on.jpg', array('alt' => '帳票管理')); 
	}elseif(isset($this->params['plugin']) && $this->params['plugin'] == 'regularbill'){ 
		echo $html->image('bt_menu02_on.jpg', array('alt' => '帳票管理')); 
	} else {
		echo $html->image('bt_menu02.jpg', array('alt' => '帳票管理', 'class' => 'imgover'));
	}
?>
		
		
			<ul class=" dmenu">
                    <li><span><?php echo $customHtml->plink('顧客から絞り込み', array('controller' => 'customers', 'action' => 'select')); ?></span>
                    </li>
                    <li class="line"><?php echo $html->image("i_line_dmenu.gif");?></li>
                    <li><span><?php echo $customHtml->plink('見積書一覧', array('controller' => 'quotes', 'action' => 'movetoindex')); ?></span>
                    </li>
                    <li><span><?php echo $customHtml->plink('見積書を作成する', array('controller' => 'quotes', 'action' => 'add')); ?></span>
                    </li>
                    <li class="line"><?php echo $html->image("i_line_dmenu.gif");?></li>

                    <li><span><?php echo $customHtml->plink('請求書一覧', array('controller' => 'bills', 'action' => 'movetoindex')); ?></span>
                    </li>
                    <li><span><?php echo $customHtml->plink('請求書を作成する', array('controller' => 'bills', 'action' => 'add')); ?></span>
                    </li>
                    <li><span><?php echo $customHtml->plink('合計請求書一覧', array('controller' => 'totalbills', 'action' => 'movetoindex')); ?></span>
                    </li>
                    <li class="line"><?php echo $html->image("i_line_dmenu.gif");?></li>

                    <li><span><?php echo $customHtml->plink('納品書一覧', array('controller' => 'deliveries', 'action' => 'movetoindex')); ?></span>
                    </li>
                    <li><span><?php echo $customHtml->plink('納品書を作成する', array('controller' => 'deliveries', 'action' => 'add')); ?></span>
                    </li>
                    <li class="line"><?php echo $html->image("i_line_dmenu.gif");?></li>

                    <?php if(isset($rb_flag) && $rb_flag ){ ?>
                    <li><span><?php echo $customHtml->plink('定期請求書雛形一覧', array('controller' => 'regularbill', 'action' => 'index', 'plugin'=>'regularbill')); ?></span>
                    </li>
                    <li><span><?php echo $customHtml->plink('定期請求書雛形を作成する', array('plugin'=>'regularbill','controller' => 'regularbill', 'action' => 'add')); ?></span>
                    </li>
                    <li class="line"><?php echo $html->image("i_line_dmenu.gif");?></li>
                    <?php } ?>

                    <li><span><?php echo $customHtml->plink('確認メール一覧', array('controller' => 'mails', 'action' => 'index')); ?></span>
                    </li>

                    <li class="last"><?php echo $html->image("bg_dmenu_btm.png" ,array('class'=>'alphafilter'));?></li>
                </ul>
                </li>
                <li>
                    <?php if(preg_match('/^customers|^customer_charges|^coverpages/', $controller) && !preg_match('/^customers\/select/', $controller."/".$action)){ ?>
                    <?php echo $html->image('bt_menu03_on.jpg', array('alt' => '顧客管理')); ?>
                    <?php }else{ ?>
                    <?php echo $html->image('bt_menu03.jpg', array('alt' => '顧客管理', 'class' => 'imgover')); ?>
                    <?php } ?>
                    <ul class="dmenu">
                        <li><span><?php echo $customHtml->plink('取引先を見る', array('controller' => 'customers', 'action' => 'movetoindex')); ?></span>
                        </li>
                        <li><span><?php echo $customHtml->plink('取引先担当者を見る', array('controller' => 'customer_charges', 'action' => 'index')); ?></span>
                        </li>
                        <li><span><?php echo $customHtml->plink('送付状を作成する', array('controller' => 'coverpages', 'action' => 'index')); ?></span>
                        </li>
                        <li class="last"><?php echo $html->image("bg_dmenu_btm.png" ,array('class'=>'alphafilter'));?>
                        </li>
                    </ul>
                </li>
                <li>
                    <?php if(preg_match('/^companies|^charges|^items/', $controller)){ ?>
                    <?php echo $html->image('bt_menu04_on.jpg', array('alt' => '自社設定')); ?>
                    <?php }else{ ?>
                    <?php echo $html->image('bt_menu04.jpg', array('alt' => '自社設定', 'class' => 'imgover')); ?>
                    <?php } ?>
                    <ul class="dmenu">
                        <li><span><?php echo $customHtml->plink('基本情報を見る', array('controller' => 'companies', 'action' => 'index')); ?></span>
                        </li>
                        <li><span><?php echo $customHtml->plink('自社担当者を見る', array('controller' => 'charges', 'action' => 'movetoindex')); ?></span>
                        </li>
                        <li class="line"><?php echo $html->image("i_line_dmenu.gif");?></li>

                        <li><span><?php echo $customHtml->plink('商品を見る', array('controller' => 'items', 'action' => 'movetoindex')); ?></span>
                        </li>
                        <li><span><?php echo $customHtml->plink('商品を登録する', array('controller' => 'items', 'action' => 'add')); ?></span>
                        </li>
                        <li class="last"><?php echo $html->image("bg_dmenu_btm.png" ,array('class'=>'alphafilter'));?>
                        </li>

                    </ul>
                </li>
                <li>
                    <?php if($user['AUTHORITY']==0){ ?>
                    <?php if(preg_match('/^administers|^histories|^configurations|^postcode|^view_options|^personals/', $controller)){ ?>
                    <?php echo $html->image('bt_menu05_on.jpg', array('alt' => '管理者メニュー')); ?>
                    <?php }else{ ?>
                    <?php echo $html->image('bt_menu05.jpg', array('alt' => '管理者メニュー', 'class' => 'imgover')); ?>
                    <?php } ?>
                    <ul class="dmenu">
                        <li><span><?php echo $customHtml->plink('ユーザを管理する', array('controller' => 'administers', 'action' => 'movetoindex')); ?></span>
                        </li>
                        <li><span><?php echo $customHtml->plink('操作履歴を見る', array('controller' => 'histories', 'action' => 'movetoindex')); ?></span>
                        </li>
                        <li><span><?php echo $customHtml->plink('環境設定をする', array('controller' => 'configurations', 'action' => 'index')); ?></span>
                        </li>
                        <li><span><?php echo $customHtml->plink('郵便番号を管理する', array('controller' => 'postcode', 'action' => 'index')); ?></span>
                        </li>
                        <li><span><?php echo $customHtml->plink('デザイン設定をする', array('controller' => 'view_options', 'action' => 'index')); ?></span>
                        </li>
                        <li class="line"><?php echo $html->image("i_line_dmenu.gif");?></li>
                        <li><span><?php echo $customHtml->plink('パスワードを変更する', array('controller' => 'personals', 'action' => 'pass_edit')); ?></span>
                        </li>
                        <li class="last"><?php echo $html->image("bg_dmenu_btm.png" ,array('class'=>'alphafilter'));?>
                        </li>
                    </ul>
                </li>
                </ul>
                <?php }else{ ?>
                <?php if(preg_match('/^personals/', $controller)){ ?>
                <?php echo $html->image('bt_menu06_on.jpg', array('alt' => 'ユーザメニュー')); ?>
                <?php }else{ ?>
                <?php echo $html->image('bt_menu06.jpg', array('alt' => 'ユーザメニュー', 'class' => 'imgover')); ?>
                <?php } ?>
                <ul class="dmenu">
                    <li><span><?php echo $customHtml->plink('パスワードを変更する', array('controller' => 'personals', 'action' => 'pass_edit')); ?></span>
                    </li>
                    <li class="last"><?php echo $html->image("bg_dmenu_btm.png" ,array('class'=>'alphafilter'));?></li>
                </ul>
                <?php } ?>
            </div>
        </div>
        <div id="submenu">
            <div class="login_user">
                <?php echo 'ようこそ: '.h($user['NAME']).' 様';?>
            </div>
        </div>
        <div id="pagetitle">
            <h2><?php echo $html->image('i_arrow_pagetitle.jpg'); ?><?php echo $main_title; ?></h2>
        </div>
        <!-- header_End -->

        <div id="main">
            <?php echo $content_for_layout; ?>
            <div id="popup-bg"></div>
            <div id="popup"></div>
        </div>

        <!-- footer_Start -->
        <div id="footer">
            <?php echo $customHtml->ht2br($footer); ?><br />

            <address>抹茶請求書 ver.<?php echo Configure::read('Version'); ?> <br />Copyright &copy; <?php echo date('Y');?>
                ICZ corporation. All rights reserved.</address>
        </div>
        <!-- footer_End -->
    </div>
</body>
<!-- wrapper_End -->

</html>