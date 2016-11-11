<?php
/**
 * @author - Sergey Morozov <sergmoro1@ya.ru>
 * @license - MIT
 * 
 * Before displaying the content of the post, it checks to see whether the JSON definition. 
 * If not, then the post is displayed as is otherwise the post is converted to the record.
 * Record attributes used in a ./views.
 */

class LisetteJDPApplication
{
 	const THUMBNAIL = true; // should be shown for every post
 	const GEOCODER = true; // JSON definitions have addresses that should be geocoded
 	
	public $points = []; // YaMap points
 	public $categories = []; // all categories - term_id, slug, parent
 	public $slug = []; // category's slug => term_id
 	
 	public $begin, $end = 0; // positions of begin and end of definition
 	public $hide_post_text_area; // shold be or not shown the text of defination

	// field's values
	private static $options;
	
 	// name of legal fields
 	private static $fields;
 	
 	// default values
 	private static $edit_defaults; 
 	private static $criteria_defaults;
 	// criteria params
 	public $params;
 	
 	// custom xml
	public $feeds = [];

	/* 
	 * Initialization,
	 * add hooks and filters.
	 */
	public function __construct() {
		
		self::$options = require(dirname(__FILE__) . '/config/select_options.php');
		$a = require(dirname(__FILE__) . '/config/fields.php');
		self::$fields = $a['legal'];
		self::$edit_defaults = $a['defaults']['edit'];
		self::$criteria_defaults = $a['defaults']['criteria'];
		
		
		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', [ $this, 'load_admin_css_scripts' ] );
			add_action( 'edit_form_after_title', [ $this, 'edit_form_after_title' ] );
			add_action( 'admin_bar_menu', [ $this, 'add_bar_menu' ], 999 );
			add_action( 'admin_menu', [ $this, 'add_menu' ] );
			add_filter( 'default_content', [ $this, 'add_new_ad'] );
			add_action( 'edit_form_after_editor', [ $this, 'edit_form_after_editor' ] );
			add_action( 'save_post', [ $this, 'save_post' ] );
		} else {
			add_action( 'wp_enqueue_scripts', [ $this, 'load_plugin_css_scripts' ] );
			add_action( 'plugins_loaded', [ $this, 'plugins_loaded'] );
			
			// criteria processing
			add_action( 'pre_get_posts', [ $this, 'criteria' ] );
			add_action( 'get_header', [ $this, 'get_header' ] );
			
			// set feed name to access in URL eg. /?feed=custom-xml
			add_filter( 'init', [ $this, 'init' ] );

			add_filter( 'the_content', [ $this, 'prepare' ] );
			add_filter( 'get_terms_args', [ $this, 'category_filter' ] );
			add_filter( 'post_thumbnail_html', [ $this, 'post_thumbnail_html' ] );
		}
 
