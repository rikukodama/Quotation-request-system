<?php
$formType = $this->name;
$formID = '';
$formController = '';
$mailAction = '';

switch($this->name) {
	case 'Quote':
		$formID = 'MQT_ID';
		$formController = 'quotes';
		$mailAction = 'quote';
		break;
	case 'Bill':
		$formID = 'MBL_ID';
		$formController = 'bills';
		$mailAction = 'bill';
		break;
	case 'Delivery':
		$formID = 'MDV_ID';
		$formController = 'deliveries';
		$mailAction = 'delivery';
		break;
}
?>
<h3><div class="edit_02"><span class="edit_txt">&nbsp;</span></div></h3>
<div class="contents_box">
	<?php echo $html->image('bg_contents_top.jpg'); ?>
	<div class="check_area">
		<table width="880" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<th class="w50">No.</th>
				<th class="w125">商品コード</th>
				<th class="w235">品目名</th>
				<th class="w85">数量</th>
				<th class="w75">単位</th>
				<th class="w125">単価</th>
				<th class="w185">金額</th>
			</tr>
<?php
$taxClass = array('','内 ','','非 ');
for($i = 0; $i < $dataline; $i++) {
	$tmpTax = $taxClass[$param[$i][$formType.'item']['TAX_CLASS'] % 10];

	if($i%2 == 1){
		$td_color = ' class="td_gray"';
	}else{
		$td_color = '';
	}
	// 軽減税率の対象用のマーク
  $reduced_tax_mark = '';
	if($param[$i][$formType.'item']['TAX_CLASS'] == 91 ||
		$param[$i][$formType.'item']['TAX_CLASS'] == 92){
			$reduced_tax_mark = '(※)';
	}
	echo"	<tr>
			<td$td_color>".($param[$i][$formType.'item']['ITEM_NO']||$param[$i][$formType.'item']['ITEM_NO']=='0'    ? $customHtml->ht2br($param[$i][$formType.'item']['ITEM_NO'],   $formType.'item', 'ITEM_NO')    : '&nbsp;')."</td>
			<td$td_color>".(isset($param[$i][$formType.'item']['ITEM_CODE'])  ? $customHtml->ht2br($param[$i][$formType.'item']['ITEM_CODE'], $formType.'item', 'ITEM_CODE')  : '&nbsp;')."</td>
			<td$td_color>".(isset($param[$i][$formType.'item']['ITEM'])       ? $customHtml->ht2br($param[$i][$formType.'item']['ITEM'],      $formType.'item', 'ITEM'). $reduced_tax_mark    : '&nbsp;')."</td>
			<td$td_color>".(isset($param[$i][$formType.'item']['QUANTITY'])   ? $customHtml->ht2br($param[$i][$formType.'item']['QUANTITY'],  $formType.'item', 'QUANTITY', $param[$formType]['DECIMAL_QUANTITY'] )   : '&nbsp;')."</td>
			<td$td_color>".(isset($param[$i][$formType.'item']['UNIT'])       ? $customHtml->ht2br($param[$i][$formType.'item']['UNIT'],      $formType.'item', 'UNIT')       : '&nbsp;')."</td>
			<td$td_color>".(isset($param[$i][$formType.'item']['UNIT_PRICE']) ? $customHtml->ht2br($param[$i][$formType.'item']['UNIT_PRICE'],$formType.'item', 'UNIT_PRICE', $param[$formType]['DECIMAL_UNITPRICE']) : '&nbsp;')."</td>
			<td$td_color>".$tmpTax.(isset($param[$i][$formType.'item']['AMOUNT'])     ? $customHtml->ht2br($param[$i][$formType.'item']['AMOUNT'],    $formType.'item', 'AMOUNT')     : '&nbsp;')."</td>
			</tr>";
			} ?>
		</table>
	</div>
	<div class="contents_area3">
		<table width="880" cellpadding="0" cellspacing="0" border="0">
			<?php
			if($param[$formType]['REDUCED_RATE_TOTAL']) {
				echo '<tr><td colspan="8">「※」は軽減税率対象であることを示します。</td></tr>';
			}?>
			<tr><td colspan="8"><?php echo $html->image('i_line_dot2.gif',array('class'=>'pb5')); ?></td></tr>
			<tr>
				<th>割引設定</th>
				<td colspan="3"><?php
				if($param[$formType]['DISCOUNT_TYPE'] == 1) { echo $param[$formType]['DISCOUNT'] ? number_format($param[$formType]['DISCOUNT']).'円引き' :'　' ; }
				if($param[$formType]['DISCOUNT_TYPE'] == 0) { echo $param[$formType]['DISCOUNT'] ? number_format($param[$formType]['DISCOUNT']).'％引き' :'　' ; }
    		?>
			</tr>
			<tr><td colspan="4" class="line"><?php echo $html->image('i_line_dot2.gif'); ?></td></tr>
			<tr>
				<th class="w100">数量小数表示</th>
				<td class="w240"><?php echo $decimals[$param[$formType]['DECIMAL_QUANTITY']]; ?></td>
				<th class="w100">単価小数表示</th>
				<td class="w440"><?php echo $decimals[$param[$formType]['DECIMAL_UNITPRICE']]; ?></td>
			</tr>
			<tr><td colspan="4" class="line"><?php echo $html->image('i_line_dot2.gif'); ?></td></tr>
			<tr>
				<th class="w100">消費税設定</th>
				<td class="w240"><?php echo $excises[$param[$formType]['EXCISE']]; ?></td>
				<th class="w100">消費税端数処理</th>
				<td class="w440"><?php echo $fractions[$param[$formType]['TAX_FRACTION']]; ?></td>
			</tr>
			<tr><td colspan="4" class="line"><?php echo $html->image('i_line_dot2.gif'); ?></td></tr>
			<tr>
				<th class="w100">消費税端数計算</th>
				<td class="w240"><?php echo $tax_fraction_timing[$param[$formType]['TAX_FRACTION_TIMING']]; ?></td>
				<th class="w100">基本端数処理</th>
				<td class="w440"><?php echo $fractions[$param[$formType]['FRACTION']]; ?></td>
			</tr>

			<tr><td colspan="4" class="line"><?php echo $html->image('i_line_dot2.gif'); ?></td></tr>
			<tr>
				<td class="pt10">
					<?php echo $html->image('i_subtotal.jpg',array('alt' => '小計')); ?>
				</td>
				<td class="pt10"><?php echo $customHtml->ht2br($param[$formType]['SUBTOTAL'],$formType,'SUBTOTAL'); ?>円</td>
				<td class="pt10">
					<?php echo $html->image('i_tax.jpg',array('alt' => '消費税')); ?>
				</td>
				<td class="pt10"><?php	echo $customHtml->ht2br($param[$formType]['SALES_TAX'],$formType,'SALES_TAX'); ?>円</td>
			</tr>
			<tr>
				<td class="pt10">
					<?php echo $html->image('i_total.jpg',array('alt' => '合計')); ?>
				</td>
				<td colspan="3" class="pt10"><?php echo $customHtml->ht2br($param[$formType]['TOTAL'],$formType,'TOTAL'); ?>円</td>
			</tr>
			<?php if($param[$formType]['tax_kind_count'] >= 1){ ?>
				<tr><td colspan="8"><?php echo $html->image('i_line_dot2.gif',array('class'=>'pb5')); ?></td></tr>

				<?php if($param[$formType]['TEN_RATE_TOTAL']){ ?>
				<tr>
					<td class="pt10">
						<?php echo $html->image('button/i_10_tax.jpg', array('alt' => '10%対象')); ?>
					</td>
					<td class="pt10"><?php echo $customHtml->ht2br($param[$formType]['TEN_RATE_TOTAL'],$formType,'TOTAL'); ?>円</td>
					<td class="pt10">
						<?php echo $html->image('i_tax.jpg',array('alt' => '消費税')); ?>
					</td>
					<td class="pt10"><?php echo $customHtml->ht2br($param[$formType]['TEN_RATE_TAX'],$formType,'SALES_TAX'); ?>円</td>
				</tr>
				<?php } ?>

				<?php if($param[$formType]['REDUCED_RATE_TOTAL']){ ?>
				<tr>
					<td class="pt10">
						<?php echo $html->image('button/i_reduced_tax.jpg', array('alt' => '8%(軽減)対象')); ?>
					</td>
					<td class="pt10"><?php echo $customHtml->ht2br($param[$formType]['REDUCED_RATE_TOTAL'],$formType,'TOTAL'); ?>円</td>
					<td class="pt10">
						<?php echo $html->image('i_tax.jpg',array('alt' => '消費税')); ?>
					</td>
					<td class="pt10"><?php echo $customHtml->ht2br($param[$formType]['REDUCED_RATE_TAX'],$formType,'SALES_TAX'); ?>円</td>
				</tr>
				<?php } ?>

				<?php if($param[$formType]['EIGHT_RATE_TOTAL']){ ?>
				<tr>
					<td class="pt10">
						<?php echo $html->image('button/i_8_tax.jpg', array('alt' => '8%対象')); ?>
					</td>
					<td class="pt10"><?php echo $customHtml->ht2br($param[$formType]['EIGHT_RATE_TOTAL'],$formType,'TOTAL'); ?>円</td>
					<td class="pt10">
						<?php echo $html->image('i_tax.jpg',array('alt' => '消費税')); ?>
					</td>
					<td class="pt10"><?php echo $customHtml->ht2br($param[$formType]['EIGHT_RATE_TAX'],$formType,'SALES_TAX'); ?>円</td>
				</tr>
				<?php } ?>

				<?php if($param[$formType]['FIVE_RATE_TOTAL']){ ?>
				<tr>
					<td class="pt10">
						<?php echo $html->image('button/i_5_tax.jpg', array('alt' => '5%対象')); ?>
					</td>
					<td class="pt10"><?php echo $customHtml->ht2br($param[$formType]['FIVE_RATE_TOTAL'],$formType,'TOTAL'); ?>円</td>
					<td class="pt10">
						<?php echo $html->image('i_tax.jpg',array('alt' => '消費税')); ?>
					</td>
					<td class="pt10"><?php echo $customHtml->ht2br($param[$formType]['FIVE_RATE_TAX'],$formType,'SALES_TAX'); ?>円</td>
				</tr>
				<?php } ?>
			<?php } ?>
		</table>
	</div>
	<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
</div>