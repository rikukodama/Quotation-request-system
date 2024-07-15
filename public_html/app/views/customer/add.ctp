<script type="text/javascript">
<!--
	function charge_reset() {
		$('#SETCHARGE').children('input[type=text]').val('');
		$('#SETCHARGE').children('input[type=hidden]').val(0);
		return false;
	}
// -->
</script>
<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('/img/company/i_guide.jpg'); ?>
		<p>こちらのページは顧客情報設定の画面です。<br />必要な情報を入力の上「保存する」ボタンを押下すると顧客情報を作成できます。</p>
	</div>
</div>
<br class="clear" />
<!-- header_End -->

<!-- contents_Start -->
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="edit_01"><span class="edit_txt">&nbsp;</span></div></h3>

	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<?php echo $form->create("Customer", array("type" => "post", "controller" => "customers", "class" => "Customer")); ?>
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th style="width:130px;" <?php echo $form->error("NAME")?' class="txt_top"':''; ?>><span class ="float_l">社名</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:750px;">
					<?php echo $form->text('NAME', array('class' => 'w300'.($form->error('NAME')?' error':''), 'maxlength' => 60)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['CMP_NAME']; ?></span>
					<br /><span class="must"><?php echo $form->error('NAME'); ?></span>
					</td>

				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;"  <?php echo $form->error("NAME_KANA")?' class="txt_top"':''; ?>><span class ="float_l">社名カナ</span></th>
					<td style="width:750px;">
					<?php echo $form->text('NAME_KANA', array('class' => 'w300'.($form->error('NAME_KANA')?' error':''),'maxlength' => 100)); ?>
					<br /><span class="must"><?php echo $form->error('NAME_KANA'); ?></span>
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
					<th style="width:130px;" <?php echo ($form->error("POSTCODE1")||$form->error("POSTCODE2"))?' class="txt_top"':''; ?>><span class ="float_l">郵便番号</span>
					</th>
							<td style="width:750px;">
							<?php
								echo $form->text("POSTCODE1", array("class" => "w60".($form->error("POSTCODE1")||$form->error("POSTCODE2")?' error':''),'maxlength' => 3));
							?>
			 				<span class="pl5 pr5">-</span>
			 				<?php
			 					echo $form->text("POSTCODE2", array("class" => "w60".($form->error("POSTCODE1")||$form->error("POSTCODE2")?' error':''),'maxlength' => 4));
			 				?>
							<div><?php echo $ajax->div("target"); ?><?php echo $ajax->divEnd("target"); ?></div>
			 				<br /><span class="usernavi"><?php echo $usernavi['POSTCODE']; ?></span>

							<br /><span class="must"><?php if($form->error("POSTCODE1")){echo $form->error("POSTCODE1");}else if($form->error("POSTCODE2")){echo $form->error("POSTCODE2");} ?></span>
							</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;"><span class ="float_l">都道府県</span></th>
					<td style="width:750px;">
					<?php echo $form->input('CNT_ID', array('label' => false, 'options' => $countys)); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" <?php echo $form->error("ADDRESS")?' class="txt_top"':''; ?>><span class ="float_l">住所</span></th>
					<td style="width:750px;">
					<?php echo $form->text('ADDRESS', array('class' => 'w600'.($form->error('ADDRESS')?' error':''),'maxlength' => 100)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['ADDRESS']; ?></span>
					<br /><span class="must"><?php echo $form->error('ADDRESS'); ?></span>
					</td>

				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error("BUILDING")?' class="txt_top"':''; ?>>建物名</th>
					<td>
					<?php echo $form->text('BUILDING', array('class' => 'w600'.($form->error('BUILDING')?' error':''),'maxlength' => 100)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['BUILDING']; ?></span>
					<br /><span class="must"><?php echo $form->error('BUILDING'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>

					<th style="width:130px;" <?php echo $perror == 1?' class="txt_top"':''; ?>><span class ="float_l">電話番号</span></th>
					<td style="width:750px;">
					<?php if($perror==1){echo $form->text("PHONE_NO1", array("class" => "w60 error",'maxlength' => 5));}
						else{echo $form->text("PHONE_NO1", array("class" => "w60",'maxlength' => 5));} ?>
		 			<span class="pl5 pr5">-</span>
		 			<?php if($perror==1){echo $form->text("PHONE_NO2", array("class" => "w60 error",'maxlength' => 4));}
						else{echo $form->text("PHONE_NO2", array("class" => "w60",'maxlength' => 4));} ?>
		 			<span class="pl5 pr5">-</span>
		 			<?php if($perror==1){echo $form->text("PHONE_NO3", array("class" => "w60 error",'maxlength' => 4));}
						else{echo $form->text("PHONE_NO3", array("class" => "w60",'maxlength' => 4));} ?>
					<br /><span class="usernavi"><?php echo $usernavi['PHONE']; ?></span>
					<br /><span class="must"><?php if($perror==1)echo "正しい電話番号を入力してください"?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $ferror == 1?' class="txt_top"':''; ?>>FAX番号</th>
					<td>
					<?php if($ferror==1){echo $form->text("FAX_NO1", array("class" => "w60 error",'maxlength' => 4));}
						else{echo $form->text("FAX_NO1", array("class" => "w60",'maxlength' => 4));} ?>
					<span class="pl5 pr5">-</span>
					<?php if($ferror==1){echo $form->text("FAX_NO2", array("class" => "w60 error",'maxlength' => 4));}
						else{echo $form->text("FAX_NO2", array("class" => "w60",'maxlength' => 4));} ?>
					<span class="pl5 pr5">-</span>
					<?php if($ferror==1){echo $form->text("FAX_NO3", array("class" => "w60 error",'maxlength' => 4));}
						else{echo $form->text("FAX_NO3", array("class" => "w60",'maxlength' => 4));} ?>
					<br /><span class="usernavi"><?php echo $usernavi['FAX']; ?></span>
					<br /><span class="must"><?php if($ferror==1)echo "正しいFAX番号を入力してください"?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error("WEBSITE")?' class="txt_top"':''; ?>>ホームページ</th>
					<td>
					<?php echo $form->text('WEBSITE', array('class' => 'w600'.($form->error('WEBSITE')?' error':''),'maxlength' => 100)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['WEBSITE']; ?></span>
					<br /><span class="must"><?php echo $form->error('WEBSITE'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>自社担当者</th>
					<td id="SETCHARGE">
						<?php echo $form->input('CHR_NAME', array('label' => false, 'div' => false, 'readonly'=>'readonly', 'class' => 'w100')); ?>
						<?php echo $form->hidden('CHR_ID'); ?>

						<?php echo $html->link($html->image('bt_registered.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'charge\');',null,false)); ?>
						<?php echo $html->link($html->image('bt_reset.jpg'),'#',array('escape' => false, 'onclick'=>'return charge_reset();',null,false)); ?>

					<br /><span class="usernavi"><?php echo $usernavi['CHR_ID']; ?></span>
					</td>
				</tr>
			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>

	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="company_02"><span class="edit_txt">&nbsp;</span></div></h3>

	<div class="contents_box">
		<span class="usernavi"><?php echo $usernavi['CUSTOMER_PAYMENT']; ?></span>
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th style="width:130px;"<?php echo $form->error("CUTOOFF_DATE")?' class="txt_top"':''; ?>>締日</th>
					<td style="width:750px;">
					<?php echo $form->input('CUTOOFF_SELECT', $cutooff_select,array('class'=>'txt_mid')); ?>
					<?php echo $form->text("CUTOOFF_DATE", array('class' =>'w60 mr5 ml5'.($form->error('CUTOOFF_DATE')?' error':''),'maxlength' => 2)); ?>日
					<br /><span class="usernavi"><?php echo $usernavi['CST_CUTOOFF']; ?></span>
					<br /><span class="must"><?php echo $form->error('CUTOOFF_DATE'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error("PAYMENT_DAY")?' class="txt_top"':''; ?>>支払日</th>
					<td>
					<?php echo $form->input('PAYMENT_MONTH', array('label' => false, "div"=>false, 'options' => $payment,'class'=>'w120 mr20','size'=>'1')); ?>
					<?php echo $form->input('PAYMENT_SELECT', $payment_select); ?>
					<?php echo $form->text("PAYMENT_DAY", array('class' =>'w60 mr5 ml5'.($form->error('PAYMENT_DAY')?' error':''),'maxlength' => 2)); ?>日
					<br /><span class="usernavi"><?php echo $usernavi['CST_PAYMENT']; ?></span>
					<br /><span class="must"><?php echo $form->error('PAYMENT_DAY'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>消費税設定</th>
					<td><?php echo $form->input('EXCISE', $excises); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>消費税端数処理</th>
					<td><?php echo $form->input('TAX_FRACTION', $fractions); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>消費税端数計算</th>
					<td><?php echo $form->input('TAX_FRACTION_TIMING', $tax_fraction_timing); ?>
					</td>
				</tr>				
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>				<tr>
					<th>基本端数処理</th>
					<td><?php echo $form->input('FRACTION', $fractions); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th class="txt_top">備考</th>
					<td>
					<?php echo $form->textarea('NOTE', array('class' => 'textarea'.($form->error('NOTE')?' error':''),'maxlength' => 1000)); ?>
						<br /><span class="must"><?php echo $form->error('NOTE'); ?></span>
					</td>
				</tr>

			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>

	<div class="edit_btn">
		<?php echo $form->submit('bt_save.jpg', array('div' => false , 'name' => 'submit', 'alt' => '保存する', 'class' => 'imgover')); ?>
		<?php echo $form->submit('bt_cancel.jpg', array('div' => false , 'name' => 'cancel', 'alt' => 'キャンセル', 'class' => 'imgover')); ?>
	</div>
</div>
<?php echo $customHtml->hiddenToken(); ?>
<?php echo $form->hidden("USR_ID",array('value'=>$user['USR_ID'])); ?>
<?php echo $form->hidden("UPDATE_USR_ID",array('value'=>$user['USR_ID'])); ?>
<?php echo $form->end(); ?>