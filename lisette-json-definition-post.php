<?php
/*
Plugin Name: Lisette JSON Definition Post
Plugin URI: http://lisette.su/re-agent
Description: Record Definition in a post_content by JSON like notation - { name:"string", name:number, ... }
Version: 1.0
Author: sergmoro1@ya.ru
Author URI: http://lisette.su
License: GPL
*/
 
/**
 * Plugin version
 * 
 * @var string
 */
define('JDP_VERSION', '1.0');
 
/**
 * Path to the plugin directory
 * 
 * @var string
 */
define('JDP__DOCUMENT_ROOT', dirname(__FILE__));

require_once 'Application.php';
require_once 'Criteria.php';
require_once 'YaMap.php';
 
try {
    $application = new Lisette_JDP_Application();
} catch (Exception $e) {
    echo $e->getMessage();
}
