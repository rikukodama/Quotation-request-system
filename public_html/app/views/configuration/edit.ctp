<?php	//完了メッセージ
	echo $session->flash();
?>
<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('/img/company/i_guide.jpg'); ?>
		<p>こちらのページは環境設定編集の画面です。<?php if($user["AUTHORITY"]==0){ ?><br />必要な情報を入力の上「編集する」ボタンを押下すると環境設定を変更できます。<?php } ?></p>
	</div>
</div>
<br class="clear" />
<!-- header_End -->
<!-- contents_Start -->
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
	<h3><div class="edit_02_edit_mail"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<?php echo $form->create("Configuration", array("type" => "file", "controller" => "configurations", "class" => "Configuration")); ?>
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th style="width:160px;"><span class ="float_l">送信者名</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:720px;">
						<?php echo $form->text('FROM_NAME', array('class' => 'w300'.($form->error('FROM_NAME')?' error':''),'maxlength'=>60)); ?>
						<br /><span class="usernavi"><?php echo $usernavi['MAIL_FROM_NAME']; ?></span>
						<br /><span class="must"><?php echo $form->error('FROM_NAME') ; ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:160px;"><span class ="float_l">送信者アドレス</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:720px;">
						<?php echo $form->text('FROM', array('class' => 'w300'.($form->error('FROM')?' error':''),'maxlength'=>30)); ?>
						<br /><span class="usernavi"><?php echo $usernavi['MAIL_FROM']; ?></span>
						<br /><span class="must"><?php echo $form->error('FROM') ; ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:160px;" ><span class ="float_l">SMTPの使用</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:720px;">
						<?php echo $form->input('STATUS', array('label' => false, 'options' => $status));?>
						<br /><span class="usernavi"><?php echo $usernavi['MAIL_SMTP']; ?></span>
					</td>
				</tr>
			</table>
			<div class='Smtpuse'>
			<table >
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" ><span class ="float_l">プロトコル</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:710px;">
						<?php echo $form->input('PROTOCOL', $protocol); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" ><span class ="float_l">SMTPセキュリティ</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:710px;">
						<?php echo $form->input('SECURITY', $security); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" ><span class ="float_l">SMTPサーバ</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:710px;">
						<?php echo $form->text('HOST', array('class' => 'w300'.($form->error('HOST')?' error':''),'maxlength'=>30)); ?>
						<br /><span class="must"><?php echo $form->error('HOST') ; ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" ><span class ="float_l">ポート番号</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:710px;">
						<?php echo $form->text('PORT', array('class' => 'w300'.($form->error('PORT')?' error':''),'maxlength'=>30)); ?>
						<br /><span class="must"><?php echo $form->error('PORT') ; ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>SMTPユーザ</th>
					<td style="width:750px;">
						<?php echo $form->text('USER', array('class' => 'w300'.($form->error('USER')?' error':''),'maxlength'=>30)); ?>
						<br /><span class="usernavi"><?php echo $usernavi['MAIL_SMTP_USER']; ?></span>
						<br /><span class="must"><?php echo $form->error('USER') ; ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>SMTPパスワード</th>
					<td style="width:750px;">
						<?php echo $form->text('PASS', array('class' => 'w300'.($form->error('PASS')?' error':''),'maxlength'=>30)); ?>
						<br /><span class="usernavi"><?php echo $usernavi['MAIL_SMTP_PW']; ?></span>
						<br /><span class="must"><?php echo $form->error('PASS') ; ?></span>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
			</table>
			</div>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>
	<div class="edit_btn">
		<?php
		    echo $form->submit('bt_save.jpg', array('div' => false , 'name' => 'submit', 'alt' => '保存する', 'class' => 'imgover'));
		    echo $form->submit('bt_cancel.jpg', array('div' => false , 'name' => 'cancel', 'alt' => 'キャンセル', 'class' => 'imgover'));

		?>
	</div>
</div>
<?php echo $customHtml->hiddenToken(); ?>
<?php echo $form->hidden("CON_ID", array("value" => "1")); ?>
<?php echo $form->end(); ?>