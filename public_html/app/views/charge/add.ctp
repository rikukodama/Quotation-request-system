<script type="text/javascript">
<!--
$(document).ready(function($){
	if($('input[name="data[Charge][SEAL_METHOD]"]:checked').val() == 1){
		$('div.SEAL_METHOD').slideToggle();
	}

	$('input[name="data[Charge][SEAL_METHOD]"]').change(function(){

		$('div.SEAL_METHOD').slideToggle();
	});
});

//-->
</script>


<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('/img/company/i_guide.jpg'); ?>
		<p>こちらのページは自社担当者登録の画面です。<br />必要な情報を入力の上「保存する」ボタンを押下すると自社担当者を作成できます。</p>
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
			<?php echo $form->create("Charge", array("type" => "file", "controller" => "charges", "class" => "Charge")); ?>
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th>ステータス</th>
					<td>
					<?php echo $form->input('STATUS', array('label' => false, 'options' => $status));?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:150px;" <?php echo $form->error("CHARGE_NAME")?' class="txt_top"':''; ?>><span class ="float_l">担当者名</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:730px;">
					<?php echo $form->text('CHARGE_NAME', array('class' => 'w300'.($form->error('CHARGE_NAME')?' error':''),'maxlength'=>60)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['CHARGE_NAME']; ?></span>
					<br /><span class="must"><?php echo $form->error('CHARGE_NAME'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:150px;" <?php echo $form->error("CHARGE_NAME_KANA")?' class="txt_top"':''; ?>><span class ="float_l">担当者名カナ</span></th>
					<td style="width:730px;">
					<?php echo $form->text('CHARGE_NAME_KANA', array('class' => 'w300'.($form->error('CHARGE_NAME_KANA')?' error':''),'maxlength'=>60)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['CHARGE_NAME_KANA']; ?></span>
					<br /><span class="must"><?php echo $form->error('CHARGE_NAME_KANA'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:150px;" <?php echo $form->error("UNIT")?' class="txt_top"':''; ?>><span class ="float_l">部署名</span></th>
					<td style="width:730px;">
					<?php echo $form->text('UNIT', array('class' => 'w300'.($form->error('UNIT')?' error':''),'maxlength'=>60)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['UNIT']; ?></span>
					<br /><span class="must"><?php echo $form->error('UNIT'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error("POST")?' class="txt_top"':''; ?>>役職名</th>
					<td>
					<?php echo $form->text('POST', array('class' => 'w300'.($form->error('POST')?' error':''),'maxlength'=>60)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['POST']; ?></span>
					<br /><span class="must"><?php echo $form->error('POST'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th <?php echo $form->error("MAIL")?' class="txt_top"':''; ?>>メールアドレス</th>
					<td>
					<?php echo $form->text('MAIL', array('class' => 'w300'.($form->error('MAIL')?' error':''),'maxlength'=>256)); ?>
					<br /><span class="must"><?php echo $form->error('MAIL'); ?></span>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo ($form->error("POSTCODE1")||$form->error("POSTCODE2"))?' class="txt_top"':''; ?> style="width:150px;"><span class ="float_l">郵便番号</span></th>
							<td style="width:730px;">
							<?php
								echo $form->text("POSTCODE1", array("class" => "w60".($form->error("POSTCODE1")||$form->error("POSTCODE2")?' error':''),'maxlength'=>3));
							?>
							<span class="pl5 pr5">-</span>
							<?php
								echo $form->text("POSTCODE2", array("class" => "w60".($form->error("POSTCODE1")||$form->error("POSTCODE2")?' error':''),'maxlength'=>4));
							?>
							<br /><span class="usernavi"><?php echo $usernavi['POSTCODE']; ?></span>
							<div><?php echo $ajax->div("target"); ?><?php echo $ajax->divEnd("target"); ?></div>

							<br /><span class="must"><?php if($form->error("POSTCODE1")){echo $form->error("POSTCODE1");}else if($form->error("POSTCODE2")){echo $form->error("POSTCODE2");} ?></span>
							</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:150px;" ><span class ="float_l">都道府県</span></th>
					<td style="width:730px;">
					<?php echo $form->input('CNT_ID', array('label' => false, 'options' => $countys)); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:150px;" <?php echo $form->error("ADDRESS")?' class="txt_top"':''; ?>><span class ="float_l">住所</span></th>
					<td style="width:730px;">
					<?php echo $form->text('ADDRESS', array('class' => 'w600'.($form->error('ADDRESS')?' error':''),'maxlength'=>100)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['ADDRESS']; ?></span>
					<br /><span class="must"><?php echo $form->error('ADDRESS'); ?></span>
					</td>

				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error("BUILDING")?' class="txt_top"':''; ?>>建物名</th>
					<td>
					<?php echo $form->text('BUILDING', array('class' => 'w600'.($form->error('BUILDING')?' error':''),'maxlength'=>100)); ?>
					<br /><span class="usernavi"><?php echo $usernavi['BUILDING']; ?></span>
					<br /><span class="must"><?php echo $form->error('BUILDING'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>

					<th  style="width:150px;" <?php echo $perror == 1?' class="txt_top"':''; ?>><span class ="float_l">電話番号</span></th>
					<td style="width:730px;">
					<?php if($perror==1){echo $form->text("PHONE_NO1", array("class" => "w60 error",'maxlength'=>5));}
						else{echo $form->text("PHONE_NO1", array("class" => "w60",'maxlength'=>5));} ?>
		 			<span class="pl5 pr5">-</span>
		 			<?php if($perror==1){echo $form->text("PHONE_NO2", array("class" => "w60 error",'maxlength'=>4));}
						else{echo $form->text("PHONE_NO2", array("class" => "w60",'maxlength'=>4));} ?>
		 			<span class="pl5 pr5">-</span>
		 			<?php if($perror==1){echo $form->text("PHONE_NO3", array("class" => "w60 error",'maxlength'=>4));}
						else{echo $form->text("PHONE_NO3", array("class" => "w60",'maxlength'=>4));} ?>
					<br /><span class="usernavi"><?php echo $usernavi['PHONE']; ?></span>
					<br /><span class="must"><?php if($perror==1)echo "正しい電話番号を入力してください"?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $ferror == 1?' class="txt_top"':''; ?>>FAX番号</th>
					<td>
					<?php if($ferror==1){echo $form->text("FAX_NO1", array("class" => "w60 error",'maxlength'=>4));}
						else{echo $form->text("FAX_NO1", array("class" => "w60",'maxlength'=>4));} ?>
		 			<span class="pl5 pr5">-</span>
		 			<?php if($ferror==1){echo $form->text("FAX_NO2", array("class" => "w60 error",'maxlength'=>4));}
						else{echo $form->text("FAX_NO2", array("class" => "w60",'maxlength'=>4));} ?>
		 			<span class="pl5 pr5">-</span>
		 			<?php if($ferror==1){echo $form->text("FAX_NO3", array("class" => "w60 error",'maxlength'=>4));}
						else{echo $form->text("FAX_NO3", array("class" => "w60",'maxlength'=>4));} ?>
					<br /><span class="usernavi"><?php echo $usernavi['FAX']; ?></span>
					<br /><span class="must"><?php if($ferror==1)echo "正しいFAX番号を入力してください"?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr><th>担当者印</th><td>
				<?php
					$seal = 0;
					if(isset($this->data['Charge']['SEAL_METHOD'])) $seal = $this->data['Charge']['SEAL_METHOD'];
					echo $form->radio('SEAL_METHOD', $seal_method, array('value' => $seal,'label' => false, 'legend' => false, 'class' => 'ml20 mr5 txt_mid'));
				?></td></tr>
				<tr><th>&nbsp;</th></tr>
			</table>

			<div class='SEAL_METHOD'>
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th style="width:150px;">&nbsp;</th>
					<td><?php
						if(isset($image)){
							echo $html->image(array("controller" => "charges",'action' => 'contents/'.$id),array("width"=>"100","height"=>"100"));
						}
						?>
					<?php echo $form->file("image"); ?>
					<?php echo $form->checkbox("DEL_SEAL",array("style" => "width:30px;")); ?>削除
					<br /><span class="usernavi"><?php echo $usernavi['SEAL']; ?></span>
					<br /><span class="must"><?php echo $ierror==1?'画像はjpeg,png,gifのみです':'' ?></span>
					<br /><span class="must"><?php echo $ierror==2?'1MB以上の画像は登録できません':'' ?></span>
					<br /><span class="must"><?php echo $ierror==3?'正しい画像を指定してください':'' ?></span>
					</td>
				</tr>
			</table>
			</div>
			<div class='SEAL_METHOD' style="display:none">
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th style="width:150px;">&nbsp;</th>
					<td>
						<?php echo $form->text("SEAL_STR", array("class" => "w300",'maxlength'=>4));?>
						<br /><span class="usernavi"><?php echo $usernavi['SEAL_METHOD']; ?></span>
						<br /><span class="must"><span class="must"><?php echo $form->error('SEAL_STR'); ?></span>
					</td>
				</tr>
			</table>

			</div>
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>押印設定</th>
					<td><?php echo $form->radio('CHR_SEAL_FLG', $seal_flg, array('value' => 1,'label' => false, 'legend' => false, 'class' => 'ml20 mr5 txt_mid')); ?>
					<br /><span class="usernavi"><?php echo $usernavi['CHR_SEAL_FLG']; ?></span>
					<br /><span class="must"><?php echo $form->error('CHR_SEAL_FLG');?></span>
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