<?php echo $html->css("popup")."\n"; ?>

<script type="text/javascript">
<!--
	function insert(no,id){
		$('#SETCUSTOMERCHARGE').children('input[type=text]').val($('#name'+no).html());
		$('#SETCUSTOMERCHARGE').children('input[type=hidden]').val(id);
		$('#SETCCUNIT').children('input[type=text]').val($('#unit'+no).html());
		popupclass.popup_close();
		return false;
	}

	var url="<?php echo $html->url('/ajax/popup'); ?>";

	function paging(page) {
		var id = $('#SETCUSTOMER').children('input[type=hidden]').val();

		var param = eval({
			"type"   : "customer_charge",
			"page"   : page,
			"id"     : id
		});

		//キーワードがある場合
		if($("#CHRC_KEYWORD").val()){
			param["keyword"] = $("#CHRC_KEYWORD").val();
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
		"CUSTOMER_NAME"   : 0,
		"LAST_UPDATE" : 0,
		"CHARGE_NAME_KANA"   : 0
	});

	function sorting(sort) {
		var id = $('#SETCUSTOMER').children('input[type=hidden]').val();

		var param = eval({
			"type"   : "customer_charge",
			"sort"   : sort,
			"desc"   : sortBy[sort],
			"id"     : id
		});

		if($("#CHRC_KEYWORD").val()){
			param["keyword"] = $("#CHRC_KEYWORD").val();
		}

		$.post(url, {params:param}, function(d){
			$('#popup').html(d);
			//降順昇順いれかえ
			sortBy["CUSTOMER_NAME"] = 0;
			sortBy["LAST_UPDATE"] = 1;
			sortBy["CHARGE_NAME_KANA"] = 0;

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
	<?php echo $html->image('/img/popup/tl_customercharge.jpg',array('style'=>'padding-bottom:10px;')); ?>
	<div class="popup_contents_box">
		<div class="popup_contents_area clearfix">
			<table width="500" cellpadding="0" cellspacing="0" border="0" class="tbl">
				<tr>
					<td colspan="5" class="w20 center">
						<?php echo ($user['AUTHORITY']!=1)?$html->link($html->image('bt_new2.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'add_customer_charge\' ,' .$cst_id. ');', 'class'=>'float_l lmargin20')):
						$html->link($html->image('bt_new2.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'add_customer_charge\');', 'class'=>'float_l lmargin40'));
						?>
						<?php echo $nowpage; ?>
						<?php echo $paging; ?>
					</td>
				</tr>
				<tr>
					<td colspan="5">
						<a href="javascript:void(0)" onclick="return sorting('CHARGE_NAME_KANA');">担当者名(カナ)</a> / <a href="javascript:void(0)" onclick="return sorting('CUSTOMER_NAME');">顧客名(カナ)</a> / <a href="javascript:void(0)" onclick="return sorting('LAST_UPDATE');">更新日順</a>　　
						<?php echo $form->input('CHRC_KEYWORD', array('label' => false, 'div' => false,'error'=>false, 'class' => 'w120 p2')); ?>
						<?php echo $html->link($html->image('bt_search.jpg'),'#',array('escape' => false,'onclick'=>'return popupclass.popupajax(\'customer_charge\');')); ?>
					</td>
				</tr>
				<tr class="bgti">
					<td class="w20"></td>
					<td class="w100">名前</td>
					<td class="w100">顧客名</td>
					<td class="w60">部署</td>
					<td>作成者</td>
				</tr>
				<?php
				$i=0;
				foreach($customer_charge as $key => $value){?>
				<tr<?php if(($i%2)==1) echo " class=\"bgcl\"";?>>
					<td class="w20"><?php echo $html->link($html->image('bt_insert.jpg'),'#',array('escape' => false,'onclick'=>'return insert('.$key.','.$value['CustomerCharge']['CHRC_ID'].');',null,false)); ?></td>
					<td class="w100" id="name<?php echo $key;?>"><?php echo $value['CustomerCharge']['CHARGE_NAME']; ?>
					</td>
					<td class="w100"><?php echo $value['Customer']['NAME']; ?>
					</td>
					<td  class="w60" id="unit<?php echo $key;?>"><?php echo $value['CustomerCharge']['UNIT']; ?>	</td>
					<?php echo $form->hidden("CHRC_ID",array('value'=>$value['CustomerCharge']['CHRC_ID'],'id'=>'CHRC_ID')); ?>
					<td ><?php echo $value['User']['NAME']; ?>
					</td>
				</tr>
				<?php
				$i++;
				} ?>
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
