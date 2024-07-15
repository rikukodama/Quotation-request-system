<?php
	$formType = $this->name;

	$taxOption = $taxClass;
	unset($taxOption["0"]);

	$taxClassforJson;
	$index = 0;
	foreach($taxOption as $key => $name) {
		$taxClassforJson[$index] = array();
		$taxClassforJson[$index]["name"] = $name;
		$taxClassforJson[$index]["key"] = $key;
		$index++;
	}
?>
<script type="text/javascript">
form.tax_rates = <?php echo json_encode($taxRates); ?>;
form.tax_rates_option = <?php echo json_encode($taxClassforJson); ?>;
form.tax_operation_date = <?php echo json_encode($taxOperationDate)?>;
</script>
<h3>
	<div class="edit_02" align="right">
		<span class="show_bt2_on" ><?php echo $html->image('button/hide.png',array('class' =>'imgover','alt'  => 'on', 'onclick' => "return edit2_toggle('on');")); ?></span>
		<span class="show_bt2_off" style="display:none" onClick="return edit2_toggle('off');"><?php echo $html->image('button/show.png',array('class' =>'imgover','alt'  => 'off')); ?></span>
		<span class="edit_txt">&nbsp;</span>
	</div>
</h3>

<div class="contents_box">
    <?php echo $html->image('bg_contents_top.jpg'); ?>
	<div class="contents_area2">
		<span class="usernavi"><?php echo $usernavi['ITEM_LIST']; ?></span>
		<table id="detail_table" width="920px" cellpadding="0" cellspacing="0" border="0">
			<tr>
			<?php
			if($error['ITEM']['FLAG']!=0){
				for($i=0;$i<$dataline;$i++){
					if(isset($error['ITEM']['NO'][$i]))echo "<td></td><td colspan=7><font color='red'>商品名の".++$error['ITEM']['NO'][$i]."行目にエラーがあります</font></td></tr><tr>";
				}
			}
			if($error['ITEM_NO']['FLAG']!=0){
			for($i=0;$i<$dataline;$i++){
				if(isset($error['ITEM_NO']['NO'][$i])) echo "<td></td><td colspan=7><font color='red'>NOの".++$error['ITEM_NO']['NO'][$i]."行目にエラーがあります</font></td></tr><tr>";
			}
			}
			if($error['QUANTITY']['FLAG']!=0){
				for($i=0;$i<$dataline;$i++){
					if(isset($error['QUANTITY']['NO'][$i])) echo "<td></td><td colspan=7><font color='red'>数量の".++$error['QUANTITY']['NO'][$i]."行目にエラーがあります</font></td></tr><tr>";
				}
			}
			if($error['UNIT']['FLAG']!=0){
				for($i=0;$i<$dataline;$i++){
					if(isset($error['UNIT']['NO'][$i])) echo "<td></td><td colspan=7><font color='red'>単位の".++$error['UNIT']['NO'][$i]."行目にエラーがあります</font></td></tr><tr>";
				}
			}
			if($error['UNIT_PRICE']['FLAG']!=0){
				for($i=0;$i<$dataline;$i++){
					if(isset($error['UNIT_PRICE']['NO'][$i])) echo "<td></td><td colspan=7><font color='red'>単価の".++$error['UNIT_PRICE']['NO'][$i]."行目にエラーがあります</font></td></tr><tr>";
				}
			}
			?>
			</tr>
			<tr>
				<td class="w24">&nbsp;</td>
				<th class="w39">No.</th>
				<th class="w70">商品コード</th>
				<th class="w167">品目名</th>
				<th class="w74">数量</th>
				<th class="w54">単位</th>
				<th class="w94">単価</th>
				<th class="w114">金額</th>
				<th class="w260">行属性　 /　 税区分</th>
				<td class="w24">&nbsp;</td>
			</tr>
			<?php for($i = 0; $i < $dataline; $i++){ ?>
			<tr class="row_<?php echo $i; ?>" >
				<td><?php echo $html->image('bt_delete.jpg', array('alt' => '×', 'url' => '#', 'class'=>'delbtn', 'onclick' => 'return form.f_delline('.$i.');')); ?></td>
				<td><?php echo $form->inputarray($formType.'item', 'ITEM_NO',            $i, null, array('maxlength' => '2', 'class' => 'w31'.(isset($error['ITEM_NO']['NO'][$i])?' error':''))); ?></td>
				<td><?php echo $form->inputarray($formType.'item', 'ITEM_CODE',          $i, null, array('maxlength' => '8', 'class' => 'w64'.(isset($error['ITEM_CODE']['NO'][$i])?' error':''))); ?></td>
				<td>
				<?php echo $form->inputarray($formType.'item', 'ITEM',               $i, null, array('div' => false, 'maxlength' => '80', 'class' => 'w120'.(isset($error['ITEM']['NO'][$i])?' error':''))); ?>
				<span id="INSERT_ITEM_IMG<?php echo $i;?>" ><?php echo $html->image('bt_select3.jpg', array('style' => 'margin: 0px 0px 2px','alt' => '商品選択', 'url' => '#', 'onclick'=>'form.focusline = '.$i.';focusLine();return popupclass.popupajax(\'select_item\');')); ?></span>
				</td>
				<td><?php echo $form->inputarray($formType.'item', 'QUANTITY',           $i, null, array('maxlength' => '7', 'onkeyup' => "recalculation('$formType')", 'class' => 'w63'.(isset($error['QUANTITY']['NO'][$i])?' error':''))); ?></td>
				<td><?php echo $form->inputarray($formType.'item', 'UNIT',               $i, null, array('maxlength' => '8', 'class' => 'w45'.(isset($error['UNIT']['NO'][$i])?' error':''))); ?></td>
				<td><?php echo $form->inputarray($formType.'item', 'UNIT_PRICE',         $i, null, array('maxlength' => '9', 'onkeyup' => "recalculation('$formType')", 'class' => 'w73'.(isset($error['UNIT_PRICE']['NO'][$i])?' error':''))); ?></td>
				<td><?php echo $form->inputarray($formType.'item', 'AMOUNT',             $i, null, array('class' => 'w103', 'readonly'=>'readonly', 'onChange' => "recalculation('$formType')")); ?></td>
				<td>
					<?php echo $form->inputarray($formType.'item', 'LINE_ATTRIBUTE',     $i, 'select', array('div' => false, 'label' => false, 'options' => $lineAttribute, 'class' => 'w103', 'onChange' => "changeAttribute('$formType' ,$i, value);", 'style' => 'display: inline')); ?>
					<?php echo $form->inputarray($formType.'item', 'TAX_CLASS',     $i, 'select', array('div' => false, 'label' => false, 'options' => $taxClass, 'class' => 'w105', 'onChange' => "changeTaxClass('$formType' ,$i, value);", 'style' => 'display: inline', 'value' => isset($defaultExcise) ? $defaultExcise : $this->data[$i][$formType.'item']['TAX_CLASS'])); ?>
					<?php //echo $form->select('TAX_RATE', $tax_rates_options, null , array('empty' => false)); ?>
					<?php echo $form->inputarray($formType.'item', 'DISCOUNT', $i, "hidden"); ?>
					<?php echo $form->inputarray($formType.'item', 'DISCOUNT_TYPE', $i, "hidden"); ?>
				</td>
				<td>
					<?php echo $html->image('bt_up.jpg', array('class' => 'btn_up', 'alt' => '×', 'url' => 'javascript:void(0);', 'onclick' => "form.focusline=$i;form.f_up();")); ?>
					<?php echo $html->image('bt_down.jpg', array('class' => 'btn_down','alt' => '×','url' => 'javascript:void(0);', 'onclick' => "form.focusline=$i;form.f_down();")); ?>
				</td>
			</tr>
			<?php } ?>
			<tr >
				<td colspan="8" class="pl30">
					<?php echo $html->image('bt_add.jpg', array('alt' => '行を追加する', 'url' => 'javascript:void(0)', 'onclick' => 'return form.f_addline(null);')); ?>
					<?php echo $html->image('button/insert.png', array('alt' => '行を挿入する', 'url' => 'javascript:void(0)', 'onclick' => 'form.f_insert();return false;')); ?>
					<?php echo $html->image('bt_break.png', array('alt' => '改ページを挿入する', 'url' => 'javascript:void(0)', 'onclick' => 'form.f_insert(8);return false;')); ?>
					<span onclick="form.f_up()" style="background-color: #CCCCCC"><?php echo $html->image('button/up.png',array('class' =>'imgover','alt'  => 'off')); ?></span>
					<span onclick="form.f_down()" style="background-color: #CCCCCC"><?php echo $html->image('button/down.png',array('class' =>'imgover','alt'  => 'on')); ?></span>
					<br />
					<span class="usernavi">　<?php echo $usernavi['MOVE_LINE']; ?></span>
					<span class="usernavi"><?php echo $usernavi['ADD_LINE']; ?></span>
				</td>
				<td colspan="2"><?php echo $html->image('bt_clear.png', array('alt' => 'リセット', 'url' => '#', 'onclick'=>'return form.f_reset(\'null\');', 'class' => 'float_r')); ?></td>
			</tr>
		</table>
	</div>

	<div class="contents_area3">
		<div align="left">
			<br />
			<span class="w180" >&nbsp;</span>
			<span class="show_btdetail_on"  <?php if (!isset($error['DISCOUNT'])|| !$error['DISCOUNT']) {echo 'style="display:none"';} ?>>
				<?php echo $html->image('button/d_up.png',array('class' =>'imgover','alt'  => 'on', 'onclick' =>"return detail_toggle('on');")); ?> 金額詳細設定を非表示にする</span>
			<span class="show_btdetail_off"  onClick="return detail_toggle('off');" <?php if (isset($error['DISCOUNT'])&& $error['DISCOUNT']) {echo 'style="display:none"';} ?>>
                <?php echo $html->image('button/d_down.png',array('class' =>'imgover','alt'  => 'off')); ?> 金額詳細設定を表示する</span>
		</div>

        <div id="detail" <?php if (!isset($error['DISCOUNT']) && $form->error('TAX_FRACTION_TIMING') === null ) {echo 'style="display:none"';} ?>>
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr><td colspan="8"><?php echo $html->image('i_line_dot2.gif', array('class' => 'pb5')); ?></td></tr>
				<tr>
					<th class="txt_top w100">割引設定</th>
					<td colspan="3" style="width:780px;">
						<?php echo $form->text('DISCOUNT', array('maxlength' => '15', 'class' => 'w140'.(isset($error['DISCOUNT'])&& $error['DISCOUNT']>=1?' error':'').($form->error('DISCOUNT')?' error':'')));?>
						<?php echo $form->radio('DISCOUNT_TYPE', $discount, array('label' => false, 'legend' => false, 'class' => 'ml10 mr5 txt_mid')); ?>
						<br /><span class="must"><?php echo (isset($error['DISCOUNT'])&& $error['DISCOUNT']==1?'割引が長すぎます':''); ?></span>
						<span class="must"><?php echo (isset($error['DISCOUNT'])&& $error['DISCOUNT']==3?'複数の消費税区分が設定されている場合は、割引設定は利用できません。':$form->error('DISCOUNT')); ?></span>
						<br />
						<br /><span class="usernavi"><?php echo $usernavi['DISCOUNT']; ?></span>
					</td>
				</tr>
				<tr><td colspan="4" class="line"><?php echo $html->image('i_line_dot2.gif'); ?></td></tr>
				<tr>
					<th class="w100">数量小数表示</th>
					<td colspan="3" style="width:780px;">
						<?php echo $form->radio('DECIMAL_QUANTITY', $decimal, array('label' => false, 'legend' => false, 'class' => 'ml20 mr5 txt_mid')); ?>
					</td>
				</tr>
				<tr><td colspan="4" class="line"><?php echo $html->image('i_line_dot2.gif'); ?></td></tr>
				<tr>
					<th class="w100">単価小数表示</th>
					<td colspan="3" style="width:780px;">
						<?php echo $form->radio('DECIMAL_UNITPRICE', $decimal, array('label' => false, 'legend' => false, 'class' => 'ml20 mr5 txt_mid')); ?>
					</td>
				</tr>
				<tr><td colspan="4" class="line"><?php echo $html->image('i_line_dot2.gif'); ?></td></tr>
				<tr>
					<th class="w100">消費税設定</th>
					<td id="EXCISE" class="w240">
						<?php echo $form->radio('EXCISE', $excises, array('label' => false, 'legend' => false, 'class' => 'ml20 mr5 txt_mid')); ?>
					</td>
					<th class="w100">消費税端数処理</th>
					<td id="TAX_FRACTION" class="w440">
						<?php echo $form->radio('TAX_FRACTION', $fractions, array('label' => false, 'legend' => false, 'class' => 'ml20 mr5 txt_mid')); ?>
					</td>
				</tr>
				<tr><td colspan="4" class="line"><?php echo $html->image('i_line_dot2.gif'); ?></td></tr>

				<tr>
					<th class="w100">消費税端数計算</th>
					<td id="TAX_FRACTION_TIMING" class='w240<?php echo ($form->error('TAX_FRACTION_TIMING') !== null) ? ' error' : '' ?>'>
						<?php echo $form->radio('TAX_FRACTION_TIMING', $tax_fraction_timing, array('label' => false, 'legend' => false, 'class' => 'ml20 mr5 txt_mid')); ?>
						<?php if($form->error('TAX_FRACTION_TIMING') !== null) : ?>
							<?= '<span class="must">' . $form->error('TAX_FRACTION_TIMING') . '</span>'?>
						<?php endif; ?>
						<br />
						<span class="usernavi">※発行日を2023年10月01日以降に設定した場合、消費税端数計算は法律により自動的に「帳票単位」に設定されます</span>						
					</td>
					<th class="w100">基本端数処理</th>
					<td id="FRACTION" class="w440">
						<?php echo $form->radio('FRACTION', $fractions, array('label' => false, 'legend' => false, 'class' => 'ml20 mr5 txt_mid')); ?>
					</td>

				</tr>

			</table>
		</div>

		<table width="880" cellpadding="0" cellspacing="0" border="0">
			<tr><td colspan="4" class="line"><?php echo $html->image('i_line_dot2.gif'); ?></td></tr>
			<tr>
				<td class="pt10">
					<?php echo $html->image('button/i_subtotal.jpg', array('alt' => '小計')); ?>
				</td>
				<td class="pt10">
					<?php echo $form->text('SUBTOTAL', array('class' => 'w200', 'readonly'=>'readonly')); ?>
				</td>
				<td class="pt10">
					<?php echo $html->image('i_tax.jpg', array('alt' => '消費税')); ?>
				</td>
				<td class="pt10">
					<?php echo $form->text('SALES_TAX', array('class' => 'w200', 'readonly'=>'readonly')); ?>
				</td>
			</tr>
			<tr>
				<td class="pt10">
					<?php echo $html->image('i_total.jpg', array('alt' => '合計')); ?>
				</td>
				<td colspan="3" class="pt10">
					<?php echo $form->text('TOTAL', array('class' => 'w200', 'readonly'=>'readonly')); ?>
				</td>
			</tr>
		</table>

		<table width="880" cellpadding="0" cellspacing="0" border="0" id="every_tax_table">
			<tr><td colspan="8"><?php echo $html->image('i_line_dot2.gif', array('class' => 'pb5')); ?></td></tr>
			<tr id="ten_rate_tax">
				<td class="pt10">
					<?php echo $html->image('button/i_10_tax.jpg', array('alt' => '10%対象')); ?>
				</td>
				<td class="pt10">
					<?php echo $form->text('TEN_RATE_TOTAL', array('class' => 'w200', 'readonly'=>'readonly')); ?>
				</td>
				<td class="pt10">
					<?php echo $html->image('i_tax.jpg', array('alt' => '消費税')); ?>
				</td>
				<td class="pt10">
					<?php echo $form->text('TEN_RATE_TAX', array('class' => 'w200', 'readonly'=>'readonly')); ?>
				</td>
			</tr>
			<tr id="reduced_rate_tax">
				<td class="pt10">
					<?php echo $html->image('button/i_reduced_tax.jpg', array('alt' => '8%(軽減)対象')); ?>
				</td>
				<td class="pt10">
					<?php echo $form->text('REDUCED_RATE_TOTAL', array('class' => 'w200', 'readonly'=>'readonly')); ?>
				</td>
				<td class="pt10">
					<?php echo $html->image('i_tax.jpg', array('alt' => '消費税')); ?>
				</td>
				<td class="pt10">
					<?php echo $form->text('REDUCED_RATE_TAX', array('class' => 'w200', 'readonly'=>'readonly')); ?>
				</td>
			</tr>
			<tr id="eight_rate_tax">
				<td class="pt10">
					<?php echo $html->image('button/i_8_tax.jpg', array('alt' => '8%対象')); ?>
				</td>
				<td class="pt10">
					<?php echo $form->text('EIGHT_RATE_TOTAL', array('class' => 'w200', 'readonly'=>'readonly')); ?>
				</td>
				<td class="pt10">
					<?php echo $html->image('i_tax.jpg', array('alt' => '消費税')); ?>
				</td>
				<td class="pt10">
					<?php echo $form->text('EIGHT_RATE_TAX', array('class' => 'w200', 'readonly'=>'readonly')); ?>
				</td>
			</tr>
			<tr id="five_rate_tax">
				<td class="pt10">
					<?php echo $html->image('button/i_5_tax.jpg', array('alt' => '5%対象')); ?>
				</td>
				<td class="pt10">
					<?php echo $form->text('FIVE_RATE_TOTAL', array('class' => 'w200', 'readonly'=>'readonly')); ?>
				</td>
				<td class="pt10">
					<?php echo $html->image('i_tax.jpg', array('alt' => '消費税')); ?>
				</td>
				<td class="pt10">
					<?php echo $form->text('FIVE_RATE_TAX', array('class' => 'w200', 'readonly'=>'readonly')); ?>
				</td>
			</tr>
		</table>
	</div>
	<?php echo $html->image('bg_contents_bottom.jpg', array('class' => 'block')); ?>
</div>
