<div class="container">      
	<hr>
		<div class="row margin-top-10">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
										<ul class="list-inline text-sm">
											<?php 
												$terms = get_terms("areas");
												if ( !empty( $terms ) && !is_wp_error( $terms ) ){
													foreach ( $terms as $term ) { ?>
											<li><a href="<?php echo get_term_link( $term ); ?>" title="<?php echo get_term_link( $term ); ?>"><?php echo $term->name; ?></a></li>
											<?php  } 
											} ?>	
										</ul>
			</div>
		</div>



      <!-- FOOTER -->
      <footer>
        <p class="pull-right"><a class="btn btn-sm btn-default" href="#">Back to top</a></p>
        <p>&copy; 2014 <?php bloginfo('siteurl'); ?> | <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
      </footer>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster 
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/holder.js"></script>
    -->
    
    	<!-- FLEXSLIDER INIT SCRIPT-->

  	<!-- gMap PLUGIN -->


	
  <!-- INIT SCRIPT - show gMap onclick -->
<?php if(is_page('edit-availability-property')): 
	
	  $booking_engine = new BookingCalendar();
	  //$booked_dates = $booking_engine->booking_by_month('2013',$_GET['prop_id']);
	  //$booked_dates=$booking_engine->BookedNight($_GET['prop_id']);
	  $booked_dates=$booking_engine->GetOccupancy($_GET['prop_id']);
?>
<script>
 jQuery('#addcamere').click(function() {


       	jQuery.post(ajaxurl, { action: 'test_ajax', data:jQuery("#room_form").serialize() }, function(output) {
	       jQuery('#wait_room').hide();

	       jQuery('#resultcamere').append(output);

	        jQuery('#resultcamere').fadeIn('slow');
	       });
});
</script>
  <script>

	jQuery(document).ready(function() {
	
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		jQuery('#calendar').fullCalendar({

		
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
			editable: true,
			events: [
			
			<?php 
			foreach($booked_dates as $booking):
				//ciclare le request con start end e il gioco è fatto checkin checkout
				 ?>
				{
					title: '<?php echo $booking->payment_status ?> <?php echo $booking->contactsurname ?> <?php echo $booking->contactname ?> <?php echo $booking->total_price; ?> <?php echo $booking->notes; ?>',
					start: new Date(2014, <?php echo $booking_engine->date_from_mysql($booking->checkin,'m')-1; ?>, <?php echo $booking_engine->date_from_mysql($booking->checkin,'d'); ?>),
					end: new Date(2014, <?php echo $booking_engine->date_from_mysql($booking->checkout,'m')-1; ?>, <?php echo $booking_engine->date_from_mysql($booking->checkout,'d')-1; ?>)				

				},				
				<?php endforeach;
				
			?>

			]
		});
		
	});



</script>

<?php endif; ?>

<?php if(is_page('edit-prices')): 
	$_calendar = new MyCalendar();
	$month = '09';
	$num_weeks=$_calendar->num_weeks($month, 2014); // August 2012
	$days=$_calendar->days($month, 2014, $i, $num_weeks);

	
	$current_post = $_GET['prop_id'];
	$bookingcal= new BookingCalendar();
	$prices=$bookingcal->get_room_prices($current_post);
?>

<script>

	jQuery(document).ready(function() {
		
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();

		jQuery('#prices_calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
			allDay:true,
			defaultDate: date,
			editable: true,
			eventLimit: false, // allow "more" link when too many events
			events: [
			
			{
				title: '80',
				start: '2014-01-01',
				end: '2014-12-31'
			},
			
			<?php foreach($prices as $price): ?>
				{
					title: '<?php echo $price->adult_price; ?>',
					start: '<?php echo $price->from_date; ?>',
					end: '<?php echo $price->to_date; ?>'
				},
			<?php endforeach; ?>
			]
		});
		
	});

</script>



<?php endif; ?>



    
    <?php wp_footer(); ?>
    
  </body>
</html>
