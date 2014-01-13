<?php
/**
 * Convert JSON definition to the model, 
 * choice models by criteria, 
 * edit model in a dashboard.
 * 
 * Categories that defined for real estate (you can define your own)
 * name, description, slug
 * 1-ком, одно-комнатные, 1rooms
 * 2-ком, двух-комнатные, 2rooms
 * 3-ком, трех-комнатные, 3rooms
 * 4-ком, четырех-комнатные, 4rooms
 * 5-ком и >, много-комнатные, 5rooms
 * Аренда, предложения по аренде недвижимости, 0rent
 * Продажа, предложения по продаже недвижимости, 0sell
 * комната, комнаты, room
 * 	- в-квартире, комнаты в квртире, in-flat
 * 	- отдельная, изолированные комнаты, separate
 * квартира, квартиры, flat
 * 	- вторичка, вторичное жильё, 2hand
 * 	- новостройка, новостройки, 1hand
 * дом, дома, house
 * 	- дача, дачи, dacha
 * 	- коттедж, коттеджи, cottage
 * 	- таунхаус, таунхаусы, townhouse
 * участок, участоки, lot
 * 	- коммерческий, участки коммерческие, commercial
 * 	- частный, участки частные, private
*/

class Lisette_JDP_Application
{
 	private $thumbnail = true; // if theme may use thumbnail, then false
	public $points = array(); // YaMap points
 	public $begin, $end = 0; // {} positions
 	public $hide_post_text_area; // flag
 	public $categories = array(); // all categories - term_id, slug, parent
 	public $slug = array(); // category's slug => term_id

	/* all fields names that can be standing in json definition should be defined here */
 	private static $fields = array(
 		'what', 'deal', 'type', 
 		'country', 'state', 'city', 'district', 'locality', 'street', 
 		'lat', 'lng', 
 		'rooms', 'total', 'living', 'kitchen','lot', 
 		'project', 'material', 
 		'floor', 'floors',
 		'price',
 		'phone', 'email', 
 		'description',
 	);

	/* field values */
	private static $options = array(
		'deal' => array( '0rent' => 'аренда', '0sell' => 'продажа', ),
		'what' => array( 'room' => 'комната', 'flat' => 'квартира',	'house' => 'дом', 'lot' => 'участок', ),
		'type' => array(
			'room' => array( 'separate' => 'изолированная',	'in-flat' => 'в квартире', ),
			'flat' => array( '2hand' => 'вторичка', '1hand' => 'новостройка', ),
			'house' => array( 'cottage' => 'коттедж', 'townhouse' => 'таунхаус', 'dacha' => 'дача', ),
			'lot' => array(	'private' => 'частный',	'farm' => 'сельхозназначения', 'commercial' => 'коммерческий',	),
		),
		'country' => array(	'Russia' => 'Россия', '-1' => '---', 'Bulgaria' => 'Болгария',	'Turkey' => 'Турция', 
			'Montenegro' => 'Черногория', 'Czech-Republic' => 'Чехия',
		),
		'state' => array( 'Tatarstan' => 'Татарстан', '-1' => '---', 'Mari-El' => 'Марий-Эл', ),
		'city' => array( 'Kazan' => 'Казань', '-1' => '---',
			'Naberezhnye-Chelny' => 'Набережны Челны', 'Nizhnekamsk' => 'Нижнекамск', 'Zelenodolsk' => 'Зеленодольск', '-2' => '---',
			'Elabuga' => 'Елабуга', 'Zainsk' => 'Заинск', 'Verchniy-Uslon' => 'Верхний-Услон', 'Laishevo' => 'Лаишево', '-3' => '---',
			'Volzhsk' => 'Волжск', ),
		'district' => array(
			'Kazan' => array( 'Aviastroitelny' => 'Авиастроительный район', 'Vakhitovsky' => 'Вахитовский район', 'Kirovsky' => 'Кировский район',
				'Moskovsky' => 'Московский район', 'Novo-Savinovsky' => 'Ново-Савиновский район',	'Privolzhsky' => 'Приволжский район',	'Soviet' => 'Советский район', ),
			'Naberezhnye-Chelny' => array( 'Avtozavodsky' => 'Автозаводский район', 'Komsomolsky' => 'Комсомольский район', 'Central' => 'Центральный район', ),
		),
		'rooms' => array( '-' => 'выбрать', '1rooms' => '1-комнатная', '2rooms' => '2-комнатная', 
			'3rooms' => '3-комнатная', '4rooms' => '4-комнатная', '5rooms' => 'много-комнатная', ),
		'project' => array( '-' => 'выбрать', 'hru'=>'хрущевка', 'len'=>'ленинградка', 'mos'=>'московский', 'ind'=>'индивидуальный', 'sta'=>'сталинка', ),
		'material' => array( '-' => 'выбрать', 'brick'=>'кирпичный', 'panel'=>'панельный', 'block'=>'блочный', 'monolit'=>'монолитный', 'wood'=>'деревянный', ),
	);
	
