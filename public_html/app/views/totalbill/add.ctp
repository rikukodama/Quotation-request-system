<script type="text/javascript">
<!--
	function customer_reset() {
		$('#SETCUSTOMER').children('input[type=text]').val('');
		$('#SETCUSTOMER').children('input[type=hidden]').val('');
		return false;
	}

function cstchr_reset() {
	$('#SETCUSTOMERCHARGE').children('input[type=text]').val('');
	$('#SETCUSTOMERCHARGE').children('input[type=text]').removeAttr('readonly')
	$('#SETCUSTOMERCHARGE').children('input[type=hidden]').val('');
	$('#SETCCUNIT').children('input[type=text]').val('');
	$('#SETCCUNIT').children('input[type=text]').removeAttr('readonly')
	return false;
}
// -->
</script>

<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('i_guide02.jpg'); ?>
		<p>こちらのページは合計請求書作成の画面です。<br />必要な情報を入力の上「保存する」ボタンを押すと合計請求書を作成できます。</p>
	</div>
</div>
<br class="clear" />

<!-- contents_Start -->
<div id="contents">
  <?php echo $form->create("Totalbill", array("type" => "post", "controller" => "totalbills", "class"=>"Totalbill", 'action' => 'add')); ?>
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
	<h3><div class="edit_01"><span class="edit_txt">&nbsp;</span></div></h3>
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
						    var cal1 = new JKL.Calendar("calid", "TotalbillAddForm", "data[Totalbill][DATE]");
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
            <?php echo $form->text('SUBJECT' ,array('class' => 'w320 mr10'.($form->error('SUBJECT')?' error':''),'maxlength'=>80,'onkeyup' => 'count_str("subject_rest", value, 40)')); ?>
						<span id="subject_rest"></span>
						<br /><span class="usernavi"><?php echo $usernavi['SUBJECT']; ?></span>
						<br /><span class="must"><?php echo $form->error('SUBJECT'); ?></span>
          </td>
				</tr>
				<tr><td colspan="4" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error('CST_ID')?' class="txt_top"':''; ?>><span class ="float_l">顧客名</span><?php echo $html->image('i_must.jpg', array('alt' => '必須', 'class' => 'pl10 mr10 float_r')); ?></th>
						<td id="SETCUSTOMER" colspan="3">
							<?php echo $form->input('CUSTOMER_NAME', array('label' => false, 'div' => false, 'readonly'=>'readonly', 'class' => 'w130'.($form->error('CST_ID')?' error':''))); ?>
							<?php echo $form->hidden('CST_ID'); ?>
							<?php echo $html->link($html->image('bt_select2.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'select_customer\');')); ?>
							<?php echo $html->link($html->image('bt_delete2.jpg'),'#',array('escape' => false, 'onclick'=>'return customer_reset();')); ?>
						<br /><span class="usernavi"><?php echo $usernavi['CST_ID']; ?></span>
						<br /><span class="must"><?php echo $form->error('CST_ID'); ?></span>
					</td>
				</tr>
				<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;"<?php echo $form->error('NO')?' class="txt_top"':''; ?>>顧客担当者名</th>
					<td style="width:270px;" id="SETCUSTOMERCHARGE">
					<?php echo $form->text('CUSTOMER_CHARGE_NAME' ,array('class' => 'w120 p2'.(isset($error['CUSTOMER_CHARGE_NAME'])?' error':''), 'readonly'=>'readonly')); ?>
					<?php echo $form->hidden('CHRC_ID'); ?>
					<?php echo $html->link($html->image('bt_select2.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'customer_charge\');')); ?>
					<?php echo $html->link($html->image('bt_delete2.jpg'),'#',array('escape' => false, 'onclick'=>'return cstchr_reset();')); ?>
					<br /><span class="must"><?php if(isset($error['CUSTOMER_CHARGE_NAME'])){echo $error['CUSTOMER_CHARGE_NAME'];}; ?></span>
					</td>
					<th style="width:170px;">担当者部署名</th>
					<td style="width:270px;" id="SETCCUNIT">
					<?php echo $form->text('CUSTOMER_CHARGE_UNIT' ,array('class' => 'w180 p2'.(isset($error['CUSTOMER_CHARGE_UNIT'])?' error':''), 'readonly'=>'readonly')); ?>
					<br /><span class="must"><?php if(isset($error['CUSTOMER_CHARGE_UNIT'])){echo $error['CUSTOMER_CHARGE_UNIT'];}; ?></span>
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
					<th<?php echo $form->error('DUE_DATE')?' class="txt_top"':''; ?>><span class ="float_l">振込期限</span></th>
					<td colspan="3">
            <?php echo $form->text('DUE_DATE' ,array('class' => 'w320 mr10'.($form->error('DUE_DATE')?' error':''),'maxlength'=>20,'onkeyup' => 'count_str("duedate_rest", value, 20)')); ?>
						<span id="duedate_rest"></span>
						<br /><span class="usernavi"><?php echo $usernavi['DUE_DATE']; ?></span>
						<br /><span class="must"><?php echo $form->error('DUE_DATE'); ?></span>
          </td>
				</tr>
			</table>
		</div>
    <?php echo $html->image('bg_contents_bottom.jpg', array('class' => 'block')); ?>
	</div>
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
	<div class="listview">
		<h3><div class="edit_02_bill"><span class="edit_txt">&nbsp;</span></div></h3>
		<div class="contents_box mb40">
			<?php echo $html->image('bg_contents_top.jpg'); ?>
			<div class="list_area">


			<?php
				if(isset($billlist)&&is_array($billlist)){
					echo '<table width="900" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<th class="w150">請求書番号</th>
						<th class="w200">件名</th>
						<th class="w250">顧客名</th>
						<th class="w100">発行日</th>
						<th class="w100">振込期限</th>
						<th class="w100">合計金額</th>
					<tr>';
					$i=0;
					foreach($billlist as $key => $val){
						echo "<tr>
						<td>".$customHtml->ht2br(($val['Bill']['NO'])?$val['Bill']['NO']:'　','Totalbill','NO')."</td>
						<td>".$customHtml->ht2br($val['Bill']['SUBJECT'],'Totalbill','NO')."</td>
						<td>".$customHtml->ht2br($val['Customer']['NAME'],'Totalbill','NAME')."</td>
						<td>".$customHtml->ht2br($val['Bill']['ISSUE_DATE'],'Totalbill','ISSUE_DATE')."</td>
						<td>".$customHtml->ht2br(($val['Bill']['DUE_DATE'])?$val['Bill']['DUE_DATE']:'　','Totalbill','DUE_DATE')."</td>
						<td id=TOTAL".$i.">".(isset($val['Bill']['TOTAL'])?$customHtml->ht2br($val['Bill']['TOTAL'],'Totalbill','TOTAL'):'　')."</td>";
						echo '<div id="tax'.$i.'" style="display:none;">'.$customHtml->ht2br($val['Bill']['SALES_TAX']).'</div>';
						echo '<div id="subtotal'.$i.'" style="display:none;">'.$customHtml->ht2br($val['Bill']['SUBTOTAL']).'</div>';
						echo "</tr>";
						$i++;
					}
					echo '</table>';
				}
			?>
			</div>
	</div>
	</div>

	<div class="listview hidebox_d">
		<h3><div class="edit_02_abstract"><span class="edit_txt">&nbsp;</span></div></h3>

		<div class="contents_box mb40">
			<?php echo $html->image('bg_contents_top.jpg'); ?>
			<div class="list_area">
				<?php
						echo '<table width="900" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<th class="w150">前月請求額</th>
							<th class="w150">御入金額</th>
							<th class="w150">繰越金額</th>
							<th class="w150">今回御買上額</th>
							<th class="w150">消費税</th>
							<th class="w150">今回請求額</th>
						<tr>';
							echo "	<tr>
							<td>".$form->input('LASTM_BILL', array('maxlength'=>'15','label' => false, 'div' => false,  'class' => 'w130'.($form->error('LASTM_BILL')?' error':'')))."</td>
							<td>".$form->input('DEPOSIT', array('maxlength'=>'15','label' => false, 'div' => false,'class' => 'w130'.($form->error('DEPOSIT')?' error':'')))."</td>
							<td>".$form->input('CARRY_BILL', array('maxlength'=>'15','label' => false, 'div' => false,'readonly'=>'readonly', 'class' => 'w130'.($form->error('CARRY_BILL')?' error':'')))."</td>
							<td>".$form->input('SALE', array('maxlength'=>'15','label' => false, 'div' => false,'readonly'=>'readonly','class' => 'w130'.($form->error('SALE')?' error':'')))."</td>
							<td>".$form->input('SALE_TAX', array('maxlength'=>'15','label' => false, 'div' => false,'readonly'=>'readonly','class' => 'w130'.($form->error('SALE_TAX')?' error':'')))."</td>
							<td>".$form->input('THISM_BILL', array('maxlength'=>'15','label' => false, 'div' => false, 'readonly'=>'readonly','class' => 'w130'.($form->error('THISM_BILL')?' error':'')))."</td>
							</tr>";
				?>
				</table>
				<div>
				<br /><span class="must"><?php
				if($form->error('LASTM_BILL')||$form->error('DEPOSIT')||$form->error('CARRY_BILL')||$form->error('SALE')||$form->error('SALE_TAX')||$form->error('THISM_BILL')){

					echo "入力項目にエラーがあります";
				}
				?>
				</span>
				</div>
				<div style="margin-top:30px;">
				<?php echo (isset($cst_name))?$form->hidden("CUSTOMER_NAME",array('value'=>$cst_name)):'';?>
				<?php echo (isset($cst_id))?$form->hidden("CST_ID",array('value'=>$cst_id)):'';?>
				<?php echo $form->hidden('EDIT_STAT',array('value'=>$edit_stat)); ?>
				<?php echo $form->hidden("TOTAL"); ?>
				<?php $i=0;foreach($bill_id as $key => $val){
						echo $form->inputarray('Totalbillitem','MBL_ID',$i, 'hidden',array('value'=>$val));
					$i++;
				}?>
				<?php echo $form->hidden("TOTAL"); ?>
				</div>
			</div>
			<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>
		</div>
	</div>
	<div class="listview hidebox_s">
		<h3><div class="edit_02_tbilamount"><span class="edit_txt">&nbsp;</span></div></h3>

		<div class="contents_box mb40">
			<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<table width="880" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<th style="width:150px;">小計</th>
				<td style="width:730px;">
					<?php echo $form->input('SUBTOTAL', array('maxlength'=>'15','label' => false, 'readonly'=>'readonly','div' => false,'class' => 'w300'.($form->error('SUBTOTAL')?' error':'')));?>
					<br /><span class="must"><?php echo $form->error('SUBTOTAL'); ?></span>
				</td>
			</tr>
			<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
			<tr>
				<th>消費税</th>
				<td>
					<?php echo $form->input('SALE_TAX', array('maxlength'=>'15','label' => false,'readonly'=>'readonly','div' => false,'class' => 'w300'.($form->error('SALE_TAX')?' error':'')));?>
					<br /><span class="must"><?php echo $form->error('SALE_TAX'); ?></span>
				</td>
			</tr>
			<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
			<tr>
				<th>合計請求額</th>
				<td>
					<?php echo $form->input('THISM_BILL', array('maxlength'=>'15','label' => false,'readonly'=>'readonly', 'div' => false, 'class' => 'w300'.($form->error('THISM_BILL')?' error':'')))?>
					<br /><span class="must"><?php echo $form->error('THISM_BILL'); ?></span>
				</td>
			</tr>
			</table>

			</div>
						<?php echo (isset($cst_name))?$form->hidden("CUSTOMER_NAME",array('value'=>$cst_name)):'';?>
							<?php echo (isset($cst_id))?$form->hidden("CST_ID",array('value'=>$cst_id)):'';?>
							<?php echo $form->hidden('EDIT_STAT',array('value'=>$edit_stat)); ?>
							<?php echo $form->hidden("TOTAL"); ?>
							<?php $i=0;foreach($bill_id as $key => $val){
									echo $form->inputarray('Totalbillitem','MBL_ID',$i, 'hidden',array('value'=>$val));
								$i++;
							}?>
							<?php echo $form->hidden("TOTAL"); ?>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>

	</div>
	</div>
	<div class="edit_btn">
		<?php echo $form->submit('bt_save.jpg', array('div' => false , 'name' => 'submit', 'alt' => '変更する', 'class' => 'imgover imgcheck')); ?>
    	<?php echo $form->submit('bt_cancel.jpg', array('div' => false , 'name' => 'cancel', 'alt' => 'キャンセル', 'class' => 'imgover imgcheck')); ?>
	</div>
	<?php echo $customHtml->hiddenToken(); ?>
	<?php echo $form->hidden("USR_ID",array('value'=>$user['USR_ID'])); ?>
	<?php echo $form->hidden("UPDATE_USR_ID",array('value'=>$user['USR_ID'])); ?>
  <?php echo $form->end(); ?>
</div>
<!-- contents_End -->