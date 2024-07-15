<script type="text/javascript">
<!--
	//送信の確認
	function rec() {
		if (confirm("１度送信してしまうとPDFのダウンロードが出来なくなりますが、送信をしてもよろしいですか？")){
			//送信
			return true;
		} else {
			//キャンセル
			return false;
		}
	}
// -->
</script>
<div id="contents">
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg',array('class'=>'block')); ?>
		<div class="contents_area">
			<?php echo $form->create("Mail", array("type" => "post", "controller" => "mails")); ?>

			<h3 class="mail_h3">こちらで、入力データの確認をしてください。</h3>
			<p class="mb30">確認が終わったら、送信ボタンを押して送信します。</p>
			<div class="mail_table">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th >入力確認</th>
				</tr>
				<tr>
				<td></td>
				</tr>
				<tr>
					<th class="th_fromt"colspan="2">ステータス</th>
					<td class="td_pwf"" colspan="2" style="line-height:1.5em;">
						<?php echo $status; ?><?php echo $form->hidden("STATUS"); ?>
					</td>
				</tr>
				<tr>
					<th class="th_pw" colspan="2">コメント<br>（200文字まで）</th>
					<td class="td_pw"" colspan="2" >
						<?php echo $comment; ?><?php echo $form->hidden("COMMENT"); ?>
					</td>
				</tr>
			</table>
			</div>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>
	</div>
	<div class="edit_btn">
		<?php echo $form->hidden("TML_ID"); ?>
		<?php echo $form->hidden("TOKEN"); ?>
		<?php echo $form->hidden("tkn"); ?>
		<?php echo $form->submit('/img/bt_back.jpg', array('name' => 'logind', 'div' => false , 'alt' => '戻る')); ?>
		<?php echo $form->submit('/img/bt_submit.jpg', array('name' => 'send', 'onclick' => 'return rec()', 'div' => false , 'alt' => '送信')); ?>
	</div>
	<?php echo $form->end(); ?>
</div>