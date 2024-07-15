<?php
	echo $session->flash();
?>
<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('/img/company/i_guide.jpg'); ?>
		<p>こちらのページは郵便番号の管理画面です。<br />
		<?php echo $html->link('日本郵便のサイト', 'http://www.post.japanpost.jp/zipcode/dl/kogaki-zip.html', array('target' => '_blank'));?>
		から、「全国一括」をダウンロードし、KEN_ALL.CSVをアップロードしてください。
		</p>
	</div>
</div>
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
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th style="width:200px;">郵便番号件数</th>
					<td>
						<?php echo $count;?>件
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:200px;">CSVデータアップロード</th>
					<td>
					<?php echo $form->file('Post.Csv'); ?>
					<?php echo $form->submit('アップロード', array('onclick' => '$(this).hide();')); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:200px;">郵便番号の修復</th>
					<td>
					更新に失敗した場合は、
					<?php echo $html->link('こちら', array('controller' => 'postcode', 'action' => 'reset')); ?>
					をクリックすると郵便番号を初期状態に戻すことができます。
					</td>
				</tr>
			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>
	<?php echo $form->end();?>
	<div class="edit_btn">
	</div>
</div>
<?php echo $form->hidden("CMP_ID", array("value" => "1")); ?>