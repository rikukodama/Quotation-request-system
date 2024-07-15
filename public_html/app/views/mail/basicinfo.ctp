<div id="contents">
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg',array('class'=>'block')); ?>
		<div class="contents_area">
			<?php echo $form->create("Mail", array("type" => "post", "controller" => "mail")); ?>

			<h3 class="mail_h3">こちらで、データ送信者と宛先の指定をしてください。</h3>
			<p class="mb30">「氏名」「メールアドレス」については、それぞれ「自社担当者」「取引先担当者」であらかじめ情報が登録されている場合、<br />「登録情報から選択」ボタンで情報を呼び出すことができます。</p>
			<div class="mail_table">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th class="th_title">件名</th>
					<td class="td_title" colspan="3"><?php echo $subject; ?> ( <?php echo $customer; ?> )</td>
				</tr>
				<tr>
					<th class="th_fromt" width="40" rowspan="2">From</th>
					<td class="td_fromt" width="150">送信者</td>
					<td class="td_fromt" width="300" id="FROMNAME">
						<?php echo $form->text("CHARGE", array('class'=>'w300'.(isset($error['CHARGE'])?' error':''),'max_length' => 60)); ?>
						<br /><span class="must"><?php echo isset($error['CHARGE'])?$error['CHARGE']:''; ?></span>
					</td>
					<td class="td_fromt" width="420">
						<?php echo $html->link($html->image('bt_registered.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'from\');',null,false)); ?>
					</td>
				</tr>
				<tr>
					<td class="td_fromb">返信用メールアドレス</td>
					<td class="td_fromb" colspan="2" id="FROM">
						<?php echo $form->text("FROM", array('class'=>'w300'.(isset($error['FROM'])?' error':''))); ?>
						<br /><span class="must"><?php echo isset($error['FROM'])?$error['FROM']:''; ?></span>
					</td>
				</tr>
				<tr>
					<th class="th_tot" width="40" rowspan="2">To</th>
					<td class="td_tot">氏名</td>
					<td id="TONAME" class="td_tot" width="300">
						<?php echo $form->text("CUSTOMER_CHARGE", array('class'=>'w300'.(isset($error['CUSTOMER_CHARGE'])?' error':''),'max_length' => 60)); ?>
						<br /><span class="must"><?php echo isset($error['CUSTOMER_CHARGE'])?$error['CUSTOMER_CHARGE']:''; ?></span>
					</td>
					<td class="td_tot" width="440">
						<?php echo $html->link($html->image('bt_registered.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'to\', \''.$cstid.'\');',null,false)); ?>
					</td>
				</tr>
				<tr>
					<td class="td_tob">メールアドレス</td>
					<td id="TO" class="td_tob" colspan="2">
						<?php echo $form->text("TO", array('class'=>'w300'.(isset($error['TO'])?' error':''))); ?>
						<br /><span class="must"><?php echo isset($error['TO'])?$error['TO']:''; ?></span>
					</td>
				</tr>
				<tr>
					<th class="th_pw" colspan="2"><?php echo $html->image("i_pw.jpg"); ?><br /><p>（データ保護のため受信者がデータをダウンロードする前にパスワードを設定することができます）</p></th>
					<td class="td_pw" colspan="2">
						<?php echo $form->text("PASSWORD1", array('class'=>'w200'.(isset($error['PASSWORD1'])?' error':''))); ?>
						<br /><span class="must"><?php echo isset($error['PASSWORD1'])?$error['PASSWORD1']:''; ?></span>
						<br />※パスワードは、自動送信されませんので、別途送信してください。
					</td>
				</tr>
			</table>
			</div>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>
			</div>
			<?php echo $form->hidden("USR_ID"); ?>
			<?php echo $form->hidden("SUBJECT", array('value' => $subject)); ?>
			<?php echo $form->hidden("TYPE"); ?>
			<?php echo $form->hidden("FRM_ID"); ?>
			<?php echo $form->hidden("tkn"); ?>
			<?php echo $form->hidden("COMPANY", array('value' => $company)); ?>
			<?php echo $form->hidden("CUSTOMER", array('value' => $customer)); ?>
			<div class="edit_btn">
			<?php echo $form->submit('/img/bt_next.jpg', array('div' => false , 'name' => 'body', 'alt' => '次へ', 'class' => 'imgover')); ?>
			<?php echo $form->end(); ?>
			</div>
</div>