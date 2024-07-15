<?php echo $html->css("popup")."\n"; ?>
<script type="text/javascript">
<!--
	function insert(no){
		if(form.focusline == undefined){
			form.focusline = 0;
		}
		var data = eval("(" + $('#popup_itemlist').text() + ")");
		var dec = $('input[name="data['+form.maintype+'][DECIMAL_UNITPRICE]"]:checked').val();
		$('select[name="data['+form.focusline+']['+form.type+'][LINE_ATTRIBUTE]"]').val(0);
		$('input[name="data['+form.focusline+']['+form.type+'][ITEM]"]').val(data[no].Item.ITEM);
		$('input[name="data['+form.focusline+']['+form.type+'][ITEM_CODE]"]').css('color', '#000').val(data[no].Item.ITEM_CODE);
		$('input[name="data['+form.focusline+']['+form.type+'][UNIT]"]').css('color', '#000').val(data[no].Item.UNIT);
		var excise = $('input[name="data['+form.maintype+'][EXCISE]"]:radio:checked').val();
		//$('input[name="data['+form.focusline+']['+form.type+'][TAX_CLASS]"]').val(data[no].Item.TAX_CLASS);

		if(data[no].Item.TAX_CLASS == 3){
			$('select[name="data['+form.focusline+']['+form.type+'][TAX_CLASS]"]').val(data[no].Item.TAX_CLASS);
		}else {
			var tax_operation_date = <?php echo json_encode($taxOperationDate)?>;
			var issue_date = $("input.cal.date").val();
			issue_date = issue_date.replace(/-/g, '/');
			var prefix = "";
			$.each(tax_operation_date, function(per, dates) {
				//IE8 対応

				dates["start"] = dates["start"].replace(/-/, '/').replace(/-/, '/');
				if(Date.parse(dates["start"]) <= Date.parse(issue_date)) {
				if(per > 5) prefix = per;
				}
			});
			$('select[name="data['+form.focusline+']['+form.type+'][TAX_CLASS]"]').val(prefix + "" + data[no].Item.TAX_CLASS);
		}

		$('input[name="data['+form.focusline+']['+form.type+'][UNIT_PRICE]"]').css('color', '#000').val(this.number_format(data[no].Item.UNIT_PRICE));

		setReadOnly(form.maintype);
		focusLine(form.maintype);
		recalculation(form.maintype);
		popupclass.popup_close();
		return false;
	}


	var url="<?php echo $html->url('/ajax/popup'); ?>";

	function paging(page) {
		var param = eval({
			"type"   : "select_item",
			"page"   : page
		});

		//キーワードがある場合
		if($("#ITEM_KEYWORD").val()){
			param["keyword"] = $("#ITEM_KEYWORD").val();
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
		"ITEM_CODE"   : 0,
		"UNIT_PRICE"   : 0,
		"ITEM_KANA" : 1,
		"LAST_UPDATE" : 0,
		"TAX_CLASS" : 0
	});

	function sorting(sort) {
		var param = eval({
			"type"   : "select_item",
			"sort"   : sort,
			"desc"   : sortBy[sort]
		});

		if($("#ITEM_KEYWORD").val()){
			param["keyword"] = $("#ITEM_KEYWORD").val();
		}

		$.post(url, {params:param}, function(d){
			$('#popup').html(d);
			//降順昇順いれかえ
			sortBy["ITEM_CODE"] = 0;
			sortBy["UNIT_PRICE"] = 0;
			sortBy["ITEM_KANA"] = 1;
			sortBy["LAST_UPDATE"] = 1;
			sortBy["TAX_CLASS"] = 1;
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
	<?php echo $html->image('/img/popup/tl_item.jpg',array('style'=>'padding-bottom:10px;'));?>
	<div class="popup_contents_box">
		<div class="popup_contents_area clearfix">
			<table width="550" cellpadding="0" cellspacing="0" border="0" class="tbl">
				<tr>
					<td colspan="6" class="w20 center">
						<?php echo ($user['AUTHORITY']!=1)?$html->link($html->image('bt_new2.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'item\');', 'class'=>'float_l lmargin20')):
						$html->link($html->image('bt_new2.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'item\');', 'class'=>'float_l lmargin40'));
						?>
						<?php echo $nowpage; ?>
						<?php echo $paging; ?>

					</td>
				</tr>
				<tr>
					<td colspan="6">
						<a href="javascript:void(0)" onclick="return sorting('ITEM_KANA');">商品名(カナ)</a> / <a href="javascript:void(0)" onclick="return sorting('ITEM_CODE');">商品コード</a> / <a href="javascript:void(0)" onclick="return sorting('UNIT_PRICE');">単価順</a>/ <a href="javascript:void(0)" onclick="return sorting('LAST_UPDATE');">更新日順</a>/ <a href="javascript:void(0)" onclick="return sorting('TAX_CLASS');">税区分</a>　　
						<?php echo $form->input('ITEM_KEYWORD', array('label' => false, 'div' => false,'error'=>false, 'class' => 'w100 p2')); ?>
						<?php echo $html->link($html->image('bt_search.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'select_item\');')); ?>
					</td>
				</tr>
				<tr class="bgti">
					<td class="w40"></td>
					<td class="w120">商品名</td>
					<td class="w80">商品コード</td>
					<td class="w80">単価</td>
					<td class="w40">税区分</td>
					<td>作成者</td>
				</tr>
				<?php
				$i=0;
				foreach($item as $key => $value){?>
				<tr<?php if(($i%2)==1) echo " class=\"bgcl\"";?>>
					<td class="w40"><?php echo $html->link($html->image('bt_insert.jpg'),'#',array('escape' => false,'onclick'=>"return insert('$key')" ));?></td>
					<td class="w120" id="ITEM<?php echo $key;?>"><?php echo $value['Item']['ITEM']; ?>
					</td>
					<td class="w80" id="ITEM_CODE<?php echo $key;?>"><?php echo $value['Item']['ITEM_CODE']; ?>
					</td>
					<td id="UNIT_PRICE<?php echo $key;?>"><?php echo number_format($value['Item']['UNIT_PRICE'])."円"; ?>
					</td>
					<td class="w40" id="TAX_CLASS<?php echo $key;?>"><?php echo $excises[$value['Item']['TAX_CLASS']]; ?>
					</td>
					<td><?php echo $value['User']['NAME']; ?></td>
					<?php echo $form->hidden("ITM_ID",array('value'=>$value['Item']['ITM_ID'],'id'=>'ITM_ID')); ?>
				</tr>
				<?php
				$i++;
				} ?>
			</table>
			<div class="save_btn">
				<?php echo $html->link($html->image('bt_cancel_s.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popup_close();',null,false)); ?>
			</div>
			<div id="popup_itemlist" style="display: none">
			<?php echo $form->hidden("sort"); ?>
			<?php echo $form->hidden("desc"); ?>
			<?php echo isset($item) ? json_encode($item) : false ; ?>
			</div>
		</div>
	</div>
</div>
</form>
