<?php
//Get api info from db
$url_get_token       = get_option('url_api_get_token');
$appId               = get_option('id_api_question');
$secretKey           = get_option('secretKey_api_question');
$url_import_question = get_option('url_api_import_question');
?>

<h3>Settings API</h3>
<form name="form1" method="post" action="">
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
	<table cellspacing="0" class="hdTable">
		<tr>
			<td valign="top">URL Get token</td>
			<td valign="top">
				<input type="text" name="url_api_get_token" value="<?php echo $url_get_token; ?>"/>
			</td>
		</tr>
		<tr>
			<td valign=""top>APP ID</td>
			<td valign=""top>
				<input type="text" name="id_api_question" value="<?php echo $appId; ?>"/>
			</td>
		</tr>
		<tr>
			<td valign=""top>Secret Key</td>
			<td valign=""top>
				<input type="text" name="secretKey_api_question" value="<?php echo $secretKey; ?>"/>
			</td>
		</tr>
		<tr>
			<td valign="top">URL Import Question</td>
			<td valign="top">
				<input type="text" name="url_api_import_question" value="<?php echo $url_import_question; ?>"/>
			</td>
		</tr>
		<tr>
			<td valign="middle">&nbsp;</td>
			<td valign="middle">
				<input type="submit" name="save-setting-api-question" class="button button-primary" style="float:right" value="UPDATE"/>
			</td>
		</tr>
	</table>
</form>