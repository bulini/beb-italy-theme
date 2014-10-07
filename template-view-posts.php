<?php 
if (!is_user_logged_in() ) { wp_redirect( wp_login_url(get_permalink( $post->ID )) ); exit;	} 
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
global $paged;
/* Template Name: View Posts */ 

get_header();
$options=load_theme_options();
?>
<?php //get_template_part('header-logged'); ?>
<div class="container" style="background:#f9f9f9;">
<!-- #primary BEGIN -->
	<div class="row">
		<?php get_template_part('inc/breadcrumb-owner'); ?>
	</div>
	  
<div class="row">
  <div class="col-md-2">
  	<h3>Istruzioni</h3>
  	<ul>
	  	<li>Prova</li>
	  	<li>Prova</li>
	  	<li>Prova</li>
	  	<li>Prova</li>
  	</ul>
  </div>
	 <div class="col-md-10">
<h3 class="subheader">Gestione strutture</h3>
<p>Visualizza 
<a href="#" id="only_published">Solo approvate</a> | <a href="#" id="only_pending">Da approvare</a>
</p>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th></th>
				<th>Nome</th>
				<th colspan="4">Azioni</th>

			</tr>
			</thead>
			<?php 
			//admin query vediamo tutto
			$user = $current_user->ID;
			//echo $user;
			if($user!=1):


			//utente vede solo le sue
					global $current_user;
					get_currentuserinfo();				
					$user = $current_user->ID;
					$query = new WP_Query( array(
					'author' => $user, // Show Posts made only by the current user.
					'post_type' => 'accommodations',
					'posts_per_page' =>'5',
					'paged' => $paged,
					'post_status' => array('publish', 'pending')
				)
			);
			
			else:
				$query = new WP_Query(array('post_type' => 'accommodations', 'paged' => $paged, 'posts_per_page' =>100, 'post_status' => array('publish', 'pending') ) );
			endif;
			
 ?>
			<tbody>
			<?php while ($query->have_posts()) : $query->the_post(); 
				$attr=array(
				'class'	=> "img-circle"

);
			?>
			<tr class="row_<?php echo get_post_status( get_the_ID() ) ?>">
				<td><?php the_post_thumbnail('thumbnail',$attr); ?></td>
				<td><b><a href="<?php echo get_permalink(get_the_ID()); ?>"><?php echo get_the_title(); ?></a> (<?php echo get_post_status( get_the_ID() ) ?>)</b></td>
				<td><a class="button small" href="<?php bloginfo('siteurl'); ?>/edit-property-services/?prop_id=<?php echo get_the_ID(); ?>">Modifica dati</a></td>
				<td><a class="button small" href="<?php bloginfo('siteurl'); ?>/edit-gallery-property/?prop_id=<?php echo get_the_ID(); ?>">Gestione fotografie</a></td>
				<td><a class="button small" href="<?php bloginfo('siteurl'); ?>/edit-property-allottments/?prop_id=<?php echo get_the_ID(); ?>">Camere - Alloggi</a></td>
				<td><?php if( !(get_post_status() == 'trash') ) : ?>
						<a class="button small" onclick="return confirm('Are you sure you wish to delete post: <?php echo get_the_title() ?>?')"href="<?php echo get_delete_post_link( get_the_ID() ); ?>">Elimina struttura</a>
					<?php endif; ?>
				</td>
			
				
			</tr>
			<tr class="row_<?php echo get_post_status( get_the_ID() ) ?>">
				<td colspan="7">			
<?php if(has_properties(get_the_ID())<1): ?><div class="alert alert-warning" role="alert">Attenzione!<br />Aggiungi subito almeno una camera altrimenti la tua struttura non verr&agrave; visualizzata!<?php endif; ?></div></td>
			</tr>
			<tr class="row_<?php echo get_post_status( get_the_ID() ) ?>">
			<td colspan="7"><?php get_properties(get_the_ID()); ?></td>
			</tr>


		<?php endwhile; ?>
			</tbody>
		</table>
		<div class="navigation">
			<?php echo paginate_links(); ?>
        </div>
		<?php 
			
		 ?>
	
			
</div><!-- #primary END -->

  </div>
</div>

<?php get_footer(); ?>