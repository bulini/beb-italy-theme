<?php 
//update_option('siteurl','http://testing.dev/wpsendit_227');
//update_option('home','http://testing.dev/wpsendit_227');

include('libs/metaboxes/accommodation.php');

include('libs/theme-customization.php');
include('libs/theme-init.php');


function get_topmost_parent($post_id){
  $parent_id = get_post($post_id)->post_parent;
  if($parent_id == 0){
    return $post_id;
  }else{
    return get_topmost_parent($parent_id);
  }
}

/* Accommodations stuff */

function test_ajax()
{
	$serialized=explode('&',$_POST['data']);
	
	foreach($serialized as $k=>$v):
		$property[$k]=$v;
	endforeach;
	
	//variabili x inserimento ROOM
	$room_title=$property[0];
	$room_title=explode('=',$property[0]);
	$room_title=$room_title[1];
	//posti
	$room_maxpax=$property[1];
	$room_maxpax=explode('=',$property[1]);
	$room_maxpax=$room_maxpax[1];

	//BAMBINI DI DIO........
	

	$room_maxchildren=$property[2];
	$room_maxchildren=explode('=',$property[2]);
	$room_maxchildren=$room_maxchildren[1];



	$room_allottments=$property[3];
	$room_allottments=explode('=',$property[3]);
	$room_allottments=$room_allottments[1];
	
	$room_parent=$property[4];
	$room_parent=explode('=',$property[4]);
	$room_parent=$room_parent[1];

	$room_booking=$property[5];
	$room_booking=explode('=',$property[5]);
	$room_booking=$room_booking[1];

	
	//titolo stanza composto da nome hotel + stanza x evitare duplicati...
	$main_name=get_the_title($room_parent);
	
			// ADD THE FORM INPUT TO $new_post ARRAY
		$room_title=esc_attr(strip_tags($room_title));
		
		$new_room = array(
		'post_title'	=>	$main_name.' - '.$room_title,
		'post_author'	=>	$user_id,
		'post_parent'	=>	$room_parent,
		'post_status'	=>	'publish',           // Choose: publish, preview, future, draft, etc.
		'post_type'	=>	'properties'  //'post',page' or use a custom post type if you want to
		);
	
		//SAVE THE POST
		
		$rid = wp_insert_post($new_room);
		
		update_post_meta($rid, 'bookandpay_maxpeople', $room_maxpax);
		update_post_meta($rid, 'bookandpay_maxchildren', $room_maxchildren);

		update_post_meta($rid, 'bookandpay_allottments', $room_allottments);
		update_post_meta($rid, 'bookandpay_instant_booking', $room_booking);
		update_post_meta($rid, 'bookandpay_instant_booking', $room_booking);
		//da dinamicizzare per altri siti
		update_post_meta($rid, 'bookandpay_price_rule', 'price_for_property');
		update_post_meta($rid, 'bookandpay_enabled', 'on');


								
		//esco l ultima riga inserita
		get_property($rid);
}



add_action('wp_ajax_test_ajax', 'test_ajax');
add_action('wp_ajax_nopriv_test_ajax', 'test_ajax');

add_action('wp_head','ajaxurl');

function ajaxurl() {
?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php
}






function has_properties($id)
{
	$properties=get_posts('post_type=properties&post_parent='.$id);
	return count($properties);
}