		// admin
		$this->init_categories();
		
	}
	
	private function init_categories() {
		$this->categories = $this->get_categories_by( 
			[ 'term_id', 'slug', 'parent' ], 
			[ 'orderby'=>'name', 'hide_empty' => 0 ]
		);
		foreach ( $this->categories as $category )
			$this->slug[$category['slug']] = $category['term_id'];
	}
	
	public function add_menu() {
        add_submenu_page( 'edit.php', '', 'Add New Ad', 'manage_options', 'post-new.php?ad=1' );
	}
	
	/*
	 * Fill in just added post with default json definition
	 * @param content
	 * @ return string
	 */
	public function add_new_ad($content) {
		if ( isset( $_GET['ad'] ) && $_GET['ad'] == 1 ) {
			$content = file_get_contents(plugins_url( 'config/new_ad.json' , __FILE__ ));
		}
		return $content;
	}		
	
	public function add_bar_menu( $wp_admin_bar ) {
		$url = admin_url() . 'post-new.php?ad=1';
		$args = [
			'id' => 'new-ad',
			'title' => 'Ad',
			'href' => $url,
			'parent' => 'new-content',
			'group' => false,
		];
		$wp_admin_bar->add_node( $args );
	}
	
	/*
	 * Show edit form in admin panel
	 * @param $post
	 */
	public function edit_form_after_title($post) {
		$this->hide_post_text_area = false;
		if ( $this->is_definition( $post->post_content ) ) {
			$edit = dirname( __FILE__ ) . '/views/edit.php'; 
			if( file_exists( $edit ) ) {
				if ( isset( $_GET['ad'] ) && $_GET['ad'] == 1 )
					wp_set_object_terms( $post->ID, self::$edit_defaults, 'category' );
				include_once( $edit );
				$this->hide_post_text_area = true;
			} else
				_e('/views/edit.php not found.', 'lisette-jdp');
		}
	}

	/*
	 * Add </div> if needed
	 * @param $post
	 */
	function edit_form_after_editor($post) {
		if ( $this->hide_post_text_area ) 
			echo '</div>';
	}		

	public function post_thumbnail_html( $html ) {
		global $post;
		return self::THUMBNAIL ? '' : ( is_single( $post ) ? '' : $html );
	}
	
	public function save_post( $post_id ) {
		if ( has_post_thumbnail($post_id) ) 
			return;
		
		if ( !($shortcode = $this->get_shortcode($_POST['content'])) )
			return;
		
		$thumbnail = each ( $this->get_images($shortcode, 'thumbnail') );

		add_post_meta( $post_id, '_thumbnail_id', $thumbnail['key'], true );
	}
	
	/*
	 * Set post's categories filter
	 * @param array $args
	 */
	public function category_filter( $args ) {
		$args['orderby'] = 'slug';
		$blog = get_category_by_slug('blog');
		// exclude blog (default category in WP)   
		$args['exclude'] = $blog->term_id;
		return $args;
	}
	
	public function get_header() {
		global $post;
		if ( is_single() && $this->is_definition($post->post_content) ) {
			$model = $this->convert( $post->post_content );
			$model->title = $post->post_title;
			$this->setPoint($model);
		}
	}
	
	/*
	 * Get all published posts with JSON, convert them and select by criteria 
	 * by setting new query
	 * @param object $query
	 */
	public function criteria( $query ) {
		global $wpdb;
		if ( $query->is_home() && $query->is_main_query() ) {
			$q = "SELECT ID, post_content, post_title FROM $wpdb->posts " . 
				"WHERE post_status = 'publish' AND post_type = 'post'"; 
			$ads = $wpdb->get_results( $q ); 
		
			// fill in $models
			$models = [];
			foreach ($ads as $ad) {  
				if ( $this->is_definition($ad->post_content) ) {
					$model = $this->convert( $ad->post_content );
					$model->title = $ad->post_title;
					$model->shortcode = $this->get_shortcode( $ad->post_content );
					$models[$ad->ID] = $model;
				}  
			}
			
			$this->setParams();
			
			$this->points = [];
			// no ID with 0, so it's mean nothing found 
			$ids = [0];
			// choce models by criteria
			foreach ( $models as $id => $model ) {
				if($this->condition($model)) {
					$ids[] = $id;
					if(self::GEOCODER)
						$this->setPoint($model);
				}
			}
			// set new query
			$query->set( 'post__in', $ids );
		}
	}

	public function condition($model) {
		return true;
	}
	
	public function setParams() {
		$this->params = [];
		foreach(self::$criteria_defaults as $name => $default)
			$this->params[$name] = isset( $_GET[$name] ) ? $_GET[$name] : $default;
	}

	/*
	 * Load language
	 */
	public function plugins_loaded() {
		load_plugin_textdomain( 'lisette-json-definition-post', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
 	}
	
	public function init() {
		// set feeds
		foreach($this->feeds as $feed)
			add_feed( $feed, [ $this, 'xml' ] );
	}

	/*
	 * Load feed
	 */
	public function xml() {
		if ( have_posts() ) {
			$feed = dirname( __FILE__ ) . '/feeds/' .  $_GET['feed'] . '.php'; 
			if( file_exists( $feed ) )
				include_once( $feed );
			else
				_e('Sorry, feed has not registered.', 'lisette-jdp');
		} else {
			_e('Sorry, no posts matched your criteria.', 'lisette-jdp');
		}
	}

	public function load_admin_css_scripts() {
		wp_enqueue_style( 'lisette-json-definition-post', plugins_url( 'css/back-end.css', __FILE__ ));
		wp_register_script( 'lisette_jdp_start', plugins_url( 'js/start.js', __FILE__ ) );
		wp_enqueue_script( 'lisette_jdp_start' );
		wp_register_script( 'lisette_jdp_edit', plugins_url( 'js/edit.js', __FILE__ ) );
		wp_enqueue_script( 'lisette_jdp_edit' );
		// set categories
		wp_localize_script( 'lisette_jdp_edit', 'lisette_jdp_categories', $this->categories );
		if(self::GEOCODER) {
			wp_register_script( 'yandexMap', 'http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU' );
			wp_enqueue_script( 'yandexMap' );
			wp_register_script( 'lisette_jdp_address', plugins_url( 'js/address.js', __FILE__ ) );
			wp_enqueue_script( 'lisette_jdp_address' );
		}
	}
 
	public function load_plugin_css_scripts() {
		wp_enqueue_style( 'lisette-json-definition-post', plugins_url( 'css/front-end.css', __FILE__ ));
		if(self::GEOCODER) {
			wp_register_script( 'yandexMap', 'http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU' );
			wp_enqueue_script( 'yandexMap' );
			wp_register_script( 'lisette_jdp_yamap', plugins_url( 'js/yamap.js', __FILE__ ) );
			wp_enqueue_script( 'lisette_jdp_yamap' );
			// set points on the Yandex map
			wp_localize_script( 'lisette_jdp_yamap', 'yaMapPoints', $this->points );
		}
	}

	/**
	 * Read JSON definition from post and return a model 
	 * post format: {name1:"string",name2:number ...}
	 * @param string $content - post content
	 */
	private function convert($content) {
		// call $this->definition before to set $begin, $end
		$json_string = substr( $content, $this->begin, $this->end - $this->begin + 1 );

		// Replace, delete unnecessary symbols, tags
		$json_string = str_replace( ["&#171;","&#187;","&#8243;","&#8220;","&#8221;","&#8222;","&#8216;","&#8217;"], '"', $json_string );
		$json_string = strip_tags( $json_string );
		$json_string = str_replace( ["\r","\n"], '', $json_string );
		// from name:value to "name":value 
		foreach(self::$fields as $field) {
			$json_string = str_replace( $field.':', '"' . $field . '":', $json_string );
		}
		
		return json_decode ( $json_string );
	}

	/* 
	 * Get short code for image slider
	 * @param string $content - post content
	 */
	private function get_shortcode($content) {
		if(preg_match('/\[\w+ id=\d+\]/', $content, $matches))
			return $matches[0];
		else 
			return '';
	}

	/* 
	 * Is post has a JSON definition?
	 * @param string $content - post content
	 */
	private function is_definition($content) {
		$this->begin = strpos($content, '{');
		$this->end = strpos($content, '}');
		return ( $this->begin === false || $this->end === false ) ? false : true;
	}

	/* 
	 * Pre-format model, add calculated atributes
	 * @param object $model
	 */
	public function format($model, $xml=false) {
		if ( !$xml ) {
			// do something with the model
		}
		return $model;
	}

	/* 
	 * If post has JSON definition convert it and make a view or
	 * return as is
	 * @param string $content - post content
	 * $return string view
	 */
	public function prepare($content) {
		global $post;
		if( !$this->is_definition($content) )
			/* No definitions - return as is */
			return $content;
		
		if( $model = $this->convert($content) ) {
			
			$model = $this->format($model);			

			$shortcode = $this->get_shortcode($content);
			
			// different views for list and single post
			if ( is_single($post) ) {
				$content = $shortcode . $this->get_view($model, 'view_' . $model->what);
				if(self::GEOCODER)
					$this->points[0] = $this->yaPoint($model);
			} else {
				if ( self::THUMBNAIL ) {
					$model->image = $this->get_image( $shortcode );
					$content = $this->get_view( $model, '_view_thumbnail' );
				} else
					$content = $this->get_view($model);
			}
		} else {
			$content = _e( 'json format error', 'lisette-jdp' ) . ' - ' . json_last_error_msg();
		}
		return $content;
	}

	/* 
	 * Set point for Yandex Map
	 * @param object $model
	 * $return array coordinates & balloon definition
	 */
	public function yaPoint($model) {
		return [ 
			'lng' => (float) $model->lng, 'lat' => (float) $model->lat, 
			'icon' => '', 'header' => $model->title, 
			'body' => '', 'footer' => '',
		];
	}
	
	/* 
	 * Set poin for the Yandex map
	 * @param object $model with longitude and latitude depend on address.
	 */
	private function setPoint( $model ) {
		if ( $model->lng != "0" && $model->lat != "0" ) {
			$this->points[] = $this->yaPoint($model);
		}
	}

	/* 
	 * Fill in view
	 * @param object $model
	 * @param string $view - name of file
	 * $return string view
	 */
	private function get_view($model, $view = '_view') {
		$content = file_get_contents(plugins_url( 'views/'.$view.'.php' , __FILE__ ));
		foreach($model as $name => $value) {
			if($value === 0) $value = ''; 
			$content = str_replace( '[' . $name . ']', $value, $content );
		}
		return $content;
	}
	
	/*
	 * Get slider
	 * @param string $shortcode
	 * @param string name - slider name
	 * @return object term
	 */
	private function get_slider_term($shortcode, $name) {
		if(preg_match('/id=(\d+)/', $shortcode, $matches)) {
			// find term by name = slider id
			return get_term_by( 'name', $matches[1], $name);
		} else
			return false;
	}

	/*
	 * Get thumbnail
	 * @param string $shortcode
	 * @return string image tag
	 */
	private function get_image($shortcode) {
		global $wpdb;
		if( $term = $this->get_slider_term($shortcode, 'ml-slider') ) {
			// find objects with $term->term_taxonomy_id
			$object = $wpdb->get_row(
				"SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = $term->term_taxonomy_id"
			);
			return "<a href='" . get_permalink() . "'>" . wp_get_attachment_image( $object->object_id, 'thumbnail', false ) . "</a>";
		} else
			return '';
	}

	/*
	 * Get all images
	 * @param string $shortcode
	 * @return array of all images
	 */
	public function get_images($shortcode, $size = 'large') {
		global $wpdb;
		if( $term = $this->get_slider_term($shortcode, 'ml-slider') ) {
			// find objects with $term->term_taxonomy_id
			$objects = $wpdb->get_results(
				"SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = $term->term_taxonomy_id"
			);
			$images = [];
			foreach($objects as $object)
				$images[$object->object_id] = wp_get_attachment_image_src( $object->object_id, $size, true );
			return $images;
		} else
			return false; 
	}
	
	/*
	 * Make <select> tag
	 * @param string $item - selected item
	 * @param array $options - list of options 
	 */
	public static function show($item, $options) {
		foreach ( $options as $value => $title ) {
			if ( substr($value, 0, 1) == '-' )
				echo '<option ' . 
					($item == '-' ? 'selected' : '') . ' disabled value="-">' . $title . '</option>';
			else
				echo '<option ' . 
					($item == $value ? 'selected' : '') . ' value="' . $value . '"/>' . 
					$title . '</option>';
		}
	}
	
	/*
	 * Select categories by criteria
	 * @param array $fields - needed category fields
	 * @param array $criteria - key => value criteria 
	 */
	private function get_categories_by( $fields, $criteria ) {
		$categories = get_categories( $criteria );
		$slice = [];
		foreach ( $categories as $category ) {
			$a = [];
			foreach($fields as $field)
				$a[$field] = $category->$field;
			$slice[] = $a;
		}
		return $slice;
	}

	public function getOption($model, $option) {
		return self::$options[$option][$model->$option];
	}
}

