<script type="text/javascript">
<!--

function show_serial(val) {
	if(val == 0) {
		$('#serial_option').slideDown();
	}
	if(val == 1) {
		$('#serial_option').slideUp();
	}
}

function update_serial(no) {
	$('#SERIAL'+no+'CHANGED').val(1);

}

function change_sample(no) {
	var str = "";

	if($('#SERIAL'+no+'NUMBERINGFORMAT').val() == 0) {
		str += $('#SERIAL'+no+'PREFIX').val();
		if($('#SERIAL'+no+'NEXT').val().length < 6) {
			str += ('00000' + $('#SERIAL'+no+'NEXT').val()).slice(-5);
		}else {
			str += $('#SERIAL'+no+'NEXT').val();
		}
		$('#sample'+no).html(str);
	}else {
		str += $('#SERIAL'+no+'PREFIX').val();
		str += '<?php echo date("ymd");?>';
		if($('#SERIAL'+no+'NEXT').val().length < 2) {
			str += ('00000' + $('#SERIAL'+no+'NEXT').val()).slice(-2);
		}else {
			str += $('#SERIAL'+no+'NEXT').val();
		}
		$('#sample'+no).html(str);
	}
}

function format_change(val, no) {
	if(val == 1) {
		$('.NF'+no).fadeOut();
		$('#SERIAL'+no+'NEXT').val(1);

	}
	else{
		$('.NF'+no).fadeIn();
	}
}

$(document).ready(function($){
	if(<?php echo $this->data['Company']['SERIAL_NUMBER'];?>) {
		$('#serial_option').hide();
	}
	<?php
		for($i = 0; $i < 5; $i++) {
			if($this->data['SERIAL'][$i]['NUMBERING_FORMAT']) {
				echo '$(".NF'.$i.'").fadeOut();'."\n";
			}
		}
	?>
});

// -->
</script>

<?php	//完了メッセージ
	echo $session->flash();
?>
<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('/img/company/i_guide.jpg'); ?>
		<p>こちらのページは自社情報設定の画面です。<br />必要な情報を入力の上「保存する」ボタンを押下すると自社情報の変更を保存できます。</p>
	</div>
