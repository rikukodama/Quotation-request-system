<?php echo $html->css("popup")."\n"; ?>
<script type="text/javascript">
<!--
	function insert(no){
		$('#SETCUSTOMER').children('input[type=text]').val($('#name'+no).html());
		$('#SETCUSTOMER').children('input[type=hidden]').val($('#id'+no).children('input#CST_ID').val());

		$('#SETCHARGE').children('input[type=text]').val($('#id'+no).children('input#CHARGE_NAME').val());
		$('#SETCHARGE').children('input[type=hidden]').val($('#id'+no).children('input#CHR_ID').val());

		if($('#EXCISE').html()){
			$('#EXCISE').children('input[type=radio]').val([$('#id'+no).children('input#EXCISE').val()]);
			$('#FRACTION').children('input[type=radio]').val([$('#id'+no).children('input#FRACTION').val()]);
			$('#TAX_FRACTION').children('input[type=radio]').val([$('#id'+no).children('input#TAX_FRACTION').val()]);
			$('#TAX_FRACTION_TIMING').children('input[type=radio]').val([$('#id'+no).children('input#TAX_FRACTION_TIMING').val()]);
			$('#HONOR').children('input[type=text]').val([$('#id'+no).children('input#HONOR_TITLE').val()]);
			$('#HONOR').children('input[type=radio]').val([$('#id'+no).children('input#HONOR_CODE').val()]);
			form.f_subtotal();
		}

		var address = $('#id'+no).children('input#C_POSTCODE').val() + " " +$('#id'+no).children('input#C_SEARCH_ADDRESS').val();
		$("#INSERT_ADDRESS").html('<a href="javascript:void(0)" onclick="insert_address('+ no +');" >'+ address +'</a>');
		popupclass.popup_close();
		return false;
	}


	//顧客情報から住所を自動入力
	function insert_address(no) {
		$("#CustomerChargeADDRESS").val([$('#id'+no).children('input#C_ADDRESS').val()]);
		$("#CustomerChargePOSTCODE1").val([$('#id'+no).children('input#C_POSTCODE1').val()]);
		$("#CustomerChargePOSTCODE2").val([$('#id'+no).children('input#C_POSTCODE2').val()]);
		$("#CustomerChargeCNTID").val([$('#id'+no).children('input#C_CNT_ID').val()]);
		$("#CustomerChargeBUILDING").val([$('#id'+no).children('input#C_BUILDING').val()]);
		$("#INSERT_ADDRESS").html("");
	}


	var url="<?php echo $html->url('/ajax/popup'); ?>";

	function paging(page) {


		var param = eval({
			"type"   : "select_customer",
			"page"   : page
		});

		//キーワードがある場合
		if($("#CST_KEYWORD").val()){
			param["keyword"] = $("#CST_KEYWORD").val();
		}

		//ソートされている場合
		if($("#sort").val()){
			param["sort"] = $("#sort").val();
			param["desc"] = $("#desc").val();
		}
		$.post(url, {params:param}, function(d){
			$('#popup').html(d);
		});

	}

	//顧客一覧並び替え
	var sortBy = eval({
		"NAME_KANA"   : 0,
		"LAST_UPDATE"   : 0
	});

	function sorting(sort) {
		var param = eval({
			"type"   : "select_customer",
			"sort"   : sort,
			"desc"   : sortBy[sort]
		});

		if($("#CST_KEYWORD").val()){
			param["keyword"] = $("#CST_KEYWORD").val();
		}

		$.post(url, {params:param}, function(d){
			$('#popup').html(d);
			//降順昇順いれかえ
			sortBy["NAME_KANA"] = 0;
			sortBy["LAST_UPDATE"] = 1;
			sortBy[sort] = 1 - param["desc"];
		});
	}
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
a.lmargin40{
	margin-left:40px;
}
a.lmargin20{
	margin-left:20px;
}
--></style>
<form id="popupCharge">
<div id="popup_contents">
	<?php echo $html->image('/img/popup/tl_customer.jpg');?>
	<div class="popup_contents_box">
		<div class="popup_contents_area clearfix">
			<table width="500" cellpadding="0" cellspacing="0" border="0" class="tbl">
				<tr>
					<td colspan="<?php echo($user['AUTHORITY']!=1)?"3":"2";?>" class="w40 center">
						<?php echo ($user['AUTHORITY']!=1)?$html->link($html->image('bt_new2.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'customer\');', 'class'=>'float_l lmargin20')):
						$html->link($html->image('bt_new2.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'customer\');', 'class'=>'float_l lmargin40'));
						?>
						<?php echo $nowpage; ?>
						<?php echo $paging; ?>
					</td>
				</tr>
				<tr>
					<td colspan="<?php echo($user['AUTHORITY']!=1)?"3":"2";?>">
						　<a href="javascript:void(0)" onclick="return sorting('NAME_KANA');">顧客名(カナ)</a>/<a href="javascript:void(0)" onclick="return sorting('LAST_UPDATE');">更新日順</a>　　
						<?php echo $form->input('CST_KEYWORD', array('label' => false, 'div' => false,'error'=>false, 'class' => 'w200 p2')); ?>
						<?php echo $html->link($html->image('bt_search.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'select_customer\');')); ?>
					</td>
				</tr>
				<tr class="bgti">
					<td class="w40"></td>
					<td class=<?php echo($user['AUTHORITY']!=1)?"'w80'":"w120";?>>顧客名</td>
					<?php echo($user['AUTHORITY']!=1)?'<td class="w100">作成者</td>':'';?>
				</tr>
				<?php $i=0;
				foreach($customer as $key => $value){?>
				<tr<?php if(($i%2)==1) echo " class=\"bgcl\"";?>>
					<td class='center' id="id<?php echo $key;?>"><?php echo $html->link($html->image('bt_insert.jpg'),'#',array('escape' => false,'onclick'=>'return insert('.$key.');',null,false)); ?>
						<?php echo $form->hidden("CST_ID", array('value'=>$value['Customer']['CST_ID'])); ?>
						<?php echo $form->hidden("EXCISE", array('value'=>$value['Customer']['EXCISE'])); ?>
						<?php echo $form->hidden("FRACTION", array('value'=>$value['Customer']['FRACTION'])); ?>
						<?php echo $form->hidden("TAX_FRACTION", array('value'=>$value['Customer']['TAX_FRACTION'])); ?>
						<?php echo $form->hidden("TAX_FRACTION_TIMING", array('value'=>$value['Customer']['TAX_FRACTION_TIMING'])); ?>
						<?php echo $form->hidden("HONOR_CODE", array('value'=>$value['Customer']['HONOR_CODE'])); ?>
						<?php echo $form->hidden("HONOR_TITLE", array('value'=>$value['Customer']['HONOR_TITLE'])); ?>
						<?php echo $form->hidden("CHR_ID", array('value' => isset($value['Charge']['CHR_ID']) ? $value['Charge']['CHR_ID'] : null)); ?>
						<?php echo $form->hidden("CHARGE_NAME", array('value' => isset($value['Charge']['CHARGE_NAME']) ? $value['Charge']['CHARGE_NAME'] : null)); ?>
						<?php echo $form->hidden("C_SEARCH_ADDRESS", array('value' => $value['Customer']['SEARCH_ADDRESS'])); ?>
						<?php
							$post_code =  "";
							if($value['Customer']['POSTCODE1'] && $value['Customer']['POSTCODE2']) {
								$post_code = "〒".$value['Customer']['POSTCODE1']."-".$value['Customer']['POSTCODE2'];
							}
						?>
						<?php echo $form->hidden("C_POSTCODE", array('value' => $post_code)); ?>
						<?php echo $form->hidden("C_ADDRESS", array('value' => $value['Customer']['ADDRESS'])); ?>
						<?php echo $form->hidden("C_POSTCODE1", array('value' => $value['Customer']['POSTCODE1'])); ?>
						<?php echo $form->hidden("C_POSTCODE2", array('value' => $value['Customer']['POSTCODE2'])); ?>
						<?php echo $form->hidden("C_CNT_ID", array('value' => $value['Customer']['CNT_ID'])); ?>
						<?php echo $form->hidden("C_BUILDING", array('value' => $value['Customer']['BUILDING'])); ?>
					</td>
					<td class=<?php echo($user['AUTHORITY']!=1)?"'w80'":"''";?> id="name<?php echo $key;?>"><?php echo $value['Customer']['NAME']; ?></td>
					<?php echo ($user['AUTHORITY']!=1)?"<td id=user".$key.">".$value['User']['NAME']."</td>":''; ?>
				</tr>
				<?php
				$i++;
				} ?>
				<?php if($paging){?>
				<tr>
					<td colspan="3" class="w40 center">
						<?php echo $paging; ?>
					</td>
				</tr>
				<?php }?>
			</table>
			<div class="save_btn">
				<?php echo $form->hidden("sort"); ?>
				<?php echo $form->hidden("desc"); ?>
				<?php echo $html->link($html->image('bt_cancel_s.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popup_close();',null,false)); ?>
			</div>
		</div>
	</div>
</div>
</form>