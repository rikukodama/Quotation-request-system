<?php //帳票管理の商品登録ポップアップ画面 ?>
<?php echo $html->css("popup")."\n"; ?>
<form id="popupForm">
<div id="popup_contents">
	<?php echo $html->image('/img/popup/tl_entry.jpg',array('style'=>'padding-bottom:10px;')); ?>
	<?php echo $form->hidden('type',array('value' => 'customer')); ?>
	<div class="popup_contents_box">
		<div class="popup_contents_area clearfix">
			<table width="440" cellpadding="0" cellspacing="0" border="0">
				<tr class="popup_item">
					<th style="width:130px;">商品<?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10')); ?></th>
					<td style="width:310px;"><?php echo $form->text("ITEM",array('class'=>'w300','maxlength' => 60)); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('/img/popup/i_line_solid.gif'); ?></td></tr>
				<tr class="popup_item_kana">
					<th style="width:130px;">商品名カナ</th>
					<td style="width:310px;"><?php echo $form->text("ITEM_KANA",array('class'=>'w300','maxlength' => 50)); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('/img/popup/i_line_solid.gif'); ?></td></tr>
				<tr class="popup_item_code">
					<th style="width:130px;">商品コード</th>
					<td style="width:310px;"><?php echo $form->text("ITEM_CODE",array('class'=>'w300','maxlength' => 8)); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('/img/popup/i_line_solid.gif'); ?></td></tr>
				<tr class="popup_unit">
					<th style="width:130px;">単位</th>
					<td style="width:310px;"><?php echo $form->text("UNIT",array('class'=>'w300','maxlength' => 8)); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('/img/popup/i_line_solid.gif'); ?></td></tr>
				<tr class="popup_unitprice">
					<th style="width:130px;">価格</th>
					<td style="width:310px;"><?php echo $form->text("UNIT_PRICE",array('class'=>'w300','maxlength' => 8)); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('/img/popup/i_line_solid.gif'); ?></td></tr>
				<tr class="">
					<th style="width:130px;">税区分</th>
					<td style="width:310px;"><?php echo $form->radio("TAX_CLASS",array(2=>'外税',1=>'内税',  3=>'非課税'),array('value'=>$TaxClass , 'legend'=>false,'separator'=>'　')); ?></td>
				</tr>
			</table>
			<div class="save_btn">
				<?php echo $form->hidden('type', array('value' => 'item')); ?>
				<?php echo $html->link($html->image('bt_save2.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupinsert(\'item\')',null,false)); ?>
				<?php echo $html->link($html->image('bt_cancel_s.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popup_close();',null,false)); ?>
				<?php echo $customHtml->hiddenToken(); ?>
				<?php echo $form->hidden("USR_ID",array('value'=>$user['USR_ID'])); ?>
				<?php echo $form->hidden("UPDATE_USR_ID",array('value'=>$user['USR_ID'])); ?>
			</div>
		</div>
	</div>
</div>
</form>