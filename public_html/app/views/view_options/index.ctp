<?php	//完了メッセージ
	echo $session->flash();
?>
<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('/img/company/i_guide.jpg'); ?>
		<p>こちらのページはデザイン設定確認の画面です。<?php if($user["AUTHORITY"]==0){ ?><br />「編集する」ボタンを押下するとデザイン設定を変更できます。<?php } ?></p>
	</div>
</div>
<br class="clear" />
<!-- header_End -->
<!-- contents_Start -->
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
	<h3><div class="edit_02_view_option"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<table width="880" cellpadding="0" cellspacing="0" border="0">

				<?php
					for($i = 0; $i < count($options); $i++){
						$option_name = $options[$i]['ViewOption']['OPTION_NAME'];
						$option_name_jp = $options[$i]['ViewOption']['OPTION_NAME_JP'];
						$option_value = $options[$i]['ViewOption']['OPTION_VALUE'];
				?>
				<tr>
					<th width="130px"><?php echo $option_name_jp ?></th>
					<td width="750px">
						<?php
							if($option_name==='logo'){
								if(!empty($option_value)){
									echo $html->image('cms/'.$option_value,array("height"=>"40"));
									echo "　".$option_value."<br /><br />";
								}
							}
							else{
								echo $customHtml->ht2br($option_value);
							}
						?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<?php }?>
			</table>
			</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
		</div>

	</div>
	<div class="edit_btn">
		<?php
		if($user['AUTHORITY']==0){
			echo $html->link($html->image('bt_edit.jpg',array('class'=>'imgover','alt'=>'編集する')), array('controller' => 'view_options', 'action' => 'edit/'),array('escape' => false));
		}?>
	</div>
<?php echo $form->hidden("CMP_ID", array("value" => "1")); ?>