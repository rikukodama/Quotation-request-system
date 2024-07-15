<?php
	echo $session->flash();
?>
<br class="clear" />
<!-- header_End -->
<!-- contents_Start -->
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
	<h3><div class="edit_02_edit_postcode"><span class="edit_txt">&nbsp;</span></div></h3>
	<?php echo $form->create(array('action' => 'update', 'type' => 'file'));?>
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<?php echo $html->link($sqlRes['count']."件のデータを".$action."します。", array('action' => 'query', 'sql' => urlencode($sqlRes['sql']), 'backup' => urlencode($backup))); ?>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>
	<?php echo $form->end();?>
	<div class="edit_btn">
	</div>
</div>
<?php echo $form->hidden("CMP_ID", array("value" => "1")); ?>