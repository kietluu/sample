<?php
add_action('admin_menu', 'register_questionnaire_admin');
function register_questionnaire_admin()
{
	add_submenu_page('edit.php?post_type=post_type_question',
		'Push Questions',
		'Push Questions',
		'manage_options',
		'setting_question',
		'register_questionnaire_admin_callback');
}

function register_questionnaire_admin_callback()
{
	wp_enqueue_style('style-name', plugin_dir_url(__FILE__) . 'admin.css');
	
	//Save setting API
	if (!empty($_POST['save-setting-api-question'])) {
		save_setting_api();
	}
	if (!empty($_POST['push-question'])) {
		return push_question();
	}
	
	//Save setting question
	if (!empty($_POST['save-setting-question'])) {
		save_setting_question();
	}
	?>
	<div class="wrap">
		<?php if(is_super_admin()) { ?>
		<div class="hdContent">
			<?php
			require('template/api-setting.php');
			?>
		</div>
		<div class="hdContent">
			<?php
			require('template/question-setting.php');
			?>
		</div>
		<?php } ?>
		<div class="hdContent">
			<?php
			require('template/rule-setting.php');
			?>
		</div>
	</div>
	<?php
}

function push_question()
{
	$category_json   = get_option('category_rule_json');
	$category_object = json_decode($category_json);
	$total_question  = get_option('total_question');
	
	// 1. check setting is enough total question
	// 2. get question for each category rule -> check enought question
	// 3. merge all result
	// 4.push question
	$conditions                   = null;
	$questions_list               = null;
	$questions_list_ids           = null;
	$total_question_setting       = 0;
	$number_old_question['total'] = 0;
	
	//get list question IDs used last week
	$used_questionIDs = get_old_question();
	
	foreach ($category_object as $category) {
		$total_question_setting += $category->num;
		
		// Todo
		/**
		 * * Build condition query
		 * 1. for old question (used)
		 * Condition same with new question
		 * but status = used
		 * and id in array(ids)
		 *
		 * ==>> get old rule -> get old ids => array IDs
		 * ==>> after one week -> check old rule is available
		 * or update old rule
		 *
		 * ==> check number
		 */
		
		if ($category->old) {
			if (empty($used_questionIDs)) {
				echo '<div class="notice notice-error is-dismissible"><p>Không có câu hỏi đã sử dụng tuần trước.</p></div>';
				return;
			}
			$number_old_question['total']               = $number_old_question['total'] + $category->num;
			$number_old_question[$category->categoryId] = $category->num;
			$args                                       = array(
				'post_type'      => 'post_type_question',
				'post_status'    => 'used',
				'posts_per_page' => $category->num,
				'orderby'        => 'rand',
				'tax_query'      => array(
					array(
						'taxonomy' => 'question-category',
						'field'    => 'term_id',
						'terms'    => $category->categoryId,
					),
				),
				'meta_key'       => 'meta-box-question-level',
				'meta_value'     => $category->level,
				'post__in'       => $used_questionIDs
			);
		} else {
			//2. for new question (published)
			$args = array(
				'post_type'      => 'post_type_question',
				'post_status'    => 'publish',
				'posts_per_page' => $category->num,
				'orderby'        => 'rand',
				'tax_query'      => array(
					array(
						'taxonomy' => 'question-category',
						'field'    => 'term_id',
						'terms'    => $category->categoryId,
					),
				),
				'meta_key'       => 'meta-box-question-level',
				'meta_value'     => $category->level,
			);
		}
		$conditions[] = $args;
	}
	
	if ($total_question_setting < $total_question) {
		echo "<strong style='color: red;'>Số câu hỏi đã chọn theo Rules " . $total_question_setting .
			". Tổng số câu hỏi " . $total_question .
			". Cần chọn thêm " . ($total_question - $total_question_setting) . ". </strong><br>";
		echo '<a href="javascript:window.location.reload(true)" >Back</a>';
		return;
	} elseif ($total_question_setting > $total_question) {
		echo "<strong style='color: red;'>Số câu hỏi đã chọn theo Rules " . $total_question_setting .
			". Tổng số câu hỏi " . $total_question .
			". Cần bỏ bớt " . ($total_question_setting - $total_question) . "</strong><br>";
		
		echo '<a href="javascript:window.location.reload(true)" >Back</a>';
		return;
		
	} elseif ($conditions) {
		$round = 0;
		
		foreach ($conditions as $cond) {
			$level = $cond['meta_value'];
			$catID = $cond['tax_query'][0]['terms'];
			
			$the_query = new WP_Query($cond);
			if ($the_query->have_posts()) {
				// compare category num vs $the_query->post_count -> enough or not
				if ($the_query->post_count < $cond['posts_per_page']) {
					$term = get_term($catID, 'question-category');
					echo "<strong style='color: red;'>Không đủ số lượng câu hỏi cho danh mục : " . $term->name . "</strong><br>";
					echo "<strong style='color: red;'>Vui lòng chỉnh lại cài đặt.</strong><br>";
					echo '<a href="javascript:window.location.reload(true)" >Back</a>';
					print_r($the_query->post_count);
					print_r($cond['posts_per_page']);
					return;
				}
				
				$questions = $the_query->get_posts();
				foreach ($questions as $question) {
					
					$answerOptions = null;
					$ans1          = get_post_meta($question->ID, 'hdQue_post_class1', true);
					$ans2          = get_post_meta($question->ID, 'hdQue_post_class3', true);
					$ans3          = get_post_meta($question->ID, 'hdQue_post_class4', true);
					$correct       = get_post_meta($question->ID, 'hdQue_post_class2', true);
					$description   = esc_attr(get_post_meta($question->ID, 'wtt_question_description', true));
					if($correct == "no"){
						echo "<strong style='color: red;'>Câu hỏi chưa chọn </strong><br>";
						echo "<strong style='color: red;'>Số câu hỏi theo rule: " . count($questions_list_ids) . "</strong><br>";
						echo "<strong style='color: red;'>Số câu hỏi yêu cầu : " . $total_question . "</strong><br>";
						echo '<a href="javascript:window.location.reload(true)" >Back</a>';
						return;
					}
					
					$answerOptions[] = ["key" => 1, "value" => $ans1];
					$answerOptions[] = ["key" => 2, "value" => $ans2];
					$answerOptions[] = ["key" => 3, "value" => $ans3];
					
					$round++;
					$temp = [
						"content"       => $question->post_title,
						"round"         => $round,
						"description"   => $description,
						"answerOptions" => $answerOptions,
						"answer"        => $correct,
						"timeLimit"     => 10,
						"level"         => $level
					];
					
					$questions_list[]     = $temp;
					$questions_list_ids[] = $question->ID;
				}
			}
		}
	}
	
	//sort array by level
	$questions_list = sort_question_by_level($questions_list);
	
	if (count($questions_list_ids) < $total_question) {
		echo "<strong style='color: red;'>Không đủ số lương câu hỏi để import</strong><br>";
		echo "<strong style='color: red;'>Số câu hỏi theo rule: " . count($questions_list_ids) . "</strong><br>";
		echo "<strong style='color: red;'>Số câu hỏi yêu cầu : " . $total_question . "</strong><br>";
		echo '<a href="javascript:window.location.reload(true)" >Back</a>';
		return;
	} elseif (count($questions_list_ids) > $total_question) {
		echo "<strong style='color: red;'>Vuợt quá số lượng câu hỏi cho phép</strong><br>";
		echo "<strong style='color: red;'>Số câu hỏi theo rule: " . count($questions_list_ids) . "</strong><br>";
		echo "<strong style='color: red;'>Số câu hỏi yêu cầu : " . $total_question . "</strong><br>";
		echo '<a href="javascript:window.location.reload(true)" >Back</a>';
		return;
	}
	if (empty($questions_list)) {
		echo "<strong style='color: red;'>Cannot push empty questions.</strong><br>";
		echo '<a href="javascript:window.location.reload(true)" >Back</a>';
		return;
	}
	
	$total_question = get_option('total_question');
	$name_game      = get_option('name_game');
	$livestreamUrl  = get_option('livestream_url');
	$beginAt        = get_option('beginAt');
	$prize          = get_option('prize');
	
	// Create content json
	$content = [
		'name'           => $name_game,
		"livestreamUrls" => ["$livestreamUrl"],
		"beginAt"        => $beginAt,
		"prize"          => $prize,
		"questions"      => $questions_list
	];
	
	/****************/
	// Push question API
	$json_questions = json_encode($content);
	
	send_question_api($json_questions, $questions_list_ids, $category_json);
	
	echo "<button onclick='window.location.reload(true);' class='button button-primary'>Back</button>";
	return;
}

