<?php 
/*
Template Name: New bb
*/
get_header(); 
?>
<?php get_template_part('page-carousel'); wp_reset_query(); ?>
	<!-- Main blog .container -->
	<div class="container">
	

	
	
	<!-- Teasers right side wrapper col-->
		<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7" >
			<div class="row margin-top-10">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<h1 class="page-title"><?php the_title(); ?></h1>
				</div>

				<hr class="hr-sm">
				<?php 
				
				while ( have_posts() ) : the_post(); ?>						
				<!-- ITEM-->		
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-10 col-lg-12">
						<?php
						if ( is_user_logged_in() ) { ?>
							
						<?php the_content(); ?>
							
						<?php } else {
						$args = array(
					        'echo'           => true,
					        'redirect'       => get_permalink( $post->ID), 
					        'form_id'        => 'loginform',
					        'label_username' => __( 'Username' ),
					        'label_password' => __( 'Password' ),
					        'label_remember' => __( 'Remember Me' ),
					        'label_log_in'   => __( 'Log In' ),
					        'id_username'    => 'user_login',
					        'id_password'    => 'user_pass',
					        'id_remember'    => 'rememberme',
					        'id_submit'      => 'wp-submit',
					        'remember'       => true,
					        'value_username' => NULL,
					        'value_remember' => false);
											
							wp_login_form($args); 						
							}
						?>
						
						</div>

					</div>		
				<!-- /ITEM-->
				<?php endwhile; ?>
				
		</div>
	 </div>
	 <div id="sidemap" class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
	 <h1>Ciaone</h1>
	 <div class="panel">
	 	<div class="map_canvas" style="height:400px;width:100%; margin: 10px 20px 10px 0; border:1px solid #ccc;"></div>
	 	<a id="reset" href="#" style="display:none;">Reset Marker</a>	 	
	 </div>

	 </div>
	<!-- /Main blog .container -->
    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->

</div>
<!-- /.container-->

	<?php get_template_part('pre-footer');?>
	<?php get_footer(); ?>