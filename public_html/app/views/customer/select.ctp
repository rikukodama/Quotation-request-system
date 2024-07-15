<script><!--
try{
	window.addEventListener("load",initTableRollovers('index_table'),false);
 }catch(e){
 	window.attachEvent("onload",initTableRollovers('index_table'));
}
--></script>
<?php	//完了メッセージ
	echo $session->flash();
?>
<?php
$paginator->options($options);
?>
<div id="contents">
	<?php echo $this->Form->create("Customer", array('type' => 'get', 'action' => 'select', 'novalidate'=> true)); ?>
		<div class="arrow_under"><?php echo $this->Html->image('i_arrow_under.jpg'); ?></div>

		<h3>
			<div class="search">
				<span class="edit_txt">&nbsp;</span>
			</div>
		</h3>
		<div class="search_box">
			<div class="search_area">
	
				<table width="600" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<th>顧客名</th>
						<td><?php echo $this->Form->text('NAME',array('class' => 'w350')); ?></td>
					</tr>
				</table>
	
				<div class="search_btn">
				<?php echo $html->link($html->image("bt_search.jpg"), '#',array('escape' => false, 'onclick' => "$('#".$this->name.ucfirst($this->action)."Form').submit();"),null,false); ?>
				</div>
			</div>
			<?php echo $this->Html->image('/img/document/bg_search_bottom.jpg',array('class'=>'block')); ?>
		</div>
	<?php echo $this->Form->end(); ?>

	<h3><div class="edit_02_customer"><span class="edit_txt">&nbsp;</span></div></h3>

	<div class="contents_box mb40">
		<div id='pagination'>
		<?php echo $paginator->data['Customer']["count"]; ?>
		</div>
		<div id='pagination'>
		<?php echo $paginator->prev('<< '.__('前へ', true),
			array(),
			null,
			array('class'=>'disabled', 'tag' => 'span')
		); ?>
		 |
		<?php echo $paginator->numbers().
		' | '.
		$paginator->next(__('次へ', true).' >>', array(), null, array('tag' => 'span', 'class' => 'disabled'));
		?>
	</div>
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="list_area">
			<?php if(is_array($list)){ ?>
				<table width="900" cellpadding="0" cellspacing="0" border="0"style="break-word:break-all;" id="index_table">
					<thead>
					<tr>
						<th class="w50"><?php echo $customHtml->sortLink('No.', 'Customer.CST_ID'); ?></th>
						<th class="w250"><?php echo $customHtml->sortLink('顧客名', 'Customer.NAME_KANA'); ?></th>
						<th class="w100"><?php echo $customHtml->sortLink('電話番号', 'Customer.PHONE_NO1'); ?></th>
						<th class="w100"><?php echo $customHtml->sortLink('担当者', 'Customer.CHR_ID'); ?></th>
						<th class="w400">帳票</th>
					</tr>
					</thead>
					
					<tbody>
					<?php foreach($list as $key => $val){ ?>
					<tr>
						<td><?php echo $val['Customer']['CST_ID']; ?></td>
						<td><?php echo $authcheck[$val['Customer']['CST_ID']]==1?$html->link($val['Customer']['NAME'], array('controller' => 'customers', 'action' => 'check/'.$val['Customer']['CST_ID'])):$customHtml->ht2br($val['Customer']['NAME']); ?></td>
						<td><?php if(!(empty($val['Customer']['PHONE_NO1']) && empty($val['Customer']['PHONE_NO2']) && empty($val['Customer']['PHONE_NO3']))) { echo $val['Customer']['PHONE_NO1']."-".$val['Customer']['PHONE_NO2']."-".$val['Customer']['PHONE_NO3']; }else {echo "&nbsp;";} ?></td>
						<td><?php echo $val['Customer']['CHR_ID']?$customHtml->ht2br($charges[$val['Customer']['CHR_ID']],'Charge','CHARGE_NAME'):'&nbsp;'; ?></td>
						<td>
						<?php
							echo $html->link('見積書', array('controller' => 'quotes', 'action' => 'index', 'customer' => $val['Customer']['CST_ID']));
							echo '('.$inv_num[$val['Customer']['CST_ID']]['Quote'].'件)';
							echo ' / ';
							echo $html->link('請求書', array('controller' => 'bills', 'action' => 'index', 'customer' => $val['Customer']['CST_ID']));
							echo '('.$inv_num[$val['Customer']['CST_ID']]['Bill'].'件)';
							echo ' / ';
							echo $html->link('納品書', array('controller' => 'deliveries', 'action' => 'index', 'customer' => $val['Customer']['CST_ID']));
							echo '('.$inv_num[$val['Customer']['CST_ID']]['Delivery'].'件)';
							?>
						</td>
					</tr>
					<?php } ?>
					</tbody>
				</table>
			<?php } ?>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>
	</div>
</div>