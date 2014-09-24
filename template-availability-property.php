<?php 
get_header();

/* Template Name: Edit availability room */ 

$bookingcal= new BookingCalendar();

$query = new WP_Query(array('post_type' => 'properties', 'posts_per_page' =>'-1', 'post_status' => array('publish', 'pending', 'draft', 'private', 'trash') ) );

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


if(isset($_GET['del_req'])) {
	$bookingcal->DeleteOccupancy($_GET['del_req']);
}



if(isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {



	//ciclo l inserimento in base all allottment per portare a 3 inserendo 3 reuest di allottment 
	$allottments=get_post_meta($current_post, 'bookandpay_allottments', true);

	for($i=1;$i<=$allottments;$i++) {
		$bookingcal->InsertOccupancy($current_post);		
	}


//print_r($_POST);

}

$context='Gestione Calendario';
?>


<div class="container" style="background:#f9f9f9;">
	<!-- #primary BEGIN -->
	
	<div class="row">
		<?php get_template_part('inc/breadcrumb-owner'); ?>
	</div>	
	<div class="row">
		<?php get_template_part('inc/nav-owner'); ?>
	</div>
	
	<div class="row">
		 <div class="col-md-12">		
				Numero di unit&agrave; in allottment: 			

		<h3>Camere occupate</h3>
		<?php $occupancies=$bookingcal->GetOccupancy($current_post); 
			if(count($occupancies)>0): ?>
			<table class="table table-striped">
			<thead>
				<tr>
					<th>id</th>
					<th>Name</th>
					<th>dal</th>
					<th>al</th>
					<th>Status</th>
					<th>Azioni</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($occupancies as $occupancy): ?>
				<tr>
					<td><?php echo $occupancy->id_request; ?></td>
					<td><?php echo $occupancy->contactsurname ?> <?php echo $occupancy->contactname ?></td>
					<td><?php echo date("d/m/Y",strtotime($occupancy->checkin)); ?></td>
					<td><?php echo date("d/m/Y",strtotime($occupancy->checkout)); ?></td>
					<td><span class="round <?php echo $occupancy->payment_status ?> label"><?php echo $occupancy->payment_status ?></span></td>
					<td><a onclick="return confirm('sei sicuro di voler liberare la camera?')" href="<?php bloginfo('siteurl'); ?>/owner-panel/edit-availability-property?prop_id=<?php echo $_GET['prop_id']; ?>&del_req=<?php echo $occupancy->id_request; ?>">Elimina</a></td>
				</tr>
			<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
			</table>
		<hr />
		<form action="" method="post">

				
		
		<div class="well">			
				<p>Inserendo data inizio data fine e note del blocco, potrete bloccare la stanza</p>		
			<div class="row">
				<div class="col-md-6">		
			        <div class="col-md-6">    
						<input type="text" id="checkin" name="bookandpaycalendar_checkin" placeholder="Data inizio" class="datepicker" />            
			        </div>  
			        <div class="col-md-6">    
			         	<input type="text" id="checkout" name="bookandpaycalendar_checkout" placeholder="Data fine" class="datepicker" />            
			        </div>
				</div>
	
				<div class="col-md-6">			
					<div class="col-md-6">
				        <input type="text" id="note" name="owner_notes" placeholder="Motivo o nominativo (pieno chiuso)"  />            
				        <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>		
				    </div>			
				    <div class="col-md-6">
						<input type="hidden" name="submitted" id="submitted" value="true" />
						<input type="submit" value="blocca camera" class="button" />
				    </div>
				</div>			
			</div>
			
		</div>
		
		
		

		</form>
		<hr />
		<div id="calendar"></div>



	</div><!-- #primary END -->
	</div>
</div>
<?php get_footer(); ?>