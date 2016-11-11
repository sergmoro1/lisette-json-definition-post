<?php
/**
 * @author - Sergey Morozov <sergmoro1@ya.ru>
 * @license - MIT
 * 
 * All possible attributes in a record. 
 * And some defaults for the forms - ./views/edit.php, ./views/criteria.php.
 * 
 */
return [
	// all fields in a record
	'legal' => [
		'what',
		'deal', 
		'type', 
		'country', 'state', 'city', 'district', 'locality', 'street', 
		'lat', 'lng', 
		'rooms', 
		'total', 'living', 'kitchen','lot', 
		'project', 
		'material', 
		'floor', 'floors',
		'price',
		'phone', 'email', 
		'description',
	],
	// defaults
 	'defaults' => [
		// admin
		'edit' => ['0sell', 'flat', '2hand', '1rooms'],
		// client side
		'criteria' => ['deal' => '*', 'what' => '*', 'p1' => 0, 'p2' => 999999999],
	],
];
