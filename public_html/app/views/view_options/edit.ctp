<script type="text/javascript">
<!--
function set_default(_option) {
	if(_option == 'logo') {
		$('#ViewOptionLogoDefault').val('i_logo.jpg');
		$('#default_logo').attr('style','');
		$('#logo_image').attr('style','display: none');
		$('#logo_name').html('i_logo.jpg');
		$('#ViewOptionLogo').val('');
		$('#logo_reset').attr('style','display: none');

	}
	if(_option == 'title') {
		$('#ViewOptionTitle').val('抹茶請求書');
	}
	if(_option == 'footer') {
		$('#ViewOptionFooter').val('抹茶請求書');
	}
	return false;

}

function reset_default(){
	$('#ViewOptionLogoDefault').val(0);

}
// -->
</script>
<?php	//完了メッセージ
	echo $session->flash();
?>
<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('/img/company/i_guide.jpg'); ?>
		<p>こちらのページはデザイン設定編集の画面です。<?php if($user["AUTHORITY"]==0){ ?><br />必要な情報を入力の上「保存する」ボタンを押下するとデザイン設定を変更できます。<?php } ?></p>
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
			<?php echo $form->create("ViewOption", array("type" => "file", "controller" => "view_options", "class" => "ViewOption")); ?>
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<?php
					for($i = 0; $i < count($options); $i++){
						$option_name = $options[$i]['ViewOption']['OPTION_NAME'];
						$option_name_jp = $options[$i]['ViewOption']['OPTION_NAME_JP'];
						$option_value = $options[$i]['ViewOption']['OPTION_VALUE'];
				?>
				<tr >
					<th width="330px"><?php echo $option_name_jp ?></th>
					<td width="550px">
						<?php
							if($option_name==='logo'){
								if(!empty($option_value)){
									echo $html->image('cms/i_logo.jpg',array("height"=>"40" ,'id' => 'default_logo','style' => 'display: none'));
									echo $html->image('cms/'.$option_value,array("height"=>"40" ,'id' => 'logo_image'));
									echo "　<span id =\"logo_name\">".$option_value."</span><br /><br />";
								}
								echo $form->file("$option_name",array('onClick' => 'reset_default()')).'<br />';
								if($option_value !== 'i_logo.jpg') echo $html->link($html->image('bt_s_reset.jpg'),'#',array('id' => 'logo_reset','escape' => false, 'onclick'=>'return set_default(\''.$option_name.'\');'));
								echo $form->hidden('logo_default' , array('value' => 0));
								echo '<br /><span class="usernavi">'.$usernavi['LOGO'].'</span>';
							}
							else{
								echo $form->text($option_name, array('value' => $option_value,'class' => 'w600'.($form->error($option_name)?' error':''),'maxlength'=>100));
								echo $html->link($html->image('bt_s_reset.jpg'),'#',array('escape' => false, 'onclick'=>'return set_default(\''.$option_name.'\');'));
							}
						?>
						<br />
						<span class="must">
						<?php
								if($option_name==='logo' &&isset($logo_error)){
									switch($logo_error){
										case 0:
											break;
										case 1:
											echo '画像はjpegかpngのみです<br />';
											break;
										case 2:
											echo '画像サイズは1MBまでです<br />';
											break;
										case 3:
											echo '画像はjpegかpngのみです<br />';
											echo '画像サイズは1MBまでです<br />';
											break;
										case 4:
											echo '正しい画像形式ではありません';
											break;
									}
								}else {
									if($form->error($option_name)){echo $form->error($option_name);}
								}
						?>
						</span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<?php }?>
			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>
	<div class="edit_btn">
		<?php echo $customHtml->hiddenToken(); ?>
		<?php
		    echo $form->submit('bt_save.jpg', array('div' => false , 'name' => 'submit', 'alt' => '保存する', 'class' => 'imgover'));
		    echo $form->submit('bt_cancel.jpg', array('div' => false , 'name' => 'cancel', 'alt' => 'キャンセル', 'class' => 'imgover'));
		?>
	</div>
</div>
