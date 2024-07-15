<?php	//完了メッセージ
	echo $session->flash();
?>

<script type="text/javascript">
<!--
/*
var url="<?php echo $html->url('/ajax/excel'); ?>";

$(document).ready(function($){

	$('select.date').change(function(){

		var param = eval({
			"year1"  : $('#year1').val(),
			"month1" : $('#month1').val(),
			"day1"   : $('#day1').val(),
			"year2"  : $('#year2').val(),
			"month2" : $('#month2').val(),
			"day2"   : $('#day2').val()
		});

		$.post(url, {params:param}, function(d){
			$('#test').html(d);
		});
	});

	var param = eval({
		"year1"  : $('#year1').val(),
		"month1" : $('#month1').val(),
		"day1"   : $('#day1').val(),
		"year2"  : $('#year2').val(),
		"month2" : $('#month2').val(),
		"day2"   : $('#day2').val()
	});

	$.post(url, {params:param}, function(d){
		$('#test').html(d);
	});
});
*/

// -->
</script>

<div id="contents">
	<div class="search_box">
		<div class="search_area">
 				<?php echo $form->create("Quote", array("type" => "post", "controller" => "quotes")); ?>
			<table width="600" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td>
						以下のプルダウンより抽出する期間を設定してください。期間は見積書の発行日となります。
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $form->dateTime('Quote', 'DATE1', null , 1); ?>
						　～　
						<?php echo $form->dateTime('Quote', 'DATE2', null , 2); ?>
					</td>
				</tr>
			</table>
			<div id="test"></div>

			<div class="search_btn">
				<?php echo $form->submit('bt_search.jpg', array('name' => 'download', 'alt' => '検索する')); ?>
			</div>
			<?php echo $form->end(); ?>
		</div>
		<?php echo $html->image('/img/document/bg_search_bottom.jpg',array('class'=>'block')); ?>
	</div>
</div>