function get_properties($id)
{
	$url = get_bloginfo('siteurl');
	$properties=get_posts('post_type=properties&post_parent='.$id);
	//print_r($properties);
	$i=0;
	if($properties) {  ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Sistemazioni</th><th>Posti letto</th><th>Bambini</th><th>Quantit&agrave;</th><th></th>
			</tr>
		</thead>
		<tbody>
<?php
	foreach($properties as $property):
		$max_pax=get_post_meta($property->ID, 'bookandpay_maxpeople', true);
		$max_children=get_post_meta($property->ID, 'bookandpay_maxchildren', true);

		$allottments=get_post_meta($property->ID, 'bookandpay_allottments', true);
		$i++;

?>
 <tr>
          <td>
           	<?php echo $property->post_title; ?>
          </td>
          <td>
           	<?php echo $max_pax; ?>
          </td>
          <td>
           	<?php echo $max_children; ?>
          </td>
          <td>
           	<?php echo $allottments; ?>
          </td>
          <td>
           <i class="general foundicon-calendar"><span class="has-tip" data-width="210" title="Da qui puoi impostare la disponibilit&agrave; periodica della stanza"> <a href="<?php bloginfo('siteurl');?>/owner-panel/edit-availability-property/?prop_id=<?php echo $property->ID ?>">disponibilit&agrave;</a></i> 
            | <a href="<?php bloginfo('siteurl');?>/owner-panel/edit-prices/?prop_id=<?php echo $property->ID ?>">prezzi</a></span>
            | <?php 
	            
	            
		            echo '<a href="';
		            echo wp_nonce_url("$url/wp-admin/post.php?post=$property->ID&action=delete", 'delete-post_' . $property->ID);
		            echo '">Elimina</a>';
		      
            ?>
          </td>

 </tr>
<?php endforeach; ?>
</tbody>
</table>
<?php 
	}
}






function get_property($id)
{
	$property=get_post($id);
	if($property) { 
		$max_pax=get_post_meta($property->ID, 'bookandpay_maxpeople', true);
		$max_children=get_post_meta($property->ID, 'bookandpay_maxchildren', true);
		$allottments=get_post_meta($property->ID, 'bookandpay_allottments', true);

?>
	<tr>
		<td>
           	<?php echo $property->post_title; ?>
          </td>
          <td>
           	<?php echo $max_pax; ?>
          </td>
          <td>
           	<?php echo $max_children; ?>
          </td>
          <td>
           	<?php echo $allottments; ?>
          </td>
          <td>
           <i class="general foundicon-calendar"><span class="has-tip" data-width="210" title="Da qui puoi impostare la disponibilit&agrave; periodica della stanza"> <a href="<?php bloginfo('siteurl');?>/owner-panel/edit-availability-property/?prop_id=<?php echo $property->ID ?>">disponibilit&agrave;</a></i> 
            | <a href="<?php bloginfo('siteurl');?>/owner-panel/edit-prices/?prop_id=<?php echo $property->ID ?>">prezzi</a></span>
            | <?php 
	            
	            
		            echo '<a href="';
		            echo wp_nonce_url("$url/wp-admin/post.php?post=$property->ID&action=delete", 'delete-post_' . $property->ID);
		            echo '">Elimina</a>';
		      
            ?>
          </td>

 </tr>

<?php 
	}
	
}


/*frontend single post room */

function get_rooms($id)
{
	$properties=get_posts('post_type=properties&post_parent='.$id);
	//print_r($properties);
	$i=0;
	?>
	<table style="width:100%;">
		<thead>
			<tr>
				<th>Camera</th>
				<th>Posti</th>
				<th>Prezzo</th>
			</tr>
		</thead>
		<tbody>
	<?php

	foreach($properties as $property):
		$max_pax=get_post_meta($property->ID, 'bookandpay_maxpeople', true);
		$allottments=get_post_meta($property->ID, 'bookandpay_allottments', true);
		$i++;

?>
 <tr>
          <td>
           	<?php echo $property->post_title; ?>
          </td>
          <td>
           	<?php echo $max_pax; ?>
          </td>
          <td>
	      	&euro;<?php echo room_lowest_price($property->ID); ?> 
	      </td>

 </tr>
<?php endforeach; ?>
		</tbody>
	</table>
<?php }

function room_exists($name,$parent)
{
	global $wpdb;
	return $wpdb->get_row("SELECT * FROM wp_posts WHERE post_parent = '" . $parent . "' AND post_title = '" . $title_str . "'", 'ARRAY_A');
}


