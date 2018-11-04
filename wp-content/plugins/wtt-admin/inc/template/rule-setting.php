<?php

function save_setting_rules($old = false)
{
	if ($old) {
		if (empty($_POST['category_old_question']) || empty($_POST['level_old_question']) || empty($_POST['num_old_question'])) {
			echo '<div class="notice notice-error is-dismissible"><p>Không để trống thông tin</p></div>';
			return;
		}
		$category_id = $_POST['category_old_question'];
		$level       = $_POST['level_old_question'];
		$num         = $_POST['num_old_question'];
	} else {
		if (empty($_POST['category_id']) || empty($_POST['question-level']) || empty($_POST['num_question'])) {
			echo '<div class="notice notice-error is-dismissible"><p>Không để trống thông tin</p></div>';
			return;
		}
		$category_id = $_POST['category_id'];
		$level       = $_POST['question-level'];
		$num         = $_POST['num_question'];
	}
	
	$category_json  = get_option('category_rule_json');
	$category_array = json_decode($category_json);
	
	$total_question = get_option('total_question');
	
	$total_question_selected = 0;
	foreach ($category_array as $category) {
		$total_question_selected += $category->num;
	}
	
	$total = $total_question_selected + $num;
	if ($total > ((int)$total_question)) {
		echo "<strong style='color: red;'>Số câu hỏi đã chọn " . $total_question_selected .
			". Tổng số câu hỏi: " . $total_question .
			". Bạn chỉ được chọn thêm " . ($total_question - $total_question_selected) . ". </strong>";
		
	} else {
		if ($old) {
			$rule = [
				'categoryId' => $category_id,
				'level'      => $level,
				'num'        => $num,
				'old'        => true,
			];
		} else {
			$rule = [
				'categoryId' => $category_id,
				'level'      => $level,
				'num'        => $num,
				'old'        => false,
			];
		}
		
		$category_json    = get_option('category_rule_json');
		$category_array   = json_decode($category_json, true);
		$category_array[] = $rule;
		
		update_option('category_rule_json', json_encode($category_array));
		echo '<div class="notice notice-success is-dismissible"><p>Thêm rule thành công.</p></div>';
		
	}
}

function remove_rule()
{
	$key            = $_POST['key_rule'];
	$category_json  = get_option('category_rule_json');
	$category_array = json_decode($category_json, true);
	if (is_array($category_array)) {
		unset($category_array[$key]);
		update_option('category_rule_json', json_encode($category_array));
		echo '<div class="notice notice-success is-dismissible"><p>Xoá rule thành công.</p></div>';
	}
}

function get_cate_level()
{
	global $wpdb;

//	get cate - level - number
// -> check by js number question -> not allow Save
	$allCat = get_terms(array(
		'taxonomy'   => 'question-category',
		'hide_empty' => false,
	));
	
	// collect all category
	// foreach cate -> collect all question
	// read result filter - level
//	print_r($allCat);
	$list = null;
	
	if ($allCat) {
		foreach ($allCat as $cat) {
			$sql = "	SELECT DISTINCT wp_postmeta.meta_value
FROM wp_posts
  LEFT JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id)
  INNER JOIN wp_postmeta ON (wp_posts.ID = wp_postmeta.post_id)
WHERE wp_term_relationships.term_taxonomy_id = $cat->term_id
	AND wp_postmeta.meta_key = 'meta-box-question-level';";
			
			$result              = $wpdb->get_results($sql, ARRAY_N);
			$list[$cat->term_id] = $result;
		}
	}
//	echo '<pre>';
//	print_r($list);
//	echo '</pre>';
	return $list;
}

