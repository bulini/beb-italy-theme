<?php /* Template Name: Edit Posts */ 

$query = new WP_Query(array('post_type' => 'accommodations', 'posts_per_page' =>'-1', 'post_status' => array('publish', 'pending', 'draft', 'private', 'trash') ) );

if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
	
	if(isset($_GET['prop_id'])) {
		
		if($_GET['prop_id'] == $post->ID)
		{
			$current_post = $post->ID;

			$title = get_the_title();
			$content = get_the_content();

		}
	}

endwhile; endif;
wp_reset_query();

global $current_post;

$postTitleError = '';

if(isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

	if(trim($_POST['postTitle']) === '') {
		$postTitleError = 'Please enter a title.';
		$hasError = true;
	} else {
		$postTitle = trim($_POST['postTitle']);
	}

	$post_information = array(
		'ID' => $current_post,
		'post_title' => esc_attr(strip_tags($_POST['postTitle'])),
		'post_content' => esc_attr(strip_tags($_POST['postContent'])),
		'post-type' => 'post',
		'post_status' => 'pending'
	);

	$post_id = wp_update_post($post_information);

	if($post_id)
	{
		wp_redirect(home_url());
		exit;
	}

}

?>

<?php get_header(); ?>

	<!-- #primary BEGIN -->
	<div class="row room-content">
	 <div class="twelve columns">

		<form action="" id="primaryPostForm" method="POST">

			<fieldset>

				<label for="postTitle"><?php _e('Post\'s Title:', 'framework') ?></label>

				<input type="text" name="postTitle" id="postTitle" value="<?php echo $title; ?>" class="required" />

			</fieldset>

			<?php if($postTitleError != '') { ?>
				<span class="error"><?php echo $postTitleError; ?></span>
				<div class="clearfix"></div>
			<?php } ?>

			<fieldset>
						
				<label for="postContent"><?php _e('Post\'s Content:', 'framework') ?></label>

				<textarea name="postContent" id="postContent" rows="8" cols="30"><?php echo $content; ?></textarea>

			</fieldset>

			<fieldset>
				
				<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>

				<input type="hidden" name="submitted" id="submitted" value="true" />
				<button type="submit"><?php _e('Update Post', 'framework') ?></button>

			</fieldset>

		</form>


	</div><!-- #primary END -->
	</div>

<?php get_footer(); ?>