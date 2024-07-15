<div id="contents">
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg',array('class'=>'block')); ?>
		<div class="contents_area">
			<?php echo $form->create("Mail", array("type" => "post", "controller" => "mail")); ?>

			<h3 class="mail_h3">こちらで、送信内容を確認してください。</h3>
			<p class="mb30">確認が終わりましたら、送信を押して送信できます。</p>
			<div class="mail_table">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th class="th_title">件名</th>
					<td class="td_title" colspan="3"><?php echo $param['SUBJECT']; ?></td>
				</tr>
				<tr>
					<th class="th_fromt" width="40" rowspan="2">From</th>
					<td class="td_fromt" width="120">送信者</td>
					<td class="td_fromt" width="740" id="FROMNAME"><?php echo $param['CHARGE']; ?></td>
				</tr>
				<tr>
					<td class="td_fromb">返信用メールアドレス</td>
					<td class="td_fromb" colspan="2" id="FROM">
						<?php echo $param['FROM']; ?>
					</td>
				</tr>
				<tr>
					<th class="th_tot" width="40" rowspan="2">To</th>
					<td class="td_tot" width="100">氏名</td>
					<td id="TONAME" class="td_tot" width="300">
						<?php echo $param['CUSTOMER_CHARGE']; ?>
					</td>
				</tr>
				<tr>
					<td class="td_tob">メールアドレス</td>
					<td id="TO" class="td_tob" colspan="2">
						<?php echo $param['TO']; ?>
					</td>
				</tr>
				<tr>
					<th class="th_pw" colspan="2"><?php echo $html->image("i_pw.jpg");?><br /><p>（データ保護のため受信者がデータをダウンロードする前にパスワードを設定することができます）</p></th>
					<td class="td_pw" colspan="2">
						<?php echo $param['PASSWORD1']; ?>
					</td>
				</tr>
			</table>
			</div>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>
	</div>

	<div class="contents_box mt20">
		<?php echo $html->image('bg_contents_top.jpg',array('class'=>'block')); ?>
		<div class="contents_area">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr><td><?php echo $body; ?></td></tr>
			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>
	</div>

	<?php echo $form->hidden("USR_ID"); ?>
	<?php echo $form->hidden("TYPE"); ?>
	<?php echo $form->hidden("FRM_ID"); ?>
	<?php echo $form->hidden("SUBJECT"); ?>
	<?php echo $form->hidden("MAIL", array('value' => $to)); ?>
	<?php echo $form->hidden("BODY", array('value' => $body)); ?>
	<?php echo $form->hidden("CORD"); ?>
	<?php echo $form->hidden("TO"); ?>
	<?php echo $form->hidden("FROM"); ?>
	<?php echo $form->hidden("COMPANY"); ?>
	<?php echo $form->hidden("CUSTOMER"); ?>
	<?php echo $form->hidden("CHARGE"); ?>
	<?php echo $form->hidden("CUSTOMER_CHARGE"); ?>
	<?php echo $form->hidden("PASSWORD1"); ?>
	<?php echo $form->hidden("tkn"); ?>
	<div class="edit_btn">
		<?php echo $form->submit('/img/bt_back.jpg', array('div' => false , 'name' => 'body', 'alt' => '戻る', 'class' => 'imgover')); ?>
		<?php echo $form->submit('/img/bt_submit.jpg', array('div' => false , 'name' => 'send', 'alt' => '送信', 'onclick' => 'return sendmail();', 'class' => 'imgover')); ?>
	</div>

			<?php echo $form->end(); ?>
</div>