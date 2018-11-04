<?php
//Get info game from db
$total_question = get_option( 'total_question' );
$name_game      = get_option( 'name_game' );
$livestreamUrl  = get_option( 'livestream_url' );
$beginAt        = get_option( 'beginAt' );
$prize          = get_option( 'prize' );
?>
<h3>Questions Setting</h3>

<form method="post" action="">
	<table class="hdTable">
		<tr>
			<td>Số lượng câu hỏi</td>
			<td>
				<input type="text" name="total_question" value="<?php echo $total_question; ?>"/>
			</td>
		</tr>
		<tr>
			<td>Tên trò chơi</td>
			<td>
				<input type="text" name="name_game" value="<?php echo $name_game; ?>"/>
			</td>
		</tr>
		<tr>
			<td>Link livestream</td>
			<td>
				<input type="text" name="livestream_url" value="<?php echo $livestreamUrl; ?>"/>
			</td>
		</tr>
		<tr>
			<td>Thời gian bắt</td>
			<td>
				<input type="text" name="beginAt" value="<?php echo $beginAt; ?>"/>
			</td>
		</tr>
		<tr>
			<td>Phần thưởng</td>
			<td>
				<input type="number" name="prize" value="<?php echo $prize; ?>"/>
			</td>
		</tr>
		<tr>
			<td valign="middle">
				<input type="submit" class="button button-primary" name="push-question" value="Push Question" />
			</td>
			<td valign="middle">
				<input type="submit" class="button button-primary" name="save-setting-question" value="Save" style="float:right"/>
			</td>
		</tr>
	</table>
</form>