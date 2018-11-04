<?php
/*
Template Name: Questionnaire Results
*/
?>

<?php 
error_reporting(0);
$paged = $_POST[quQuizPaged];
$total = 0;
$passPerc = stripslashes($_POST[quPassPercent]);
$fb = $_POST[quQuizFb];
$total_Questions = $_POST[maxValue];
$currentScore = $_POST[currentScore];

if ($paged != "-1") {
	for ($x = 1; $x <= $paged; $x++) {
		if ($_POST["q$x"]) {	   
		    $total = $total + $_POST["q$x"];
		}

	} 
}

else {
	foreach ($_POST as $key => $value) {
		if (ctype_digit($value)) {
			$total = $total + htmlspecialchars($value);
		}
	}
	// $total - $total - htmlspecialchars($value);
	$total = $total - $passPerc;
	$total = $total - $total_Questions;
	$total = $total - $currentScore;
	if($fb != "no") {$total = $total - $fb;}

}



$total = $total + $currentScore;
$total_Percent = $total / $total_Questions;
$total_Percent = $total_Percent * 100;
$total_Percent = round($total_Percent,2);

echo '<h2>You Scored <span>'.$total.'/'.$total_Questions.' or '.$total_Percent.'%</span></h2>';
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($total_Percent < $passPerc) { 
	$captionText = $_POST[quFail]; 
	echo '<h3>'.stripslashes(nl2br($captionText)).'</h3>';
}
else {
	$captionText = $_POST[quPass];

	echo '<h3>'.stripslashes(nl2br($captionText)).'</h3>';
}
?>



<?php if ($_POST[quQuizShare] == "yes") { 
$tw = stripslashes($_POST[quQuizTw]);
$quTitle = stripslashes($_POST[quQuizTitle]);
$quAddress = $_POST[quQuizAddress];
?>
?>
		</div>
<?php } ?>