function send_question_api($content = null, $questionIds = null, $rules)
{
	
	$access_token = null;
	//get token
	if (is_callable('curl_init')) {
		$curl = curl_init();
		
		$url_get_token = get_option('url_api_get_token');
		$appId         = get_option('id_api_question');
		$secretKey     = get_option('secretKey_api_question');
		
		if (empty($url_get_token) || empty($appId) || empty($secretKey)) {
			echo "<strong style='color: red;'>Thiếu thông tin API</strong><br>";
			echo '<a href="javascript:window.location.reload(true)" >Back</a>';
			return;
		}
		
		
		$key_info = '{
		    "appId": "' . $appId . '",
		    "secret": "' . $secretKey . '",
		    "deviceInfo":{}
		}';
		
		curl_setopt_array($curl, array(
			CURLOPT_URL            => $url_get_token,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 3000,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "POST",
			CURLOPT_POSTFIELDS     => $key_info,
			CURLOPT_HTTPHEADER     => array(
				"cache-control: no-cache",
				"content-type: application/json"
			),
		));
		
		$response = curl_exec($curl);
		$response = json_decode($response);
		
		$err = curl_error($curl);
		curl_close($curl);
		
		
		if ($response->ok != 1) {
			echo "cURL Error #:" . $err;
			exit();
		} else {
			$access_token = $response->data;
		}
	}
	if (empty($access_token)) return;
	
	
	//authenticate
	$url_import = get_option('url_api_import_question');
	
	$header = array(
		"authentication: Bearer $access_token",
		"authorization: Basic dGVzdF9xdWl6OnRlc3RfcXVpekBAQCMjIw==",
		"cache-control: no-cache",
		"content-type: application/json"
	);
	
	if (is_callable('curl_init')) {
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL            => $url_import,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 3000,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "POST",
			CURLOPT_POSTFIELDS     => $content,
			CURLOPT_HTTPHEADER     => $header,
		));
		
		$response = curl_exec($curl);
		$err      = curl_error($curl);
		curl_close($curl);
		
		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			echo $response;
			
			disable_question($questionIds);
			log_info($content, $questionIds, $rules);
		}
	}
}

