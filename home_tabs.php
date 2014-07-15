<?php 
$options=load_theme_options();
?>
<ul id="myTab" class="nav nav-tabs">
	<li class="active"><a href="#mappa" data-toggle="tab">Find a place</a></li>
	<li><a href="#zone" data-toggle="tab">Places</a></li>
</ul>

  <div id="myTabContent" class="tab-content">
	<div class="tab-pane fade in active" id="mappa">
			<div class="row white">
				<div class="col-md-12">
					<h3>Suca</h3>				
				</div>
			</div>												

	</div>
	 
	<div class="tab-pane fade" id="zone">
		<div class="container white">
		<h3><?php echo $options['city-name']; ?></h3>
		<?php $terms = get_terms("areas");
		 	if ( !empty( $terms ) && !is_wp_error( $terms ) ){
		 		foreach ( $terms as $term ) { ?>
		
		<!-- ITEM-->
		<div class="row hov-action-border ">
	
			<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
				<h3><a href="<?php echo get_term_link( $term ); ?>" title=""><?php echo $term->name; ?></a> <small>B & B - Guesthouses</small></h3>
				<p><?php echo term_description($term->term_id,'areas');?></p>
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-left">
				<a class="btn btn-link" href="#" title="go" style="margin-top:20px;"><i class="icon-angle-right icon-3x text-info"></i></a>
			</div>
		</div>
		<!-- /ITEM-->
		<hr class="hr-sm">
		<?php    }
		 }
		?>	
	
	<!-- / ITEMS-->
		</div>
	</div>

  </div>