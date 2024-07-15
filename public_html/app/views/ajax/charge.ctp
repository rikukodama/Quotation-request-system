<?php echo $html->css("popup")."\n"; ?>

<script type="text/javascript">
<!--
	function insert(no){
		$('#SETCHARGE').children('input[type=text]').val($('#name'+no).html());
		$('#SETCHARGE').children('input[type=hidden]').val($('#user'+no).children('input#CHR_ID').val());
		$('#SET_CHR_SEAL_FLG').children('input[type=radio]').removeAttr('checked');

		if($('#user'+no).children('input#TMP_CHR_SEAL_FLG').val() == 1) {
			$('#SET_CHR_SEAL_FLG').children('input[type=radio]:first').attr('checked', 'checked');
		}else {
			$('#SET_CHR_SEAL_FLG').children('input[type=radio]:last').attr('checked', 'checked');
		}
		popupclass.popup_close();
		return false;
	}


	var url="<?php echo $html->url('/ajax/popup'); ?>";

	function paging(page) {
		var param = eval({
			"type"   : "charge",
			"page"   : page
		});

		//キーワードがある場合
		if($("#CHR_KEYWORD").val()){
			param["keyword"] = $("#CHR_KEYWORD").val();
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

	//商品一覧並び替え
	var sortBy = eval({
		"CHARGE_NAME_KANA"   : 0,
		"LAST_UPDATE" : 0
	});

	function sorting(sort) {
		var param = eval({
			"type"   : "charge",
			"sort"   : sort,
			"desc"   : sortBy[sort]
		});

		if($("#CHR_KEYWORD").val()){
			param["keyword"] = $("#CHR_KEYWORD").val();
		}

		$.post(url, {params:param}, function(d){
			$('#popup').html(d);
			//降順昇順いれかえ
			sortBy["CHARGE_NAME_KANA"] = 0;
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
td.w260{
	width:360;
}
--></style>

<form id="popupCharge">
<div id="popup_contents">
	<?php echo $html->image('/img/popup/tl_charge.jpg'); ?>
	<div class="popup_contents_box">
		<div class="popup_contents_area clearfix">
			<table width="503" cellpadding="0" cellspacing="0" border="0" class="tbl">
				<tr>
					<td colspan="4" class="w40 center">
						<?php echo $nowpage; ?>
						<?php echo $paging; ?>
					</td>
				</tr>
				<tr>
					<td colspan="4">
						　<a href="javascript:void(0)" onclick="return sorting('CHARGE_NAME_KANA');">担当者名(カナ)</a> / <a href="javascript:void(0)" onclick="return sorting('LAST_UPDATE');">更新日順</a>　　
						<?php echo $form->input('CHR_KEYWORD', array('label' => false, 'div' => false,'error'=>false, 'class' => 'w120 p2')); ?>
						<?php echo $html->link($html->image('bt_search.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'charge\');')); ?>
					</td>
				</tr>
				<tr class="bgti">
					<td class="w40"></td>
					<td class="W140">名前</td>
					<td class="W140">部署名</td>
					<td >作成者</td>
				</tr>
				<?php $i=0;
				foreach($charge as $key => $value){?>
				<tr<?php if(($i%2)==1) echo " class=\"bgcl\"";?>>
					<td class="w40 center" id="user<?php echo $key; ?>"><?php echo $html->link($html->image('bt_insert.jpg'),'#',array('escape' => false,'onclick'=>'return insert('.$key.');',null,false)); ?>
					<?php echo $form->hidden("CHR_ID",array('value'=>$value['Charge']['CHR_ID'])); ?>
					<?php echo $form->hidden('TMP_CHR_SEAL_FLG', array('value'=>$value['Charge']['CHR_SEAL_FLG']));?>
					</td>
					<td class="w140" id=name<?php echo $key;?>><?php echo $value['Charge']['CHARGE_NAME']; ?></td>
					<td class="w140"><?php echo $value['Charge']['UNIT']; ?></td>
					<td><?php echo $value['User']['NAME']; ?></td>
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