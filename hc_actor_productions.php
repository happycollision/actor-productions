<?php
/*
Plugin Name: Happy Collision Actor Productions Post Type
Description: Creates a "Productions" post type for use on actor sites
Author: Don Denton
Version: 1.0
Author URI: http://happycollision.com
Depends: Simple Fields
*/

add_action('init', 'production_init');
function production_init() 
{
  $labels = array(
    'name' => _x('Productions', 'post type general name'),
    'singular_name' => _x('Production', 'post type singular name'),
    'add_new' => _x('Add New', 'Production'),
    'add_new_item' => __('Add New Production'),
    'edit_item' => __('Edit Production'),
    'new_item' => __('New Production'),
    'view_item' => __('View Production'),
    'search_items' => __('Search Productions'),
    'not_found' =>  __('No productions found'),
    'not_found_in_trash' => __('No productions found in Trash'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'hierarchical' => true,
    'menu_position' => null,
    'supports' => array('title','thumbnail','editor', 'excerpt'),
    'has_archive' => true
  ); 
  register_post_type('productions',$args);
}

//add filter to ensure the text Production, or production, is displayed when user updates a production 
add_filter('post_updated_messages', 'production_updated_messages');
function production_updated_messages( $messages ) {

  $messages['production'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Production updated. <a href="%s">View Production</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Production updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('production restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Production published. <a href="%s">View Production</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Production saved.'),
    8 => sprintf( __('Production submitted. <a target="_blank" href="%s">Preview production</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Production scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Production</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Production draft updated. <a target="_blank" href="%s">Preview Production</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

//hook into the init action and call create_production_taxonomies when it fires
add_action( 'init', 'create_productions_taxonomies', 0 );
function create_productions_taxonomies(){
  // Author: NOT hierarchical (like tags)
	$labels = array(
		'name' => _x( 'Production Company', 'taxonomy general name' ),
		'singular_name' => _x( 'Production Company', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Production Companies' ),
		'popular_items' => __( 'Popular Production Companies' ),
		'all_items' => __( 'All Production Companies' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Production Company' ), 
		'update_item' => __( 'Update Production Company' ),
		'add_new_item' => __( 'Add New Production Company' ),
		'new_item_name' => __( 'New Production Company' ),
		'separate_items_with_commas' => __( 'A successfully added company will appear below this text. Be sure to edit the details.' ),
		'add_or_remove_items' => __( 'Add or remove production company' ),
		'choose_from_most_used' => __( 'Choose from the most used production companies' )
	); 

    if(!taxonomy_exists('production_company')){
		register_taxonomy('production_company','productions',array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'company' ),
		));
	}

  // Author: NOT hierarchical (like tags)
	$labels = array(
		'name' => _x( 'Venues', 'taxonomy general name' ),
		'singular_name' => _x( 'Venue', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Venues' ),
		'popular_items' => __( 'Popular Venues' ),
		'all_items' => __( 'All Venues' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Venue' ), 
		'update_item' => __( 'Update Venue' ),
		'add_new_item' => __( 'Add New Venue' ),
		'new_item_name' => __( 'New Venue' ),
		'separate_items_with_commas' => __( 'Separate venues with commas' ),
		'add_or_remove_items' => __( 'Add or remove venue' ),
		'choose_from_most_used' => __( 'Choose from the most used venues' )
	); 
	
    if(!taxonomy_exists('venue')){
		register_taxonomy('venue','productions',array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'venue' ),
		));
	}
}

// A callback function to add a custom field to our "venue" taxonomy
function production_company_taxonomy_custom_fields($tag) {
   // Check for existing taxonomy meta for the term you're editing
    $t_id = $tag->term_id; // Get the ID of the term you're editing
    $term_meta = get_option( "taxonomy_term_$t_id" ); // Do the check
?>

<tr class="form-field">
	<th scope="row" valign="top">
		<span class="hint">The following fields are optional</span>
	</th>
</tr>
<tr class="form-field">
	<th scope="row" valign="top">
		<label for="company_website">Production Company's Website</label>
	</th>
	<td>
		<input type="text" name="term_meta[company_website]" id="term_meta[company_website]" size="50" style="width:60%;" value="<?php echo $term_meta['company_website'] ? $term_meta['company_website'] : ''; ?>"><br />
		<span class="description"><?php _e('Without http://'); ?></span>
	</td>
</tr>
<?php
}

// A callback function to add a custom field to our "venue" taxonomy
function venue_taxonomy_custom_fields($tag) {
   // Check for existing taxonomy meta for the term you're editing
    $t_id = $tag->term_id; // Get the ID of the term you're editing
    $term_meta = get_option( "taxonomy_term_$t_id" ); // Do the check
?>

<tr class="form-field">
	<th scope="row" valign="top">
		<span class="hint">The following fields are optional</span>
	</th>
</tr>
<tr class="form-field">
	<th scope="row" valign="top">
		<label for="venue_website">Venue's Website</label>
	</th>
	<td>
		<input type="text" name="term_meta[venue_website]" id="term_meta[venue_website]" size="50" style="width:60%;" value="<?php echo $term_meta['venue_website'] ? $term_meta['venue_website'] : ''; ?>"><br />
		<span class="description"><?php _e('Without http://'); ?></span>
	</td>
</tr>
<tr class="form-field">
	<th scope="row" valign="top">
		<label for="venue_street_address">Venue's Street Number</label>
	</th>
	<td>
		<input type="text" name="term_meta[venue_street_address]" id="term_meta[venue_street_address]" size="50" style="width:60%;" value="<?php echo $term_meta['venue_street_address'] ? $term_meta['venue_street_address'] : ''; ?>"><br />
		<span class="description"><?php _e('1234 Sesame Street'); ?></span>
	</td>
</tr>
<tr class="form-field">
	<th scope="row" valign="top">
		<label for="venue_city">Venue's City</label>
	</th>
	<td>
		<input type="text" name="term_meta[venue_city]" id="term_meta[venue_city]" size="50" style="width:60%;" value="<?php echo $term_meta['venue_city'] ? $term_meta['venue_city'] : ''; ?>"><br />
		<span class="description"><?php _e('New York City'); ?></span>
	</td>
</tr>
<tr class="form-field">
	<th scope="row" valign="top">
		<label for="venue_state">Venue's State/Province</label>
	</th>
	<td>
		<input type="text" name="term_meta[venue_state]" id="term_meta[venue_state]" size="50" style="width:60%;" value="<?php echo $term_meta['venue_state'] ? $term_meta['venue_state'] : ''; ?>"><br />
		<span class="description"><?php _e('New York'); ?></span>
	</td>
</tr>
<tr class="form-field">
	<th scope="row" valign="top">
		<label for="venue_country">Venue's Country</label>
	</th>
	<td>
		<input type="text" name="term_meta[venue_country]" id="term_meta[venue_country]" size="50" style="width:60%;" value="<?php echo $term_meta['venue_country'] ? $term_meta['venue_country'] : ''; ?>"><br />
		<span class="description"><?php _e('USA'); ?></span>
	</td>
</tr>

<?php
}

// A callback function to save our extra taxonomy field(s)
function save_taxonomy_custom_fields( $term_id ) {
    if ( isset( $_POST['term_meta'] ) ) {
        $t_id = $term_id;
        $term_meta = get_option( "taxonomy_term_$t_id" );
        $cat_keys = array_keys( $_POST['term_meta'] );
            foreach ( $cat_keys as $key ){
            if ( isset( $_POST['term_meta'][$key] ) ){
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        //save the option array
        update_option( "taxonomy_term_$t_id", $term_meta );
    }
}

// Add the fields to the "venue" taxonomy, using our callback function
add_action( 'venue_edit_form_fields', 'venue_taxonomy_custom_fields', 10, 2 );

// Save the changes made on the "venue" taxonomy, using our callback function
add_action( 'edited_venue', 'save_taxonomy_custom_fields', 10, 2 );

// Add the fields to the "company" taxonomy, using our callback function
add_action( 'production_company_edit_form_fields', 'production_company_taxonomy_custom_fields', 10, 2 );

// Save the changes made on the "production_company" taxonomy, using our callback function
add_action( 'edited_production_company', 'save_taxonomy_custom_fields', 10, 2 );


/*
****************************** Meta Boxes *********************
 */

#hc Find a way to get this box above the simple fields box
add_action("admin_init", "production_meta_init");
 
function production_meta_init(){
	//format: add_meta_box( $id, $title, $callback, $page, $context, $priority );
	add_meta_box('production_info_meta', 'Production Information', 'production_info_meta', 'productions', 'normal', 'low');
}
 
function production_info_meta() {
	global $post;
	?>
	
	<div>These two fields are required. They tell the system if the show is current so it can then make adjustements to display based on these dates. These dates will determine if the show is currently running or not. You may decide if you'd like these dates to be based on your total involvement in the process, or the first available date for the public to see the show. It is up to you.</div>
	<p><label>First date - </label> <span class="hint">Format: <code>YYYY-MM-DD</code></span><br />
	<input type="text" value="<?php echo get_post_meta($post->ID,'hc_first_date', true); ?>" size="10" name="hc_first_date">
	</p>
	<p><label>Last date - </label> <span class="hint">Format: <code>YYYY-MM-DD</code></span><br />
	<input type="text" value="<?php echo get_post_meta($post->ID,'hc_last_date', true); ?>" size="10" name="hc_last_date">
	</p>
	
	<br><br>
		
	<div>These are optional fields.</div>
	<p><label>Production Web Address - </label> <span class="hint">This is independent of venues for touring shows. Example: <code>FiddlerOnTour.com</code></span><br />
	<input class="left" type="text" value="<?php echo get_post_meta($post->ID,'hc_production_url', true); ?>" size="50" name="hc_production_url">
	</p>

	<p><label>Web Address for Ticket Purchase - </label> <span class="hint">Format: <code>theatretickets.com</code></span><br />
	<input type="text" value="<?php echo get_post_meta($post->ID,'hc_tickets_url', true); ?>" size="50" name="hc_tickets_url">
	</p>
	
	<input type="hidden" value="true" name="hc_production_autosave_check">
	
	<?php
}


//Now to make sure the ALL the data gets saved:
add_action('save_post', 'hc_production_save_details');
function hc_production_save_details(){
	global $post;
	
	if($_POST['hc_production_autosave_check']){
		
		//Save the external information fields
		update_post_meta($post->ID, 'hc_production_url', $_POST['hc_production_url']);
		update_post_meta($post->ID, 'hc_tickets_url', $_POST['hc_tickets_url']);
		update_post_meta($post->ID, 'hc_first_date', $_POST['hc_first_date']);
		update_post_meta($post->ID, 'hc_last_date', $_POST['hc_last_date']);
		}
	
}

require_once('simple_fields_call.php');


/********************************************************
*********************************************************

			CLASS STUFF
			
********************************************************
*******************************************************/

class ActorProductions {
	private static $current = array();
	private static $upcoming = array();
	private static $past = array();
	private static $all = array();
	
	//pointers inside each of the above arrays
	private static $heads = array(
			'current' => 1
			,'upcoming' => 1
			,'past' => 1
			,'all' => 1
		);	
	
	
	
	public function __construct(){
		//Get the productions by meta_key of hc_first_date with most recent being first
		$productions = get_posts(array(
			'post_type' => 'productions'
			,'orderby' => 'meta_value'
			,'meta_key' => 'hc_first_date'
			,'order' => 'DESC'
			));
		
		//Loop through each production. 
		foreach($productions as $production){
			//Populate all necessary fields.
			$production_data = self::populate_data($production->ID);
			
			//Put in array under the appropriate variable.
			if(self::is_current($production_data)){
				self::$current[$production->ID] = $production_data;
			
			}elseif(self::is_upcoming($production_data)){
				self::$upcoming[$production->ID] = $production_data;
			
			}else{ //(is past)
				self::$past[$production->ID] = $production_data;
			}
			
			//also put into all data array
			self::$all[$production->ID] = $production_data;
		}
				
	}
	
	private static function populate_data($id){
		//Get all normal meta fields
		$normal_custom_data = get_post_custom($id);
		//pair down data to the essentials
		$important_keys = array(
			'hc_production_url'
			,'hc_tickets_url'
			,'hc_first_date'
			,'hc_last_date'
		);
		foreach($important_keys as $important_key){
			$normal_essential_data[$important_key] = $normal_custom_data[$important_key][0];
		}

		//Get all REGULAR taxonomy data
		$taxonomy_data = self::taxonomy_data($id);
		
		//Get all Simple Fields meta fields
		$simple_fields_data = simple_fields_get_post_group_values($id, "Production Dates and Venues", true, 2);
		
		//combine into one set of data
		$all_data = array(
			'essential_data' => $normal_essential_data
			,'taxonomy_data' => $taxonomy_data
			,'simple_fields_data' => $simple_fields_data
		);
		
		//Make inferences based on the different data sets
		//is current?
		self::determine_relevance($all_data);
		//is multi-venue?
		self::multi_venue($all_data);

		//ddprint($all_data);
		return $all_data;
	}
	
	private static function multi_venue(&$data){
		if(count($data['simple_fields_data']) > 1){$data['multi_venue'] = true; return;}
		$data['multi_venue'] = false; return;
	}
		
	private static function determine_relevance(&$data){
		//normalize the data
		$today = date('Ymd');
		$first_date = str_replace('-', '', $data['essential_data']['hc_first_date']);
		$last_date = str_replace('-', '', $data['essential_data']['hc_last_date']);
		
		if($today < $first_date && $today < $last_date) {$data['relevance'] = 'upcoming'; return;}
		if($today > $last_date && $last_date != 0 ) {$data['relevance'] = 'past'; return;}
		$data['relevance'] = 'current'; return;
		
		
	}
	
	private static function taxonomy_data($id){
		//Get regular values
		$venues = get_the_terms($id, 'venue');
		$company = get_the_terms($id, 'production_company');
		
		//foreach: add custom values
		
		//return single array
		$taxonomy_data = array('venue' => $venues, 'company' => $company);
		return $taxonomy_data;
	}
	
	private static function is_current($data){
		if ($data['relevance'] == 'current') return true;
		return false;
	}
	
	private static function is_upcoming($data){
		if ($data['relevance'] == 'upcoming') return true;
		return false;
	}
	
	private static function is_past($data){
		if ($data['relevance'] == 'past') return true;
		return false;
	}
	
	//These three are jsut for display/debugging, really
	public function current_list(){
		return self::$current;
	}
	public function upcoming_list(){
		return self::$upcoming;
	}
	public function past_list(){
		return self::$past;
	}
	
	//operates similarly to WordPress's have_posts()
	public function have_productions($list = 'all'){
		if( self::$heads[$list] <= count(self::$$list) ) return true;
		return false;
	}
	
	//operates similarly to WordPress's the_post()
	public function the_production($list = 'all'){
		global $hc_production;
		
		$array_position = self::$heads[$list]-1;
		$array_keys = array_keys(self::$$list);
		$current_key = $array_keys[$array_position];

		$hc_production = new ActorProduction( self::${$list}[$current_key], $current_key );
		//ddprint($production);
		
		//advance internal pointer
		self::$heads[$list]++;
	}
	
	public function reset_productions($list = 'all'){
		self::$heads[$list] = 1;
	}
	
}

class ActorProduction {
	public $ID;
	public $multi_venue;
	public $relevance;
	
	public $title;
	public $tickets;
	public $company;
	
	//Things that need loops
	public $venue;
	
	
	public function __construct($AParray, $id){
		$this->ID = $id;
		$this->title = get_the_title($id);
		foreach ($AParray as $key => $value) {
			if($key == 'taxonomy_data'){
				foreach ($value as $k => $v){
					$this->$k = $v;
				}
				continue;
			}
			if($key == 'essential_data'){
				foreach ($value as $k => $v){
					$k = str_replace('hc_', '', $k);
					$this->$k = $v;
				}
				continue;
			}
			//all the rest
			$this->$key = $value;
		}
		
		$this->simple_fields_populate();
		$this->venue = $this->additional_taxonomy_data(current($this->venue));
		$this->company = $this->additional_taxonomy_data(current($this->company));
	}
	
	private function simple_fields_populate(){
		if (is_array($this->simple_fields_data)) {
			foreach($this->simple_fields_data as $key => &$field){
				if($field["Venue"][0] == ''){
					unset($this->simple_fields_data[$key]);
					continue;
				}
				
				$field['Venue'] = get_term($field['Venue'][0],'venue');
				$field['Venue'] = $this->additional_taxonomy_data($field['Venue']);
				
				$field['Venue']->preview = $field['Venue Previews Start Date'];
				unset($field['Venue Previews Start Date']);
				$field['Venue']->opening = $field['Venue Opening'];
				unset($field['Venue Opening']);
				$field['Venue']->closing = $field['Venue Closing'];
				unset($field['Venue Closing']);
				
				//remove unnessecary verbiage
				foreach($field['Venue'] as $key => $data) {
					if(substr($key, 0, 6) == 'venue_'){
						$replaced = str_replace('venue_', '', $key);
						$field['Venue']->$replaced = $data;
						unset($field['Venue']->$key);
					}
				}
				
				$field = $field['Venue'];
			}
			
			$this->venues = $this->simple_fields_data;
			unset($this->simple_fields_data);
			
		}
	}
	
	private function additional_taxonomy_data($taxonomy_object){
		$additional_data = get_option("taxonomy_term_$taxonomy_object->term_id");
		if(is_array($additional_data)){
			foreach ($additional_data as $key => $datum){
				$taxonomy_object->$key = $datum;
			}
		}
		return $taxonomy_object;
	}
	
	//Use inside $production->loop
	public function featured_image_url($size = null){
		echo $this->get_featured_image_url($size);
	}
	public function get_featured_image_url($size = null){
		if($size == null) $size = 'full';
		$imgsrc = wp_get_attachment_image_src( get_post_thumbnail_id( $this->ID ), $size);
		return $imgsrc[0];
	}
	
	public function the_title(){
		echo $this->get_the_title();
	}
	public function get_the_title(){
		return $this->title;
	}
	
	public function is_multi_venue(){
		return $this->multi_venue;
	}
	
	//Destructive. Use with caution.
	public function format_dates($format=null){
		if($format==null)return;
		$fields_with_dates = array(
			'preview'
			,'opening'
			,'closing'
		);
		
		foreach ($this->venues as $venue_obj){
			foreach($fields_with_dates as $date_key){
				if($venue_obj->$date_key==null) continue;
				$venue_obj->$date_key = date($format, strtotime($venue_obj->$date_key));
			}
		}
	}
}

/********************************************************
*********************************************************

			WIDGET STUFF
			
********************************************************
*******************************************************/

/**
 * Adds Foo_Widget widget.
 */
class Productions_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'productions_widget', // Base ID
			'Productions', // Name
			array( 'description' => __( 'Displays Current and Upcoming Productions with options for past ones.', 'hc_actor_productions' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		//$title = apply_filters( 'widget_title', $instance['title'] );
		//echo '<div style="background-color:#ccc; position:absolute; top:0; left:0; right:0; padding:8em">';
		$productions_data = new ActorProductions;
		
		echo $before_widget;
		
		
		if ($productions_data->have_productions('current')): 
			echo $before_title . 'Currently' . $after_title;
			while ($productions_data->have_productions('current')): 
				echo '<ul class="current-productions">';
				
				$productions_data->the_production('current');
				global $hc_production;
				//ddprint($hc_production);
				?>
				<li class="production-info">
					<?php if($hc_production->company != ''){
						echo "<div class='production-company'>{$hc_production->company->name} presents</div>";
					}?>
					<h4 class="production-title"><a href="<?php echo post_permalink($hc_production->ID); ?>"><?php $hc_production->the_title(); ?></a></h4>
					<?php if(has_post_thumbnail($hc_production->ID)){?>
						<img src="<?php $hc_production->featured_image_url('medium'); ?>"/>
					<?php } ?>
					
					<ul class="venues">
					<?php $hc_production->format_dates('D, M jS');?>
					<?php foreach ($hc_production->venues as $venue):?>
						<li class="venue-info">
							<h5 class="venue-name">
								<?php if($venue->website != '') {
									echo "<a href='http://{$venue->website}'>$venue->name</a>";
								}else{
									echo "$venue->name";
								}?>
							</h5>
							<?php if ($venue->city != ''){ ?>
								<?php
									$query = array(
										$venue->name,
										$venue->street_address,
										$venue->city,
										$venue->state,
										$venue->country
									);
									foreach ($query as $key => $data){
										if($data=='') unset($query[$key]);
									}
									$query = implode(',+', $query);
								?>
								<div class="location">
									<a href="http://maps.google.com/maps?q=<?php echo $query;?>" target="_blank">
										<span class="city"><?php echo $venue->city; ?>,</span>
										<span class="state"><?php echo $venue->state; ?></span>
										<span class="country"><?php echo $venue->country; ?></span>
									</a>
								</div>
							<?php } ?>
							<ul class="dates">
								<?php
								if($venue->preview) echo "<li class='previews'>Previews begin <span>$venue->preview</span></li>";
								if($venue->opening) echo "<li class='opening'>Opening is <span>$venue->opening</span></li>";
								if($venue->closing) echo "<li class='closing'>Closing is <span>$venue->closing</span></li>";
								?>
							</ul>
						</li><!--venue-info-->
					<?php endforeach; ?>
					</ul><!--venues-->
				</li><!--production-info-->
				<?
				//ddprint($production);
			endwhile; echo '</ul><!--current-productions-->';
		endif;
		
		echo $before_title . 'Upcoming' . $after_title;
		echo $before_title . 'Past Productions' . $after_title;
		
		echo $after_widget;
		//echo '</div>';
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		//$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['past_num'] = strip_tags( $new_instance['past_num'] );
		
		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
/*
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
*/
		if ( isset( $instance[ 'past_num' ] ) ) {
			$past_num = $instance[ 'past_num' ];
		}
		else {
			$past_num = '5';
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'past_num' ); ?>">Number of Past Productions to show:</label> 
		<input id="<?php echo $this->get_field_id( 'past_num' ); ?>" name="<?php echo $this->get_field_name( 'past_num' ); ?>" type="text" size="3" value="<?php echo esc_attr( $past_num ); ?>" />
		</p>
		<?php 
	}

} // class Foo_Widget

// register Foo_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "productions_widget" );' ) );