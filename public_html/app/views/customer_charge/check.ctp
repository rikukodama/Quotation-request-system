<?php	//完了メッセージ
	echo $session->flash();
?>
<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('/img/company/i_guide.jpg'); ?>
		<p>こちらのページは取引先担当者確認の画面です。<br />「編集する」ボタンを押すと取引先担当者を編集することができます。</p>
	</div>
</div>
<br class="clear" />
<!-- header_End -->

<!-- contents_Start -->
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
	<div class="edit_btn2">
		<?php if($editauth){
			echo $html->link($html->image('bt_edit.jpg',array('class'=>'imgover','alt'=>'編集する')), array('controller' => 'customer_charges', 'action' => 'edit/'.$chrcID),array('escape' => false));
		}?>
		<?php //echo $html->link($html->image('bt_index.jpg',array('class'=>'imgover','alt'=>'一覧')), array('controller' => 'customer_charges', 'action' => 'index'),array('escape' => false)); ?>
        <?php
            echo $form->create($this->name, array('type' => 'post', 'action' => 'moveback', 'style' => 'display:inline;'));
            echo $html->link($html->image('bt_index.jpg',array('class'=>'imgover','alt'=>'一覧')), 'javascript:move_to_index();',array('escape' => false));
            echo $form->end();
        ?>
    </div>
	<h3><div class="edit_01_c_charge"><span class="edit_txt">&nbsp;</span></div></h3>

	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th style="width:150px;">ステータス</th>
					<td style="width:730px;"><?php echo $status[$this->data['CustomerCharge']['STATUS']]; ?></td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>顧客名</th>
					<td><?php echo $this->data['CustomerCharge']['CST_ID']!=0?$customer:""; ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th>担当者名</th>
					<td><?php echo $customHtml->ht2br($this->data['CustomerCharge']['CHARGE_NAME']); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th>担当者名カナ</th>
					<td><?php echo $customHtml->ht2br($this->data['CustomerCharge']['CHARGE_NAME_KANA']); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th>部署名</th>
					<td><?php echo $customHtml->ht2br($this->data['CustomerCharge']['UNIT']); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th>役職名</th>
					<td><?php echo $customHtml->ht2br($this->data['CustomerCharge']['POST']); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th>メールアドレス</th>
					<td><?php echo $customHtml->ht2br($this->data['CustomerCharge']['MAIL']); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th>郵便番号</th>
					<td>
					<?php
						if(empty($this->data['CustomerCharge']['POSTCODE1']) && empty($this->data['CustomerCharge']['POSTCODE2'])) {

						}else{
							echo $customHtml->ht2br($this->data['CustomerCharge']['POSTCODE1']." - ".$this->data['CustomerCharge']['POSTCODE2']);
						}
					?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th>都道府県</th>
					<td>
					<?php
						if($this->data['CustomerCharge']['CNT_ID']) {
							echo $countys[$this->data['CustomerCharge']['CNT_ID']];
						}
					?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th>住所</th>
					<td><?php echo $customHtml->ht2br($this->data['CustomerCharge']['ADDRESS']); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th>建物名</th>
					<td><?php echo $customHtml->ht2br($this->data['CustomerCharge']['BUILDING']); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th>電話番号</th>
					<td>
					<?php
						if(empty($this->data['CustomerCharge']['PHONE_NO1']) && empty($this->data['CustomerCharge']['PHONE_NO2']) && empty($this->data['CustomerCharge']['PHONE_NO3'])) {

						}else {
							echo $customHtml->ht2br($this->data['CustomerCharge']['PHONE_NO1']." - ".$this->data['CustomerCharge']['PHONE_NO2']." - ".$this->data['CustomerCharge']['PHONE_NO3']);
						}
					?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th>FAX番号</th>
					<td><?php echo $customHtml->ht2br(($this->data['CustomerCharge']['FAX_NO1']&& $this->data['CustomerCharge']['FAX_NO2']&& $this->data['CustomerCharge']['FAX_NO3'])?$this->data['CustomerCharge']['FAX_NO1']." - ".$this->data['CustomerCharge']['FAX_NO2']." - ".$this->data['CustomerCharge']['FAX_NO3']:""); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
	<div class="edit_btn2">
		<?php if($editauth){
			echo $html->link($html->image('bt_edit.jpg',array('class'=>'imgover','alt'=>'編集する')), array('controller' => 'customer_charges', 'action' => 'edit/'.$chrcID),array('escape' => false));
		}?>
		<?php echo $html->link($html->image('bt_index.jpg',array('class'=>'imgover','alt'=>'一覧')), array('controller' => 'customer_charges', 'action' => 'index'),array('escape' => false)); ?>
	</div>
</div>