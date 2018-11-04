<?php
function add_metabox_question_level()
{
	add_meta_box('question_level', 'Level', 'question_level_callback', 'post_type_question', 'advanced', 'high',
		array(1, 2, 3,4,5,6,7,8,9,10)
	);
}

add_action('add_meta_boxes', 'add_metabox_question_level');


function question_level_callback($post, $metabox)
{
	// Input hidden security
	wp_nonce_field(basename(__FILE__), "meta-box-question-level-nonce");
	?>
	<select name="meta-box-question-level">
		<?php
		// List level
		$option_values = array(1, 2, 3,4,5,6,7,8,9,10);
		
		// Get data in database
		$question_level = get_post_meta($post->ID, "meta-box-question-level", true);
		
		// Add selected
		foreach($option_values as $key => $value)
		{
			if($value == $question_level)
			{
				?>
				<option selected value="<?php echo $value; ?>"><?php echo $value; ?></option>
				<?php
			}
			else
			{
				?>
				<option value="<?php echo $value; ?>"><?php echo $value; ?></option>
				<?php
			}
		}
		?>
	</select>
	<?php
}


function save_metabox_question_level($post_id, $post, $update)
{
	// Đây chính là input hidden Security mà ta đã tạo ở hàm show_metabox_contain
	if (!isset($_POST["meta-box-question-level"]) || !wp_verify_nonce($_POST["meta-box-question-level-nonce"], basename(__FILE__)))
	{
		return $post_id;
	}
	
	// Kiểm tra quyền
	if(!current_user_can("edit_post", $post_id))
	{
		return $post_id;
	}
	
	// Nếu auto save thì không làm gì cả
	if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
	{
		return $post_id;
	}
	
	if('post_type_question' != $post->post_type){
		return $post_id;
	}
	
	
	// Lấy thông tin từ client
	$metabox_question_level = (isset($_POST["meta-box-question-level"])) ? $_POST["meta-box-question-level"] : '';
	
	// Cập nhật thông tin, hàm này sẽ tạo mới nếu như trong db chưa tồn tại
	update_post_meta($post_id, "meta-box-question-level", $metabox_question_level);
}

add_action('save_post', 'save_metabox_question_level', 10, 3);







/**
 * Adds "Import" button on module list page
 */
function addCustomImportButton()
{
	global $current_screen;
	
	// Not our post type, exit earlier
	// You can remove this if condition if you don't have any specific post type to restrict to.
	if ('module' != $current_screen->post_type) {
		return;
	}
	
	?>
	<script type="text/javascript">
        jQuery(document).ready( function($)
        {
            jQuery(jQuery(".wrap h2")[0]).append("<a  id='doc_popup' class='add-new-h2'>Import</a>");
        });
	</script>
	<?php
}






add_action('admin_head-edit.php','addCustomImportButton');


add_filter('views_edit-post','my_filter');
add_filter('views_edit-page','my_filter');

function my_filter($views){
	$views['import'] = '<a href="#" class="primary">Import</a>';
	return $views;
}