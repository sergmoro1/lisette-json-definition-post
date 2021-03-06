<?php
/**
 * @author - Sergey Morozov <sergmoro1@ya.ru>
 * @license - MIT
 *
 * Define here all categories of your application in a WordPress terms. 
 * 
 */
return [
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => 'Продажа',
		'category_description' => 'предложения по продаже недвижимости',
		'category_nicename' => '0sell',
		'category_parent' => 0,
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => 'Аренда',
		'category_description' => 'предложения по аренде недвижимости',
		'category_nicename' => '0rent',
		'category_parent' => 0,
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => '1-ком',
		'category_description' => 'одно-комнатные',
		'category_nicename' => '1rooms',
		'category_parent' => 0,
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => '2-ком',
		'category_description' => 'двух-комнатные',
		'category_nicename' => '2rooms',
		'category_parent' => 0,
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => '3-ком',
		'category_description' => 'трех-комнатные',
		'category_nicename' => '3rooms',
		'category_parent' => 0,
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => '4-ком',
		'category_description' => 'четырех-комнатные',
		'category_nicename' => '4rooms',
		'category_parent' => 0,
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => '5-ком и >',
		'category_description' => 'много-комнатные',
		'category_nicename' => '5rooms',
		'category_parent' => 0,
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => 'дом',
		'category_description' => 'дома',
		'category_nicename' => 'house',
		'category_parent' => 0,
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => 'дача',
		'category_description' => 'дачи',
		'category_nicename' => 'dacha',
		'category_parent' => 'house',
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => 'коттедж',
		'category_description' => 'коттеджи',
		'category_nicename' => 'cottage',
		'category_parent' => 'house',
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => 'таунхаус',
		'category_description' => 'таунхаусы',
		'category_nicename' => 'townhouse',
		'category_parent' => 'house',
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => 'квартира',
		'category_description' => 'квартиры',
		'category_nicename' => 'flat',
		'category_parent' => '0',
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => 'вторичка',
		'category_description' => 'вторичное жильё',
		'category_nicename' => '2hand',
		'category_parent' => 'flat',
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => 'новостройка',
		'category_description' => 'новострйки',
		'category_nicename' => '1hand',
		'category_parent' => 'flat',
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => 'комната',
		'category_description' => 'комнаты',
		'category_nicename' => 'room',
		'category_parent' => '0',
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => 'в-квартире',
		'category_description' => 'комнаты в квртире',
		'category_nicename' => 'in-flat',
		'category_parent' => 'room',
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => 'отдельная',
		'category_description' => 'изолированные комнаты',
		'category_nicename' => 'saparate',
		'category_parent' => 'room',
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => 'участок',
		'category_description' => 'земельные участки',
		'category_nicename' => 'lot',
		'category_parent' => '0',
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => 'коммерческий',
		'category_description' => 'участки коммерческие',
		'category_nicename' => 'commercial',
		'category_parent' => 'lot',
	],
	[
		'cat_id' => 0,
		'taxonomy' => 'category',
		'cat_name' => 'частный',
		'category_description' => 'участки частные',
		'category_nicename' => 'private',
		'category_parent' => 'lot',
	],
];
