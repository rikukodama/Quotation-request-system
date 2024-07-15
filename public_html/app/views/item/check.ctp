<?php	//完了メッセージ
	echo $session->flash();
?>
<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('/img/company/i_guide.jpg'); ?>
		<p>こちらのページは商品確認の画面です。<br />「編集する」ボタンを押すと商品情報を編集することができます。</p>
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
					<th style="width:130px;" >商品</th>
					<td style="width:750px;">
					<?php echo $customHtml->ht2br($params['Item']['ITEM']); ?>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" >商品名カナ</th>
					<td style="width:750px;">
					<?php echo $customHtml->ht2br($params['Item']['ITEM_KANA']); ?>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" >商品コード</th>
					<td style="width:750px;">
					<?php echo $customHtml->ht2br($params['Item']['ITEM_CODE']); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" >単位</th>
					<td style="width:750px;">
					<?php echo $customHtml->ht2br($params['Item']['UNIT']); ?>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" >価格</th>
					<td style="width:750px;">
					<?php echo $customHtml->ht2br($params['Item']['UNIT_PRICE'],null,'UNIT_PRICE'); ?>
					</td>

				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" >税区分</th>
					<td style="width:750px;">
					<?php echo $excises[$params['Item']['TAX_CLASS']] ?>
					</td>
				</tr>
			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>
	<div class="edit_btn">
		<?php if($editauth){?>
			<?php echo $html->link($html->image('bt_edit.jpg',array('class'=>'imgover','alt'=>'編集する')), array('controller' => 'items', 'action' => 'edit/'.$params['Item']['ITM_ID']),array('escape' => false));?>
		<?php }?>
        <?php echo $form->hidden("ITM_ID"); ?>
<?php echo $form->end(); ?>
		    <?php //echo $html->link($html->image('bt_index.jpg', array('class'=>'imgover', 'alt' => '一覧',)),array('controller' => 'items', 'action' => 'index'),array('escape' => false)); ?>
            <?php
                echo $form->create($this->name, array('type' => 'post', 'action' => 'moveback', 'style' => 'display:inline;'));
                echo $html->link($html->image('bt_index.jpg',array('class'=>'imgover','alt'=>'一覧')), 'javascript:move_to_index();',array('escape' => false));
                echo $form->end();
            ?>
	</div>
</div>
