<?php /* Template Name: Edit prices for room */ 
get_header();

$bookingcal= new BookingCalendar();


if(isset($_GET['del_period'])) {
	$bookingcal->DeletePrice($_GET['del_period']);
}


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

if(isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {
	$bookingcal= new BookingCalendar();

		$bookingcal->SetPrice($current_post,$_POST['adult_price'],$_POST['children_price'],$_POST['bookandpaycalendar_checkin'],$_POST['bookandpaycalendar_checkout'],$_POST['offer_name'],$_POST['offer']);		
		echo 1;
}





?>

<?php get_template_part('header-logged'); ?>

<div class="container" style="background:#f9f9f9;">
	<div class="row">
		<?php get_template_part('inc/breadcrumb-owner'); ?>
	</div>

	<!-- #primary BEGIN -->
	<div class="row">
	 <div class="col-md-12">
		<h3 class="subheader"><?php echo $title; ?></h3>
	 </div>
	</div>

	<div class="row">
		<?php get_template_part('inc/nav-owner'); ?>
	</div>
	<div class="row">
	 <div class="col-md-12">
	
		<form action="" method="post">
		
		<span class="">
			<p>Per gestire i prezzi basta inserire data inizio data fine periodo e prezzo, potete anche inserire prezzi differenti per singoli giorni.</p>
		</span>
		<div class="row">

	        <div class="col-md-2">    
				   <input type="text" id="checkin" name="bookandpaycalendar_checkin" placeholder="Data inizio" class="datepicker" />            
	        </div>  
	
	        <div class="col-md-2">    
	         <input type="text" id="checkout" name="bookandpaycalendar_checkout" placeholder="Data fine" class="datepicker" />            
	        </div>  
	        <div class="col-md-2">
	        	<input type="text" id="adult_price" name="adult_price" placeholder="prezzo adulti"  />            
	        </div>
	    <!--
	        <div class="one columns">
	        	<input type="hidden" id="children_price" name="children_price" placeholder="prezzo bambini"  />            
	        </div>
	        -->
	        <div class="four columns">
	        	<input type="text" id="offer_name" name="offer_name" placeholder="Date un nome alla vostra offerta (es 3 notti 100 euro)"  />            
	        	<label>Selezionate se volete pubblicare questo periodo come offerta e date un nome identificativo</label><input type="checkbox" value="1" name="offer" id="offer" />
	        </div>


	        <div class="col-md-2">
	        	<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
	        	<input type="hidden" name="submitted" id="submitted" value="true" />

		        <input type="submit" value="salva prezzi" class="button" />
	        </div>
		</div>
		<div class="alert-box secondary">
				<i>Attenzione se una data risulta presente in pi&ugrave; periodi il sistema utilizzer&agrave; il prezzo pi&ugrave; recente</i>
				<a href="" class="close">&times;</a>
		</div>
		
		</fieldset>
		</form>
		
		<div id="prices_calendar"></div>
		
		
	</div><!-- #primary END -->
	</div>
</div>

<?php get_footer(); ?>