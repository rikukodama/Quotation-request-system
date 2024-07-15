<div id="contents">
	<div class="contents_box">
		<?php echo $html->image('/img/bg_contents_top.jpg',array('class'=>'block')); ?>
			<?php echo $form->create("Mail", array("type" => "post", "controller" => "mails")); ?>

		<div class="contents_area">
			<h3 class="mail_h3">データのダウンロード</h3>

			<p class="p_message"><?php echo $rcv_name; ?> 様宛てに <?php echo $snd_name; ?> 様より下記データが届いています。<br />ダウンロードボタンを押してデータをダウンロードしてください。</p>

			<p class="pb20">※データのお預かり期間は本日より7日間となっておりますので、期間内にダウンロードしていただきますようお願いします。<br />&emsp;お預かり期間が過ぎた場合は送信者様にご連絡ください。</p>

			<div class="mail_table2">

			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th class="th_l">件名</th>
					<th class="th_r">ダウンロード</th>
				</tr>
				<tr>
					<td width="420"><?php echo $subject; ?></td>
					<td width="130">
						<?php echo $html->link($html->image('bt_download1.jpg',array('class'=>'imgover','alt'=>'ダウンロード')), array('controller' => $type, 'action' => 'pdf/'.$frm_id.'/download/'.$token),array('escape' => false)); ?>
					</td>
				</tr>
			</table>
			</div>
		</div>
		<?php echo $html->image('/img/bg_contents_bottom.jpg',array('class'=>'block')); ?>
	</div>

	<div class="contents_box mt20">
		<?php echo $html->image('/img/bg_contents_top.jpg',array('class'=>'block')); ?>
		<div class="contents_area">

			<h3 class="mail_h3">ステータスの選択</h3>

			<p class="pb20">データをご確認いただき、修正が必要な場合はステータスの「修正願い」にチェックをつけてください。<br />修正点がなくデータに問題なければ、「確認済み」にチェックを付け、「送信」ボタンを押してください。</p>

			<div class="mail_table3 mb30">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th width="120">ステータス</th>
					<td width="410">
					<?php echo $form->radio('STATUS', array(1 => '確認済み', 2 => '修正願い'), array('label' => false, 'legend' => false, 'class' => 'ml20 mr5 txt_mid')); ?>
					</td>
				</tr>
			</table>
			</div>
			<h3 class="mail_h3">コメントの記入</h3>

			<p class="pb20">ステータスで「修正願い」を選択した場合、修正内容を以下のコメント欄にご記入ください。<br />送信されたコメントについては、データ送信者に送信されます。</p>

			<div class="mail_table3">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th class="t_top" width="120">コメント<br />(200文字まで)</th>
					<td width="410">
						<?php echo $form->textarea("COMMENT", array('rows'=>'8', 'class'=>'textarea2'.(isset($error['COMMENT'])?' error':''))); ?>
						<br /><span class="must"><?php echo isset($error['COMMENT'])?$error['COMMENT']:''; ?></span>
					</td>
				</tr>
			</table>
			</div>
		</div>
		<?php echo $html->image('/img/bg_contents_bottom.jpg',array('class'=>'block')); ?>
	</div>
	<div class="edit_btn">
		<?php echo $form->hidden("TML_ID"); ?>
		<?php echo $form->hidden("TOKEN"); ?>
		<?php echo $form->hidden("tkn"); ?>
		<?php echo $form->submit('/img/bt_next.jpg', array('name' => 'reaffirmation', 'alt' => '確認')); ?>
	<?php echo $form->end(); ?>
	</div>
</div>