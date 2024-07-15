<?php echo $html->css("popup")."\n"; ?>
<?php //帳票管理の顧客登録ポップアップ画面 ?>
<form id="popupForm">
<div id="popup_contents">
	<?php echo $html->image('/img/popup/tl_customercharge.jpg',array('style'=>'padding-bottom:10px;')); ?>
	<?php echo $form->hidden('type',array('value' => 'customer_charge')); ?>
	<div class="popup_contents_box">
		<div class="popup_contents_area clearfix">
			<table width="440" cellpadding="0" cellspacing="0" border="0">
				<tr class="popup_cname">
					<th style="width:130px;">担当者名<?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10')); ?></th>
					<td style="width:310px;"><?php echo $form->text("CHARGE_NAME",array('class'=>'w300', 'maxlength' => 60)); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('/img/popup/i_line_solid.gif'); ?></td></tr>
				<tr class="popup_ckname">
					<th>担当者名（カナ） </th>
					<td><?php echo $form->text("CHARGE_NAME_KANA",array('class'=>'w300','maxlength' => 100)); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('/img/popup/i_line_solid.gif'); ?></td></tr>

				<tr class="popup_cunit">
				<th style="width:130px;">担当者部署名 </th>
					<td style="width:310px;"><?php echo $form->text("UNIT",array('class'=>'w300', 'maxlength' => 60)); ?></td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('/img/popup/i_line_solid.gif'); ?></td></tr>
				<tr class="popup_postcode">
					<th>郵便番号 </th>
					<td>
					<?php echo $form->text("POSTCODE1" , array("class" => "w60 zip",'maxlength' => 3)); ?>
					<span class="pl5 pr5">-</span>
					<?php echo $form->text("POSTCODE2" , array("class" => "w80 zip",'maxlength' => 4)); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('/img/popup/i_line_solid.gif'); ?></td></tr>
				<tr class="popup_county">
					<th>都道府県 </th>
					<td><?php echo $form->input('CNT_ID', array('label' => false, 'options' => $countys)); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('/img/popup/i_line_solid.gif'); ?></td></tr>
				<tr class="popup_address">
					<th>住所 </th>
					<td><?php echo $form->text("ADDRESS",array('class'=>'w300','maxlength' => 100)); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('/img/popup/i_line_solid.gif'); ?></td></tr>
				<tr class="popup_phone">
					<th>電話番号 </th>
					<td><?php echo $form->text("PHONE_NO1", array("class" => "w60 phone",'maxlength' => 4)); ?>
		 			- <?php echo $form->text("PHONE_NO2", array("class" => "w60 phone",'maxlength' => 4)); ?>
		 			- <?php echo $form->text("PHONE_NO3", array("class" => "w60 phone",'maxlength' => 4)); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('/img/popup/i_line_solid.gif'); ?></td></tr>
			</table>
			<div class="save_btn">
				<?php echo $html->link($html->image('bt_save2.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupinsert(\'customer_charge\')',null,false)); ?>
				<?php echo $html->link($html->image('bt_cancel_s.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popup_close();',null,false)); ?>
				<?php echo $customHtml->hiddenToken(); ?>
				<?php echo $form->hidden("USR_ID",array('value'=>$user['USR_ID'])); ?>
				<?php echo $form->hidden("UPDATE_USR_ID",array('value'=>$user['USR_ID'])); ?>
				<?php echo $form->hidden("CST_ID", array('value' =>$cst_id)); ?>
			</div>
		</div>
	</div>
</div>
</form>
