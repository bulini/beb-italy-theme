jQuery('.input').addClass('form-control');
jQuery('.cmb_text_medium').addClass('form-control');
jQuery('.cmb_text_medium').addClass('form-control');
jQuery('.cmb_textarea_small').addClass('form-control');
jQuery('.cmb_select').addClass('form-control');
jQuery('.button-primary').addClass('btn btn-success');
jQuery('.button').addClass('btn btn-success');

jQuery('#addcamere').click(function() {
   	jQuery.post(ajaxurl, { action: 'test_ajax', data:jQuery("#room_form").serialize() }, function(output) {
       jQuery('#wait_room').hide();
       jQuery('#resultcamere').append(output);
        jQuery('#resultcamere').fadeIn('slow');
       });
  });
    
jQuery('.remImage').click(function() {
var attID = jQuery(this).attr('name');
jQuery.ajax({
    type: 'post',
    url: '/wp-admin/admin-ajax.php',
    data: {
        action: 'delete_attachment',
        att_ID: jQuery(this).attr('name'),
        _ajax_nonce: jQuery('#nonce').val(),
        post_type: 'attachment'
    },
    success: function() {
        alert('#file-'+attID+' cancellato con successo');
        //console.log('#file-'+attID)
        jQuery('#file-'+attID).fadeOut();    
    }
 });
 });





jQuery(window).load(function(){
   jQuery('.flexslider').flexslider({
      animation: "fade",
      controlNav: "thumbnails",
      start: function(slider){
      jQuery('body').removeClass('loading');
        }
      });
 });
 
 
 		
jQuery(document).ready(function(){
	jQuery('.toggler').click(function(){
	jQuery('.gmappanel').removeClass('hide-map').addClass('show-map').css('height', '600');
	jQuery('.toggler').css('display', 'none');
	
	jQuery('.toggler-hide').css('display', 'block');
	address_field = jQuery('#map').attr("data-address");
	lat_field = jQuery('#map').attr("data-lat");
	lng_field = jQuery('#map').attr("data-lng");

	//alert(address_field);
			var $map = jQuery('#map');
			google.maps.event.addDomListener(window, 'resize', function() {
				map.setCenter(homeLatlng);
			});
			if( $map.length ) {
	
				$map.gMap({
					address: address_field,
					zoom: 14,
					markers: [
						{ 'latitude': lat_field, 'longitude': lng_field, 'html': '<h5>'+address_field+'</h5>'}
					]
				});
	
			}
	
	
	});
	jQuery('.toggler-hide').click(function(){
	jQuery('.gmappanel').removeClass('show-map').addClass('hide-map').css('height', '0');
	jQuery('.toggler').css('display', 'block');
	jQuery('.toggler-hide').css('display', 'none');
	});
});


      jQuery(function(){
        jQuery("#address").geocomplete({
          map: ".map_canvas",
          details: "form ",
          markerOptions: {
            draggable: true
          }
        });
        
        jQuery("#address").bind("geocode:dragged", function(event, latLng){
          jQuery("input[name=lat]").val(latLng.lat());
          jQuery("input[name=lng]").val(latLng.lng());
          jQuery("#reset").show();
        });
        
        
        jQuery("#reset").click(function(){
          jQuery("#address").geocomplete("resetMarker");
          jQuery("#reset").hide();
          return false;
        });
        
        jQuery("#find").click(function(){
          jQuery("#address").trigger("geocode");
        }).click();
      });
