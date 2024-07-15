<?php	//完了メッセージ
	echo $session->flash();
?>
<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('/img/i_adduser.jpg'); ?>
		<p>こちらのページはユーザ確認の画面です。<br />「編集する」ボタンを押すとユーザを編集できます。</p>
	</div>
</div>
<br class="clear" />
<!-- header_End -->

<!-- contents_Start -->
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="edit_01_administer"><span class="edit_txt">&nbsp;</span></div></h3>
	<?php echo $form->create("Administer", array('type' => 'post', 'action' => 'edit','class'=>'Administer')); ?>
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th>ステータス</th>
					<td>
					<?php echo $status[$params['Administer']['STATUS']]?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" >名前</th>
					<td style="width:710px;">
						<?php echo $customHtml->ht2br($params['Administer']['NAME']);?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" >名前カナ</th>
					<td style="width:710px;">
						<?php echo $customHtml->ht2br($params['Administer']['NAME_KANA']);?>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" >部署名</th>
					<td style="width:710px;">
						<?php echo $customHtml->ht2br($params['Administer']['UNIT']);?>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" >メールアドレス</th>
					<td style="width:710px;">
						<?php echo $customHtml->ht2br($params['Administer']['MAIL']);?>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" >権限</th>
					<td style="width:710px;">
						<?php echo $authority[$params['Administer']['AUTHORITY']];?>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" >ユーザID</th>
					<td style="width:710px;">
						<?php echo $customHtml->ht2br($params['Administer']['LOGIN_ID']);?>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" >パスワード</th>
					<td style="width:710px;">
						<?php echo "************";?>
					</td>
				</tr>

			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>
	<div class="edit_btn">
		    <?php echo $html->link($html->image('bt_edit.jpg',array('class'=>'imgover','alt'=>'編集する')), array('controller' => 'administers', 'action' => 'edit/'.$params['Administer']['USR_ID']),array('escape' => false)); ?>
            <?php echo $form->end(); ?>
            <?php //echo $html->link($html->image('bt_index.jpg',array('class'=>'imgover','alt'=>'一覧')), array('controller' => 'administers', 'action' => 'index'),array('escape' => false)); ?>
            <?php
                echo $form->create($this->name, array('type' => 'post', 'action' => 'moveback', 'style' => 'display:inline;'));
                echo $html->link($html->image('bt_index.jpg',array('class'=>'imgover','alt'=>'一覧')), 'javascript:move_to_index();',array('escape' => false));
                echo $form->end();
            ?>
    </div>
</div>
