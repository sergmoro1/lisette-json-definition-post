<?php
/**
 * @author - Sergey Morozov <sergmoro1@ya.ru>
 * @license - MIT
 *
 * <select> lists values for the admin dashboard. 
 * 
 */
return [
	'deal' => [ 
		'0rent' => 'аренда', 
		'0sell' => 'продажа',
	],
	'what' => [ 
		'room' => 'комната', 
		'flat' => 'квартира',
		'house' => 'дом', 
		'lot' => 'участок',
	],
	'type' => [
		'room' => [ 
			'separate' => 'изолир.',
			'in-flat' => 'в кавртире',
		],
		'flat' => [
			'2hand' => 'вторичка',
			'1hand' => 'новостройка',
		],
		'house' => [
			'cottage' => 'коттедж',
			'townhouse' => 'тоунхауз',
			'dacha' => 'дача',
		],
		'lot' => [
			'private' => 'частный',
			'farm' => 'сельхоз',
			'commercial' => 'коммерч.',
		],
	],
	'country' => [
		'Russia' => 'Россия',
		'-1' => '-',
		'Bulgaria' => 'Болгария',
		'Turkey' => 'Турция', 
		'Montenegro' => 'Черногория',
		'Czech-Republic' => 'Чехия',
	],
	'state' => [
		'Tatarstan' => 'Татарстан', 
		'-1' => '---', 
		'Mari-El' => 'Марий Эл',
	],
	'city' => [
		'Kazan' => 'Казань', 
		'-1' => '---',
		'Naberezhnye-Chelny' => 'Набережные Челны', 
		'Nizhnekamsk' => 'Нижнекамск', 
		'Zelenodolsk' => 'Зеленодольск', 
		'-2' => '---',
		'Elabuga' => 'Елабуга', 
		'Zainsk' => 'Заинск', 
		'Verchniy-Uslon' => 'Верхний Услон', 
		'Laishevo' => 'Лаишево', 
		'-3' => '---',
		'Volzhsk' => 'Волжск',
	],
	'district' => [
		'Kazan' => [ 
			'Aviastroitelny' => 'Авиастроительный', 
			'Vakhitovsky' => 'Вахитовский', 
			'Kirovsky' => 'Кировский',
			'Moskovsky' => 'Московский', 
			'Novo-Savinovsky' => 'Ново-Савиновский',
			'Privolzhsky' => 'Приволжский',	
			'Soviet' => 'Советсткий',
		],
		'Naberezhnye-Chelny' => [ 
			'Avtozavodsky' => 'Автозаводский', 
			'Komsomolsky' => 'Комсомольский', 
			'Central' => 'Центральный',
		],
	],
	'rooms' => [ 
		'-' => 'выбор', 
		'1rooms' => '1 ком.', 
		'2rooms' => '2 ком.', 
		'3rooms' => '3 ком.', 
		'4rooms' => '4 ком.', 
		'5rooms' => '5 ком.',
	],
	'project' => [ 
		'-' => 'выбор', 
		'hru'=>'хрущевка', 
		'len'=>'ленинградка', 
		'mos'=>'московский', 
		'ind'=>'индивидуальный', 
		'sta'=>'сталинка',
	],
	'material' => [
		'-' => 'выбор', 
		'brick'=>'кирпич', 
		'panel'=>'панельный', 
		'block'=>'блок', 
		'monolit'=>'монолит', 
		'wood'=>'дерево',
	],
];