</div>
<br class="clear" />
<!-- header_End -->
<!-- contents_Start -->
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
	<h3><div class="company_01"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<?php echo $form->create("Company", array("type" => "file", "controller" => "companys", "class" => "Company")); ?>
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th style="width:130px;" <?php echo $form->error("NAME")?' class="txt_top"':''; ?>><span class ="float_l">自社名</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:750px;">
					<?php echo $form->text('NAME', array('class' => 'w300'.($form->error('NAME')?' error':''), 'maxlength' => 60)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['CMP_NAME']; ?></span>
					<br /><span class="must"><?php echo $form->error('NAME'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error("REPRESENTATIVE")?' class="txt_top"':''; ?>>代表者名</th>
					<td>
					<?php echo $form->text('REPRESENTATIVE', array('class' => 'w300'.($form->error('REPRESENTATIVE')?' error':''), 'maxlength' => 60)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['REPRESENTATIVE']; ?></span>
					<br /><span class="must"><?php echo $form->error('REPRESENTATIVE'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" <?php echo ($form->error("POSTCODE1")||$form->error("POSTCODE2"))?' class="txt_top"':''; ?>><span class ="float_l">郵便番号</span>
					<?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
							<td style="width:750px;">
							<?php
								echo $form->text("POSTCODE1", array("class" => "w60".($form->error("POSTCODE1")||$form->error("POSTCODE2")?' error':''), 'maxlength' => 3));
							?>
			 				<span class="pl5 pr5">-</span>
			 				<?php
			 					echo $form->text("POSTCODE2", array("class" => "w60".($form->error("POSTCODE1")||$form->error("POSTCODE2")?' error':''), 'maxlength' => 4));
			 				?>
							<div><?php echo $ajax->div("target"); ?><?php echo $ajax->divEnd("target"); ?></div>
							<br /><span class="usernavi"><?php echo $usernavi['POSTCODE']; ?></span>
							<br /><span class="must"><?php if($form->error("POSTCODE1")){echo $form->error("POSTCODE1");}else if($form->error("POSTCODE2")){echo $form->error("POSTCODE2");} ?></span>
							</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" ><span class ="float_l">都道府県</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:750px;">
					<?php echo $form->input('CNT_ID', array('label' => false, 'options' => $countys, 'div' => false, 'error' => false, 'class' => ($form->error('CNT_ID')?' error':''))); ?>
					<br /><span class="must"><?php echo $form->error('CNT_ID'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" <?php echo $form->error("ADDRESS")?' class="txt_top"':''; ?>><span class ="float_l">住所</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:750px;">
					<?php echo $form->text('ADDRESS', array('class' => 'w600'.($form->error('ADDRESS')?' error':''), 'maxlength' => 100)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['ADDRESS']; ?></span>
					<br /><span class="must"><?php echo $form->error('ADDRESS'); ?></span>
					</td>

				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error("BUILDING")?' class="txt_top"':''; ?>>建物名</th>
					<td>
					<?php echo $form->text('BUILDING', array('class' => 'w600'.($form->error('BUILDING')?' error':''), 'maxlength' => 100)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['BUILDING']; ?></span>
					<br /><span class="must"><?php echo $form->error('BUILDING'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>

					<th style="width:130px;" <?php echo $perror == 1?' class="txt_top"':''; ?>><span class ="float_l">電話番号</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:750px;">
					<?php if($perror==1){echo $form->text("PHONE_NO1", array("class" => "w60 error", 'maxlength' => 5));}
						else{echo $form->text("PHONE_NO1", array("class" => "w60", 'maxlength' => 5));} ?>
		 			<span class="pl5 pr5">-</span>
		 			<?php if($perror==1){echo $form->text("PHONE_NO2", array("class" => "w60 error", 'maxlength' => 4));}
						else{echo $form->text("PHONE_NO2", array("class" => "w60", 'maxlength' => 4));} ?>
		 			<span class="pl5 pr5">-</span>
		 			<?php if($perror==1){echo $form->text("PHONE_NO3", array("class" => "w60 error", 'maxlength' => 4));}
						else{echo $form->text("PHONE_NO3", array("class" => "w60", 'maxlength' => 4));} ?>
					<br /><span class="usernavi"><?php echo $usernavi['PHONE']; ?></span>
					<br /><span class="must"><?php if($perror==1)echo "正しい電話番号を入力してください"?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $ferror == 1?' class="txt_top"':''; ?>>FAX番号</th>
					<td>
					<?php if($ferror==1){echo $form->text("FAX_NO1", array("class" => "w60 error", 'maxlength' => 4));}
						else{echo $form->text("FAX_NO1", array("class" => "w60", 'maxlength' => 4));} ?>
		 			<span class="pl5 pr5">-</span>
		 			<?php if($ferror==1){echo $form->text("FAX_NO2", array("class" => "w60 error", 'maxlength' => 4));}
						else{echo $form->text("FAX_NO2", array("class" => "w60", 'maxlength' => 4));} ?>
		 			<span class="pl5 pr5">-</span>
		 			<?php if($ferror==1){echo $form->text("FAX_NO3", array("class" => "w60 error", 'maxlength' => 4));}
						else{echo $form->text("FAX_NO3", array("class" => "w60", 'maxlength' => 4));} ?>
					<br /><span class="usernavi"><?php echo $usernavi['FAX']; ?></span>
					<br /><span class="must"><?php if($ferror==1)echo "正しいFAX番号を入力してください"?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error("INVOICE_NUMBER")?' class="txt_top"':''; ?>>登録番号</th>
					<td>
					<?php echo $form->text('INVOICE_NUMBER', array('class' => 'w300'.($form->error('INVOICE_NUMBER')?' error':''), 'maxlength' => 14)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['INVOICE_NUMBER']; ?></span>
					<br /><span class="must"><?php echo $form->error('INVOICE_NUMBER'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error('HONOR_TITLE')?' class="txt_top"':''; ?>><span class ="float_l">敬称</span></th>
					<td id="HONOR" colspan="3">
					<?php echo $form->radio('HONOR_CODE', $honor, array('label' => false, 'legend' => false, 'class' => 'ml20 mr5 txt_mid')); ?>
					<?php echo $form->text('HONOR_TITLE' ,array('class' => 'w160 mr10'.($form->error('HONOR_TITLE')?' error':''),'maxlength'=>8)); ?>
						<br /><span class="usernavi"><?php echo $usernavi['HONOR']; ?></span>
						<br /><span class="must"><?php echo $form->error('HONOR_TITLE'); ?></span>
          			</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>社判登録<br /></th>
					<td><?php
						if(isset($image))
						{
							echo $html->image(array("controller" =>"companies",'action' => 'contents'),array("width"=>"100","height"=>"100"));
						}
						?>
					<?php echo $form->file("image"); ?>
					<?php echo $form->checkbox("DEL_SEAL",array("style" => "width:30px;")); ?>削除
					<div></div>
					<br /><span class="usernavi"><?php echo $usernavi['SEAL']; ?></span>
					<br /><span class="must"><?php echo $ierror==1?'画像はjpeg,png,gifのみです':'' ?></span>
					<br /><span class="must"><?php echo $ierror==2?'1MB以上の画像は登録できません':'' ?></span>
					<br /><span class="must"><?php echo $ierror==3?'正しい画像を指定してください':'' ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error('CMP_SEAL_FLG')?' class="txt_top"':''; ?>><span class ="float_l">押印設定</span></th>
					<td id="SEAL_FLG" colspan="3">
					<?php echo $form->radio('CMP_SEAL_FLG', $seal_flg, array('label' => false, 'legend' => false, 'class' => 'ml20 mr5 txt_mid')); ?>
						<br /><span class="usernavi"><?php echo $usernavi['CMP_SEAL_FLG']; ?></span>
						<br /><span class="must"><?php echo $form->error('CMP_SEAL_FLG'); ?></span>
          			</td>
				</tr>
			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>

	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="company_02"><span class="edit_txt">&nbsp;</span></div></h3>

	<div class="contents_box">
		<span class="usernavi"><?php echo $usernavi['CMP_PAYMENT']; ?></span>
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th style="width:130px;"<?php echo $form->error("CUTOOFF_DATE")?' class="txt_top"':''; ?>>締日</th>
					<td style="width:750px;">
					<?php echo $form->input('CUTOOFF_SELECT', $cutooff_select,array('class'=>'txt_mid')); ?>
					<?php echo $form->text("CUTOOFF_DATE", array('class' =>'w60 mr5 ml5'.($form->error('CUTOOFF_DATE')?' error':''), 'maxlength' => 2)); ?>日
					<br /><span class="usernavi"><?php echo $usernavi['CUTOOFF']; ?></span>
					<br /><span class="must"><?php echo $form->error('CUTOOFF_DATE'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error("PAYMENT_DAY")?' class="txt_top"':''; ?>>支払日</th>
					<td>
					<?php echo $form->input('PAYMENT_MONTH', array('label' => false, "div"=>false, 'options' => $payment,'class'=>'w120 mr20','size'=>'1')); ?>
					<?php echo $form->input('PAYMENT_SELECT', $payment_select); ?>
					<?php echo $form->text("PAYMENT_DAY", array('class' =>'w60 mr5 ml5'.($form->error('PAYMENT_DAY')?' error':''), 'maxlength' => 2)); ?>日
					<br /><span class="usernavi"><?php echo $usernavi['PAYMENT']; ?></span>
					<br /><span class="must"><?php echo $form->error('PAYMENT_DAY'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>数量小数部表示</th>
					<td>
						<?php echo $form->input('DECIMAL_QUANTITY', $decimals); ?>
					</td>

				</tr>
				<tr>
					<th>単価小数部表示</th>
					<td>
						<?php echo $form->input('DECIMAL_UNITPRICE', $decimals); ?>
					</td>

				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>消費税設定</th>
					<td>
						<?php echo $form->input('EXCISE', $excises); ?>
					</td>

				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>消費税端数処理</th>
					<td>
						<?php echo $form->input('TAX_FRACTION', $fractions); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>消費税端数計算</th>
					<td>
						<?php echo $form->input('TAX_FRACTION_TIMING', $tax_fraction_timing); ?>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>基本端数処理</th>
					<td>
						<?php echo $form->input('FRACTION', $fractions); ?>
					</td>
				</tr>
			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>

	</div>

	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="company_03"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="contents_box">
		<span class="usernavi"><?php echo $usernavi['ACCOUNT_HOLDER']; ?></span>
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<table width="880" cellpadding="0" cellspacing="0" border="0">

				<tr>
					<th style="width:130px;"<?php echo $form->error("ACCOUNT_HOLDER")?' class="txt_top"':''; ?>>名義</th>
					<td style="width:750px;">
					<?php echo $form->text('ACCOUNT_HOLDER', array('class' => 'w300'.($form->error('ACCOUNT_HOLDER')?' error':''), 'maxlength' => 200)); ?>
					<br /><span class="must"><?php echo $form->error('ACCOUNT_HOLDER'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error("BANK_NAME")?' class="txt_top"':''; ?>>銀行名</th>
					<td>
					<?php echo $form->text('BANK_NAME', array('class' => 'w300'.($form->error('BANK_NAME')?' error':''), 'maxlength' => 200)); ?>
					<br /><span class="must"><?php echo $form->error('BANK_NAME'); ?></span>
					</td>

				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error("BANK_BRANCH")?' class="txt_top"':''; ?>>支店名</th>
					<td><?php echo $form->text('BANK_BRANCH', array('class' => 'w300'.($form->error('BANK_BRANCH')?' error':''), 'maxlength' => 200)); ?>
					<br /><span class="must"><?php echo $form->error('BANK_BRANCH'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>口座区分</th>
					<td>
					<?php echo $form->input('ACCOUNT_TYPE', array('label' => false, 'options' => $account_type,'class'=>'w120 mr20','size' => '1')); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error("ACCOUNT_NUMBER")?' class="txt_top"':''; ?>>口座番号</th>
					<td>
					<?php echo $form->text('ACCOUNT_NUMBER', array('class' => 'w300'.($form->error('ACCOUNT_NUMBER')?' error':''), 'maxlength' => 7)); ?>
					<br /><span class="must"><?php echo $form->error('ACCOUNT_NUMBER'); ?></span>
					</td>
				</tr>
			</table>
		</div>
	<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>








	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
		<h3><div class="company_04"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="contents_box">
		<span class="usernavi"><?php echo $usernavi['FORM_OPTION']; ?></span>
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<div>
				<table width="880" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<th style="width:130px;">枠色</th>
						<td style="width:330px;">
							<?php echo $form->input('COLOR', array('label' => false, 'options' => $colors ,'class' => 'mr200')); ?>
							<br /><span class="usernavi"><?php echo $usernavi['COLOR']; ?></span>
						</td>
						<td style="width:320px;">&nbsp;</td>
					</tr>
					<tr><td colspan="3" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
					<tr>
						<th>方向</th>
						<td>
							<?php echo $form->input('DIRECTION', array('label' => false, 'options' => $direction)); ?>
							<br /><span class="usernavi"><?php echo $usernavi['DIRECTION']; ?></span>
						</td>
						<td style="width:320px;">&nbsp;</td>
					</tr>
					<tr><td colspan="3" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
					<tr>
						<th>連番設定</th>
						<td colspan="2">
							<?php echo $form->radio('SERIAL_NUMBER', $serial, array('label' => false, 'legend' => false, 'class' => 'serial ml20 mr5 txt_mid', 'value' => isset($this->data['Company']['SERIAL_NUMBER'])?$this->data['Company']['SERIAL_NUMBER']:1,'onClick' => 'show_serial(value);')); ?>
						</td>

					</tr>

				</table>
			</div>

			<div id="serial_option">
				<table width="880" cellpadding="0" cellspacing="0" border="0">
					<tr><td colspan="4" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
                    <?php
                        $loop_end = 5;
                        if(isset($rb_flag) && $rb_flag ){
                            $loop_end = 6;
                        }
                    ?>
                    <?php for($i = 0; $i < $loop_end; $i++ ){?>
						<tr>
							<th style="width:100px;" rowspan="3">
							<?php
								switch($i) {
									case 0:
										echo '見積書';
										break;
									case 1:
										echo '納品書';
										break;
									case 2:
										echo '請求書';
										break;
									case 3:
										echo '合計請求書';
										break;
									case 4:
										echo '領収書';
										break;
									case 5:
										echo '定期請求書雛形';
										break;
								}
							?>
							</th>
							<td style="width:100px;">付番書式</td>
								<td style="width:100px;">
								<?php echo $form->input('SERIAL.'.$i.'.NUMBERING_FORMAT', array('label' => false, 'options' => $numbering_format, 'onChange' => "update_serial($i); format_change(value, $i); change_sample($i)"));?>
								<br /><span class="must"><?php echo $form->error('NUMBERING_FORMAT'); ?></span></td>
								<td style="width:580px;">
									　サンプル
									<span id="<?php echo 'sample'.$i;?>">

									<?php
										if($this->data['SERIAL'][$i]['NUMBERING_FORMAT'] == 0) {
											if(isset($this->data['SERIAL'][$i]['PREFIX'])) {
												echo $this->data['SERIAL'][$i]['PREFIX'];
											}
											echo sprintf('%05d', $this->data['SERIAL'][$i]['NEXT']);
										}else {
											if(isset($this->data['SERIAL'][$i]['PREFIX'])) {
												echo $this->data['SERIAL'][$i]['PREFIX'];
											}
											echo date("ymd");
											echo sprintf('%02d', $this->data['SERIAL'][$i]['NEXT']);
										}
									?>
									</span>
								</td>

						</tr>
						<tr>
							<td style="width:100px;">接頭文字</td>
								<td colspan="2" style="width:680px;">
								<?php echo $form->text('SERIAL.'.$i.'.PREFIX', array('class' => 'w300'.($serror[$i]['PREFIX']?' error':''), 'maxlength' => 12, 'onChange' => "update_serial($i)",'onkeyup' => 'count_str("prefix'.$i.'_rest", value, 12); change_sample('.$i.')')); ?>
								<span id=<?php echo '"prefix'.$i.'_rest"'?>></span>
								<br />&nbsp;
								<br /><span class="must">
								<?php
									if($serror[$i]['PREFIX'] == 1) {
										echo '12文字を超えています';
									}else if($serror[$i]['PREFIX'] == 2) {
										echo '半角英数字,「/」「,」「-」「_」のみ入力できます';
									}
								?>
								</span>
								</td>
						</tr>
						<tr  <?php echo 'class="NF'.$i.'"';?>>
							<td style="width:100px;" >次回番号</td>
								<td style="width:680px;" colspan="2">
								<?php echo $form->text('SERIAL.'.$i.'.NEXT', array('class' => 'w300'.($serror[$i]['NEXT']?' error':''), 'maxlength' => 8, 'onChange' => "update_serial($i)", 'onkeyup' => 'count_str("next'.$i.'_rest", value, 8); change_sample('.$i.')')); ?>
								<span id=<?php echo '"next'.$i.'_rest"'?>></span>
								<br />
								<span class="must">
								<?php
									if($serror[$i]['NEXT'] == 1) {
										echo '8文字を超えています';
									}else if($serror[$i]['NEXT'] == 2) {
										echo '半角数字のみ入力できます';
									}
								?>
								</span>
								</td>
						</tr>
						<tr><td colspan="3"<?php echo 'class="serial_update'.$i.'"';?>></td></tr>
						<tr><td colspan="3"><?php echo $form->hidden('SERIAL.'.$i.'.CHANGED'); ?>&nbsp;</td></tr>
					<?php }?>
					</table>
				</div>




		</div>

		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>

	<div class="edit_btn">
		<?php
		    echo $form->submit('bt_save.jpg', array('div' => false , 'name' => 'submit', 'alt' => '保存する', 'class' => 'imgover'));
		    echo $form->submit('bt_cancel.jpg', array('div' => false , 'name' => 'cancel', 'alt' => 'キャンセル', 'class' => 'imgover'));

		?>
	</div>
</div>
<?php echo $form->hidden("CMP_ID", array("value" => "1")); ?>
<?php echo $customHtml->hiddenToken(); ?>
<?php echo $form->end(); ?>