<?php	//完了メッセージ
	echo $session->flash();
?>
<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('/img/i_adduser.jpg'); ?>
		<p>こちらのページはパスワード変更の画面です。<br />パスワードを入力の上「変更する」ボタンを押すとパスワードを変更することができます。</p>
	</div>
</div>
<br class="clear" />
<!-- header_End -->

<!-- contents_Start -->
<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
<div class="user_reset_box">
<?php echo $form->create('Personal', array('type' => 'post', 'action' => 'pass_edit'));?>
		<div class="user_reset_area">
			<table cellspacing="0" cellpadding="0" border="0" width="600">
				<tr>
					<th>パスワード</th>
					<td>
						<?php echo $form->password('EDIT_PASSWORD',array('class' => 'w200')); ?>
						<br /><span class="usernavi"><?php echo $usernavi['USR_PASSWORD']; ?></span>
						<br /><span class="must"><?php echo $form->error('EDIT_PASSWORD') ?></span>
					</td>
				</tr>
				<tr>
					<th>パスワード確認</th>
					<td>
						<?php echo $form->password('EDIT_PASSWORD1',array('class' => 'w200')); ?>
						<br /><span class="usernavi"><?php echo $usernavi['USR_CPASSWORD']; ?></span>
						<br /><span class="must"><?php echo $form->error('EDIT_PASSWORD') ?></span>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="edit_btn">
			<?php echo $customHtml->hiddenToken(); ?>
		    <?php echo $form->submit('bt_change.jpg', array('div' => false , 'name' => 'submit', 'alt' => '変更する', 'class' => 'imgover')); ?>
		    <?php echo $form->submit('bt_cancel.jpg', array('div' => false , 'name' => 'cancel', 'alt' => 'キャンセル', 'class' => 'imgover')); ?>
	</div>
<?php echo $form->end();?>
<!-- contents_End -->