<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('/img/i_adduser.jpg'); ?>
		<p>こちらのページはユーザ登録の画面です。<br />必要な情報を入力の上「保存する」ボタンを押すとユーザの変更を保存することができます。</p>
	</div>
</div>
<br class="clear" />
<!-- header_End -->

<!-- contents_Start -->
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="edit_01_administer"><span class="edit_txt">&nbsp;</span></div></h3>
	<?php echo $form->create("Administer", array('type' => 'post', 'action' => 'add','class'=>'Administer')); ?>
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th style="width:170px:">ステータス</th>
					<td>
					<?php echo $form->input('STATUS', array('label' => false, 'options' => $status));?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" <?php echo $form->error("NAME")?' class="txt_top"':''; ?>><span class ="float_l">名前</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:710px;">
					<?php echo $form->text('NAME', array('class' => 'w300'.($form->error('NAME')?' error':''),'maxlength'=>60)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['USR_NAME']; ?></span>
					<br /><span class="must"><?php echo $form->error('NAME'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" <?php echo $form->error("NAME_KANA")?' class="txt_top"':''; ?>>名前カナ</th>
					<td style="width:710px;">
					<?php echo $form->text('NAME_KANA', array('class' => 'w300'.($form->error('NAME_KANA')?' error':''),'maxlength'=>60)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['USR_NAME_KANA']; ?></span>
					<br /><span class="must"><?php echo $form->error('NAME_KANA'); ?></span>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" <?php echo $form->error("UNIT")?' class="txt_top"':''; ?>>部署名</th>
					<td style="width:710px;">
					<?php echo $form->text('UNIT', array('class' => 'w300'.($form->error('UNIT')?' error':''),'maxlength'=>60)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['UNIT']; ?></span>
					<br /><span class="must"><?php echo $form->error('UNIT'); ?></span>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" <?php echo $form->error("MAIL")||$error['MAIL']==1?' class="txt_top"':''; ?>><span class ="float_l">メールアドレス</span></th>
					<td style="width:710px;">
					<?php echo $form->text('MAIL', array('class' => 'w300'.($form->error('MAIL')||$error['MAIL']==1||$error['MAIL']==2?' error':''),'maxlength'=>256)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['USR_MAIL']; ?></span>
					<br /><span class="must"><?php echo $form->error('MAIL'); ?></span>
					<br />
					<span class="must">
					<?php
						if($error['MAIL'] == 1){
							echo 'そのメールアドレスは既に使われています';
						}else if($error['MAIL'] == 2) {
							echo '有効なメールアドレスではありません';
						}
					?>
					</span>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" <?php echo $form->error("AUTHORITY")?' class="txt_top"':''; ?>>権限</th>
					<td style="width:710px;">
					<?php echo $form->input('AUTHORITY', array('label' => false, 'options' => $authority)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['AUTHORITY']; ?></span>
					<br /><span class="must"><?php echo $form->error('AUTHORITY'); ?></span>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" <?php echo $error['LOGIN_ID']==1?' class="txt_top"':''; ?>><span class ="float_l">ユーザID</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:710px;">
					<?php echo $form->text('LOGIN_ID', array('class' => 'w300'.($error['LOGIN_ID']==1||$form->error('LOGIN_ID')?' error':''),'maxlength'=>10)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['USR_ID']; ?></span>
					<br /><span class="must"><?php echo ($error['LOGIN_ID']==1? 'ユーザIDが既に使用されています':'') ?></span>
					<br /><span class="must"><?php echo $form->error('LOGIN_ID'); ?></span>
					<div><?php echo $ajax->div("target"); ?><?php echo $ajax->divEnd("target"); ?></div>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" <?php echo $form->error("EDIT_PASSWORD")?' class="txt_top"':''; ?>><span class ="float_l">パスワード</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:710px;">
					<?php echo $form->password('EDIT_PASSWORD', array('class' => 'w300'.($form->error('EDIT_PASSWORD')?' error':''),'maxlength'=>20)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['USR_PASSWORD']; ?></span>
					<br /><span class="must"><?php echo $form->error('EDIT_PASSWORD'); ?></span>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" <?php echo $form->error("EDIT_PASSWORD1")?' class="txt_top"':''; ?>><span class ="float_l">パスワード(確認)</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:710px;">
					<?php echo $form->password('EDIT_PASSWORD1', array('class' => 'w300'.($form->error('EDIT_PASSWORD1')?' error':''),'maxlength'=>20)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['USR_CPASSWORD']; ?></span>
					<br /><span class="must"><?php echo $form->error('EDIT_PASSWORD1'); ?></span>
					</td>
				</tr>

			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>
	<div class="edit_btn">
			<?php echo $customHtml->hiddenToken(); ?>
		    <?php echo $form->submit('bt_save.jpg', array('div' => false , 'name' => 'submit', 'alt' => '保存する', 'class' => 'imgover')); ?>
		    <?php echo $form->submit('bt_cancel.jpg', array('div' => false , 'name' => 'cancel', 'alt' => 'キャンセル', 'class' => 'imgover')); ?>
	</div>
</div>
<?php echo $form->end(); ?>