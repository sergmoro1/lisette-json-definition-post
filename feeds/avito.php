<?php
/**
 * @author - Sergey Morozov <sergmoro1@ya.ru>
 * @license - MIT
 * 
 * Avito.ru feed
 *
 */

global $post;

$what = array('flat'=>'Квартиры', 'room'=>'Комнаты', 'house'=>'Дома, дачи, коттеджи', 'lot'=>'Участки');
$deal = array('0sell'=>'Продам', '0rent'=>'Сдам');
$marketType = array('1hand'=>'Новостройка', '2hand'=>'Вторичка');
$objectType = array('cottage'=>'Коттедж', 'townhouse'=>'Таунхаус', 'dacha'=>'Дача', 
	'private'=>'Поселений (ИЖС)', 'farm'=>'Сельхозназначения (СНТ, ДНП)', 'commercial'=>'Промназначения');
$region = array( 'Naberezhnye-Chelny' => 'Тукаевский район', 'Nizhnekamsk' => 'Нижнекамский район', 'Zelenodolsk' => 'Зеленодольский район',
			'Elabuga' => 'Елабужский район', 'Zainsk' => 'Заинский район', 'Verchniy-Uslon' => 'Верхне-Услонский район', 'Laishevo' => 'Лаишевский район');
$project = array('cru'=>'Хрущевка', 'len'=>'Ленинградка', 'mos'=>'Московский', 'ind'=>'Индивидуальный', 'sta'=>'Сталинка');
$houseType = array('brick'=>'Кирпичный', 'panel'=>'Панельный', 'block'=>'Блочный', 'monolit'=>'Монолитный', 'wood'=>'Деревянный');
$wallsType = array('brick'=>'Кирпич', 'panel'=>'Ж/б панели', 'wood'=>'Брус', 'block'=>'Пеноблоки');

header('Content-type: text/xml; charset=utf-8', true);
echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;

?>

<Ads target="Avito.ru" formatVersion="1">

<?php while (have_posts()) : the_post(); ?>
	<?php	if( !$this->is_definition($post->post_content) ) continue; ?>

	<?php if( $model = $this->convert($post->post_content) ) : ?>

		<?php
		$model = $this->format($model, true);			
		$shortcode = $this->get_shortcode($post->post_content);
		$images = $this->get_images($shortcode);
		?>

	<?php else: ?>
		<Ad>
			<Id><?php echo $post->Id . _e('Definition error'); continue; ?></Id>
		</Ad>
	<?php endif; ?>
	
	<Ad>
		<Id><?php echo $post->ID; ?></Id>
		<AdStatus>Free</AdStatus>
		
		<Category><?php echo $what[$model->what]; ?></Category>
		<OperationType><?php echo $deal[$model->deal]; ?></OperationType>

		<?php if( $model->what == 'flat' && isset($model->type) ): ?>
			<MarketType><?php echo $marketType[$model->type]; ?></MarketType>
		<?php endif; ?>

		<?php if( in_array($model->what, array('house','lot')) && isset($model->type) ): ?>
			<ObjectType><?php echo $objectType[$model->type]; ?></ObjectType>
		<?php endif; ?>
		
		<Country><?php echo self::$options['country'][$model->country]; ?></Country>
		<Region><?php echo self::$options['state'][$model->state]; ?></Region>

		<?php if( isset($region[$model->city]) ): ?>
			<RegionArea><?php echo $region[$model->city]; ?></RegionArea>
		<?php endif; ?>

		<?php if( isset($model->city) ): ?>
			<City><?php echo self::$options['city'][$model->city]; ?></City>
		<?php endif; ?>

		<?php if( isset($model->district) && $model->district<>'' ): ?>
			<CityArea><?php echo self::$options['district'][$model->city][$model->district]; ?></CityArea>
		<?php endif; ?>

		<?php if( isset($model->locality) && $model->locality<>'' ): ?>
			<Locality><?php echo $model->locality; ?></Locality>
		<?php endif; ?>

		<Street><?php echo $model->street; ?></Street>
		
		<?php if( in_array($model->what, array('flat','house')) && isset($model->rooms) ): ?>
			<Rooms><?php echo substr( $model->rooms, 0, 1 ); ?></Rooms>
		<?php endif; ?>
		
		<?php if( in_array($model->what, array('room','flat','house')) ): ?>
			<Square><?php echo $model->total; ?></Square>
		<?php endif; ?>

		<?php if( $model->what == 'flat' ): ?>
			<?php if( isset($model->living) ): ?>
				<Living><?php echo $model->living; ?></Living>
			<?php endif; ?>
			<?php if( isset($model->kitchen) ): ?>
				<Kitchen><?php echo $model->kitchen; ?></Kitchen>
			<?php endif; ?>
		<?php endif; ?>
		
		<?php if( in_array($model->what, array('house','lot')) && isset($model->lot) ): ?>
		<LandArea><?php echo $model->lot; ?></LandArea>
		<?php endif; ?>

		<?php if( in_array($model->what, array('room','flat')) && isset($model->project) ): ?>
		<Project><?php echo $project[$model->project]; ?></Project>
		<?php endif; ?>

		<?php if( $model->what == 'house' && isset($model->material) ): ?>
		<HouseType><?php echo $houseType[$model->material]; ?></HouseType>
		<?php endif; ?>

		<?php if( in_array($model->what, array('room','flat')) && isset($model->material) ): ?>
		<WallsType><?php echo $wallsType[$model->material]; ?></WallsType>
		<?php endif; ?>

		<Price><?php echo $model->price; ?></Price>
		
		<?php if( $images ): ?>
			<Images>
			<?php foreach($images as $image): ?>
				<Image url='<?php echo $image[0]; ?>'/>
			<?php endforeach; ?>
			</Images>
		<?php endif; ?>
		
		<Description><?php echo $model->description; ?></Description>
	</Ad>
<?php endwhile; ?>
</Ads>