class MyCalendar
{
    // helper function to find the number of weeks in a  month
   // you'll need this in the HTML loop that will be shown after the code
    function num_weeks($month, $year)
    {
        $num_weeks=4;
    
        $first_day = $this->first_day($month, $year);  
        
        // if the first week doesn't start on monday 
        // we are sure that the month has at minimum 5 weeks
        if($first_day!=1) $num_weeks++;
        
        $widows=$first_day-1;  
        $fw_days=7-$widows;
        if($fw_days==7) $fw_days=0;       
        
        $numdays=date("t",mktime(2, 0, 0, $month, 1, $year));
        
        if( ($numdays - $fw_days) > 28 ) $num_weeks++;
        
        return $num_weeks;                  
    }
    
    // this method returns array with the days 
    // in a given week. Always starts from Monday and return 7 numbers
    // if the day is there, returns the date, otherwise returns zero
    // very useful to build the empty cells of the calendar
    function days($month, $year, $week, $num_weeks=0)
    {
        $days=array();
        
        if($num_weeks==0) $num_weeks=$this->num_weeks($month, $year);
        
        // find which day of the week is 1st of the given month        
        $first_day = $this->first_day($month, $year);
                
        // find widow days (first week)
        $widows=$first_day-1;
        
        // first week days
        $fw_days=7-$widows;
        
        // if $week==1 don't do further calculations
        if($week==1)
        {
            for($i=0;$i<$widows;$i++) $days[]=0;            
            for($i=1;$i<=$fw_days;$i++) $days[]=$i;            
            return $days;
        }
        
        // any other week
        if($week!=$num_weeks)
        {
            $first=$fw_days+(($week-2)*7);
            for($i=$first+1;$i<=$first+7;$i++) $days[]=$i;            
            return $days;
        }
        
        
        # only last week calculations below
        
        // number of days in the month
        $numdays=date("t",mktime(2, 0, 0, $month, 1, $year));
                
        // find orphan days (last week)  
        $orphans=$numdays-$fw_days-(($num_weeks-2)*7);                     
        $empty=7-$orphans;
        for($i=($numdays-$orphans)+1;$i<=$numdays;$i++) $days[]=$i;
        for($i=0;$i<$empty;$i++) $days[]=0;
        return $days;
    }
    
   // finds which day of the week is the first day of the month
    function first_day($month, $year)
    {
        $first_day= date("w", mktime(2, 0, 0, $month, 1, $year));
        if($first_day==0) $first_day=7; # convert Sunday
        
        return $first_day;
    }
}

	function stars($post_id='')
	{
		global $post;		
		
		if($post_id!='')
		{
			$post_id=$post_id;	
		} else {
			$post_id=$post->ID;	
		
		}

		$stars_array = wp_get_post_terms($post_id, 'stars', array("fields" => "names"));
		$stars=$stars_array[0];
		
		if((int) $stars>0):
			for($i=1;$i<=(int)$stars;$i++){
				echo '&#9733; ';
			}
		endif;		
	}

	function get_stars($post_id='')
	{
		global $post;		
		
		if($post_id!='')
		{
			$post_id=$post_id;	
		} else {
			$post_id=$post->ID;	
		
		}

		$stars_array = wp_get_post_terms($post_id, 'stars', array("fields" => "names"));
		$stars=$stars_array[0];
		$markup='';
		if((int) $stars>0):
			for($i=1;$i<=(int)$stars;$i++){
				$markup.= '&#9733; ';
			}
			return $markup;
		endif;		
	}



	function room_lowest_price($id)
	{
		global $wpdb;
		$price_row=$wpdb->get_row("SELECT * FROM wp_property_prices WHERE post_id = $id and from_date >= DATE(CURDATE()) order by adult_price desc limit 0,1");
		$price=$price_row->adult_price;
		return $price;
	}





/* end accommodations */