function disable_question($questionIds)
{
	if (empty($questionIds)) return;
	foreach ($questionIds as $id) {
		wp_update_post(
			array(
				'ID'          => $id,
				'post_status' => 'used'
			));
	}
}


function save_setting_question()
{
	$total_question = $_POST['total_question'];
	update_option('total_question', $total_question);
	
	//Game info
	$name_game     = $_POST['name_game'];
	$livestreamUrl = $_POST['livestream_url'];
	$beginAt       = $_POST['beginAt'];
	$prize         = $_POST['prize'];
	
	//Update game info
	update_option('name_game', $name_game);
	update_option('livestream_url', $livestreamUrl);
	update_option('beginAt', $beginAt);
	update_option('prize', $prize);
}

function save_setting_api()
{
	$url_get_token       = $_POST['url_api_get_token'];
	$appId               = $_POST['id_api_question'];
	$secretKey           = $_POST['secretKey_api_question'];
	$url_import_question = $_POST['url_api_import_question'];
	
	//update info API
	update_option('url_api_get_token', $url_get_token);
	update_option('id_api_question', $appId);
	update_option('secretKey_api_question', $secretKey);
	update_option('url_api_import_question', $url_import_question);
}

function log_info($content, $questionIds, $rules)
{
	global $wpdb;
	
	$table_name = $wpdb->prefix . 'log_push_question';
	
	$wpdb->insert(
		$table_name,
		array(
			'time'    => current_time('mysql'),
			'content' => $content,
			'ids'     => json_encode($questionIds),
			'rules'   => $rules,
		)
	);
}

function get_old_question()
{
	global $wpdb;
	$questionIDs  = null;
	$table_name   = $wpdb->prefix . 'log_push_question';
	$current_time = current_time('mysql');
	$aweek        = gmdate('Y-m-d H:i:s', (time() - (7 * DAY_IN_SECONDS)));
	$query        = "SELECT ids FROM $table_name WHERE
					time >= '$aweek' AND time <= '$current_time'  AND ids != 'null'
					ORDER BY time DESC LIMIT 1";
	$result       = $wpdb->get_results($query, ARRAY_N);
	
	if (!empty($result)) {
		$temp = $result[0][0];
		$ids  = json_decode($temp);
		if (is_array($ids)) $questionIDs = $ids;
	}
	
	return $questionIDs;
}

function get_old_rule()
{
	global $wpdb;
	$ids          = null;
	$table_name   = $wpdb->prefix . 'log_push_question';
	$current_time = current_time('mysql');
	$aweek        = gmdate('Y-m-d H:i:s', (time() - (7 * DAY_IN_SECONDS)));
	$query        = "SELECT rules FROM $table_name WHERE
					time >= '$aweek' AND time <= '$current_time'  AND ids != 'null'
					ORDER BY time DESC LIMIT 1";
	$rules        = $wpdb->get_results($query, ARRAY_N);
	$list         = null;
	if (!empty($rules))
		$rules = $rules[0][0];
	$rules = json_decode($rules);
	foreach ($rules as $rule) {
		$list[$rule->categoryId][] = $rule->level;
	}
	return $list;
}

function sort_question_by_level($questions_list)
{
	$level = array();
	
	foreach ($questions_list as $key => $row) {
		$level[$key] = $row['level'];
	}
	array_multisort($level, SORT_ASC, $questions_list);
	
	foreach ($questions_list as $key => $row) {
		$questions_list[$key]['round'] = $key + 1;
	}
	return $questions_list;
}
