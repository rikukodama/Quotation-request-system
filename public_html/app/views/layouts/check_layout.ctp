<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; ?>
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
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-store">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Expires" content="-1">
<link rel="icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
<title><?php echo $customHtml->ht2br($title);?></title>
<?php echo $html->css("import")."\n"; ?>
</head>
<body>

<!-- wrapper_Start -->
<div id="wrapper">

<!-- header_Start -->
<div id="header" class="clearfix">
	<h1><?php echo $html->image($logo, array('alt' => '抹茶請求書' , 'height' => 40)); ?></h1>
</div>
<div id="submenu_no" class="mb20">
<?php echo $html->image('bg_submenu_no.jpg', array('alt' => '抹茶請求書')); ?>
</div>
<br class="clear" />
<!-- header_End -->

<?php echo $content_for_layout; ?>


<!-- footer_Start -->
<div id="footer">
<?php echo $customHtml->ht2br($footer); ?>
	<address>抹茶請求書		ver.<?php echo Configure::read('Version'); ?><br /> Copyright &copy; <?php echo date('Y');?> ICZ corporation. All rights reserved.</address>
</div>
<!-- footer_End -->
</div>
<!-- wrapper_End -->
</body>
</html>