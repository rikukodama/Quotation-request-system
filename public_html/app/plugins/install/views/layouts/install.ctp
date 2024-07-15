<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<title><?php echo $main_title; ?> | 抹茶 請求書</title>
<?php echo $html->script("jquery-1.4.4.min")."\n"; ?>
<?php echo $customAjax->postcode(); ?>
<?php echo $html->css("import")."\n"; ?>
</head>
<body>

<!-- wrapper_Start -->
<div id="wrapper">

<!-- header_Start -->
<div id="header" class="clearfix">
	<h1><?php echo $html->image('i_logo.jpg'); ?></h1>
</div>
<br class="clear" />
<div id="submenu">
	<div id="submenu_box"></div>
</div>
<div id="pagetitle">
	<h2><?php echo $html->image('i_arrow_pagetitle.jpg'); ?><?php echo $main_title; ?></h2>
</div>
<!-- header_End -->
<div id="main">
<?php echo $content_for_layout; ?>
</div>

<!-- footer_Start -->
<div id="footer">
	<address>Copyright &copy; <?php echo date('Y')?> ICZ corporation. All rights reserved.</address>
</div>
<!-- footer_End -->
</body>
<!-- wrapper_End -->
</html>