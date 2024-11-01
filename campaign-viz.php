<?php
/*
Plugin Name: Campaign Coverage Visualizations
Plugin URI: 
Description: Uses custom post types, custom taxonomies, and the DayLife API to create a searchable database of candidates, insertable into posts via infobox
Author: Team Awesome
Version: .12
Author URI: 
*/

/**
 * @author Benjamin J. Balter
 * @package ccviz
 * @since .1
 */
 
/**
 * Grab the DayLifeAPI wrapper
 * @since .1
 */
require_once('daylife-api.php');

/**
 * Registers candidate custom post type
 * @since .1
 */
function ccviz_register_cpt() {

	//setup global array with our meta values
	global $ccviz_meta;
	$ccviz_meta[] = 'ccviz_opponent';
	$ccviz_meta[] = 'ccviz_mentions';
	$ccviz_meta[] = 'ccviz_title_mentions';
	$ccviz_meta[] = 'fec_id';
	
	//create candidate custom post type
	$labels = array(
	  'name' => 'Candidates',
	  'singular_name' => 'Candidate',
	  'add_new' => _x('Add New', 'candidate'),
	  'add_new_item' => 'Add New Candidate',
	  'edit_item' => __('Edit Candidate'),
	  'new_item' => __('New Candidate'),
	  'view_item' => __('View Candidate'),
	  'search_items' => __('Search Candidates'),
	  'not_found' =>  __('No candidates found'),
	  'not_found_in_trash' => __('No candidates found in Trash'), 
	  'parent_item_colon' => ''
	);
	$args = array(
	  'labels' => $labels,
	  'public' => true,
	  'publicly_queryable' => true,
	  'show_ui' => true, 
	  'query_var' => true,
	  'rewrite' => array('slug'=>'candidates'),
	  'capability_type' => 'post',
	  'hierarchical' => false,
	  'menu_position' => null,
	  'register_meta_box_cb' => 'ccviz_meta_cb',
	  'supports' => array('title','editor','author','thumbnail','excerpt','comments','trackbacks','custom-fields','revisions')
	); 
	register_post_type('ccviz_candidate',$args);

}

add_action('init','ccviz_register_cpt');

/**
 * Register custom taxonomies
 * @since .1
 * @todo exclusive taxonomies
 */