function myplugin_add_meta_box() {

	$screens = array( 'accommodations','post');

	foreach ( $screens as $screen ) {

		add_meta_box(
			'myplugin_sectionid',
			__( 'Localize it', 'myplugin_textdomain' ),
			'myplugin_meta_box_callback',
			$screen
		);
	}
}
add_action( 'add_meta_boxes', 'myplugin_add_meta_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function myplugin_meta_box_callback( $post ) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'address_meta_box_nonce', 'address_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$address = get_post_meta( $post->ID, 'address', true );
	$lat = get_post_meta( $post->ID, 'lat', true );
	$lng = get_post_meta( $post->ID, 'lng', true );
	$formatted_address = get_post_meta( $post->ID, 'formatted_address', true );
	$html= '<label for="address">';
	$html.=_e( 'Address', 'myplugin_textdomain' );
	$html.= '</label>';
	$html.= '<input type="text" id="geocomplete" name="address" value="' . esc_attr( $address ) . '" size="60" /><br />';
    $html.= '  <fieldset>											   ';
    $html.= '    <label>Latitude</label>							   ';
    $html.= '    <input name="lat" type="text" value="' . esc_attr( $lat ) . '">			   ';
    $html.= '  													   ';
    $html.= '    <label>Longitude</label>							   ';
    $html.= '    <input name="lng" type="text" value="' . esc_attr( $lng ) . '">			   ';
    $html.= '  													   ';
    $html.= '    <label>Formatted Address</label>					   ';
    $html.= '    <input name="formatted_address" type="text" value="' . esc_attr( $formatted_address ) . '">';
    $html.= '  </fieldset>';





	$html.= '<div class="map_canvas" style="height:400px;width:800px; margin: 10px 20px 10px 0; border:1px solid #ccc;"></div>';
	$html.='<a id="reset" href="#" style="display:none;">Reset Marker</a>';
	echo $html;
}