 	private static $category_settings = array (
		'default' => array('0sell', 'flat', '2hand', '1rooms'), // for fields: deal, what, type, rooms
		'exclude' => array('blog'), // exclude from the categories list
	);
 	
 	/* custom-xml's */
	public $feeds = array('avito');
	 
 	/* back-end and front-end hooks */
	public function __construct() {
		if ( is_admin() ) {
			/* css & scripts */
			add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_css_scripts' ) );
			/* form for filling ad */
			add_action( 'edit_form_after_title', array( $this, 'application_form' ) );
			add_action( 'edit_form_after_editor', array( $this, 'pair_div' )  );
			/* add new menu items */
			add_action( 'admin_bar_menu', array( $this, 'top_menu_item' ), 999 );
			add_action( 'admin_menu', array( $this, 'left_menu_item' ) );
			/* default json definition for new ad  */
			add_filter( 'default_content', array( $this, 'add_new_ad') );
			add_action( 'save_post', array( $this, 'save_post' ) );
		} else {
			/* css & scripts */
			add_action( 'wp_enqueue_scripts', array( $this, 'load_plugin_css_scripts' ) );
			/* criteria processing */
			add_action( 'pre_get_posts', array( $this, 'criteria' ) );
			/* set point on a map for single post */
			add_action( 'get_header', array( $this, 'set_point' ) );
			/* set feed name to access in URL eg. /?feed=custom-xml */
			add_filter('init', array( $this, 'init' ));
			/* content changing */
			add_filter( 'the_content', array( $this, 'prepare' ) );
			add_filter( 'the_content', array($this, 'inline_php'), 0);  
			/* order by and exlude category */
			add_filter( 'get_terms_args', array( $this, 'category_filter' ) );
			/* plugin doesn't use standard thumbnail */
			add_filter( 'post_thumbnail_html', array( $this, 'empty_thumbnail' ) );
		}
 
		add_action( 'plugins_loaded', array( $this, 'language_support') );
		$this->init_categories();
		
	}

	private function init_categories() {
		$this->categories = $this->get_categories_by( 
			array('term_id', 'slug', 'parent'), 
			array('orderby'=>'name', 'hide_empty'=>0)
		);
		foreach ( $this->categories as $category )
			$this->slug[$category['slug']] = $category['term_id'];
	}
	
	public function left_menu_item() {
        add_submenu_page( 'edit.php', '', 'Добавить объявление', 
			get_post_type_object( 'post' )->cap->create_posts, 'post-new.php?ad=1' );
	}
	
	public function add_new_ad($content) {
		if ( isset( $_GET['ad'] ) && $_GET['ad'] == 1 ) {
			$content = file_get_contents(plugins_url( 'views/new_ad.txt' , __FILE__ ));
		}
		return $content;
	}		
	
