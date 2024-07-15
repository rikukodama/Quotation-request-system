<?php	//完了メッセージ
	echo $session->flash();
?>
<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('i_guide02.jpg'); ?>
		<p>こちらのページは見積書確認の画面です。<br />「編集する」ボタンを押すと見積書を編集することができます。</p>
	</div>
</div>
<br class="clear" />
<!-- header_End -->

<!-- contents_Start -->
<div id="contents">
	<?php echo $this->element('arrow_under'); ?>
	<?php echo $this->element('form/check_buttons'); ?>
	<?php echo $this->element('form/check_basic_infomation'); ?>
	<?php echo $this->element('arrow_under'); ?>
	<?php echo $this->element('form/check_detail'); ?>
	<?php echo $this->element('arrow_under'); ?>

	<h3><div class="edit_03"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th class="w100">発行ステータス</th>
					<td class="w770"><?php echo $status[$customHtml->ht2br($param['Quote']['STATUS'],'Quote','STATUS')]; ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th class="w100">納入期限</th>
					<td class="w770"><?php echo $customHtml->ht2br($param['Quote']['DEADLINE'],'Quote','DEADLINE'); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th class="w100">取引方法</th>
					<td class="w770"><?php echo $customHtml->ht2br($param['Quote']['DEAL'],'Quote','DEAL'); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th class="w100">納入場所</th>
					<td class="w770"><?php echo $customHtml->ht2br($param['Quote']['DELIVERY'],'Quote','DELIVERY'); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th class="w100">有効期限</th>
					<td class="w770"><?php echo $customHtml->ht2br($param['Quote']['DUE_DATE'],'Quote','DUE_DATE'); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th class="txt_top w100">備考</th>
					<td class="w770"><?php echo $customHtml->ht2br($param['Quote']['NOTE'],'Quote','NOTE'); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th class="w100">メモ</th>
					<td class="w770"><?php echo $customHtml->ht2br($param['Quote']['MEMO'],'Quote','MEMO'); ?></td>
				</tr>
			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>
	</div>
	<?php echo $this->element('arrow_under'); ?>
	<?php //	echo $this->element('form/check_management'); ?>

	<?php echo $this->element('form/check_buttons'); ?>
</div>