<?php
	$formType = $this->name;
	$controller = '';
	$action = '';
	switch($this->name) {
		case 'Quote':
			$controller = 'quotes';
			break;
		case 'Bill':
			$controller = 'bills';
			break;
		case 'Delivery':
			$controller = 'deliveries';
			break;
	}

	switch($this->params['action']) {
		case 'add':
			$action = 'Add';
			break;

		case 'edit':
			$action = 'Edit';
			break;
	}
	echo $form->create($formType, array("type" => "post", "controller" => $controller, "class" => $formType));
?>
<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
<div><?php echo $html->image('/img/document/i_flow.jpg',array('alt'  => '作成の流れ')); ?></div>
<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>


<h3><div class="edit_01"  align="right">
	<span class="edit_txt">&nbsp;</span>
	<span class="show_bt1_on" ><?php echo $html->image('button/hide.png',array('class' =>'imgover','alt'  => 'on', 'onclick' => "return edit1_toggle('on');")); ?> </span>
	<span class="show_bt1_off" style="display:none" onClick="return edit1_toggle('off');"><?php echo $html->image('button/show.png',array('class' =>'imgover', 'alt'  => 'off')); ?></span>
	<span class="edit_txt">&nbsp;</span>
</div></h3>


<div class="contents_box">
	<?php echo $html->image('bg_contents_top.jpg'); ?>
	<div class="contents_area">
		<table width="880" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<th<?php echo $form->error('NO')?' class="txt_top"':''; ?>>管理番号</th>
				<td width="320">
					<?php echo $form->text('NO' ,array('class' => 'w180 p2'.($form->error('NO')?' error':''),'maxlength'=>20)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['NO']; ?></span>
					<br /><span class="must"><?php echo $form->error('NO'); ?></span>
				</td>
				<th<?php echo $form->error('DATE')?' class="txt_top"':''; ?>><span class ="float_l">発行日</span><?php echo $html->image('i_must.jpg', array('alt' => '必須', 'class' => 'pl10 mr10 float_r')); ?></th>
				<td width="320">
					<script language=JavaScript>
						<!--
							var lastDate = '';
					    	var cal1 = new JKL.Calendar("calid", "<?php echo $formType.$action;?>Form", "data[<?php echo $formType;?>][DATE]");
							setInterval(function(){
								var date = $('input.cal.date').val();
								if(lastDate != date){
									lastDate = date;
									var calcDate = new Date(date);
									if(calcDate.getFullYear() >= 2024 || (calcDate.getFullYear() >= 2023 && calcDate.getMonth() >= 9)){
										$('#<?= $this->name ?>TAXFRACTIONTIMING1').attr('disabled', true);
										$('#<?= $this->name ?>TAXFRACTIONTIMING0').click();
									} else {
										$('#<?= $this->name ?>TAXFRACTIONTIMING1').removeAttr('disabled', true);
									}
								}
							},1);
						//-->
					</script>
					<?php echo $form->input('DATE', array('label' => false, 'div' => false,'onChange' => 'cal1.getFormValue(); cal1.hide();', 'readonly'=>'readonly','error'=>false, 'class' => 'w100 p2 date cal'.($form->error('DATE')?' error':''))); ?>
					<?php echo $html->image('bt_now.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'nowtime')); ?>
					<?php echo $html->image('bt_calender.jpg', array('alt' => 'カレンダー', 'url' => '#','onClick' => 'return cal1.write();','class' => 'pl5')); ?><div id="calid"></div>
				</td>
			</tr>
            
			<tr>
				<td colspan="3"></td>
				<td><span class="usernavi"><?php echo $usernavi['DATE']; ?></span>
				<br /><span class="must"><?php echo $form->error('ISSUE_DATE'); ?></span></td>
			</tr>


			<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>


			<tr>
				<th<?php echo $form->error('SUBJECT')?' class="txt_top"':''; ?>><span class ="float_l">件名</span><?php echo $html->image('i_must.jpg', array('alt' => '必須', 'class' => 'pl10 mr10 float_r')); ?></th>
				<td colspan="3">
					<?php echo $form->text('SUBJECT' ,array('class' => 'w320 mr10'.($form->error('SUBJECT')?' error':''),'maxlength'=>80, 'onkeyup' => 'count_strw("subject_rest", value, 40)')); ?>
					<span id="subject_rest"></span>
					<br /><span class="usernavi"><?php echo $usernavi['SUBJECT']; ?></span>
					<br /><span class="must"><?php echo $form->error('SUBJECT'); ?></span>
				</td>
			</tr>


			<tr><td colspan="4" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

			<tr>
				<th style="width:170px;" <?php echo $form->error('CST_ID')?' class="txt_top"':''; ?>><span class ="float_l">顧客名</span><?php echo $html->image('i_must.jpg', array('alt' => '必須', 'class' => 'pl10 mr10 float_r')); ?></th>
				<td id="SETCUSTOMER" style="width:270px;">
					<?php echo $form->input('CUSTOMER_NAME', array('label' => false, 'div' => false, 'readonly'=>'readonly', 'class' => 'w130'.($form->error('CST_ID')?' error':''))); ?>
					<?php echo $form->hidden('CST_ID'); ?>
					<?php echo $html->link($html->image('bt_select2.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'select_customer\');')); ?>
					<?php echo $html->link($html->image('bt_delete2.jpg'),'#',array('escape' => false, 'onclick'=>'return customer_reset();')); ?>
					<br /><span class="usernavi"><?php echo $usernavi['CST_ID']; ?></span>
					<br /><span class="must"><?php echo $form->error('CST_ID'); ?></span>
				</td>
				<th style="width:120px;"<?php echo $form->error('NO')?' class="txt_top"':''; ?>>顧客担当者名</th>
				<td style="width:270px;" id="SETCUSTOMERCHARGE">
					<?php echo $form->text('CUSTOMER_CHARGE_NAME' ,array('class' => 'w120 p2'.($form->error('CHRC_ID')?' error':''),'maxlength'=>30,'readonly' => 'readonly')); ?>
					<?php echo $form->hidden('CHRC_ID'); ?>
					<?php echo $html->link($html->image('bt_select2.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'customer_charge\');')); ?>
					<?php echo $html->link($html->image('bt_delete2.jpg'),'#',array('escape' => false, 'onclick'=>'return cstchr_reset();')); ?>
					<br /><span class="must"><?php echo $form->error('CHRC_ID'); ?></span>
				</td>
			</tr>

			<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

			<tr>
				<th style="width:170px;"<?php echo $form->error('NO')?' class="txt_top"':''; ?>>自社担当者名</th>
				<td style="width:270px;" id="SETCHARGE" colspan="3">
				<?php echo $form->text('CHARGE_NAME' ,array('class' => 'w120 p2'.($form->error('CHR_ID')?' error':''),'maxlength'=>30, 'readonly' => 'readonly')); ?>
				<?php echo $form->hidden('CHR_ID'); ?>
				<?php echo $html->link($html->image('bt_select2.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'charge\');')); ?>
				<?php echo $html->link($html->image('bt_delete2.jpg'),'#',array('escape' => false, 'onclick'=>'return chr_reset();')); ?>
				<br /><span class="must"><?php echo $form->error('CHR_ID'); ?></span>
				</td>
			</tr>

			<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

			<tr>
				<th<?php echo $form->error('HONOR_TITLE')?' class="txt_top"':''; ?>><span class ="float_l">敬称</span></th>
				<td id="HONOR" colspan="3">
					<?php echo $form->radio('HONOR_CODE', $honor, array('label' => false, 'legend' => false, 'class' => 'ml20 mr5 txt_mid')); ?>
					<?php echo $form->text('HONOR_TITLE' ,array('class' => 'w160 mr10'.($form->error('HONOR_TITLE')?' error':''),'maxlength'=>8)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['HONOR']; ?></span>
					<br /><span class="must"><?php echo $form->error('HONOR_TITLE'); ?></span>
				</td>
			</tr>

			<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
			<tr>
				<th<?php echo $form->error('CMP_SEAL_FLG')?' class="txt_top"':''; ?>><span class ="float_l">自社印押印設定</span></th>
				<td id="SET_CMP_SEAL_FLG" colspan="3">
					<?php echo $form->radio('CMP_SEAL_FLG', $seal_flg, array('label' => false, 'legend' => false, 'class' => 'ml20 mr5 txt_mid')); ?>
					<br /><span class="usernavi"><?php echo $usernavi['SEAL_FLG']; ?></span>
					<br /><span class="must"><?php echo $form->error('CMP_SEAL_FLG'); ?></span>
				</td>
			</tr>
			<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
			<tr>
				<th<?php echo $form->error('CHR_SEAL_FLG')?' class="txt_top"':''; ?>><span class ="float_l">担当者印押印設定</span></th>
				<td id="SET_CHR_SEAL_FLG" colspan="3">
					<?php echo $form->radio('CHR_SEAL_FLG', $seal_flg, array('label' => false, 'legend' => false, 'class' => 'ml20 mr5 txt_mid')); ?>
					<br /><span class="usernavi"><?php echo $usernavi['SEAL_FLG']; ?></span>
					<br /><span class="must"><?php echo $form->error('CHR_SEAL_FLG'); ?></span>
				</td>
			</tr>
	</table>
	</div>
	<?php echo $html->image('bg_contents_bottom.jpg', array('class' => 'block')); ?>
</div>