get_cate_level();
?>
<div class="two_third">
	<form action="" method="post">
		<h3>Setting Rule</h3>
		<?php if (!empty($_POST['save_rule_question'])) {
			save_setting_rules();
		} ?>
		<table cellspacing="0" class="hdTable">
			<tr>
				<td valign="top">Chọn category</td>
				<td valign="top">
					<select name="category_id" id="category_question">
						<option value="">-- Chọn category --</option>
						<?php
						$terms = get_terms(array(
							'taxonomy'   => 'question-category',
							'hide_empty' => false,
						));
						if ($terms) {
							foreach ($terms as $term) {
								echo "<option value='" . $term->term_id . "' label='" . $term->name . "'>$term->name</option>";
							}
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td valign="" top>Chọn level</td>
				<td valign="" top>
					<select name="question-level" id="question-level">
						<option value="">-- Chọn level --</option>
					</select>
				</td>
			</tr>
			<tr>
				<td valign="" top>Số lượng câu hỏi</td>
				<td valign="" top>
					<input type="number" name="num_question" required>
				</td>
			</tr>
			<tr>
				<td valign="middle">&nbsp;</td>
				<td valign="middle">
					<input type="submit" class="button button-primary" name="save_rule_question" style="float:right"
					       value="Save Setting"/>
			</tr>
		</table>
	</form>
	<form action="" method="post">
		<h3>Sử dụng câu hỏi cũ game trước ( 1 tuần )</h3>
		<?php
		if (!empty($_POST['submit_old_question'])) {
			save_setting_rules(true);
		}
		?>
		<table cellspacing="0" class="hdTable">
			<tr>
				<td valign="top">Chọn category</td>
				<td valign="top">
					<select name="category_old_question" id="category_old_question">
						<option value="">-- Chọn category --</option>
						<?php
						$rules = get_old_rule();
						if ($rules) {
							foreach ($rules as $key => $rule) {
								$category = get_term($key, 'question-category');
								if ($category) {
									echo "<option value='" . $key . "' label='" . $category->name . "'>$category->name</option>";
								}
							}
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td valign="" top>Chọn level</td>
				<td valign="" top>
					<select name="level_old_question" id="level_old_question">
						<option value="">-- Chọn level --</option>
					</select>
				</td>
			</tr>
			<tr>
				<td valign="" top>Số lượng câu hỏi</td>
				<td valign="" top>
					<input type="number" name="num_old_question" required>
				</td>
			</tr>
			<tr>
				<td valign="middle">&nbsp;</td>
				<td valign="middle">
					<input type="submit" class="button button-primary" name="submit_old_question" style="float:right"
					       value="Save Setting"/>
			</tr>
		</table>
	</form>
</div>
<div class="one_third last">
	<?php if (!empty($_POST['remove-rule'])) {
		remove_rule();
	} ?>
	<table cellspacing="0" id="category-rules">
		<th>Category</th>
		<th>Level</th>
		<th>Numbers</th>
		<th>Action</th>
		<?php
		$category_array = json_decode(get_option('category_rule_json'));
		foreach ($category_array as $key => $category) {
			$term = get_term($category->categoryId, 'question-category');
			?>
			<form action="" method="post">
				<tr>
					<td valign="top"><?php echo ($category->old ? "(Old) - " : "") . $term->name; ?></td>
					<td valign="top"><?php echo $category->level; ?></td>
					<td valign="top"><?php echo $category->num; ?></td>
					<td valign="top">
						<input type="hidden" name="key_rule" value="<?php echo $key; ?>">
						<input type="submit" name="remove-rule" class="button-primary" value="Remove"/>
					</td>
				</tr>
			</form>
		<?php } ?>
	</table>
</div>
<div class="clearboth"></div>

<?php
$catUsed = get_old_rule();
?>
<script type="application/javascript">
    var categories = '<?php echo json_encode(get_cate_level()); ?>';
    categories = jQuery.parseJSON(categories);
    jQuery('#category_question').change(function () {
        if (!!jQuery(this).val()) {
            var catID = jQuery(this).val();
            var el = jQuery('#question-level');
            update_level(el, categories, catID);
        }
    });

    var catUsed = '<?php echo json_encode($catUsed); ?>';
    catUsed = jQuery.parseJSON(catUsed);

    jQuery('#category_old_question').change(function () {
        if (!!jQuery(this).val()) {
            var catID = jQuery(this).val();
            var el = jQuery('#level_old_question');
            update_level(el, catUsed, catID);
        }
    });

    function update_level(el, cats, id) {
        if (!!cats[id]) {
            var level = cats[id];
            level.sort();
            el.empty();
            jQuery.each(level, function (key, level) {
                el.append("<option value='" + level + "'>" + level + "</option>");
            });
        }
    }

</script>
