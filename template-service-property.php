<?php get_header();
$options=load_theme_options();
 ?>
<?php /* Template Name: Edit services room */ 



global $current_user;
get_currentuserinfo();
if (!is_user_logged_in() ) { wp_redirect( wp_login_url(get_permalink( $post->ID )) ); exit;	} 

//print_r($current_user);


global $current_post;

$postTitleError = '';

$id=$_GET['prop_id'];

$post_author=get_post($id);

$post_author=$post_author->post_author;


if (is_user_logged_in() && (current_user_can('edit_others_posts') || $current_user->ID == $post_author) ) :
		
		
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
				'ID' => $_GET['prop_id'],
				'post_title' => esc_attr(strip_tags($_POST['postTitle'])),
				//'post_content' => esc_attr(strip_tags($_POST['postContent'])),
				'post_content' => $_POST['postContent'],				
				'post-type' => 'post',
				'post_status' => 'publish'
			);
		
			$post_id = wp_update_post($post_information);
			update_post_meta($post_id, 'bookandpay_showprice', $_POST['bookandpay_showprice']);			
			update_post_meta($post_id, 'bookandpay_instant_booking', $_POST['bookandpay_instant_booking']);			
			update_post_meta($post_id, 'bookandpay_tripadvisor_code', $_POST['bookandpay_tripadvisor_code']);			

			
		/******************
		+++++	Salvo i servizi
		*****************/
			
			$services = explode(",", get_option('custom_services_list'));
			//print_r($services);
			foreach($services as $k=>$v):
				$k=sanitize_data($k);
				$v=sanitize_data($v);	
		  		update_post_meta($post_id, sanitize_data($v),sanitize_data($_POST[$v]));
			endforeach;
		
			update_post_meta($pid, 'bookandpay_owner_address', $_POST['indirizzo']);
			//prezzo da mostrare

		
		
		}
		
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
		
		
		?>
		
		<?php get_template_part('header-logged'); ?>
		
			<!-- #primary BEGIN -->
<div class="container">


		<div class="row">		
		 <div class="col-md-12">			
			 <h3 class="subheader"><?php echo $title; ?> - <a href="<?php echo get_permalink($current_post); ?>">visualizza</a></h3>
			 <?php get_template_part('inc/nav-accommodations'); ?>
		 </div>
		</div>
			<!-- #primary BEGIN -->
			<div class="row">
			<div class="col-md-3">
				<h3>Help</h3>
			</div>
			 <div class="col-md-9">
	 
				 
				<form action="" id="edit" method="POST" role="form">
					<?php if($post_id) { ?>
					<div class="success label">Servizi struttura aggiornata con successo!</div><?php } ?>
					
			
					<div class="form-group">		
						<label for="postTitle"><?php _e('Nome struttura:', 'framework') ?></label>		
						<input type="text" name="postTitle" id="postTitle" value="<?php echo $title; ?>" class="required form-control" />
					</div>
		
					<?php if($postTitleError != '') { ?>
						<span class="error"><?php echo $postTitleError; ?></span>
						<div class="clearfix"></div>
					<?php } ?>
		
					<div class="form-group">
								
						<label for="postContent"><?php _e('Descrizione:', 'framework') ?></label>
						<?php wp_editor($content, 'postContent' ); ?>
						
		
					</div>
					
					<div class="form-group">
					<label for="bookandpay_showprice">Prezzo a partire da:</label>
				<input type="text" class="two columns" name="bookandpay_showprice" id="bookandpay_showprice" value="<?php echo get_post_meta($current_post,'bookandpay_showprice',true); ?>" />

					</div>
					
					
					
					<div class="form-group">
					<legend>Modalit&agrave; di gestione richieste</legend>
					<label for="bookandpay_instant_booking">Instant Booking</label>
						<select name="bookandpay_instant_booking">
							<option selected="selected" value="<?php echo get_post_meta($current_post,'bookandpay_instant_booking',true); ?>"><?php echo get_post_meta($current_post,'bookandpay_instant_booking',true); ?></option>
							<option value="on">on</option>
							<option value="off">off</option>
						</select>

					</div>
						
					<div class="form-group">

							<legend>Sistemazioni offerte - <a href="<?php bloginfo('siteurl'); ?>/edit-property-allottments/?prop_id=<?php echo $current_post; ?>">Aggiungi tipologia</a></legend>	

						<?php echo get_properties($current_post); ?>

					</div>
					
					<div class="form-group">
							<legend>Selezionare i servizi presenti</legend>	
		
						<?php 
							//servizi 
							$services = explode(",", get_option('custom_services_list'));
					

							//print_r($services);
							foreach($services as $k=>$val):	
								$v=explode('|',$val);
								//if(qtrans_getLanguage()=='it') {$v=$v[0]; } else {$v=$v[1]; }
								$v=$v[0];
								if(get_post_meta($current_post,sanitize_data($val),true)=='has') {$checked=' CHECKED';} else { $checked='';}
						  		echo '<label for="'.sanitize_data($v).'"><input type="checkbox" '.$checked.' name="'.sanitize_data($val).'" value="has" /> '.__(unsanitize_data($v), 'custom_service_textdomain' ). '</label><br />';
							endforeach;

							
						?>
						
						
						
					</div>
					
					
					<div class="form-group">
						<legend>Tripadvisor Widget</legend>
							<textarea name="bookandpay_tripadvisor_code" id="bookandpay_tripadvisor_code" class="form-control"><?php echo get_post_meta($current_post,'bookandpay_tripadvisor_code',true); ?></textarea>
					</div>
					
					
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
						<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
		
						<input type="hidden" name="submitted" id="submitted" value="true" />
						<button type="submit" class="button large"><?php _e('aggiorna struttura', 'framework') ?></button>
		
					</div>
		
				</form>
		
			</div><!-- #primary END -->
			</div>
</div>
			<?php get_footer(); ?>
<?php else: //current user o edit otherpost ?>
<?php wp_redirect( home_url() ); exit; 
	endif;
?>
<?php get_footer(); ?>