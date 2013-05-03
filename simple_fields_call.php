<?php
/**
 * Adds a new field group
 *
 * Usage:
 * simple_fields_register_field_group( string unique_field_group_slug, array field_groups );
 * 
 * Each field group looks like this:
 * 
 * 	array (
 * 		'name' => 'Name of field group',
 * 		'description' => "A short description of the field group",
 * 		'repeatable' => TRUE or FALSE if the field group should be repeatable,
 * 		'fields' => array of fields,
 * 		'deleted' => TRUE or FALSE
 *	),
 * 	array ( another field group array ),
 * 	array ( another field group array ),
 * 	array ( ...)
 *
 * Each field array looks like this:
 * 	array(
 * 		'slug' => "unique_field_slug"
 * 		'name' => 'Field name',
 *		'description' => 'Field description',
 *		'type' => 'field type',
 * 		'deleted' => TRUE or FALSE
 *	)
 * 
 * @param string $slug the slug of this field group. must be unique.
 * @param array $new_field_group settings/options for the new group
 * @return array the new field group as an array
 */
simple_fields_register_field_group('production_details',
	array (
		'name' => 'Production Dates and Venues',
		// #hc The description field uses WordPress's esc_html() function to display the text. That function has a filter that might be able to be utilized to un-escape this text.
		'description' => "Specify your venue information here. If you need to create a new venue, do so on the <a href='edit-tags.php?taxonomy=venue&post_type=productions'>Venues Page</a>. You may need to save a draft of this production first (near the top of the screen, above the blue button). After you create a venue on the venue page, click on it to edit even more details before you come back here.",
		'repeatable' => 1,
		'fields' => array(
			array(
				'slug' => "hc_taxonomy_field_slug",
				'name' => 'Venue',
				'description' => 'Taxonomy description',
				'type' => 'taxonomyterm',
				'type_taxonomyterm_options' => array("enabled_taxonomy" => "venue")
			)
			,array(
				'slug' => "hc_venue_preview",
				'name' => 'Venue Previews Start Date',
				'description' => 'Fill out if production has multiple venues.',
				'type' => 'date_v2',
				"options" => array(
					"date_v2" => array(
						"show" => "on_click",
						"show_as" => "date"
					)
				)
			)			
			,array(
				'slug' => "hc_venue_opening",
				'name' => 'Venue Opening',
				'description' => 'Fill out if production has multiple venues.',
				'type' => 'date_v2',
				"options" => array(
					"date_v2" => array(
						"show" => "on_click",
						"show_as" => "date"
					)
				)
			)			
			,array(
				'slug' => "hc_venue_closing",
				'name' => 'Venue Closing',
				'description' => 'Fill out if production has multiple venues.',
				'type' => 'date_v2',
				"options" => array(
					"date_v2" => array(
						"show" => "on_click",
						"show_as" => "date"
					)
				)
			)			
		)
	)
);


/*
			,array(
				'slug' => "my_text_field_slug",
				'name' => 'Test text',
				'description' => 'Text description',
				'type' => 'text'
			)
			,array(
				'slug' => "my_textarea_field_slug",
				'name' => 'Test textarea',
				'description' => 'Textarea description',
				'type' => 'textarea',
				'type_textarea_options' => array('use_html_editor' => 1)
			)
			,array(
				'slug' => "my_checkbox_field_slug",
				'name' => 'Test checkbox',
				'description' => 'Checkbox description',
				'type' => 'checkbox',
				'type_checkbox_options' => array('checked_by_default' => 1)
			)
			,array(
				'slug' => "my_radiobutton_field_slug",
				'name' => 'Test radiobutton',
				'description' => 'Radiobutton description',
				'type' => 'radiobutton',
				'type_radiobutton_options' => array(
					array("value" => "Yes"),
					array("value" => "No")
				)
			)
			,array(
				'slug' => "my_dropdown_field_slug",
				'name' => 'Test dropdown',
				'description' => 'Dropdown description',
				'type' => 'dropdown',
				'type_dropdown_options' => array(
					"enable_multiple" => 1,
					"enable_extended_return_values" => 1,
					array("value" => "Yes"),
					array("value" => "No")
				)
			)
			,array(
				'slug' => "my_file_field_slug",
				'name' => 'Test file',
				'description' => 'File description',
				'type' => 'file'
			)
			,array(
				'slug' => "my_post_field_slug",
				'name' => 'Test post',
				'description' => 'Post description',
				'type' => 'post',
				'type_post_options' => array("enabled_post_types" => array("post"))
			)
			,array(
				'slug' => "my_taxonomy_field_slug",
				'name' => 'Test taxonomy',
				'description' => 'Taxonomy description',
				'type' => 'taxonomy',
				'type_taxonomy_options' => array("enabled_taxonomies" => array("category"))
			)
			,array(
				'slug' => "my_taxonomyterm_field_slug",
				'name' => 'Test taxonomy term',
				'description' => 'Taxonomy term description',
				'type' => 'taxonomyterm',
				'type_taxonomyterm_options' => array("enabled_taxonomy" => "category")
			)
			,array(
				'slug' => "my_color_field_slug",
				'name' => 'Test color selector',
				'description' => 'Color selector description',
				'type' => 'color'
			)
			,array(
				'slug' => "my_date_field_slug",
				'name' => 'Test date selector',
				'description' => 'Date selector description',
				'type' => 'date',
				'type_date_options' => array('use_time' => 1)
			)
			,array(
				'slug' => "my_date2_field_slug",
				'name' => 'Test date selector',
				'description' => 'Date v2 selector description',
				'type' => 'date_v2',
				"options" => array(
					"date_v2" => array(
						"show" => "on_click",
						"show_as" => "datetime",
						"default_date" => "today"
					)
				)
			)			
			,array(
				'slug' => "my_user_field_slug",
				'name' => 'Test user selector',
				'description' => 'User selector description',
				'type' => 'user'
			)
*/


// function simple_fields_register_post_connector($unique_name = "", $new_post_connector = array()) {
simple_fields_register_post_connector('production_connector',
	array (
		'name' => "Production Connector",
		'field_groups' => array(
			array(
				'slug' => 'production_details',
				'context' => 'normal',
				'priority' => 'high'
			)
		),
		'post_types' => array('productions'),
		'hide_editor' => false
	)
);

/**
 * Sets the default post connector for a post type
 * 
 * @param $post_type_connector = connector id (int) or slug (string) or string __inherit__
 * 
 */
simple_fields_register_post_type_default('production_connector', 'productions');


//Hide connector box in admin.
add_action('admin_head','hide_simple_fields_connector_meta_box');
function hide_simple_fields_connector_meta_box(){
	echo "
	<style>
	#simple-fields-post-edit-side-field-settings {
		display: none;
	}
	</style>
	";
}