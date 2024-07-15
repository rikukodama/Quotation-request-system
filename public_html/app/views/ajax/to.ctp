<?php echo $html->css("popup")."\n"; ?>

<script type="text/javascript">
<!--
	function insert(no){
		$('#TONAME').children('input').val($('#name'+no).html());
		$('#TO').children('input').val($('#mail'+no).html());
		popupclass.popup_close();
		return false;
	}

	var url="<?php echo $html->url('/ajax/popup'); ?>";
	var no="<?php echo $no; ?>";

	function paging(page) {
		var param = eval({
			"type" : "to",
			"no"   : no,
			"ctype": $('input[type="radio"]:checked').val(),
			"page"   : page
		});
		$.post(url, {params:param}, function(d){
			$('#popup').html(d);
		});
	}

$(document).ready(function($){

	//ラジオボタン選択時処理
	$('input[type="radio"]').click(function(){

		var param = eval({
			"type" : "to",
			"no"   : no,
			"ctype": $(this).val()
		});
		$.post(url, {params:param}, function(d){
			$('#popup').html(d);
		});


	});
});


// -->
</script>

<style type="text/css"><!--

table.tbl {
	border: 1px #E3E3E3 solid;
	border-collapse: collapse;
	border-spacing: 0;
	margin:10px auto;
}

table.tbl tr.bgti {
    background: #EEEEEE;
}

table.tbl tr.bgcl {
    background: #F5F5F5;
}

table.tbl td {
	padding: 10px;
	border: 1px #E3E3E3 solid;
	border-width: 0 0 1px 1px;
}

table.tbl td.left {
	text-align: left;
}

table.tbl td.center {
	text-align: center;
}

--></style>

<form id="popupForm">
<div id="popup_contents">
	<?php echo $html->image('/img/popup/tl_allcharge.jpg'); ?>
	<div class="popup_contents_box">
		<div class="popup_contents_area clearfix">
			<table width="500" cellpadding="0" cellspacing="0" border="0" class="tbl">
				<tr>
					<td colspan="3" class="w40">
						<?php echo $form->radio('type', array(0 => '顧客担当者',1 => '自社担当者'), array('label' => false, 'legend' => false, 'class' => 'ml20 mr5 txt_mid')); ?>
					</td>
				</tr>
				<tr>
					<td colspan="3" class="w40 center">
						<?php echo $nowpage; ?>
						<?php echo $paging; ?>
					</td>
				</tr>
				<tr class="bgti">
					<td class="w40"></td>
					<td class="w80">名前</td>
					<td>メールアドレス</td>
				</tr>
				<?php
				$i=0;
				foreach($charge as $key => $value){?>
				<tr<?php if(($i%2)==1) echo " class=\"bgcl\"";?>>
					<td class="w40"><?php echo $html->link($html->image('bt_insert.jpg'),'#',array('escape' => false,'onclick'=>'return insert('.$key.');',null,false)); ?></td>
					<td class="w80" id="name<?php echo $key;?>"><?php echo $value['CHARGE_NAME']; ?></td>
					<td id="mail<?php echo $key;?>"><?php echo $value['MAIL']; ?></td>
				</tr>
				<?php
				$i++;
				} ?>
			</table>
			<div class="save_btn">
				<?php echo $html->link($html->image('bt_cancel_s.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popup_close();',null,false)); ?>
			</div>
		</div>
	</div>
</div>
</form>
