<?php get_header();
$options=load_theme_options();
 ?>
<?php /* Template Name: Edit property room */ 

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

//print_r($_POST['property']); 
//die();

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
		'post_status' => 'publish'
	);

	$post_id = wp_update_post($post_information);


	if($post_id)
	{
		//print_r($_POST['property_name']);
		
		/*
		foreach($_POST['property']['name'] as $property):

			// Create post object
			$my_post = array(
			  'post_title'    => $property,
			  'post_type'    => 'properties',
			  'post_content'  => $property,
			  'post_status'   => 'publish',
			  'post_parent'   => $post_id			  
			  );
		
		if(!room_exists($property,$post_id)):
			// Insert the post into the database
			$prop_id=wp_insert_post( $my_post );
			
			update_post_meta($prop_id, 'bookandpay_allottments', $_POST['property']['allottments']);
			
		else:
			wp_update_post( $my_post );
		endif;
		
		
		
		endforeach;
		*/
		//die();
		wp_redirect('edit-property-allottments?prop_id='.$post_id.'&saved=true');
		exit;
	}
	


}

?>

		<?php get_template_part('header-logged'); ?>
		
			<!-- #primary BEGIN -->

		<div class="container">

		<div class="row">		
		 <div class="col-md-12">			
			 <h3 class="subheader">Gestione camere - <?php echo $title; ?></h3>
			 <?php get_template_part('inc/nav-accommodations'); ?>
		 <p class="bg-warning">Inserisci le tipologie di alloggio che puoi offrire, se sei un hotel inserisci ad esempio camera doppia, camera singola ed il quantitativo di camere in allottment. Se sei una casa vacanze o un bed and breakfast, inserisci camera doppiao camera singola o appartamento intero. Idem per agriturismi e altre tipologie di alloggi.</p>

		 </div>
		</div>
	<div class="row room-content">
	 <div class="col-md-12">

	 
	 

		<form action="" id="room_form" method="POST" class="form-inline">

				<legend>Tipologie di alloggio e trattamento</legend>
				<div class="row">
					<div class="form-group">
						<input type="text" placeholder="Nome o tipologia alloggio" name="property_name" id="property_name" value="" class="form-control"/>
					</div>
					<div class="form-group">
						<input type="text" placeholder="Ospiti" name="property_maxpax"  id="property_maxpax" value="" class="form-control"/>
					</div>
					<div class="form-group">
						<input type="text" placeholder="Bambini" name="property_maxchildren"  id="property_maxchildren" value="" class="form-control" />
					</div>
					<div class="form-group">
						<input type="text" placeholder="quantit&agrave; disponibile"  id="property_allottments" name="property_allottments" value="" class="form-control" />
					</div>
					<div class="form-group">
						<input type="hidden" placeholder="parente" name="parent_accommodation" value="<?php echo $_GET['prop_id']; ?>" />
						<input type="hidden" name="bookandpay_instant_booking" value="<?php echo get_post_meta($_GET['prop_id'],'bookandpay_instant_booking',true); ?>" />
						
						<a id="addcamere" class="button">inserisci</a>
            		</div>
            	</div>
				

				<br />

				<div id="camerepresenti"><?php get_properties($current_post); ?>
					<table id="resultcamere" style="display:none;width:100%" class="table table-striped">
						
					</table>
				</div>
				

			<fieldset>
				<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>

				<input type="hidden" name="submitted" id="submitted" value="true" />
				<!--<button type="submit"><?php _e('Update Post', 'framework') ?></button>-->

			</fieldset>

		</form>


	</div><!-- #primary END -->
	</div>
</div>
<?php get_footer(); ?>