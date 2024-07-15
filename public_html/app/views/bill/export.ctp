<?php	//完了メッセージ
	echo $session->flash();
?>
<div id="contents">
	<div class="search_box">
		<div class="search_area">
 				<?php echo $form->create("Bill", array("type" => "post", "controller" => "bills")); ?>
			<table width="600" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td>
						以下のプルダウンより抽出する期間を設定してください。期間は請求書の発行日となります。
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $form->dateTime('Bill', 'DATE1'); ?>
						　～　
						<?php echo $form->dateTime('Bill', 'DATE2'); ?>
					</td>
				</tr>
			</table>

			<div class="search_btn">
				<?php echo $form->submit('bt_search.jpg', array('name' => 'download', 'alt' => '検索する')); ?>
			</div>
			<?php echo $form->end(); ?>
		</div>
		<?php echo $html->image('/img/document/bg_search_bottom.jpg',array('class'=>'block')); ?>
	</div>
</div>