<script type="text/javascript">
<!--
	function customer_reset() {
		$('#SETCUSTOMER').children('input[type=text]').val('');
		$('#SETCUSTOMER').children('input[type=hidden]').val('');
		return false;
	}
// -->
</script>
<?php	//完了メッセージ
	echo $session->flash();
?>
<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('i_guide02.jpg'); ?>
		<p>1.　まず、対象とする作成済みの請求書を顧客名、発行日（日付from、to）の条件で絞込みます。<br /></p>
		<p>2.　条件で検索された請求書の中から対象となる請求書を選択し、作成ボタンを押します。<br />（合計請求書のフォーマットは「簡易」「詳細」から選択できます）<br /></p>

	</div>
</div>
<br class="clear" />
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
	<div class="search_box">
		<div class="search_area">
 				<?php echo $form->create("Totalbill", array("type" => "post", "controller" => "totalbills", "class"=>"Totalbill")); ?>
			<table width="600" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th style="width:250;"<?php echo $form->error('NO')?' class="txt_top"':''; ?>>顧客名</th>
					<td width="630" colspan="3" id="SETCUSTOMER">
					<?php echo $form->text('CUSTOMER_NAME' ,array('class' => 'w140 p2','readonly'=>'readonly')); ?>
					<?php echo $form->hidden('CST_ID'); ?>
					<?php echo $html->link($html->image('bt_select2.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'select_customer\');')); ?>
					<?php echo $html->link($html->image('bt_delete2.jpg'),'#',array('escape' => false, 'onclick'=>'return customer_reset();')); ?>
					</td>
				</tr>
				<tr><th>日付 FROM</th>
					<td width="320">
						<script language=JavaScript>
							<!--
						    var cal1 = new JKL.Calendar("calid", "TotalbillAddForm", "data[Totalbill][FROM]");
						  //-->
					  </script>
						<?php echo $form->text('FROM', array('label' => false, 'div' => false,'onChange' => 'cal1.getFormValue(); cal1.hide();', 'readonly'=>'readonly','error'=>false, 'class' => 'w100 p2 date cal')); ?>
						<?php echo $html->image('bt_now.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'nowtime')); ?>
						<?php echo $html->image('bt_calender.jpg', array('alt' => 'カレンダー', 'url' => '#','onClick' => 'return cal1.write();','class' => 'pl5')); ?>
						<?php echo $html->image('bt_s_reset.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'cleartime')); ?>
					</td></tr>
				<tr><th>日付 TO</th>
					<td width="320">
						<script language=JavaScript>
							<!--
						    var cal2 = new JKL.Calendar("calid", "TotalbillAddForm", "data[Totalbill][TO]");
						  //-->
					  </script>
						<?php echo $form->text('TO', array('label' => false, 'div' => false,'onChange' => 'cal2.getFormValue(); cal2.hide();', 'readonly'=>'readonly','error'=>false, 'class' => 'w100 p2 date cal')); ?>
						<?php echo $html->image('bt_now.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'nowtime')); ?>
						<?php echo $html->image('bt_calender.jpg', array('alt' => 'カレンダー', 'url' => '#','onClick' => 'return cal2.write();','class' => 'pl5')); ?>
						<?php echo $html->image('bt_s_reset.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'cleartime')); ?>
					</td></tr>
			</table>

			<div class="search_btn">
				<?php echo $form->submit('bt_search.jpg', array('name' => 'search', 'alt' => '検索する')); ?>
			</div>
			<?php echo $form->end(); ?>
		</div>
		<?php echo $html->image('/img/document/bg_search_bottom.jpg',array('class'=>'block')); ?>
	</div><div id="calid"></div>

	<div class="listview hidebox">
		<h3><div class="edit_02_bill"><span class="edit_txt">&nbsp;</span></div></h3>
		<div class="contents_box mb40">
			<?php echo $html->image('bg_contents_top.jpg'); ?>
			<div class="list_area">
				<?php
					if(isset($billlist)&&is_array($billlist)){
						echo $form->create("Totalbill", array('type' => 'post', 'action' => 'add',"class"=>"Totalbill"));
						echo '<table width="900" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<th class="w50">選択</th>
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
							<td>".$form->checkbox($val['Bill']['MBL_ID'], array('class'=> 'chk','id'=>"check_".$i,'value'=>(isset($val['Bill']['CHK'])?'1':'0')))."</td>
							<td>".$customHtml->ht2br(($val['Bill']['NO'])?$val['Bill']['NO']:'　','Totalbill','NO')."</td>
							<td>".$customHtml->ht2br($val['Bill']['SUBJECT'],'Totalbill','NO')."</td>
							<td>".$customHtml->ht2br($val['Customer']['NAME'],'Totalbill','NAME')."</td>
							<td>".$customHtml->ht2br(($val['Bill']['ISSUE_DATE'])?$val['Bill']['ISSUE_DATE']:'　','Totalbill','ISSUE_DATE')."</td>
							<td>".$customHtml->ht2br($val['Bill']['DUE_DATE'],'Totalbill','DUE_DATE')."</td>
							<td id=TOTAL".$i.">".$customHtml->ht2br($val['Bill']['TOTAL'],'Totalbill','TOTAL')."</td>";
							echo '<div id="tax'.$i.'" style="display:none;">'.$customHtml->ht2br($val['Bill']['SALES_TAX']).'</div>';
							echo '<div id="subtotal'.$i.'" style="display:none;">'.$customHtml->ht2br($val['Bill']['SUBTOTAL']).'</div>';
							echo "</tr>";
							$i++;
						}
						echo '</table>';
					}
				?>

			</div>
			<div class="contents_area">
				<table width="880" cellpadding="0" cellspacing="0" border="0">
					<tr><td colspan="4" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
					<tr>
						<th>フォーマット</th>
						<td width="320">
						<?php echo $form->input('EDIT_STAT', $edit_stat); ?>
							<br /><span class="usernavi"><?php echo $usernavi['EDIT_STAT']; ?></span>
						</td>
					</tr>
					<tr><td colspan="4" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
			</table>
				<?php echo '<div id="billfrag" style="display:none;">'.$customHtml->ht2br($billfrag).'</div>'; ?>
				<?php echo (isset($cst_name))?$form->hidden("CUSTOMER_NAME",array('value'=>$cst_name)):'';?>
				<?php echo (isset($cst_id))?$form->hidden("CST_ID",array('value'=>$cst_id)):'';?>
				</div>

				<div class="search_btn">
					<?php if(isset($billlist)&&is_array($billlist)){?>
					<?php echo $form->submit('選択する', array('name' => 'select', 'alt' => '選択する')); ?>
					<?php }?>
				</div>
				<?php echo $form->end(); ?>
			</div>

		</div>
	</div>
</div>
