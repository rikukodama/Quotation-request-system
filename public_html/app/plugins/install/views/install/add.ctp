<div id="contents">
	<div class="contents_box mb20">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
		<?php
			echo $form->create('Install', array('class'=>'Install','url' => array('plugin' => 'install', 'controller' => 'install', 'action' => 'add')));
    	?>

			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th style="width:180px;" <?php echo $form->error("NAME")?' class="txt_top"':''; ?>>自社名<?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10')); ?></th>
					<td style="width:700px;">
					<?php echo $form->text('NAME', array('class' => 'w300'.($form->error('NAME')?' error':''))); ?>
					<br /><span class="usernavi"><?php echo $usernavi['CMP_NAME']; ?></span>
					<br /><span class="must"><?php echo $form->error('NAME'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error("REPRESENTATIVE")?' class="txt_top"':''; ?> style="width:180px;">代表者名</th>
					<td style="width:700px;">
					<?php echo $form->text('REPRESENTATIVE', array('class' => 'w300'.($form->error('REPRESENTATIVE')?' error':''))); ?>
					<br /><span class="usernavi"><?php echo $usernavi['REPRESENTATIVE']; ?></span>
					<br /><span class="must"><?php echo $form->error('REPRESENTATIVE'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:180px;" <?php echo ($form->error("POSTCODE1")||$form->error("POSTCODE2"))?' class="txt_top"':''; ?>>郵便番号
					<?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10')); ?></th>
							<td style="width:700px;">
							<?php
								echo $form->text("POSTCODE1", array("class" => "w60".($form->error("POSTCODE1")||$form->error("POSTCODE2")?' error':'')));
							?>
			 				<span class="pl5 pr5">-</span>
			 				<?php
			 					echo $form->text("POSTCODE2", array("class" => "w60".($form->error("POSTCODE1")||$form->error("POSTCODE2")?' error':'')));
			 				?>
							<div><?php echo $ajax->div("target"); ?><?php echo $ajax->divEnd("target"); ?></div>
							<br /><span class="usernavi"><?php echo $usernavi['POSTCODE']; ?></span>
							<br /><span class="must"><?php if($form->error("POSTCODE1")){echo $form->error("POSTCODE1");}else if($form->error("POSTCODE2")){echo $form->error("POSTCODE2");} ?></span>
							</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:180px;">都道府県<?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10')); ?></th>
					<td style="width:700px;">
					<?php echo $form->input('CNT_ID', array('label' => false, 'options' => $countys)); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error("ADDRESS")?' class="txt_top"':''; ?> style="width:180px;">住所<?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10')); ?></th>
					<td style="width:700px;">
					<?php echo $form->text('ADDRESS', array('class' => 'w600'.($form->error('ADDRESS')?' error':''))); ?>
					<br /><span class="usernavi"><?php echo $usernavi['ADDRESS']; ?></span>
					<br /><span class="must"><?php echo $form->error('ADDRESS'); ?></span>
					</td>

				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error("BUILDING")?' class="txt_top"':''; ?> style="width:180px;">建物名</th>
					<td style="width:700px;">
					<?php echo $form->text('BUILDING', array('class' => 'w600'.($form->error('BUILDING')?' error':''))); ?>
					<br /><span class="usernavi"><?php echo $usernavi['BUILDING']; ?></span>
					<br /><span class="must"><?php echo $form->error('BUILDING'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>

					<th style="width:180px;" <?php echo $perror == 1?' class="txt_top"':''; ?>>電話番号<?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10')); ?></th>
					<td style="width:700px;">
					<?php if($perror==1){echo $form->text("PHONE_NO1", array("class" => "w60 error"));}
						else{echo $form->text("PHONE_NO1", array("class" => "w60"));} ?>
		 			<span class="pl5 pr5">-</span>
		 			<?php if($perror==1){echo $form->text("PHONE_NO2", array("class" => "w60 error"));}
						else{echo $form->text("PHONE_NO2", array("class" => "w60"));} ?>
		 			<span class="pl5 pr5">-</span>
		 			<?php if($perror==1){echo $form->text("PHONE_NO3", array("class" => "w60 error"));}
						else{echo $form->text("PHONE_NO3", array("class" => "w60"));} ?>
					<br /><span class="usernavi"><?php echo $usernavi['PHONE']; ?></span>
					<br /><span class="must"><?php if($perror==1)echo "正しい電話番号を入力してください"?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:180px;" <?php echo $form->error("Install.user_password")?' class="txt_top"':''; ?>>パスワード<?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10')); ?></th>
					<td style="width:700px;">
					<?php echo $form->password('Install.user_password', array('class' => 'w300'.($form->error('Install.user_password')?' error':''))); ?>
					<br /><span class="usernavi"><?php echo $usernavi['USR_PASSWORD']; ?></span>
					<br /><span class="must"><?php echo $form->error('Install.user_password'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error("Install.user_password1")?' class="txt_top"':''; ?> style="width:180px;">パスワード確認<?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10')); ?></th>
					<td style="width:700px;">
					<?php echo $form->password('Install.user_password1', array('class' => 'w300'.($form->error('Install.user_password1')?' error':''))); ?>
					<br /><span class="usernavi"><?php echo $usernavi['USR_PASSWORD']; ?></span>
					<br /><span class="must"><?php echo $form->error('Install.user_password1'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:180px;" <?php echo $form->error("Install.mail")?' class="txt_top"':''; ?>>メールアドレス<?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10')); ?></th>
					<td style="width:700px;">
					<?php echo $form->text('Install.mail', array('class' => 'w300'.($form->error('Install.mail')?' error':''))); ?>
					<br /><span class="usernavi"><?php echo $usernavi['USR_MAIL']; ?></span>
					<br /><span class="must"><?php echo $form->error('Install.mail'); ?></span>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
			</table>
		</div>
		<?php echo $form->end('登録');?>
	</div>
</div>