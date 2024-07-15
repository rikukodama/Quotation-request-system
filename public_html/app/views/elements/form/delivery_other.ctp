<h3><div class="edit_03" align="right">
	<span class="show_bt3_on"  style="display:none"><?php echo $html->image('button/hide.png',array('class' =>'imgover','alt'  => 'on', 'onclick' => "return edit3_toggle('on');")); ?></span>
	<span class="show_bt3_off" onClick="return edit3_toggle('off');"><?php echo $html->image('button/show.png',array('class' =>'imgover','alt'  => 'off')); ?></span>
	<span class="edit_txt">&nbsp;</span>
</div></h3>

<div class="contents_box">
<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area4" style="display:none">
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th class="txt_top">発行ステータス</th>
					<td>
					<?php
						if($this->action == 'edit') {
							echo $form->select('STATUS', Configure::read('IssuedStatCode'), null, array('empty' => false));
						} else {
							echo $form->select('STATUS', Configure::read('IssuedStatCode'), 1, array('empty' => false));
						}
					?>
					</td>
				</tr>

			<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error('DELIVERY')?' class="txt_top"':''; ?>>納入場所</th>
					<td>
						<?php echo $form->text('DELIVERY', array('class' => 'w320 mr10'.($form->error('DELIVERY')?' error':''),'maxlength'=>40,'onkeyup' => 'count_strw("delivery_rest", value, 20)')); ?>
						<span id="delivery_rest"></span>
						<br /><span class="usernavi"><?php echo $usernavi['DELIVERY']; ?></span>
						<br /><span class="must"><?php echo $form->error('DELIVERY'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th class="txt_top">備考</th>
					<td>
						<?php echo $form->textarea('NOTE', array('class' => 'textarea'.($form->error('NOTE')?' error':''),'onkeyup' => 'count_strw("note_rest", value, 300)')); ?>
						<br /><span id="note_rest"></span>
						<br /><span class="usernavi"><?php echo $usernavi['NOTE']; ?></span>
						<br /><span class="must"><?php echo $form->error('NOTE'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error('NO')?' class="txt_top"':''; ?>>メモ</th>
					<td>
						<?php echo $form->text('MEMO', array('class' => 'w320'.($form->error('MEMO')?' error':''),'maxlength' => 100,'onkeyup' => 'count_strw("memo_rest", value, 50)')); ?>
						<span id="memo_rest"></span>
						<br /><span class="usernavi"><?php echo $usernavi['MEMO']; ?></span>
						<br /><span class="must"><?php echo $form->error('MEMO'); ?></span>
					</td>
				</tr>
			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg', array('class' => 'block')); ?>
</div>