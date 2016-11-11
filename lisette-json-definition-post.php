<?php
/*
Plugin Name: Lisette JSON Definition Post
Plugin URI: http://lisette.vorst.ru
Description: JSON definition in a post content turns the text to the full featured record
Version: 1.1
Author: Sergey Morozov
Author URI: http://vorst.ru
License: MIT
*/
 
/**
 * Plugin version
 * 
 * @var string
 */
define('JDP_VERSION', '1.1');
 
/**
 * Path to the plugin directory
 * 
 * @var string
 */
define('JDP__DOCUMENT_ROOT', dirname(__FILE__));

require_once 'RealtyApplication.php';
require_once 'Criteria.php';
require_once 'YaMap.php';

/*
 * Filling in the categories from a pre-defined file.
 * Once, when the plugin is activated.
 */
function lisette_json_definition_post_activate() {
	$realty_categories = require(dirname(__FILE__) . '/config/categories.php');
	foreach($realty_categories as $category) {
		if(!get_category_by_slug($category['category_nicename'])) {
			if($category['category_parent']) {
				$term = get_category_by_slug($category['category_parent']);
				$category['category_parent'] = $term->term_id;
			}
			wp_insert_category($category);
		}
	}
}
register_activation_hook(__FILE__, 'lisette_json_definition_post_activate');

try {
    $application = new RealtyApplication();
} catch (Exception $e) {
    echo $e->getMessage();
}
