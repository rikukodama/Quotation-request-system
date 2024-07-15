<div id="contents">
	<div class="contents_box mb20">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
		<?php
			echo $form->create('Install', array('class'=>'Install','url' => array('plugin' => 'install', 'controller' => 'install', 'action' => 'mail')));
    	?>
<script type="text/javascript">
<!--
$(document).ready(function($){
	if($('select[name="data[Install][STATUS]"]').val()==0){
		$('div.Smtpuse').hide();
	}
	if($('input[name="data[Install][PROTOCOL]"]:checked').val()==0){
		$('input[name="data[Install][USER]"]').attr('readonly','readonly');
		$('input[name="data[Install][PASS]"]').attr('readonly','readonly');
	}

	$('select[name="data[Install][STATUS]"]').change(function(){
		if($('select[name="data[Install][STATUS]"]').val()==1){
			$('div.Smtpuse').slideDown();
			$('input[name="data[Install][PROTOCOL]"]').val(['0']);
			$('input[name="data[Install][SECURITY]"]').val(['0']);
		}
		if($('select[name="data[Install][STATUS]"]').val()==0){
			$('div.Smtpuse').slideUp();
			$('input[name="data[Install][PROTOCOL]"]').attr("checked",false);
			$('input[name="data[Install][SECURITY]"]').attr("checked",false);
			$('input[name="data[Install][HOST]"]').val(null);
			$('input[name="data[Install][PORT]"]').val(null);
			$('input[name="data[Install][USER]"]').val(null);
			$('input[name="data[Install][PASS]"]').val(null);
		}

	});

	$('input[name="data[Install][PROTOCOL]"]').change(function(){
		if($(this).val()==0){
			$('input[name="data[Install][USER]"]').attr('readonly','readonly');
			$('input[name="data[Install][PASS]"]').attr('readonly','readonly');
			$('input[name="data[Install][USER]"]').val(null);
			$('input[name="data[Install][PASS]"]').val(null);
		}
		if($(this).val()==1){
			$('input[name="data[Install][USER]"]').removeAttr('readonly');
			$('input[name="data[Install][PASS]"]').removeAttr('readonly');
		}
	});
});

//-->
</script>
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th style="width:160px;"><p class="float_l"><span class ="float_l">送信者名</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:720px;">
						<?php echo $form->text('FROM_NAME', array('value'=>$FROM_NAME,'class' => 'w300'.($form->error('FROM_NAME')?' error':''),'maxlength'=>30)); ?>
						<br /><span class="usernavi"><?php echo $usernavi['MAIL_FROM_NAME']; ?></span>
						<br /><span class="must"><?php echo $form->error('FROM_NAME') ; ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:160px;"><p class="float_l"><span class ="float_l">送信者アドレス</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class' => 'pl10 mr10 float_r')); ?></th>
					<td>
						<?php echo $form->text('FROM', array('class' => 'w300'.($form->error('FROM')?' error':''),'maxlength'=>30)); ?>
						<br /><span class="usernavi"><?php echo $usernavi['MAIL_FROM']; ?></span>
						<br /><span class="must"><?php echo $form->error('FROM') ; ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:160px;" ><span class ="float_l">SMTPの使用</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:720px;">
						<?php echo $form->input('STATUS', array('label' => false, 'options' => $status));?>
						<br /><span class="usernavi"><?php echo $usernavi['MAIL_SMTP']; ?></span>
					</td>
				</tr>
			</table>
			<div class='Smtpuse'>
			<table >
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" ><span class ="float_l">プロトコル</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:710px;">
						<?php echo $form->input('PROTOCOL', $protocol); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:170px;" ><span class ="float_l">SMTPセキュリティ</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:710px;">
						<?php echo $form->input('SECURITY', $security); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:160px"><span class ="float_l">SMTPサーバ</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:710px;">
						<?php echo $form->text('HOST', array('class' => 'w300'.($form->error('HOST')?' error':''),'maxlength'=>30)); ?>
						<br /><span class="must"><?php echo $form->error('HOST') ; ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:160px"><span class ="float_l">ポート番号</span><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 mr10 float_r')); ?></th>
					<td style="width:710px;">
						<?php echo $form->text('PORT', array('class' => 'w300'.($form->error('PORT')?' error':''),'maxlength'=>30)); ?>
						<br /><span class="must"><?php echo $form->error('PORT') ; ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>SMTPユーザ</th>
					<td style="width:750px;">
						<?php echo $form->text('USER', array('class' => 'w300'.($form->error('USER')?' error':''),'maxlength'=>30)); ?>
						<br /><span class="usernavi"><?php echo $usernavi['MAIL_SMTP_USER']; ?></span>
						<br /><span class="must"><?php echo $form->error('USER') ; ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>SMPTパスワード</th>
					<td style="width:750px;">
						<?php echo $form->text('PASS', array('class' => 'w300'.($form->error('PASS')?' error':''),'maxlength'=>30)); ?>
						<br /><span class="usernavi"><?php echo $usernavi['MAIL_SMTP_PW']; ?></span>
						<br /><span class="must"><?php echo $form->error('PASS') ; ?></span>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
			</table>
		</div>
		<?php echo $form->end('登録');?>
	</div>
</div>