/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function address_save( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['address_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['address_meta_box_nonce'], 'address_meta_box_nonce' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'accommodations' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( ! isset( $_POST['address'] ) ) {
		return;
	}

	// Sanitize user input.
	$address = sanitize_text_field( $_POST['address'] );
	$lat = sanitize_text_field( $_POST['lat'] );
	$lng = sanitize_text_field( $_POST['lng'] );
	$formatted_address = sanitize_text_field( $_POST['formatted_address'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, 'address', $address );
	update_post_meta( $post_id, 'lat', $lat );
	update_post_meta( $post_id, 'lng', $lng );
	update_post_meta( $post_id, 'formatted_address', $formatted_address );

}
add_action( 'save_post', 'address_save' );





function the_post_thumbnail_caption() {
  global $post;

  $thumbnail_id    = get_post_thumbnail_id($post->ID);
  $thumbnail_image = get_posts(array('p' => $thumbnail_id, 'post_type' => 'attachment'));

  if ($thumbnail_image) {
    echo '<span class="caption"><strong>'.$thumbnail_image[0]->post_excerpt.'</strong></span>';
  }
}





//comments

add_filter( 'comment_form_default_fields', 'bootstrap3_comment_form_fields' );
function bootstrap3_comment_form_fields( $fields ) {
    $commenter = wp_get_current_commenter();
    
    $req      = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $html5    = current_theme_supports( 'html5', 'comment-form' ) ? 1 : 0;
    
    $fields   =  array(
        'author' => '<div class="form-group comment-form-author">' . '<label for="author">' . __( 'Name' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
                    '<input class="form-control" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>',
        'email'  => '<div class="form-group comment-form-email"><label for="email">' . __( 'Email' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
                    '<input class="form-control" id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></div>',
        'url'    => '<div class="form-group comment-form-url"><label for="url">' . __( 'Website' ) . '</label> ' .
                    '<input class="form-control" id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></div>',
    );
    
    return $fields;
}




add_filter( 'comment_form_defaults', 'bootstrap3_comment_form' );
function bootstrap3_comment_form( $args ) {
    $args['comment_field'] = '<div class="form-group comment-form-comment">
            <label for="comment">' . _x( 'Comment', 'noun' ) . '</label> 
            <textarea class="form-control" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
        </div>';
    return $args;
}




//navbar hack
add_filter('body_class', 'mbe_body_class');

function mbe_body_class($classes){
    if(is_user_logged_in()){
        $classes[] = 'body-logged-in';
    } else{
        $classes[] = 'body-logged-out';
    }
    return $classes;
}



add_action('wp_head', 'mbe_wp_head');
function mbe_wp_head(){
    echo '<style>'.PHP_EOL;
    //echo 'body{ padding-top: 70px !important; }'.PHP_EOL;
    // Using custom CSS class name.
    echo 'body.body-logged-in .navbar-fixed-top{ top: 28px !important; }'.PHP_EOL;
    // Using WordPress default CSS class name.
    echo 'body.logged-in .navbar-fixed-top{ top: 28px !important; }'.PHP_EOL;
    echo '</style>'.PHP_EOL;
}



function exclude_widget_categories($args){
$exclude = "18"; // The IDs of the excluding categories
$args["exclude"] = $exclude;
return $args;
}
add_filter("widget_categories_args","exclude_widget_categories");




	


	function add_google_fonts() {
 
		wp_register_style('GoogleFonts', 'http://fonts.googleapis.com/css?family=Lato|Tangerine'); 
		wp_enqueue_style('GoogleFonts');
 
	}
 
	add_action('wp_print_styles', 'add_google_fonts');
	
	$args = array(
	'name'          => __( 'right', 'theme_text_domain' ),
	'id'            => 'right-page-sidebar',
	'description'   => '',
    'class'         => '',
	'before_widget' => '<li id="%1$s" class="widget %2$s"><div class="panel panel-default">
  <div class="panel-body">',
	'after_widget'  => '</div></div></li>',
	'before_title'  => '<h4>',
	'after_title'   => '</h4>' );
	
	register_sidebar( $args );
	$args = array(
	'name'          => __( 'left', 'theme_text_domain' ),
	'id'            => 'left-page-sidebar',
	'description'   => '',
        'class'         => '',
	//'before_widget' => '<li id="%1$s" class="widget %2$s">',
	//'after_widget'  => '</li>',
	'before_title'  => '<h3 class="widgettitle">',
	'after_title'   => '</h3>' );
	
	register_sidebar( $args );

	$args = array(
	'name'          => __( 'top', 'theme_text_domain' ),
	'id'            => 'top-page-sidebar',
	'description'   => '',
        'class'         => '',
	//'before_widget' => '<li id="%1$s" class="widget %2$s">',
	//'after_widget'  => '</li>',
	'before_title'  => '',
	'after_title'   => '' );
	
	register_sidebar( $args );




class wp_bootstrap_navwalker extends Walker_Nav_Menu {

        /**
         * @see Walker::start_lvl()
         * @since 3.0.0
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param int $depth Depth of page. Used for padding.
         */
        public function start_lvl( &$output, $depth = 0, $args = array() ) {
                $indent = str_repeat( "\t", $depth );
                $output .= "\n$indent<ul role=\"menu\" class=\" dropdown-menu\">\n";
        }

        /**
         * @see Walker::start_el()
         * @since 3.0.0
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param object $item Menu item data object.
         * @param int $depth Depth of menu item. Used for padding.
         * @param int $current_page Menu item ID.
         * @param object $args
         */
        public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
                $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

                /**
                 * Dividers, Headers or Disabled
                 * =============================
                 * Determine whether the item is a Divider, Header, Disabled or regular
                 * menu item. To prevent errors we use the strcasecmp() function to so a
                 * comparison that is not case sensitive. The strcasecmp() function returns
                 * a 0 if the strings are equal.
                 */
                if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1 ) {
                        $output .= $indent . '<li role="presentation" class="divider">';
                } else if ( strcasecmp( $item->title, 'divider') == 0 && $depth === 1 ) {
                        $output .= $indent . '<li role="presentation" class="divider">';
                } else if ( strcasecmp( $item->attr_title, 'dropdown-header') == 0 && $depth === 1 ) {
                        $output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_attr( $item->title );
                } else if ( strcasecmp($item->attr_title, 'disabled' ) == 0 ) {
                        $output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr( $item->title ) . '</a>';
                } else {

                        $class_names = $value = '';

                        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
                        $classes[] = 'menu-item-' . $item->ID;

                        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

                        if ( $args->has_children )
                                $class_names .= ' dropdown';

                        if ( in_array( 'current-menu-item', $classes ) )
                                $class_names .= ' active';

                        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

                        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
                        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

                        $output .= $indent . '<li' . $id . $value . $class_names .'>';

                        $atts = array();
                        $atts['title']  = ! empty( $item->title )        ? $item->title        : '';
                        $atts['target'] = ! empty( $item->target )        ? $item->target        : '';
                        $atts['rel']    = ! empty( $item->xfn )                ? $item->xfn        : '';

                        // If item has_children add atts to a.
                        if ( $args->has_children && $depth === 0 ) {
                                $atts['href']                   = '#';
                                $atts['data-toggle']        = 'dropdown';
                                $atts['class']                        = 'dropdown-toggle';
                        } else {
                                $atts['href'] = ! empty( $item->url ) ? $item->url : '';
                        }

                        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

                        $attributes = '';
                        foreach ( $atts as $attr => $value ) {
                                if ( ! empty( $value ) ) {
                                        $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                                        $attributes .= ' ' . $attr . '="' . $value . '"';
                                }
                        }

                        $item_output = $args->before;

                        /*
                         * Glyphicons
                         * ===========
                         * Since the the menu item is NOT a Divider or Header we check the see
                         * if there is a value in the attr_title property. If the attr_title
                         * property is NOT null we apply it as the class name for the glyphicon.
                         */
                        if ( ! empty( $item->attr_title ) )
                                $item_output .= '<a'. $attributes .'><span class="glyphicon ' . esc_attr( $item->attr_title ) . '"></span>&nbsp;';
                        else
                                $item_output .= '<a'. $attributes .'>';

                        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
                        $item_output .= ( $args->has_children && 0 === $depth ) ? ' <span class="caret"></span></a>' : '</a>';
                        $item_output .= $args->after;

                        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
                }
        }

        /**
         * Traverse elements to create list from elements.
         *
         * Display one element if the element doesn't have any children otherwise,
         * display the element and its children. Will only traverse up to the max
         * depth and no ignore elements under that depth.
         *
         * This method shouldn't be called directly, use the walk() method instead.
         *
         * @see Walker::start_el()
         * @since 2.5.0
         *
         * @param object $element Data object
         * @param array $children_elements List of elements to continue traversing.
         * @param int $max_depth Max depth to traverse.
         * @param int $depth Depth of current element.
         * @param array $args
         * @param string $output Passed by reference. Used to append additional content.
         * @return null Null on failure with no changes to parameters.
         */
        public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
        if ( ! $element )
            return;

        $id_field = $this->db_fields['id'];

        // Display this element.
        if ( is_object( $args[0] ) )
           $args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );

        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

        /**
         * Menu Fallback
         * =============
         * If this function is assigned to the wp_nav_menu's fallback_cb variable
         * and a manu has not been assigned to the theme location in the WordPress
         * menu manager the function with display nothing to a non-logged in user,
         * and will add a link to the WordPress menu manager if logged in as an admin.
         *
         * @param array $args passed from the wp_nav_menu function.
         *
         */
        public static function fallback( $args ) {
                if ( current_user_can( 'manage_options' ) ) {

                        extract( $args );

                        $fb_output = null;

                        if ( $container ) {
                                $fb_output = '<' . $container;

                                if ( $container_id )
                                        $fb_output .= ' id="' . $container_id . '"';

                                if ( $container_class )
                                        $fb_output .= ' class="' . $container_class . '"';

                                $fb_output .= '>';
                        }

                        $fb_output .= '<ul';

                        if ( $menu_id )
                                $fb_output .= ' id="' . $menu_id . '"';

                        if ( $menu_class )
                                $fb_output .= ' class="' . $menu_class . '"';

                        $fb_output .= '>';
                        $fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
                        $fb_output .= '</ul>';

                        if ( $container )
                                $fb_output .= '</' . $container . '>';

                        echo $fb_output;
                }
        }
}