function ccviz_register_taxonomies() {
	
	//create global array of all our taxonomies. This will allow us to make exclusive taxonomies
	global $ccviz_taxonomies;
	
	$ccviz_taxonomies[] = array( 
				'name' => 'ccviz_political_party',
				'exclusive' => 'true',
				'hierarchical' => 'true',
				'query_var' => 'true',
				'rewrite' => 'true',
				'defaults' => array('Republican','Democrat','Tea Party'),
				'labels' => array(
					 	   'name' => _x( 'Political Party', 'taxonomy general name' ),
					 	   'singular_name' => _x( 'Political Party', 'taxonomy singular name' ),
					 	   'search_items' =>  __( 'Search Political Parties' ),
					 	   'all_items' => __( 'All Political Parties' ),
					 	   'parent_item' => __( 'Parent Political Party' ),
					 	   'parent_item_colon' => __( 'Parent Political Party:' ),
					 	   'edit_item' => __( 'Edit Political Party' ), 
					 	   'update_item' => __( 'Update Political Party' ),
					 	   'add_new_item' => __( 'Add New Political Party' ),
					 	   'new_item_name' => __( 'New Political Party Name' ),
					 	 ),
				);
	$ccviz_taxonomies[] = array( 
				'name' => 'ccviz_office',
				'exclusive' => 'false',
				'hierarchical' => 'false',
				'query_var' => 'true',
				'rewrite' => 'true',
				'defaults' => array('Senate','House','Gubernatorial'),
				'labels' => array(
					 	   'name' => _x( 'Office', 'taxonomy general name' ),
					 	   'singular_name' => _x( 'Office', 'taxonomy singular name' ),
					 	   'search_items' =>  __( 'Search Offices' ),
					 	   'all_items' => __( 'All Offices' ),
					 	   'parent_item' => __( 'Parent Office' ),
					 	   'parent_item_colon' => __( 'Parent Office:' ),
					 	   'edit_item' => __( 'Edit Office' ), 
					 	   'update_item' => __( 'Update Office' ),
					 	   'add_new_item' => __( 'Add New Office' ),
					 	   'new_item_name' => __( 'New Office' ),
					 	 ),
				);
	$ccviz_taxonomies[] = array( 
				'name' => 'ccviz_state',
				'exclusive' => 'false',
				'hierarchical' => 'false',
				'query_var' => 'true',
				'rewrite' => 'true',
				'defaults' => array(),
				'labels' => array(
					 	   'name' => _x( 'State', 'taxonomy general name' ),
					 	   'singular_name' => _x( 'State', 'taxonomy singular name' ),
					 	   'search_items' =>  __( 'Search States' ),
					 	   'all_items' => __( 'All States' ),
					 	   'parent_item' => __( 'Parent State' ),
					 	   'parent_item_colon' => __( 'Parent State:' ),
					 	   'edit_item' => __( 'Edit State' ), 
					 	   'update_item' => __( 'Update State' ),
					 	   'add_new_item' => __( 'Add New State' ),
					 	   'new_item_name' => __( 'New State' ),
					 	 ),
				);
	$ccviz_taxonomies[] = array( 
				'name' => 'ccviz_district',
				'exclusive' => 'true',
				'hierarchical' => 'true',
				'query_var' => 'true',
				'rewrite' => 'true',
				'defaults' => array('No','First Term','Two or More Terms'),
				'labels' => array(
					 	   'name' => _x( 'District', 'taxonomy general name' ),
					 	   'singular_name' => _x( 'District', 'taxonomy singular name' ),
					 	   'search_items' =>  __( 'Search Districts' ),
					 	   'all_items' => __( 'All Districts' ),
					 	   'parent_item' => __( 'Parent District' ),
					 	   'parent_item_colon' => __( 'Parent District:' ),
					 	   'edit_item' => __( 'Edit District' ), 
					 	   'update_item' => __( 'Update District' ),
					 	   'add_new_item' => __( 'Add New District' ),
					 	   'new_item_name' => __( 'New District' ),
					 	 ),
				);	
	$ccviz_taxonomies[] = array( 
				'name' => 'ccviz_incumbancy',
				'exclusive' => 'true',
				'hierarchical' => 'true',
				'query_var' => 'true',
				'rewrite' => 'true',
				'defaults' => array('Incumbant','Challenger'),
				'labels' => array(
					 	   'name' => _x( 'Incumbency', 'taxonomy general name' ),
					 	   'singular_name' => _x( 'Incumbency', 'taxonomy singular name' ),
					 	   'search_items' =>  __( 'Search Incumbency'),
					 	   'all_items' => __( 'All Incumbency' ),
					 	   'parent_item' => __( 'Parent Incumbency' ),
					 	   'parent_item_colon' => __( 'Parent Incumbency:' ),
					 	   'edit_item' => __( 'Edit Incumbency' ), 
					 	   'update_item' => __( 'Update Incumbency' ),
					 	   'add_new_item' => __( 'Add New Incumbency' ),
					 	   'new_item_name' => __( 'New Incumbency' ),
					 	 ),
				);	
	$ccviz_taxonomies[] = array( 
				'name' => 'ccviz_cash_on_hand',
				'exclusive' => 'true',
				'hierarchical' => 'true',
				'query_var' => 'true',
				'rewrite' => 'true',
				'defaults' => array('1','2','3'),
				'labels' => array(
					 	   'name' => _x( 'Cash On Hand', 'taxonomy general name' ),
					 	   'singular_name' => _x( 'Cash On Hand', 'taxonomy singular name' ),
					 	   'search_items' =>  __( 'Search Cash On Hand' ),
					 	   'all_items' => __( 'All Cash  On Hand' ),
					 	   'parent_item' => __( 'Parent Cash  On Hand' ),
					 	   'parent_item_colon' => __( 'Parent Cash On Hand:' ),
					 	   'edit_item' => __( 'Edit Cash On Hand' ), 
					 	   'update_item' => __( 'Update Cash On Hand' ),
					 	   'add_new_item' => __( 'Add New Cash On Hand' ),
					 	   'new_item_name' => __( 'New Cash On Hand' ),
					 	 ),
				);	
				
	//loop through our taxonomy array and create the taxonomies								
	foreach ($ccviz_taxonomies as $taxonomy) {
		register_taxonomy( 
			$taxonomy['name'], 
			'ccviz_candidate', 
			array( 
				'hierarchical' => $taxonomy['hierarchical'], 
				'labels' => $taxonomy['labels'], 
				'query_var' => $taxonomy['query_var'], 
				'rewrite' => $taxnomy['rewrite'] 
			) 
		);
	}

}

