<?php
$formType = $this->name;
?>
<h3><div class="edit_04"><span class="edit_txt">&nbsp;</span></div></h3>
<div class="contents_box mb20">
	<?php echo $html->image('bg_contents_top.jpg'); ?>
	<div class="contents_area">
		<table width="880" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<th class="w100">ステータス</th>
				<td class="w770"><?php echo $status[$param[$formType]['STATUS']]; ?></td>
			</tr>
			<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
			<tr>
				<th class="w100">メモ</th>
				<td class="w770"><?php echo $customHtml->ht2br($param[$formType]['MEMO'],$formType,'MEMO'); ?></td>
			</tr>
		</table>
	</div>
	<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
</div>