function OneGallery($id)
{
	
	
	$args = array(
		'post_type' => 'attachment',
		'numberposts' => $limit,
		'orderby' => 'menu_order',
		'post_parent' => $id
	); 
	$attachments = get_posts($args);

	return $attachments;


}  

function prices_table($post_ID)
{

	$max_pax=get_post_meta($post_ID,'bookandpay_maxpeople',true);

	$bookandpay_price_rule=get_post_meta($post_ID,'bookandpay_price_rule',true);
	
	//echo $bookandpay_price_rule;
	
	if($bookandpay_price_rule=='price_for_person'):
	
	?>

		<h5><?php _e("<!--:it-->Prezzi a persona<!--:--><!--:en-->Rates per person<!--:-->"); ?></h5>
 			
 			<table id="rates" class="table table-hover table-striped">
 			<tr>
 			<thead>
 				<th><?php _e("<!--:it-->Persone<!--:--><!--:en-->People<!--:-->"); ?></th>

 				<?php for($i=1;$i<=$max_pax;$i++): ?>
 					<th><?php echo $i; ?> <?php echo get_post_meta($post_ID,"apartment-name-".$i,true); ?></th>
 				<?php endfor; ?>
 			</tr>
 			</thead>
 			<tbody>
 			<tr>
 			<td><?php _e("<!--:it-->Bassa Stagione<!--:--><!--:en-->Low Season<!--:-->"); ?></td>
 			<?php for($i=1;$i<=$max_pax;$i++): ?>

 				<td>&euro; <?php echo get_post_meta($post_ID,"apartment-rate-1-".$i,true); ?></td>
 			<?php endfor; ?> 			
 			</tr>

 			<tr>
 			<td><?php _e("<!--:it-->Media Stagione<!--:--><!--:en-->Medium Season<!--:-->"); ?></td>
 			<?php for($i=1;$i<=$max_pax;$i++): ?>
 				<td><?php echo get_post_meta($post_ID,"apartment-rate-2-".$i,true); ?></td>
 			<?php endfor; ?> 			
 			</tr>

 			<tr>
 			<td><?php _e("<!--:it-->Alta Stagione<!--:--><!--:en-->High Season<!--:-->"); ?></td>
 			<?php for($i=1;$i<=$max_pax;$i++): ?>
 				<td><?php echo get_post_meta($post_ID,"apartment-rate-3-".$i,true); ?></td>
 			<?php endfor; ?> 			
 			</tr>

 			</tbody>
 			
 			</table>
 			
 		<?php else: ?>
			<h5><?php _e("<!--:it-->Prezzi<!--:--><!--:en-->Property Rates<!--:-->"); ?></h5>
 			
 			<table id="rates" class="table table-hover table-striped">
 			<tr>
 			<thead>
 				<th><?php _e("<!--:it-->Prezzi alloggio per notte<!--:--><!--:en-->Daily property price<!--:-->"); ?></th>
 				<?php for($i=$max_pax;$i<=$max_pax;$i++): ?>
 					<th><?php for($i=1;$i<=$max_pax;$i++): ?><i class="foundicon-person"></i><?php endfor; ?></th>
 				<?php endfor; ?>
 			</tr>
 			</thead>
 			<tbody>
 			<tr>
 			<td><?php _e("<!--:it-->Bassa Stagione<!--:--><!--:en-->Low Season<!--:-->"); ?></td>
 			<?php for($i=$max_pax;$i<=$max_pax;$i++): ?>
 				<td>&euro; <?php echo get_post_meta($post_ID,"apartment-rate-1-".$i,true); ?></td>
 			<?php endfor; ?> 			
 			</tr>

 			<tr>
 			<td><?php _e("<!--:it-->Media Stagione<!--:--><!--:en-->Media Season<!--:-->"); ?></td>
 			<?php for($i=$max_pax;$i<=$max_pax;$i++): ?>
 				<td>&euro; <?php echo get_post_meta($post_ID,"apartment-rate-2-".$i,true); ?></td>
 			<?php endfor; ?> 			
 			</tr>

 			<tr>
 			<td><?php _e("<!--:it-->Alta Stagione<!--:--><!--:en-->High Season<!--:-->"); ?></td>
 			<?php for($i=$max_pax;$i<=$max_pax;$i++): ?>
 				<td>&euro; <?php echo get_post_meta($post_ID,"apartment-rate-3-".$i,true); ?></td>
 			<?php endfor; ?> 			
 			</tr>

 			</tbody>
 			
 			</table> 		
 		
 		<?php endif; ?>

<?php }



?>