add_action('init','ccviz_register_taxonomies');


/**
 * Adds our plugin options (API keys) to the admin backcend
 * @since .1
 */
function ccviz_options_init() {
    register_setting( 'ccviz_options', 'ccviz_options' );
}

add_action('admin_init','ccviz_options_init');


/**
 * Function to retrieve (and unserialize) our options
 * @since .1
 * @returns array assoc. array of options
 */
function ccviz_get_options() {
	return get_option('ccviz_options');
}

/**
 * Create options menu
 * @since .1
 * @todo links to get the API keys
 */
function ccviz_options_menu() {
?>
<div class="wrap">
	<h2>Campaign Coverage Visualization Options</h2>
	<form method="post" action='options.php'>
<?php

	//provide feedback
	settings_errors();
	
	//Tell WP that we are on the ccviz_options page
	settings_fields( 'ccviz_options' ); 
	
	//Pull the existing options from the DB
	$options = ccviz_get_options();
?>
<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="ccviz_options[daylife_key]">DayLife Key:</label></th>
			<td>
				<input name="ccviz_options[daylife_key]" type="text" id="ccviz_options[daylife_key]" value="<?php echo $options['daylife_key']; ?>" class="regular-text" />
				<span class="description">DayLife API Key <a href="">Get one here</a></span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="ccviz_options[daylife_key]">DayLife Secret:</label></th>
			<td>
				<input name="ccviz_options[daylife_secret]" type="text" id="ccviz_options[daylife_secret]" value="<?php echo $options['daylife_secret']; ?>" class="regular-text" />
				<span class="description">DayLife API Shared Secret <a href="">Get one here</a></span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="ccviz_options[opensecrets_key]">Open Secrets Key:</label></th>
			<td>
				<input name="ccviz_options[opensecrets_key]" type="text" id="ccviz_options[opensecrets_key]" value="<?php echo $options['nyt_key']; ?>" class="regular-text" />
				<span class="description">Open Secrets API Key <a href="">Get one here</a></span>
			</td>
		</tr>
</table>
	<p class="submit">
         <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>
	</form>
<?php
}

/*
 * Add options menu to candidates menu
 * @since .1
 */
function ccviz_menu_init() {
    add_submenu_page( 'edit.php?post_type=ccviz_candidate', 'Campaign Vizualization Options', 'Options', 'manage_options', 'ccviz_options_menu', 'ccviz_options_menu' );
}

add_action('admin_menu','ccviz_menu_init');

/*
 * Loop through each meta field and add to our custom metabox
 * @todo fancier labels
 * @since .1
 * @param object $post the post being edited
 */
function ccviz_add_candidate_meta($post) {
	global $ccviz_meta;	
	
	foreach ($ccviz_meta as $meta) {
		echo '<label for="' . $meta . '">' . $meta . ':</label> <input type="text" name="' . $meta . '" id="' . $meta . '" value="'.	get_post_meta( $post->ID, $meta, true ) .'" /><br />';
	}
	
}

/**
 * Callback to add our metabox to the edit candidate page
 * @since .1
 */
function ccviz_meta_cb() {
	add_meta_box( 'ccviz_meta_div', 'Meta Data', 'ccviz_add_candidate_meta', 'ccviz_candidate', 'normal', 'high');
}

/**
 * Saves custom metaboxes on candidate save
 * @param int $post_id the ID of the post being created/edited
 * @since .1
 */
function ccviz_save_meta( $post_id) {
	global $ccviz_meta;	

	foreach ($ccviz_meta as $meta) {
  		update_post_meta( $post_id, $meta, $_POST[$meta] );
	}
}

add_action( 'save_post', 'ccviz_save_meta' );

/** 
 * Build custom query to retrieve candidates
 * @since .1
 * @returns object WordPress Query
 */
function ccviz_query() {
	$args = array('post_type' => 'ccviz_candidate',	'nopaging' => true);
	$query = new wp_query($args);
	return $query;
}

/**
 * Creates searchbox and results
 * @since .1
 */
