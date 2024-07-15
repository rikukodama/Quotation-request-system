<?php echo $this->element('form/scripts'); ?>
<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('i_guide02.jpg'); ?>
		<p>こちらのページは請求書編集の画面です。<br />必要な情報を入力の上「保存する」ボタンを押すと請求書を作成できます。</p>
	</div>
</div>
<br class="clear" />

<!-- contents_Start -->
<div id="contents">
	<?php echo $this->element('form/basic_infomation'); ?>
	<?php echo $this->element('arrow_under'); ?>
	<?php echo $this->element('form/details'); ?>
	<?php echo $this->element('arrow_under'); ?>
	<?php echo $this->element('form/bill_other');?>
	<?php echo $this->element('arrow_under'); ?>
	<?php echo $this->element('form/management');?>
</div>
<!-- contents_End -->

<?php echo '<div id="itemlist" style="display:none;">'.$customHtml->ht2br($itemlist).'</div>'; ?>