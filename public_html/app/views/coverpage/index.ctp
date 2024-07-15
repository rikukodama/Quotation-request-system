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

	function chr_reset() {
		$('#SETCHARGE').children('input[type=text]').val('');
		return false;
	}

	function err_dt(_no){
		$('tr[class="row_'+_no+'"] input[class="documenttitle"]').css('background-color','#DD0000');
	}
	function err_dn(_no){
		$('tr[class="row_'+_no+'"] input[class="documentnumber"]').css('background-color','#DD0000');
	}

// -->
</script>
<?php echo $session->flash(); ?>
<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('i_guide02.jpg'); ?>
		<p>こちらのページは送付状作成の画面です。<br />必要な情報を入力の上「保存する」ボタンを押すと送付状を作成できます。</p>
	</div>
</div>
<br class="clear" />
<!-- contents_Start -->
<div id="contents">
<?php echo $form->error('CUSTOMER_NAME');?>
  <?php echo $form->create('Coverpages', array('type' => 'post','class' => 'Coverpages')); ?>
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="edit_02_coverpage"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="contents_box">
    <?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
		<?php echo $form->hidden('maxformline', array('value'=>$maxline)); ?>
		<?php echo $form->hidden('dataformline', array('value'=>$dataline)); ?>
		<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th style="width:170px;"<?php echo $form->error('NO')?' class="txt_top"':''; ?>>送付方法<?php echo $html->image('i_must.jpg', array('alt' => '必須', 'class' => 'pl10')); ?></th>
					<td style="width:710px;" colspan="3">
					<?php echo $form->radio('SEND_METHOD', $SendMethod, array('value' => '0','label' => false, 'legend' => false, 'class' => 'ml20 mr5 txt_mid')); ?>
					</td>
				</tr>
				<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;"<?php echo $form->error('NO')?' class="txt_top"':''; ?>>顧客名<?php echo $html->image('i_must.jpg', array('alt' => '必須', 'class' => 'pl10')); ?></th>
					<td style="width:710px;" colspan="3" id="SETCUSTOMER">
					<?php echo $form->text('CUSTOMER_NAME' ,array('class' => 'w140 p2'.(isset($error['CUSTOMER_NAME'])?' error':''),'readonly'=>'readonly','maxlength'=>60)); ?>
					<?php echo $form->hidden('CST_ID'); ?>
					<?php echo $html->link($html->image('bt_select2.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'select_customer\');')); ?>
					<?php echo $html->link($html->image('bt_delete2.jpg'),'#',array('escape' => false, 'onclick'=>'return customer_reset();')); ?>
					<br /><span class="must"><?php if(isset($error['CUSTOMER_NAME'])){echo $error['CUSTOMER_NAME'];}; ?></span>
					<br /><span class="usernavi"><?php echo $usernavi['CVR_CST']; ?></span>

					</td>
				</tr>
				<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;"<?php echo $form->error('NO')?' class="txt_top"':''; ?>>顧客担当者名</th>
					<td style="width:270px;" id="SETCUSTOMERCHARGE">
					<?php echo $form->text('CUSTOMER_CHARGE_NAME' ,array('class' => 'w120 p2'.(isset($error['CUSTOMER_CHARGE_NAME'])?' error':''),'maxlength'=>60)); ?>
					<?php echo $form->hidden('CHRC_ID'); ?>
					<?php echo $html->link($html->image('bt_select2.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'customer_charge\');')); ?>
					<?php echo $html->link($html->image('bt_delete2.jpg'),'#',array('escape' => false, 'onclick'=>'return cstchr_reset();')); ?>
					<br /><span class="must"><?php if(isset($error['CUSTOMER_CHARGE_NAME'])){echo $error['CUSTOMER_CHARGE_NAME'];}; ?></span>
					</td>
					<th style="width:170px;">担当者部署名</th>
					<td style="width:270px;" id="SETCCUNIT">
					<?php echo $form->text('CUSTOMER_CHARGE_UNIT' ,array('class' => 'w180 p2'.(isset($error['CUSTOMER_CHARGE_UNIT'])?' error':''),'maxlength'=>60)); ?>
					<br /><span class="must"><?php if(isset($error['CUSTOMER_CHARGE_UNIT'])){echo $error['CUSTOMER_CHARGE_UNIT'];}; ?></span>
					</td>
				</tr>
				<tr><td></td><td colspan="3"><br /><span class="usernavi"><?php echo $usernavi['CVR_CST_CHR']; ?></span></td></tr>
				<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px">自社担当者名</th>
					<td style="width:710px;" colspan="3" id="SETCHARGE">
					<?php echo $form->text('CHARGE_NAME' ,array('class' => 'w180 p2'.(isset($error['CHARGE_NAME'])?' error':''),'maxlength'=>60)); ?>
					<?php echo $form->hidden('CHR_ID'); ?>
					<?php echo $html->link($html->image('bt_select2.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'charge\');')); ?>
					<?php echo $html->link($html->image('bt_delete2.jpg'),'#',array('escape' => false, 'onclick'=>'return chr_reset();')); ?>
					<br /><span class="must"><?php if(isset($error['CHARGE_NAME'])){echo $error['CHARGE_NAME'];}; ?></span>
					<br /><span class="usernavi"><?php echo $usernavi['CHR_NAME']; ?></span>
					</td>
				</tr>
				<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;">発行日<?php echo $html->image('i_must.jpg', array('alt' => '必須', 'class' => 'pl10')); ?></th>
					<td colspan="4">
						<script language=JavaScript>
							<!--
						    var cal1 = new JKL.Calendar("calid", "CoverpagesIndexForm", "data[Coverpages][DATE]");
						  //-->
					  </script>
						<?php echo $form->input('DATE', array('label' => false, 'div' => false,'onChange' => 'cal1.getFormValue(); cal1.hide();', 'readonly'=>'readonly','error'=>false, 'class' => 'w100 p2 date cal'.(isset($error['DATE'])?' error':''))); ?>
						<?php echo $html->image('bt_now.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'nowtime')); ?>
						<?php echo $html->image('bt_calender.jpg', array('alt' => 'カレンダー', 'url' => '#','onClick' => 'return cal1.write();','class' => 'pl5')); ?><div id="calid"></div>
						<br /><span class="must"><?php if(isset($error['DATE'])){echo $error['DATE'];}; ?></span>
						<br /><span class="usernavi"><?php echo $usernavi['DATE']; ?></span>
						<div id="calid"></div>
					</td>
				</tr>
				<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;">件名</th>
					<td colspan="3">
					<?php echo $form->text('TITLE' ,array('class' => 'w180 p2'.(isset($error['TITLE'])?' error':''),'maxlength'=>40)); ?>
					<br /><span class="usernavi">　<?php echo $usernavi['CVR_TITLE']; ?></span>
					<br /><span class="must"><?php if(isset($error['TITLE'])){echo $error['TITLE'];}; ?></span>
					</td>
				</tr>
				<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
		</table>
		<div id ="SEND_DOCUMENT" >
		<table width="880" cellpadding="0" cellspacing="3" border="0" id="report">
				<tr>
					<th style="width:170px">送付書類<?php echo $html->image('i_must.jpg', array('alt' => '必須', 'class' => 'pl10')); ?></th>
					<td style="width: 42px;">&nbsp;</td>
					<th style="border-left:none;border-right:#FFF 1px solid;background:#5D3221;color:#FFFFFF;width: 250px;">書類名称</th>
					<th style="border-left:none;border-right:#FFF 1px solid;;background:#5D3221;color:#FFFFFF;width: 100px;">部数</th>
					<td style="width: 318px;">&nbsp;</td>
				</tr>

				<?php for($i=0;$i<$maxline;$i++){ ?>
				<tr class="row_<?php echo $i; ?>">
					<td style="width: 170px;">&nbsp;</td>
					<td style="width: 42px;"><?php echo $html->image('bt_delete.jpg', array('alt' => '×', 'url' => '#', 'class'=>'delbtn', 'onclick' => 'return form.coverpage_delline('.$i.');')); ?></td>
					<td style="width: 250px;"><?php echo $form->inputarray('Reports', 'DOCUMENT_TITLE',	$i, null, array('class' => 'documenttitle'.(isset($document_error['DOCUMENT_TITLE']['NO'][$i])?' error':''),'style' => 'width: 250px;','maxlength'=>30)); ?></td>
					<td style="width: 100px;"><?php echo $form->inputarray('Reports', 'DOCUMENT_NUMBER',	$i, null, array('class' => 'documentnumber'.(isset($document_error['DOCUMENT_NUMBER']['NO'][$i])?' error':''),'style' => 'width: 80px;','maxlength' => '7')); ?>&nbsp;部</td>
					<td style="width: 318px;">&nbsp;</td>
				</tr>
				<?php } ?>
				<tr><td colspan="5">&nbsp;</td></tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="2"><?php echo $html->image('bt_add.jpg', array('alt' => '行を追加する', 'url' => '/', 'onclick' => 'return form.coverpage_addline(null);')); ?></td>
					<td colspan="2"><?php echo $html->image('bt_reset.jpg', array('alt' => 'リセット', 'url' => '#', 'onclick'=>'return form.f_reset(\'null\');')); ?>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="4">
						<span class="must">
						<?php
							if(isset($document_error)&& $document_error['FLG']){
								echo '送付書類の中に入力エラーがあります<br />';
								if(isset($document_error['EMP_FLG'])&& $document_error['EMP_FLG']){
									echo '　* 送付書類が一枚もありません<br />';
								}
								if(isset($document_error['DOCUMENT_TITLE']['OVER_FLAG'])&& $document_error['DOCUMENT_TITLE']['OVER_FLAG'])  {
									echo '　* 書類名は全角15文字以内<br />';
								}
								if(isset($document_error['DOCUMENT_TITLE']['EMP_FLAG'])&& $document_error['DOCUMENT_TITLE']['EMP_FLAG'])  {
									echo '　* 書類名が入力されていません<br />';
								}
								if($document_error['DOCUMENT_NUMBER']['FLAG']) {
									echo '　* 枚数は半角数字1-7桁で入力してください<br />';
								}
							}
						?>

						</span>
					</td>
				</tr>
			</table>
			</div>
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr><td colspan="5"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:250;">状況</th>
					<td colspan="4">
					<?php echo $form->checkbox('STATUS_ASAP') ?> 至急&nbsp;&nbsp;
					<?php echo $form->checkbox('STATUS_REFERENCE') ?> ご参考まで&nbsp;&nbsp;
					<?php echo $form->checkbox('STATUS_COMFIRMATION') ?> ご確認ください&nbsp;&nbsp;
					<?php echo $form->checkbox('STATUS_REPLY') ?> ご返信ください&nbsp;&nbsp;
					</td>
				</tr>
				<tr><td colspan="5"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:250">連絡事項</th>
					<td width="630" colspan="4">
					<?php echo $form->textarea('CONTACT',array('class' => 'textarea'.(isset($error['CONTACT'])?' error form-error':''),'maxlength'=>600)); ?>
					<br /><span class="must"><?php if(isset($error['CONTACT'])){echo $error['CONTACT'];}; ?></span>
					<br /><span class="usernavi"><?php echo $usernavi['NOTE']; ?></span>
					</td>
				</tr>
			</table>





		</div>
    <?php echo $html->image('bg_contents_bottom.jpg', array('class' => 'block')); ?>
	</div>

	<div class="edit_btn">
	<?php echo $form->submit('bt_save.jpg', array('div' => false , 'name' => 'submit', 'alt' => '保存する', 'class' => 'imgover')); ?>
	<?php echo $form->submit('bt_cancel.jpg', array('div' => false , 'name' => 'cancel', 'alt' => 'キャンセル', 'class' => 'imgover')); ?>
	</div>
 	<?php echo $form->end(); ?>
</div>
