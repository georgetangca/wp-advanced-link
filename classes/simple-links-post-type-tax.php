<?php 
  /**
   * Registers Post type
   * Creates a Meta Data box, Creates the fields and handles the saving
   * @author 
   * @since 
   * @uses Extend this class into the the class registering the post types
   * @uses Make sure to call a parent::__construct()
   * @uses Add class arrays named  %post_type&_meta_fields = array();
   * @example  protected $supplier_meta_fields = array();
   * @uses This will automatically have a callback to add data when the post type is registered. Remove in args if not needed
   * @package Mat's Framework
   */
if( !class_exists( 'mat_post_type_tax' ) ){                     
class mat_post_type_tax{
	
	/**
	 * Registers the Post Types and Taxonomies automatically
	 */
	function __construct(){
		add_action('save_post', array( $this , 'meta_save' ) );
	}
	
	/**
	 * Creates the Meta Box
	 * @param obj $post the post object
	 * @since 8/10/12
	 */
	function meta_box( $post ){
		$type = $post->post_type;
		add_meta_box( $type.'_meta_box', self::human_format_slug($type) . ' Data ' , array( $this, 'meta_box_output' ), $type , 'advanced' , 'default' );
	
	}
	
	/**
	 * Creates the output for the meta box - Goes through the array with the %meta-name%_meta_Fields
	 * @param obj $post
	 * @param if key has check in it this will output a checkbox
	 * @param if key has select in it will output a select using the array with same name as key
	 * @param if the keys are set in select array they will become the selects values, otherwise the array values will become the selects values
	 * @example array( 'select_state' => 'state' ) will look for an array $select_state
	 * @example array( 'one','two', 'check_three' => 'three', 'select_state' => 'state' );
	 * @since 8/10/12
	 */
	function meta_box_output( $post ){
		$type = $post->post_type;
		//Get the proper class array
		$fields = $this->{$type .'_meta_fields'};
		wp_nonce_field( plugin_basename( __FILE__ ), $type. '_meta_box', true );
	
		//Go through all the fields in the array
		foreach( $fields as $key => $field ){
			echo '<li style="list-style: none; margin: 0 0 15px 10px">' . self::human_format_slug($field);
			
				//Checkbox
				if( strpos($key,'check') !== false){
					echo ': &nbsp; &nbsp; <input type="checkbox" name="' . $field . '" value="1" '. checked( get_post_meta( $post->ID , $field , true ), true, false ) . '/>';
				
				//Select Field	
				} elseif( strpos($key,'select') !== false ){
					echo ': &nbsp; <select name="'. $field . '">';
					
					   //Get this classes array with the same name as the key
					   $values_array = $this->{$key};
				
						//To Determine if this is an associative array or not
						$ass = ($values_array != array_values($values_array));
				    
						//Go through the matching array
						foreach( $values_array as $key => $value ){
							if( $ass ){
								//use the key as the value
								printf( '<option value="%s" %s>%s</option>', $key, selected( get_post_meta($post->ID,$field,true), $key), $value );
							} else {
								//use the value as the value
								printf( '<option value="%s" %s>%s</option>', $value, selected( get_post_meta($post->ID,$field,true), $value), $value );
							}
						}
						echo '</select><!-- End ' . $field . ' -->';
						
				//Standard Text Field	
				} else {
					echo ': <input type="text" name="' . $field . '" value="'. get_post_meta( $post->ID , $field , true ) . '" size="75"/>';
				}
			
			echo '</li>';

		}
	
	}
	
	
	/**
	 * Saves the meta fields
	 * @since 8/2/12
	 */
	function meta_save(){
		global $post;
		$type = $post->post_type;
	
		//Make sure this is valid
		if ( defined('DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;
		if ( !wp_verify_nonce( $_POST[$type.'_meta_box'], plugin_basename(__FILE__ ) ) )
			return;
	
		//Go through the options extra fields
		foreach( $this ->{$type . '_meta_fields'} as $field ){
			update_post_meta( $post->ID, $field, $_POST[$field ] );
		}
	
	
	}
	
	
	/**
	 * Registers a post type with default values which can be overridden as needed.
	 * @param $title the name of the post type
	 * @param [$args] the arguments to overwrite
	 * @example register_post_type( 'newtest' , array() );
	 * @since 7/24/12
	 *
	 **/
	function register_post_type( $title, $args = array() ){
	
		$sanitizedTitle = sanitize_title( $title );
	
		if( isset( $args['singleTitle'] ) ){
			$title = $args['singleTitle'];
		} else {
			$title = ucwords( str_replace( '_', ' ', $sanitizedTitle ) );
		}
	
	
		//If the plural title is not set make it.
		$puralTitle = isset( $args['pluralTitle'])? $args['pluralTitle']: $this->plural_title($title);
	
		$defaults = array(
				'labels' => array(
						'name'                       => $puralTitle,
						'singular_name'              => $title,
						'search_items'               => sprintf( 'Search %s', $puralTitle ),
						'popular_items'              => sprintf( 'Popular %s', $puralTitle ),
						'all_items'                  => sprintf( 'All %s' , $puralTitle ),
						'parent_item'                => sprintf( 'Parent %s', $title ),
						'parent_item_colon'          => sprintf( 'Parent %s:', $title ),
						'edit_item'                  => sprintf( 'Edit %s', $title ),
						'update_item'                => sprintf( 'Update %s' , $title ),
						'add_new_item'               => sprintf( 'Add New %s' , $title),
						'new_item_name'              => sprintf( 'New %s Name', $title ),
						'separate_items_with_commas' => sprintf( 'Seperate %s with commas', $title ),
						'add_or_remove_items'        => sprintf( 'Add or remove %s', $puralTitle ),
						'choose_from_most_used'      => sprintf( 'Choose from the most used %s', $puralTitle ),
						'view_item'                  => sprintf( 'View %s', $title ),
						'add_new'                    => sprintf( 'Add New %s', $title ),
						'new_item'                   => sprintf( 'New %s', $title ),
						'menu_name'                  => $pluralTitle
				),
				'public'            => true,
				'show_in_nav_menus' => true,
				'show_ui'           => true,
				'show_tagcloud'     => false,
				'hierarchical'      => true,
				'query_var'         => $sanitizedTitle,
				'rewrite'           => array( 'slug' => $sanitizedTitle ),
				'_builtin'          => false,
				'menu_icon' => get_bloginfo( 'stylesheet_directory' ) . '/images/menu_icon.png',
				'has_archive'       => true,
				'show_in_menu'      => true, //Change this to a string for the menu to make this is submenu of
				'supports'      => array( 'title', 'editor', 'thumbnail', 'author', 'comments' , 'genesis-seo' , 'genesis-layouts' ,
						'excerpt', 'trackbacks' , 'custom-fields' , 'comments' , 'revisions' ,'page-attributes',
						'post-formats'  ),
                                
                                   
				'register_meta_box_cb' => array( $this, 'meta_box' )
	
		);
	
	
		//Make this keep the no overwritten default labels
		if( isset( $args['labels'] ) ){
			$defaults['labels'] = wp_parse_args( $args['labels'], $defaults['labels'] );
			unset( $args['labels'] );
		}
	
		$args = wp_parse_args( $args, $defaults );
	
		$postType = isset( $args['postType'] ) ? $args['postType'] : $sanitizedTitle;

		register_post_type( $postType, $args );
	
	}
	
	
	/**
	 * Registers a taxonomy with default values which can be overridden as needed.
	 * @param $title is the name of the taxonomy
	 * @param $post_type the post type to link it to
	 * @param $args an array to overwrite the defaults
	 * @example register_taxonomy( 'post-cat', 'custom-post-type', array( 'pluralTitle' => 'lots of cats' ) );
	 *
	 */
	function register_taxonomy( $title, $post_type = '', $args = array() ){
	
		$sanitizedTaxonomy = sanitize_title( $title );
	
		if( isset( $args['singleTitle'] ) ){
			$title = $args['singleTitle'];
		} else {
			$title = ucwords( str_replace( '_', ' ', $sanitizedTaxonomy ) );
		}
	
		//If the plural title is not set make it.
		$puralTitle = isset( $args['pluralTitle'])? $args['pluralTitle']: $this->plural_title($title);
	
		$defaults = array(
				'labels' => array(
						'name'                       => $puralTitle,
						'singular_name'              => $title,
						'search_items'               => sprintf( __( 'Search %s'                   , 'gmp' ), $puralTitle ),
						'popular_items'              => sprintf( __( 'Popular %s'                  , 'gmp' ), $puralTitle ),
						'all_items'                  => sprintf( __( 'All %s'                      , 'gmp' ), $puralTitle ),
						'parent_item'                => sprintf( __( 'Parent %s'                   , 'gmp' ), $title      ),
						'parent_item_colon'          => sprintf( __( 'Parent %s:'                  , 'gmp' ), $title      ),
						'edit_item'                  => sprintf( __( 'Edit %s'                     , 'gmp' ), $title      ),
						'update_item'                => sprintf( __( 'Update %s'                   , 'gmp' ), $title      ),
						'add_new_item'               => sprintf( __( 'Add New %s'                  , 'gmp' ), $title      ),
						'new_item_name'              => sprintf( __( 'New %s Name'                 , 'gmp' ), $title      ),
						'separate_items_with_commas' => sprintf( __( 'Seperate %s with commas'     , 'gmp' ), $title      ),
						'add_or_remove_items'        => sprintf( __( 'Add or remove %s'            , 'gmp' ), $puralTitle ),
						'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', 'gmp' ), $puralTitle ),
						'menu_name' 				 => $puralTitle,
				),
				'public'            => true,
				'show_in_nav_menus' => true,
				'show_ui'           => true,
				'show_tagcloud'     => false,
				'hierarchical'      => true,
				'query_var'         => $sanitizedTaxonomy,
				'rewrite'           => array( 'slug' => $sanitizedTaxonomy ),
				'_builtin'          => false
	
	
		);
	
		//Make this keep the no overwritten default labels
		if( isset( $args['labels'] ) ){
			$defaults['labels'] = wp_parse_args( $args['labels'], $defaults['labels'] );
			unset( $args['labels'] );
		}
	
		$args = wp_parse_args( $args, $defaults );
	
		$taxonomy = isset( $args['taxonomy'] ) ? $args['taxonomy'] : $sanitizedTaxonomy;
	
		register_taxonomy( $taxonomy, $post_type, $args );
	
	
	}
	
	/**
	 * Generates plural version of title
	 *
	 * @param string $title
	 * @return string
	 */
	function plural_title( $title ){
	
		return'y' == substr($title,-1) ? rtrim($title, 'y') . 'ies' : $title . 's';
	
	}
	
	
	/**
	 * Returns a human readable slug with the _ remove and words uppercase
	 * @param string $slug
	 * @return string
	 * @since 8/2/12
	 */
	function human_format_slug( $slug ){
		return ucwords( str_replace( '_', ' ', $slug) );
	}
	
	
	
	
}}