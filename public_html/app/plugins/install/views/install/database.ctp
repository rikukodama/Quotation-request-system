<?php	//完了メッセージ
	echo $session->flash();
?>

<style type="text/css"><!--

table.tbl {
	border: 1px #E3E3E3 solid;
	border-collapse: collapse;
	border-spacing: 0;
}

table.tbl tr.bgcl {
    background: #F5F5F5;
}

table.tbl td {
	padding: 10px;
	border: 1px #E3E3E3 solid;
	border-width: 0 0 1px 1px;
}

table.tbl td.left {
	text-align: left;
}

table.tbl td.center {
	text-align: center;
}

--></style>

<div class="contents_box mb20">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
		<?php
			echo $form->create('Install', array('url' => array('plugin' => 'install', 'controller' => 'install', 'action' => 'data')));
		?>
			<table cellspacing="0" cellpadding="0" border="0" width="880" class="tbl">
				<tbody>
					<tr class="bgcl">
						<td class="w120"><p class="float_l">ホスト名</p><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 float_r')); ?></td>
						<td class="center w300"><?php echo $form->input('Install.host',array('class' => 'w250','label' => false));?></td>
						<td>同一サーバ内にデータベースがある場合は「localhost」と入力してください</td>
					</tr>
					<tr>
						<td><p class="float_l">ユーザ名</p><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 float_r')); ?></td>
						<td class="center"><?php echo $form->input('Install.login',array('class' => 'w250','label' => false));?></td>
						<td>データベースのユーザ名を入力してください</td>
					</tr>
					<tr class="bgcl">
						<td><p class="float_l">パスワード</p><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 float_r')); ?></td>
						<td class="center"><?php echo $form->input('Install.password',array('class' => 'w250','label' => false));?></td>
						<td>データベースのパスワードを入力してください</td>
					</tr>
					<tr>
						<td><p class="float_l">DB名</p><?php echo $html->image('i_must.jpg',array('alt'=>'必須','class'=>'pl10 float_r')); ?></td>
						<td class="center"><?php echo $form->input('Install.database',array('class' => 'w250','label' => false));?></td>
						<td>使用するデータベース名を入力してください</td>
					</tr>
					<tr class="bgcl">
						<td>プレフィックス</td>
						<td class="center"><?php echo $form->input('Install.prefix',array('class' => 'w250','label' => false));?></td>
						<td>同一データベース内で複数のシステムを使用する場合は入力してください</td>
					</tr>
				</tbody>
			</table>
			<br />
			<br />
			<?php echo $form->end('DB接続する'); ?>
		</div>
</div>