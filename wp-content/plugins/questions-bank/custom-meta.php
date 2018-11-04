<?php
/* Register Custom Post Type for Questionnaire
________________________________________________________
-------------------------------------------------------- */
function register_post_type_question()
{
	
	$labels = array(
		'name'               => _x('Questions', 'Post Type General Name', 'text_domain'),
		'singular_name'      => _x('Questions Bank', 'Post Type Singular Name', 'text_domain'),
		'menu_name'          => __('Questions Bank', 'text_domain'),
		'name_admin_bar'     => __('Questions Bank', 'text_domain'),
		'parent_item_colon'  => __('Parent Question:', 'text_domain'),
		'all_items'          => __('All Questions', 'text_domain'),
		'add_new_item'       => __('Add New Question', 'text_domain'),
		'add_new'            => __('Add New Question', 'text_domain'),
		'new_item'           => __('New Question', 'text_domain'),
		'edit_item'          => __('Edit Question', 'text_domain'),
		'update_item'        => __('Update Question', 'text_domain'),
		'view_item'          => __('View Question', 'text_domain'),
		'search_items'       => __('Search Question', 'text_domain'),
		'not_found'          => __('Not found', 'text_domain'),
		'not_found_in_trash' => __('Not found in Trash', 'text_domain'),
	);
	$args = array(
		'label'               => __('Questions Bank', 'text_domain'),
		'description'         => __('Post Type Description', 'text_domain'),
		'labels'              => $labels,
		'supports'            => array('title', 'author', 'revisions'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-welcome-learn-more',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'map_meta_cap'        => true,
		'taxonomies'          => array('question-category'),
		'capability_type'     => array('question', 'questions'),
		'capabilities'        => array(
			'edit_post'          => 'edit_question',
			'read_post'          => 'read_question',
			'delete_post'        => 'delete_question',
			'edit_posts'         => 'edit_questions',
			'edit_others_posts'  => 'edit_others_questions',
			'publish_posts'      => 'publish_questions',
			'read_private_posts' => 'read_private_questions',
			'create_posts'       => 'edit_questions',
		),
	);
	register_post_type('post_type_question', $args);
	
}

add_action('init', 'register_post_type_question', 0);

// Register Custom Taxonomy
function custom_taxonomy_question()
{
	
	$labels = array(
		'name'                       => _x('Categories', 'Taxonomy General Name', 'text_domain'),
		'singular_name'              => _x('Category', 'Taxonomy Singular Name', 'text_domain'),
		'menu_name'                  => __('Categories', 'text_domain'),
		'all_items'                  => __('All Categories', 'text_domain'),
		'parent_item'                => __('Parent Category', 'text_domain'),
		'parent_item_colon'          => __('Parent Category:', 'text_domain'),
		'new_item_name'              => __('New Category Name', 'text_domain'),
		'add_new_item'               => __('Add A New Category', 'text_domain'),
		'edit_item'                  => __('Edit Category', 'text_domain'),
		'update_item'                => __('Update Category', 'text_domain'),
		'view_item'                  => __('View Category', 'text_domain'),
		'separate_items_with_commas' => __('Separate Categories with commas', 'text_domain'),
		'add_or_remove_items'        => __('Add or remove Categories', 'text_domain'),
		'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
		'popular_items'              => __('Popular Categories', 'text_domain'),
		'search_items'               => __('Search Categories', 'text_domain'),
		'not_found'                  => __('Not Found', 'text_domain'),
	);
	$args = array(
		'labels'             => $labels,
		'hierarchical'       => true,
		'public'             => false,
		'show_ui'            => true,
		'show_admin_column'  => true,
		'show_in_nav_menus'  => false,
		'show_tagcloud'      => false,
		'rewrite'            => true,
		'show_in_quick_edit' => true,
		'capabilities'       => array(
			'assign_terms' => 'edit_questions',
		),
	);
	register_taxonomy('question-category', array('post_type_question'), $args);
	
}

add_action('init', 'custom_taxonomy_question', 0);


/* Show Taxonomy filter on Questions page
________________________________________________________
-------------------------------------------------------- */

add_action('restrict_manage_posts', 'hd_qu_quiz_filter');
function hd_qu_quiz_filter()
{
	
	// only display these taxonomy filters on desired custom post_type listings
	global $typenow;
	if ($typenow == 'post_type_question') {
		
		// create an array of taxonomy slugs you want to filter by - if you want to retrieve all taxonomies, could use get_taxonomies() to build the list
		$filters = array('question-category');
		
		foreach ($filters as $tax_slug) {
			// retrieve the taxonomy object
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			// retrieve array of term objects per taxonomy
			$terms = get_terms($tax_slug);
			
			// output html for taxonomy dropdown filter
			echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
			echo "<option value=''>Show All $tax_name</option>";
			foreach ($terms as $term) {
				// output each select option line, check against the last $_GET to show the current option selected
				echo '<option value="' . $term->slug . '">' . $term->name . ' (' . $term->count . ')</option>';
				
				
			}
			echo "</select>";
		}
	}
}


/* Register Custom Tax Meta
________________________________________________________
-------------------------------------------------------- */

function quiz_taxonomy_custom_fields($tag)
{
	// Check for existing taxonomy meta for the term you're editing
	$t_id = $tag->term_id; // Get the ID of the term you're editing
	$term_meta = get_option("taxonomy_term_$t_id"); // Do the check
	?>
	<tr class="form-field h3Highlight">
		<th scope="row" valign="top" colspan="2">
			<h3><?php _e('General Category Options'); ?></h3>
			<p class="description small">The basic options for this quiz</p>
		</th>

	</tr>
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="term_meta[passPercent]"><?php _e('Category pass percentage'); ?></label>
		</th>
		<td>
			<input class="widefat2" type="number" min="0" max="100" name="term_meta[passPercent]"
			       id="term_meta[passPercent]"
			       value="<?php echo $term_meta['passPercent'] ? $term_meta['passPercent'] : '70'; ?>" size="3"/>%
			<p class="description small">Enter the percentage of questions a user needs to get correct to pass the
				quiz.</p>
		</td>
	</tr>


	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="term_meta[passText]"><?php _e('Category pass text'); ?></label>
		</th>
		<td>
			<?php wp_editor(stripslashes($term_meta['passText']), "hd_quiz_term_meta_passText", array('textarea_name' => 'term_meta[passText]', 'teeny' => true, 'media_buttons' => false, 'textarea_rows' => 1, 'quicktags' => false)); ?>
			<p class="description small">Customize the text that appears when a user completes the quiz and achieves the
				pass percentage or higher.</p>

		</td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="term_meta[failText]"><?php _e('Category fail text'); ?></label>
		</th>
		<td>
			<?php wp_editor(stripslashes($term_meta['failText']), "hd_quiz_term_meta_failText", array('textarea_name' => 'term_meta[failText]', 'teeny' => true, 'media_buttons' => false, 'textarea_rows' => 1, 'quicktags' => false)); ?>
			<p class="description small">Customize the text that appears when a user completes the quiz and does not
				achieve the pass percentage.</p>
		</td>
	</tr>

	<tr class="form-field h3Highlight">
		<th scope="row" valign="top" colspan="2">
			<h3><?php _e('Category Results'); ?></h3>
			<p class="description small">What happens when a user finishes a quiz</p>
		</th>

	</tr>

	<tr class="form-field">
		<th scope="row" valign="top">
			<label><?php _e('Share results'); ?></label>
		</th>
		<td>
			<input type="radio" name="term_meta[shareResults]" id="term_meta[shareResults]1"
			       value="yes" <?php if ($term_meta['shareResults'] == "yes") {
				echo 'checked';
			}
			if (!$term_meta['shareResults']) {
				echo 'checked';
			} ?>><label for="term_meta[shareResults]1"><span></span> Show</label><br/>
			<input type="radio" name="term_meta[shareResults]" id="term_meta[shareResults]2"
			       value="no" <?php if ($term_meta['shareResults'] == "no") {
				echo 'checked';
			} ?>><label for="term_meta[shareResults]2"><span></span> Hide</label>
			<p class="description small">This option shows or hides the Facebook and Twitter share buttons that appears
				when a user completes the quiz.</p>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top">
			<label><?php _e('Results Position'); ?></label>
		</th>
		<td>
			<input type="radio" name="term_meta[resultPos]" id="term_meta[resultPos]1"
			       value="yes" <?php if ($term_meta['resultPos'] == "yes") {
				echo 'checked';
			}
			if (!$term_meta['resultPos']) {
				echo 'checked';
			} ?>><label for="term_meta[resultPos]1"><span></span> Above Category</label><br/>
			<input type="radio" name="term_meta[resultPos]" id="term_meta[resultPos]2"
			       value="no" <?php if ($term_meta['resultPos'] == "no") {
				echo 'checked';
			} ?>><label for="term_meta[resultPos]2"><span></span> Below Category</label>
			<p class="description small">The site will automatically scroll to the position of the results.</p>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top">
			<label><?php _e('Highlight correct / incorrect <strong>selected</strong> answers on completion'); ?></label>
		</th>
		<td>
			<input type="radio" name="term_meta[showResults]" id="term_meta[showResults]1"
			       value="yes" <?php if ($term_meta['showResults'] == "yes") {
				echo 'checked';
			}
			if (!$term_meta['showResults']) {
				echo 'checked';
			} ?>><label for="term_meta[showResults]1"><span></span> Yes</label><br/>
			<input type="radio" name="term_meta[showResults]" id="term_meta[showResults]2"
			       value="no" <?php if ($term_meta['showResults'] == "no") {
				echo 'checked';
			} ?>><label for="term_meta[showResults]2"><span></span> No</label>
			<p class="description small">This feature allows you to enable or disable showing what answers a user got
				right or wrong.</p>

		</td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top">
			<label><?php _e('Highlight the correct answers on completion'); ?></label>
		</th>
		<td>
			<input type="radio" name="term_meta[showResultsCorrect]" id="term_meta[showResultsCorrect]1"
			       value="yes" <?php if ($term_meta['showResultsCorrect'] == "yes") {
				echo 'checked';
			} ?>><label for="term_meta[showResultsCorrect]1"><span></span> Yes</label><br/>
			<input type="radio" name="term_meta[showResultsCorrect]" id="term_meta[showResultsCorrect]2"
			       value="no" <?php if ($term_meta['showResultsCorrect'] == "no") {
				echo 'checked';
			}
			if (!$term_meta['showResultsCorrect']) {
				echo 'checked';
			} ?>><label for="term_meta[showResultsCorrect]2"><span></span> No</label>
			<p class="description small">By default, Questions Bank will only show if a user's <strong>selected</strong>
				answer was right or wrong.</p>
			<p class="description small">Enabling this feature will go the extra step and show what the correct answer
				was if the user got the question wrong.</p>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top">
			<label><?php _e('Show the "Text that appears if answer was wrong" even if the user got the question right.'); ?></label>
		</th>
		<td>
			<input type="radio" name="term_meta[showIncorrectAnswerText]" id="term_meta[showIncorrectAnswerText]1"
			       value="yes" <?php if ($term_meta['showIncorrectAnswerText'] == "yes") {
				echo 'checked';
			} ?>><label for="term_meta[showIncorrectAnswerText]1"><span></span> Yes</label><br/>
			<input type="radio" name="term_meta[showIncorrectAnswerText]" id="term_meta[showIncorrectAnswerText]2"
			       value="no" <?php if ($term_meta['showIncorrectAnswerText'] == "no") {
				echo 'checked';
			}
			if (!$term_meta['showIncorrectAnswerText']) {
				echo 'checked';
			} ?>><label for="term_meta[showIncorrectAnswerText]2"><span></span> No</label>
			<p class="description small">Each indivdual question can have accompanying text that will show if the user
				selects the wrong answer.</p>
			<p class="description small">Enabling this feature will go the extra step and show this text even if the
				selected answer was correct.</p>
		</td>
	</tr>

	<tr class="form-field h3Highlight">
		<th scope="row" valign="top" colspan="2">
			<h3><?php _e('Advanced Category Options'); ?></h3>
			<p class="description small">These are the advanced options for the quiz if you want that extra control.</p>
		</th>

	</tr>

	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="term_meta[quizTimer]"><?php _e('Timer / Countdown'); ?></label>
			<br/>leave blank to disable
		</th>
		<td>
			<input class="widefat2" type="number" min="0" max="9999" name="term_meta[quizTimerS]"
			       id="term_meta[quizTimerS]"
			       value="<?php echo $term_meta['quizTimerS'] ? $term_meta['quizTimerS'] : '0'; ?>" size="3"/><br/>
			<p class="description">Enter how many seconds total. So 3 minutes would be 180. </p>
			<p class="description small"><strong>Please note</strong> that the timer will NOT work if the below WP
				Pagination feature is being used (it will work for jQuery pagination).</p>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top">
			<label><?php _e('Randomize <u>Question</u> Order'); ?></label>
		</th>
		<td>
			<input type="radio" name="term_meta[randomizeQuestions]" id="term_meta[randomizeQuestions]1"
			       value="rand" <?php if ($term_meta['randomizeQuestions'] == "rand") {
				echo 'checked';
			} ?>><label for="term_meta[randomizeQuestions]1"><span></span> Yes</label><br/>
			<input type="radio" name="term_meta[randomizeQuestions]" id="term_meta[randomizeQuestions]2"
			       value="menu_order" <?php if ($term_meta['randomizeQuestions'] == "menu_order") {
				echo 'checked';
			}
			if (!$term_meta['randomizeQuestions']) {
				echo 'checked';
			} ?>><label for="term_meta[randomizeQuestions]2"><span></span> No</label>
			<p class="description small"><strong>Please note</strong> that randomizing the questions is NOT possible if
				the below WP Pagination feature is being used (it will work for jQuery pagination).</p>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top">
			<label><?php _e('Randomize <u>Answer</u> Order'); ?></label>
		</th>
		<td>
			<input type="radio" name="term_meta[randomizeAnswers]" id="term_meta[randomizeAnswers]1"
			       value="yes" <?php if ($term_meta['randomizeAnswers'] == "yes") {
				echo 'checked';
			} ?>><label for="term_meta[randomizeAnswers]1"><span></span> Yes</label><br/>
			<input type="radio" name="term_meta[randomizeAnswers]" id="term_meta[randomizeAnswers]2"
			       value="no" <?php if ($term_meta['randomizeAnswers'] == "no") {
				echo 'checked';
			}
			if (!$term_meta['randomizeAnswers']) {
				echo 'checked';
			} ?>><label for="term_meta[randomizeAnswers]2"><span></span> No</label>
			<p class="description small">This feature will randomize the order that each answer is displayed.</p>

		</td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top">
			<label><?php _e('Use Pool of Questions'); ?></label><br/>
			leave blank to disable
		</th>
		<td>
			<input class="widefat2" type="number" min="0" max="30" name="term_meta[pool]" id="term_meta[pool]"
			       value="<?php echo $term_meta['pool'] ? $term_meta['pool'] : '0'; ?>" size="3"/><br/>
			<p class="description">Enter how many questions to grab. </p>
			<p class="description small"><strong>Please note</strong> that this feature CANNOT be used with WP
				Pagination. This is a limiation of WordPress.</p>
			<p class="description small">If used, this feature will randomly grab the amount of questions entered from
				the total amount of questions in that quiz. <strong>Example:</strong> If your quiz has 100 questions but
				you want the quiz to only contain 20 questions chosen at random.</p>

		</td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top">
			<label><?php _e('WP Pagination'); ?></label><br/>
			leave blank to disable
		</th>
		<td>
			<input class="widefat2" type="number" min="0" max="30" name="term_meta[paginate]" id="term_meta[paginate]"
			       value="<?php echo $term_meta['paginate'] ? $term_meta['paginate'] : '0'; ?>" size="3"/><br/>
			<p class="description">Enter how many questions per page. </p>
			<p class="description small"><strong>Please note</strong> that this feature should really only be used if
				you want to force page refreshes for ad revenue or similar.</p>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top">
			<label><?php _e('Category Shortcode'); ?></label>
		</th>
		<td>
			<p>Use the following shortcode to render this quiz:<br/> <code>[HDquiz quiz =
					"<?php echo $_GET["tag_ID"]; ?>"]</code></p>
			<p>The quiz will comprise of any questions attached to this quiz.</p>
			<hr/>
		</td>
	</tr>
	
	<?php
}


add_action('quiz_edit_form_fields', 'quiz_taxonomy_custom_fields', 10, 2);


// A callback function to save our extra taxonomy field(s)
function save_taxonomy_custom_fields($term_id)
{
	if (isset($_POST['term_meta'])) {
		$t_id = $term_id;
		$term_meta = get_option("taxonomy_term_$t_id");
		$cat_keys = array_keys($_POST['term_meta']);
		foreach ($cat_keys as $key) {
			if (isset($_POST['term_meta'][$key])) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		//save the option array
		update_option("taxonomy_term_$t_id", $term_meta);
	}
}

add_action('edited_quiz', 'save_taxonomy_custom_fields', 10, 2);
add_filter('manage_edit-quiz_columns', 'riv_quiz_columns', 5);
add_action('manage_quiz_custom_column', 'riv_quiz_custom_columns', 5, 3);

function riv_quiz_columns($defaults)
{
	unset($defaults['description']);
	unset($defaults['slug']);
	$defaults['riv_quiz_ids'] = __('Shortcode');
	return $defaults;
}

function riv_quiz_custom_columns($value, $column_name, $id)
{
	if ($column_name == 'riv_quiz_ids') {
		return '[HDquiz quiz = "' . (int)$id . '"]';
	}
}

add_action('admin_footer-edit-tags.php', 'hdQu_remove_cat_tag_description');

function hdQu_remove_cat_tag_description()
{
	global $current_screen;
	switch ($current_screen->id) {
		case 'edit-tags.php?taxonomy=question-category':
			break;
	}
	?>
	<?php
}


/* Register Custom Questionnaire Meta
________________________________________________________
-------------------------------------------------------- */

add_action('load-post.php', 'hdQue_post_meta_boxes_setup');
add_action('load-post-new.php', 'hdQue_post_meta_boxes_setup');


function hdQue_post_meta_boxes_setup()
{
	add_action('add_meta_boxes', 'hdQue_add_post_meta_boxes');
	add_action('save_post', 'hdQue_save_post_class_meta', 10, 2);
}

function hdQue_add_post_meta_boxes()
{
	add_meta_box(
		'hdQue-post-class',
		esc_html__('Answers', 'example'),
		'hdQue_post_class_meta_box',
		'post_type_question',
		'normal',
		'default'
	);
}

function hdQue_post_class_meta_box($object, $box)
{ ?>
	<?php wp_nonce_field(basename(__FILE__), 'hdQue_post_class_nonce'); ?>
	
	<?php
	wp_enqueue_media();
	?>
	<table width="100%" cellspacing="0" cellpadding="0" id="hdQuestionnaire">
		<tr>
			<td valign="top">1</td>
			<td valign="top" style="position: relative;">
				<input class="widefat" type="text" required name="hdQue-post-class1" id="hdQue-post-class1"
				       value="<?php echo esc_attr(get_post_meta($object->ID, 'hdQue_post_class1', true)); ?>"
				       size="30"/>
			</td>
			<td valign="top">
				<input type="hidden" name="hdQue-post-class2" value="no">
				<input type="radio" class="answer" name="hdQue-post-class2" id="hdQue-post-class2"
				       value="1" <?php if (esc_attr(get_post_meta($object->ID, 'hdQue_post_class2', true)) == "1") {
					echo 'checked';
				} ?>>
			</td>
		</tr>
		<tr>
			<td valign="top">2</td>
			<td valign="top" style="position: relative;">
				<input class="widefat" type="text" required name="hdQue-post-class3" id="hdQue-post-class3"
				       value="<?php echo esc_attr(get_post_meta($object->ID, 'hdQue_post_class3', true)); ?>"
				       size="30"/>
			</td>
			<td valign="top">
				<input type="radio" class="answer" name="hdQue-post-class2" id="hdQue-post-class2"
				       value="2" <?php if (esc_attr(get_post_meta($object->ID, 'hdQue_post_class2', true)) == "2") {
					echo 'checked';
				} ?>></td>
		</tr>
		<tr>
			<td valign="top">3</td>
			<td valign="top" style="position: relative;">
				<input class="widefat" type="text" required name="hdQue-post-class4" id="hdQue-post-class4"
				       value="<?php echo esc_attr(get_post_meta($object->ID, 'hdQue_post_class4', true)); ?>"
				       size="30"/>
			</td>
			<td valign="top">
				<input type="radio" class="answer" name="hdQue-post-class2" id="hdQue-post-class2"
				       value="3" <?php if (esc_attr(get_post_meta($object->ID, 'hdQue_post_class2', true)) == "3") {
					echo 'checked';
				} ?>></td>
		</tr>
	</table>
	<br/>
	<label for="wtt_question_description">Description<br/>
		<?php wp_editor(htmlspecialchars_decode(nl2br(esc_attr(get_post_meta($object->ID, 'wtt_question_description', true)))), "wtt_question_description", array('textarea_name' => 'wtt_question_description', 'teeny' => true, 'media_buttons' => false, 'textarea_rows' => 3, 'quicktags' => false, 'tinymce' => false)); ?>
	</label>
<?php }


function hdQue_save_post_class_meta($post_id, $post)
{
	
	if (!isset($_POST['hdQue_post_class_nonce']) || !wp_verify_nonce($_POST['hdQue_post_class_nonce'], basename(__FILE__)))
		return $post_id;
	
	$post_type = get_post_type_object($post->post_type);
	
	if (!current_user_can($post_type->cap->edit_post, $post_id))
		return $post_id;
	
	$meta_key = array();
	$new_meta_value = array();
	
	for ($i = 1; $i <= 4; $i++) {
		if(!isset($_POST['hdQue-post-class' . $i])) continue;
		$new_meta_value[$i] = $_POST['hdQue-post-class' . $i];
		$meta_key[$i] = 'hdQue_post_class' . $i;
		$meta_value[$i] = get_post_meta($post_id, $meta_key[$i], true);
		
		if ($new_meta_value[$i] && '' == $meta_value[$i])
			add_post_meta($post_id, $meta_key[$i], $new_meta_value[$i], true);
		elseif ($new_meta_value[$i] && $new_meta_value[$i] != $meta_value[$i])
			update_post_meta($post_id, $meta_key[$i], $new_meta_value[$i]);

		elseif ('' == $new_meta_value[$i] && $meta_value[$i])
			delete_post_meta($post_id, $meta_key[$i], $meta_value[$i]);
	}
	
//	wtt_question_description
	if(isset($_POST['wtt_question_description'])){
		$new_description = $_POST['wtt_question_description'];
		$old_description = get_post_meta($post_id, 'wtt_question_description', true);
		
		if ($new_description && '' == $old_description)
			add_post_meta($post_id, 'wtt_question_description', $new_description, true);
		elseif ($new_description && $new_description != $old_description)
			update_post_meta($post_id, 'wtt_question_description', $new_description);

		elseif ('' == $new_description && $old_description)
			delete_post_meta($post_id, 'wtt_question_description', $old_description);
	}
}
