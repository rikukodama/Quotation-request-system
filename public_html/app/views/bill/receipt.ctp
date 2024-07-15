<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('i_guide02.jpg'); ?>
		<p>こちらのページは領収書作成画面です。<br />必要な情報を入力の上「保存する」ボタンを押すと領収書のPDFが出力出来ます。</p>
	</div>
</div>
<br class="clear" />

<!-- contents_Start -->
<div id="contents">
  <?php echo $form->create("Bill", array('type' => 'post', 'action' => 'receipt',"class"=>"Receipt")); ?>
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="edit_01_receipt"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="contents_box">
    <?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th<?php echo isset($error['CST_ID'])?' class="txt_top"':''; ?>>顧客名<?php echo $html->image('i_must.jpg', array('alt' => '必須', 'class' => 'pl10')); ?></th>
					<td width="320">
          <?php echo $form->text('CST_ID' ,array('value' => $companys,'class' => 'w180 p2'.(isset($error['CST_ID'])?' error':''),'maxlength'=>60)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['CMP_NAME']; ?></span>
					<br /><span class="must"><?php if(isset($error['CST_ID'])){echo $error['CST_ID'];} ?></span>
          </td>
					<th<?php echo isset($error['TOTAL'])?' class="txt_top"':''; ?>>金額<?php echo $html->image('i_must.jpg', array('alt' => '必須', 'class' => 'pl10')); ?></th>
					<td width="320">
						<?php echo $form->text('TOTAL' ,array('class' => 'w180 p2'.(isset($error['TOTAL'])?' error':''), 'readonly'=>'readonly','maxlength'=>16)); ?>
						<br /><span class="must"><?php if(isset($error['TOTAL'])){echo $error['TOTAL'];} ?></span>
        		 	 </td>
				</tr>
				<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>発行日</th>
					<td width="320">
						<script language=JavaScript>
							<!--
						    var cal1 = new JKL.Calendar("calid", "BillReceiptForm", "data[Bill][DATE]");
						  //-->
					  </script>
						<?php echo $form->input('DATE', array('label' => false, 'div' => false,'onChange' => 'cal1.getFormValue(); cal1.hide();', 'readonly'=>'readonly','error'=>false, 'class' => 'w100 p2 date cal'.($form->error('ISSUE_DATE')?' error':''))); ?>
						<?php echo $html->image('bt_now.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'nowtime')); ?>
						<?php echo $html->image('bt_calender.jpg', array('alt' => 'カレンダー', 'url' => '#','onClick' => 'return cal1.write();','class' => 'pl5')); ?><div id="calid"></div>
						<br /><span class="usernavi"><?php echo $usernavi['DATE']; ?></span>
						<div id="calid"></div>
					</td>
					<th<?php echo isset($error['RECEIPT_NUMBER'])?' class="txt_top"':''; ?>>番号<?php echo $html->image('i_must.jpg', array('alt' => '必須', 'class' => 'pl10')); ?></th>
					<td width="320">
						<?php echo $form->text('RECEIPT_NUMBER' ,array('class' => 'w180 p2'.(isset($error['RECEIPT_NUMBER'])?' error':''),'maxlength'=>20)); ?>
						<br /><span class="usernavi"><?php echo $usernavi['RECEIPT_NUMBER']; ?></span>
						<br /><span class="must"><?php if(isset($error['RECEIPT_NUMBER'])){echo $error['RECEIPT_NUMBER'];} ?></span>
        		 	 </td>


				<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th<?php echo isset($error['PROVISO'])?' class="txt_top"':''; ?>>但書き<?php echo $html->image('i_must.jpg', array('alt' => '必須', 'class' => 'pl10')); ?></th>
					<td colspan="3">
						<?php echo $form->text('PROVISO' ,array('class' => 'w440 mr10'.(isset($error['PROVISO'])?' error':''),'maxlength'=>40)); ?>
						<br /><span class="usernavi"><?php echo $usernavi['PROVISO']; ?></span>
						<br /><span class="must"><?php if(isset($error['PROVISO'])){echo $error['PROVISO'];} ?></span>
          </td>
				</tr>
			</table>
		</div>
    <?php echo $html->image('bg_contents_bottom.jpg', array('class' => 'block')); ?>
	</div>

	<div class="edit_btn">
	<?php echo $form->submit('bt_save.jpg', array('div' => false , 'name' => 'submit', 'alt' => '保存する', 'class' => 'imgover imgcheck')); ?>
	<?php echo $form->submit('bt_cancel.jpg', array('div' => false , 'name' => 'cancel', 'alt' => 'キャンセル', 'class' => 'imgover imgcheck')); ?>
	</div>
	<?php echo $form->hidden("USR_ID"); ?>
	<?php echo $form->hidden("MBL_ID"); ?>
 	<?php echo $form->end(); ?>
</div>
<!-- contents_End -->