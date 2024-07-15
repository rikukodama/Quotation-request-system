<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('/img/company/i_guide.jpg'); ?>
		<p>こちらのページは商品登録の画面です。<br />必要な情報を入力の上「保存する」ボタンを押下すると商品の変更を保存できます。</p>
	</div>
</div>
<br class="clear" />
<!-- header_End -->

<!-- contents_Start -->
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="edit_01_item"><span class="edit_txt">&nbsp;</span></div></h3>
	<?php echo $form->create("Item", array('type' => 'post', 'action' => 'edit','class'=>'Item')); ?>
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th style="width:130px;" <?php echo $form->error("ITEM")?' class="txt_top"':''; ?>>商品<?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10')); ?></th>
					<td style="width:750px;">
					<?php echo $form->text('ITEM', array('class' => 'w300'.($form->error('ITEM')?' error':''),'maxlength'=>80)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['ITEM']; ?></span>
					<br /><span class="must"><?php echo $form->error('ITEM'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" <?php echo $form->error("ITEM")?' class="txt_top"':''; ?>>商品名カナ</th>
					<td style="width:750px;">
					<?php echo $form->text('ITEM_KANA', array('class' => 'w300'.($form->error('ITEM_KANA')?' error':''),'maxlength'=>50)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['ITEM_KANA']; ?></span>
					<br /><span class="must"><?php echo $form->error('ITEM_KANA'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" <?php echo $form->error("ITEM_CODE")?' class="txt_top"':''; ?>>商品コード</th>
					<td style="width:750px;">
					<?php echo $form->text('ITEM_CODE', array('class' => 'w300'.($form->error('ITEM_CODE')?' error':''),'maxlength'=>8)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['ITEM_CODE']; ?></span>
					<br /><span class="must"><?php echo $form->error('ITEM_CODE'); ?></span>
					</td>

				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" <?php echo $form->error("UNIT")?' class="txt_top"':''; ?>>単位</th>
					<td style="width:750px;">
					<?php echo $form->text('UNIT', array('class' => 'w300'.($form->error('UNIT')?' error':''),'maxlength'=>8)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['ITM_UNIT']; ?></span>
					<br /><span class="must"><?php echo $form->error('UNIT'); ?></span>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" <?php echo $form->error("UNIT_PRICE")?' class="txt_top"':''; ?>>価格</th>
					<td style="width:750px;">
					<?php echo $form->text('UNIT_PRICE', array('class' => 'w300'.($form->error('UNIT_PRICE')?' error':''),'maxlength'=>9)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['ITM_PRICE']; ?></span>
					<br /><span class="must"><?php echo $form->error('UNIT_PRICE'); ?></span>
					</td>

				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" <?php echo $form->error("TAX_CLASS")?' class="txt_top"':'' ?>>税区分</th>
					<td style="width:750px;">
					<?php echo $form->radio('TAX_CLASS', $excises, array('legend'=>false,'separator'=>' ')); ?>
					<br /><span class="usernavi"><?php echo $usernavi['TAX_CLASS']; ?></span>
					<br /><span class="must"><?php echo $form->error("TAX_CLASS"); ?></span>
					</td>

				</tr>
			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>
	<div class="edit_btn">
		    <?php echo $form->submit('bt_save.jpg', array('div' => false , 'name' => 'submit', 'alt' => '保存する', 'class' => 'imgover imgcheck')); ?>
		    <?php echo $form->submit('bt_cancel.jpg', array('div' => false , 'name' => 'cancel', 'alt' => 'キャンセル', 'class' => 'imgover imgcheck')); ?>
	</div>
</div>
<?php echo $customHtml->hiddenToken(); ?>
<?php echo $form->hidden("UPDATE_USR_ID",array('value'=>$user['USR_ID'])); ?>
<?php echo $form->hidden("ITM_ID"); ?>
<?php echo $form->end(); ?>