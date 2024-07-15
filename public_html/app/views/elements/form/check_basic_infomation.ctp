<?php
$formType = $this->name;
$formID = '';
$formController = '';
$mailAction = '';

switch($this->name) {
	case 'Quote':
		$formID = 'MQT_ID';
		$formController = 'quotes';
		$mailAction = 'quote';
		break;
	case 'Bill':
		$formID = 'MBL_ID';
		$formController = 'bills';
		$mailAction = 'bill';
		break;
	case 'Delivery':
		$formID = 'MDV_ID';
		$formController = 'deliveries';
		$mailAction = 'delivery';
		break;
}
?>
<h3><div class="edit_01"><span class="edit_txt">&nbsp;</span></div></h3>
<div class="contents_box">
	<?php echo $html->image('bg_contents_top.jpg'); ?>
	<div class="contents_area">
		<table width="880" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<th class="w100">管理番号</th>
				<td class="w320"><?php echo $customHtml->ht2br($param[$formType]['NO'], $formType, 'NO'); ?></td>
				<th class="w100">発行日</th>
				<td class="w320"><?php echo $customHtml->df($param[$formType]['ISSUE_DATE']);?></td>
			</tr>
			<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
			<tr>
				<th class="txt_top w100">件名</th>
				<td colspan="3"><?php echo $customHtml->ht2br($param[$formType]['SUBJECT'],'Quote','SUBJECT'); ?></td>
			</tr>
			<tr><td colspan="4" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
			<tr>
				<th class="w100">顧客名</th>
				<td colspan="3"><?php echo $customHtml->ht2br($param['Customer']['NAME'],'Customer','NAME'); ?></td>
			</tr>
			<tr><td colspan="4" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
			<tr>
				<th class="w100">顧客担当者名</th>
				<td colspan="3"><?php  if(isset($param['CustomerCharge']['CHARGE_NAME'])) echo $customHtml->ht2br($param['CustomerCharge']['CHARGE_NAME'],'CustomerCharge','CHARGE_NAME'); ?></td>
			</tr>
			<tr><td colspan="4" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
			<tr>
				<th class="w100">自社担当者名</th>
				<td colspan="3"><?php if(isset($param['Charge']['NAME'])) echo $customHtml->ht2br($param['Charge']['NAME'],'Charge','NAME'); ?></td>
			</tr>
			<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
			<tr>
				<th class="txt_top w100">敬称</th>
				<td colspan="3">
				<?php
					if($param[$formType]['HONOR_CODE'] == 2) {
						echo $param[$formType]['HONOR_TITLE'];
					}else {
						echo $honor[$param[$formType]['HONOR_CODE']];
					}
				?>
				</td>
			</tr>
			<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
			<tr>
				<th class="txt_top w100">自社印押印設定</th>
				<td colspan="3"><?php echo $seal_flg[$param[$formType]['CMP_SEAL_FLG']]?></td>
			</tr>
			<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
			<tr>
				<th class="txt_top w100">担当者印押印設定</th>
				<td colspan="3"><?php echo $seal_flg[$param[$formType]['CHR_SEAL_FLG']]?></td>
			</tr>
			<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
		</table>
	</div>
	<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>
</div>
