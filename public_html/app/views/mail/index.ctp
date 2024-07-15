<script><!--
try{
	window.addEventListener("load",initTableRollovers('index_table'),false);
 }catch(e){
 	window.attachEvent("onload",initTableRollovers('index_table'));
}
--></script>

<script>
$(function() {
	setBeforeSubmit('<?php echo $this->name.ucfirst($this->action).'Form'; ?>');
});
</script>


<?php echo $session->flash(); ?>

<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
	<h3><div class="search"><span class="edit_txt">&nbsp;</span></div></h3>
	<?php echo $form->create("Mail", array('type' => 'get', 'action' => 'index')); ?>
	<div class="search_box">
		<div class="search_area">

			<table width="600" cellpadding="0" cellspacing="0" border="0">
			<tr><th>件名</th><td><?php echo $form->text('SUBJECT',array('class' => 'w350')); ?></td></tr>
			<tr><th>送付先</th><td><?php echo $form->text('CUSTOMER_CHARGE',array('class' => 'w350')); ?></td></tr>
			<tr><th>ステータス</th><td><?php echo $form->input('STATUS', array('type' => 'select','multiple' => 'checkbox','options' => $mailstatus,'label' => false, 'div' => false)); ?></td></tr>
			<tr><th>種別</th><td><?php echo $form->input('TYPE', array('type' => 'select','multiple' => 'checkbox','options' => $type,'label' => false, 'div' => false)); ?></td></tr>
			</table>

			<div class="search_btn">
				<?php echo $html->link($html->image("bt_search.jpg"), '#',array('escape' => false, 'onclick' => "$('#".$this->name.ucfirst($this->action)."Form').submit();"),null,false); ?>
			</div>
		</div>
		<?php echo $html->image('/img/document/bg_search_bottom.jpg',array('class'=>'block')); ?>
	</div>

	<?php echo $form->end(); ?>

	<h3><div class="edit_02_mail"><span class="edit_txt">&nbsp;</span></div></h3>

	<div class="contents_box mb40">
		<div id='pagination'>
		<?php echo $paginator->data['Mail']["count"]; ?>
		</div>

		<div id='pagination'>
		<?php echo $paginator->prev('<< '.__('前へ', true),array(),null,array('class'=>'disabled', 'tag' => 'span')); ?>
		 |
		<?php echo $paginator->numbers().' | '.$paginator->next(__('次へ', true).' >>', array(), null, array('tag' => 'span', 'class' => 'disabled')); ?>
	</div>

	<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="list_area">

			<?php if(is_array($list)): ?>

			<table width="900" cellpadding="0" cellspacing="0" border="0" id="index_table">
				<thead>
				<tr>
					<th class="w50"><?php echo $customHtml->sortLink('No.', 'Mail.TML_ID'); ?></th>
					<th class="w100"><?php echo $customHtml->sortLink('送信先', 'Mail.RCV_NAME'); ?></th>
					<th class="w100"><?php echo $customHtml->sortLink('種別', 'Mail.TYPE'); ?></th>
					<th class="w200"><?php echo $customHtml->sortLink('件名', 'Mail.SUBJECT'); ?></th>
					<th class="w200"><?php echo $customHtml->sortLink('顧客名', 'Mail.CUSTOMER'); ?></th>
					<th class="w100"><?php echo $customHtml->sortLink('ステータス', 'Mail.STATUS'); ?></th>
					<th class="w100"><?php echo $customHtml->sortLink('送信日', 'Mail.SND_DATE'); ?></th>
					<th class="w100"><?php echo $customHtml->sortLink('受信日', 'Mail.RCV_DATE'); ?></th>
				</tr>
				</thead>

				<tbody>
				<?php foreach($list as $key => $val): ?>
				<tr>
					<td><?php echo $val['Mail']['TML_ID']; ?></td>
					<td><?php echo $val['Mail']['RCV_NAME']."(".$val['Mail']['RECEIVER'].")"; ?></td>
					<td><?php echo $type[$val['Mail']['TYPE']]; ?></td>
					<td><?php echo $val['Mail']['SUBJECT']; ?></td>
					<td><?php echo $val['Mail']['CUSTOMER']; ?></td>
					<td><?php echo $val['Mail']['STATUS']!=0?$html->link($mailstatus[$val['Mail']['STATUS']], array('controller' => 'mails', 'action' => 'check/'.$val['Mail']['TML_ID'])):$mailstatus[$val['Mail']['STATUS']]; ?></td>
					<td><?php echo $val['Mail']['SND_DATE']; ?></td>
					<td><?php echo ($val['Mail']['RCV_DATE'])?$val['Mail']['RCV_DATE']:'&nbsp'; ?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<?php endif; ?>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>
	</div>
</div>