	public function top_menu_item( $wp_admin_bar ) {
		$url = admin_url() . 'post-new.php?ad=1';
		$args = array(
			'id' => 'new-ad',
			'title' => 'Добавить объявление',
			'href' => $url,
			'parent' => false,
		);
		$wp_admin_bar->add_node( $args );
	}
	
	/* insert form */
	public function application_form($post) {
		$this->hide_post_text_area = false;
		if ( $this->is_definition( $post->post_content ) ) {
			$edit = dirname( __FILE__ ) . '/views/edit.php'; 
			if( file_exists( $edit ) ) {
				if ( isset( $_GET['ad'] ) && $_GET['ad'] == 1 )
					wp_set_object_terms( $post->ID, self::$category_settings['default'], 'category' );
				include_once( $edit );
				$this->hide_post_text_area = true;
			} else
				_e('/views/edit.php not found.', 'lisette-jdp');
		}
	}
	
	/* 
	 * if application form was inserted the post_content text area will be hidden by
	 * <div id='lisette_jdp_editor' style='display:none;'>
	*/
	function pair_div($post) {
		if ( $this->hide_post_text_area ) 
			echo '</div>';
	}		

	public function empty_thumbnail( $html ) {
		global $post;
		return $this->thumbnail ? '' : ( is_single( $post ) ? '' : $html );
	}
	
	public function save_post( $post_id ) {
		if ( has_post_thumbnail($post_id) ) 
			return;
		
		if ( !($shortcode = $this->get_shortcode($_POST['content'])) )
			return;
		
		/* set thumbnail by slider */
		$thumbnail = each ( $this->get_images($shortcode, 'thumbnail') );

		add_post_meta( $post_id, '_thumbnail_id', $thumbnail['key'], true );
	}
	
	public function category_filter( $args ) {
		$args['orderby'] = 'slug';
		$blog = get_category_by_slug('blog');   
		$args['exclude'] = $blog->term_id;
		return $args;
	}
	
	/* define poin for the Yandex map */
	private function set_point_by( $model ) {
		if ( $model->lng != "0" && $model->lat != "0" ) {
			$this->points[] = array( 
				'lng' => (float) $model->lng, 'lat' => (float) $model->lat, 
				'icon' => '', 'header' => number_format($model->price, 0, '', ' '), 
				'body' => $model->title, 'footer' => $model->phone,
			);
		}
	}

	public function set_point() {
		global $post;
		if ( is_single() && $this->is_definition($post->post_content) ) {
			$model = $this->convert( $post->post_content );
			$model->title = $post->post_title;
			$this->set_point_by($model);
		}
	}
	
	public function criteria( $query ) {
		global $wpdb;
		if ( $query->is_home() && $query->is_main_query() ) {
			/* select all pablished posts */
			$q = "SELECT ID, post_content, post_title FROM $wpdb->posts " . 
				"WHERE post_status = 'publish' AND post_type = 'post'"; 
			$ads = $wpdb->get_results( $q ); 
		
			/* fill in $models */
			$models = array();
			foreach ($ads as $ad) {  
				if ( $this->is_definition($ad->post_content) ) {
					$model = $this->convert( $ad->post_content );
					$model->title = $ad->post_title;
					$model->shortcode = $this->get_shortcode( $ad->post_content );
					$models[$ad->ID] = $model;
				}  
			}
			/* make the criteria */
			$deal = isset( $_GET['deal'] ) ? $_GET['deal'] : '*';
			$what = isset( $_GET['what'] ) ? $_GET['what'] : '*';

			$p1 = isset( $_GET['p1'] ) ? (int) $_GET['p1'] : 0;
			$p2 = isset( $_GET['p2'] ) ? (int) $_GET['p2'] : 999999999;
			
			$this->points = array(); 
			$ids = array();
			/* choce ads by criteria */
			foreach ( $models as $id => $model ) {
				if ( ( $deal == '*' || $deal == $model->deal ) && 
					( $what == '*' || $what == $model->what ) && 
					$model->price >= $p1 && $model->price <= $p2 ) {
					$ids[] = $id;
					$this->set_point_by($model);
				}
			}
			/* set new query */
			if ( count($ids) == 0 ) 
				$ids[] = 0; // nothing matches
			$query->set( 'post__in', $ids );
		}
	}