function ccviz_search_box() {
	global $ccviz_taxonomies; ?>
	<div id="ccviz_search">
	<form method='post'>
		<?php
		foreach ($ccviz_taxonomies as $tax) {
		$terms = get_terms($tax['name']);
		?>
		<div class="ccviz_taxonomy" id="<?php echo $tax['name']; ?>" style="float:left; padding: 10px;">
		<h3><?php echo $tax['labels']['name']; ?></h3>
		<ul>
		<?php foreach ($terms as $term) { ?>
			<li><input type="checkbox" name="<?php echo $tax['Name']; ?>" id ="<?php echo $tax['Name']; ?>" /> <label for="<?php echo $tax['Name']; ?>" value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></li>
		<?php } ?>
		</ul>
		</div>
		<?php
	}
?>
	<label for="candidate">Candidate Name: </label><input type="text" name="candidate" id="candidate">
	<input type='submit' value="Go" />
	</form>
	</div>
<?php
}

/**
 * Returns (and updates if necessary) daylifeID
 * @param int $post_id ID of current post(candidate)
 * @since .1
 * @returns int DayLife Topic ID
 */
function ccviz_get_daylife_id($post_id) {
	
	//get stored topicID
	$topic_id = get_post_meta( $post_id,'ccviz_daylife_id',true);
	
	//if we don't have a topicID
	if ( !$topic_id ) {
	
		//call the API wrapper
		global $daylifeAPI;
		
		//call
		$topic = $daylifeAPI->call("topic", "getInfo", array("name" => get_the_title( $post_id ) ) );
		
		//grab the ID returned
		$topic_id = $topic['topic'][0]['topic_id'];
		
		//store ID
		update_post_meta($post_id,'ccviz_daylife_id',$topic_id);
	}
	
	//return ID
	return $topic_id;
}

/**
 * Handles shortcode to display searcbox and list candidates
 * @todo pagination of results
 * @since .1
 */
function ccviz_list_cb() {
	ccviz_search_box();
	$posts = ccviz_query();
	if ( $posts->have_posts() ) : while ( $posts->have_posts() ) : $posts->the_post();
	?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<div class="entry-content">
				<?php global $post; $image = get_post_meta($post->ID, 'ccviz_image_url', true); ?>
				<?php if ( $image ) { ?>
				<img src="<?php echo $image; ?>" style="float:left; padding-right: 10px;"/>
				<?php } ?>
				<?php the_content(); ?>
			</div><!-- .entry-content -->
		</div><!-- #post-## -->

	<?php
	endwhile; endif;
}

add_shortcode('ccviz_list_candidates','ccviz_list_cb');

/**
 * Handles shortcode to display infobox
 * @since .1
 * @params array $atts Array of shortcode attributes
 */
function ccviz_box($atts) {
	global $daylifeAPI;
	
	//grab topic ID to pass to profile
	$topic_id = ccviz_get_daylife_id($atts['candidate']);
	
	//include profile drop-in
	include('profile.php');
}

add_shortcode('ccviz_box','ccviz_box');

/**
 * Tells WP to queue up javascript and CSS files
 */
function ccviz_enqueue() {
	wp_enqueue_script('ccviz_js', WP_PLUGIN_URL . '/campaign-viz/candidate.js', array('jquery'), '1.0' );
	wp_enqueue_style('ccviz_css', WP_PLUGIN_URL . '/campaign-viz/candidate.css' );
}

add_action('init','ccviz_enqueue');

/**
 * Filter to insert infobox when candidate page is being displayed
 * @param string $content post content
 * @returns string content w/ infobox or just original content
 * @since .1
 */
function ccviz_include_box_filter($content) {
	global $post;
	
	//if this is not a candidate page, return the original content (don't filter)
	if ($post->post_type != 'ccviz_candidate') return $content;
	
	//if this is not a single post, return original content (don't filter)
	if (!is_single()) return $content;
	
	//append shortcode before content
	return '[ccviz_box candidate="'.$post->ID.'"]' . $content;
}

//bug, displays on list view, temporarily disabled
//add_filter('the_content','ccviz_include_box_filter');

/**
 * Initialized DayLifeAPI wrapper on WP init
 */
function ccviz_init_daylife_api() {

	//get API Keys
	$options = ccviz_get_options();
	
	//set global scope
	global $daylifeAPI;
	
	//construct class
	$daylifeAPI = new DayLifeAPI($options['daylife_key'],$options['daylife_secret']);
	
}

add_action('init','ccviz_init_daylife_api');

?>