	public function language_support() {
		load_plugin_textdomain( 'lisette-json-definition-post', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
 	}
	
	public function init() {
		/* set feeds */
		foreach($this->feeds as $feed)
			add_feed( $feed, array( $this, 'xml' ) );
	}

	public function xml() {
		if ( have_posts() ) {
			$feed = dirname( __FILE__ ) . '/views/' .  $_GET['feed'] . '.php'; 
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
		wp_register_script( 'yandexMap', 'http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU' );
		wp_enqueue_script( 'yandexMap' );
		wp_register_script( 'lisette_jdp_edit', plugins_url( 'js/edit.js', __FILE__ ) );
		wp_enqueue_script( 'lisette_jdp_edit' );
		wp_register_script( 'lisette_jdp_address', plugins_url( 'js/address.js', __FILE__ ) );
		wp_enqueue_script( 'lisette_jdp_address' );
	}
 
	public function load_plugin_css_scripts() {
		wp_enqueue_style( 'lisette-json-definition-post', plugins_url( 'css/front-end.css', __FILE__ ));
		/* load Yandex map package */
		wp_register_script( 'yandexMap', 'http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU' );
		wp_enqueue_script( 'yandexMap' );
		/* load Lisette scripts for Yandex map */
		wp_register_script( 'lisette_jdp_yamap', plugins_url( 'js/yamap.js', __FILE__ ) );
		wp_enqueue_script( 'lisette_jdp_yamap' );
		/* set points on the Yandex map */
		wp_localize_script( 'lisette_jdp_yamap', 'yaMapPoints', $this->points );
	}

	/* 
	 * read json definition from post and return a model 
	 * post format: {name1:"string",name2:number ...}
	*/
	private function convert($content) {
		/* call $this->definition before to set $begin, $end */
		$json_string = substr( $content, $this->begin, $this->end - $this->begin + 1 );

		/* Replace, delete unnecessary symbols, tags */
		$json_string = str_replace( array("&#171;","&#187;","&#8243;"), '"', $json_string );
		$json_string = strip_tags( $json_string );
		$json_string = str_replace( array("\r","\n"), '', $json_string );
		/* from name:value to "name":value */ 
		foreach(self::$fields as $field) {
			$json_string = str_replace( $field.':', '"' . $field . '":', $json_string );
		}
		
		return json_decode ( $json_string );
	}

	/* schort code for image slider */
	private function get_shortcode($content) {
		if(preg_match('/\[\w+ id=\d+\]/', $content, $matches))
			return $matches[0];
		else 
			return '';
	}

	private function is_definition($content) {
		$this->begin = strpos($content, '{');
		$this->end = strpos($content, '}');
		return ( $this->begin === false || $this->end === false ) ? false : true;
	}

	public function format($model, $xml=false) {
		if ( !$xml ) {
			/* set address, skip default state and country */
			$model->address = $model->street . ' ' . 
				( ( isset( $model->locality ) && $model->locality<>'' ? $model->locality . ' ' :  '' ) . self::$options['city'][$model->city] ) . 
				( $model->state == 'Tatarstan' ? '' : ' ' . self::$options['state'][$model->state] ) .
				( $model->country == 'Russia' ? '' : ' ' . self::$options['country'][$model->country] );
			/* format price */
			$model->pricePerItem = $model->total > 0 ? number_format($model->price / $model->total, 0, '', ' ') : _e('Square is not defined');
			$model->priceUnit = $model->what == 'lot' ? 'сот' : 'м2';
			$model->priceTotal = number_format($model->price, 0, '', ' ');
			/* project, material */
			$model->project = $model->project == '-' ? '-' : self::$options['project'][$model->project];
			$model->material = $model->material == '-' ? '-' : self::$options['material'][$model->material];
		}
		
		return $model;
	}

	public function prepare($content) {
		global $post;
		if( !$this->is_definition($content) )
			/* no definitions - return as is */
			return $content;
		
		if( $model=$this->convert($content) ) {
			
			$model = $this->format($model);			

			$shortcode = $this->get_shortcode($content);
			
			/* different views for list and single post */
			if ( is_single($post) ) {
				$content = $shortcode . $this->get_view($model, 'view_' . $model->what);
				$this->points[0] = array( 
					'lng' => (float) $model->lng, 'lat' => (float) $model->lat, 
					'icon' => '', 'header' => $model->priceTotal, 
					'body' => $model->title, 'footer' => $model->phone,
				);
			} else {
				$rooms = '_rooms';
				if ( $model->what == 'lot' )
					$rooms = '';
				if ( $this->thumbnail ) {
					$model->image = $this->get_image( $shortcode );
					$content = $this->get_view( $model, '_view_thumbnail' . $rooms );
				} else
					$content = $this->get_view( $model, '_view' . $rooms );
			}
		} else { 
			$content = _e( 'json format error', 'lisette-jdp' ) . ' - ' . json_last_error();
		}
		return $content;
	}

	private function get_view($model, $view ) {
		$content = file_get_contents(plugins_url( 'views/'.$view.'.php' , __FILE__ ));
		foreach($model as $name => $value) {
			if($value === 0) $value = ''; 
			$content = str_replace( '[' . $name . ']', $value, $content );
		}
		return $content;
	}
	
	private function get_slider_term($shortcode, $name) {
		if(preg_match('/id=(\d+)/', $shortcode, $matches)) {
			/* find term by name = slider id */
			return get_term_by( 'name', $matches[1], $name);
		} else
			return false;
	}

	private function get_image($shortcode) {
		global $wpdb;
		if( $term = $this->get_slider_term($shortcode, 'ml-slider') ) {
			/* find objects with $term->term_taxonomy_id */
			$object = $wpdb->get_row(
				"SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = $term->term_taxonomy_id"
			);
			return "<a href='" . get_permalink() . "'>" . wp_get_attachment_image( $object->object_id, 'thumbnail', false ) . "</a>";
		} else
			return '';
	}

	public function get_images($shortcode, $size='large') {
		global $wpdb;
		if( $term = $this->get_slider_term($shortcode, 'ml-slider') ) {
			/* find objects with $term->term_taxonomy_id */
			$objects = $wpdb->get_results(
				"SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = $term->term_taxonomy_id"
			);
			$images = array();
			foreach($objects as $object)
				$images[$object->object_id] = wp_get_attachment_image_src( $object->object_id, $size, true );
			return $images;
		} else
			return false; 
	}
	
	/* make <select> tag by $options with choiced $item */
	public function show($item, $options) {
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
	
	private function get_categories_by( $fields, $criteria ) {
		$categories = get_categories( $criteria );
		$slice = array();
		foreach ( $categories as $category ) {
			$a = array();
			foreach($fields as $field)
				$a[$field] = $category->$field;
			$slice[] = $a;
		}
		return $slice;
	}

	/* php in posts or WordPress pages: [exec]code[/exec] */  
	function exec_php($matches){  
			eval('ob_start();'.$matches[1].'$inline_execute_output = ob_get_contents();ob_end_clean();');  
			return $inline_execute_output;  
		}  
	function inline_php($content){  
		$content = preg_replace_callback('/\[exec\]((.|\n)*?)\[\/exec\]/', array($this, 'exec_php'), $content);  
		$content = preg_replace('/\[exec off\]((.|\n)*?)\[\/exec\]/', '$1', $content);  
		return $content;  
	}  
}
