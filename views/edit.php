<script>
/* 
 * ../js/edit.js
 *
 * Set categories and show needed appendix block.
*/
window.onload = function() { 
	set_categories( <?php echo json_encode ( $this->categories ) ; ?> ); 
	show_appendix( document.getElementById('lisette_jdp_what').value );

	document.getElementById( 'lisette_jdp_save' ).onclick = function() {
		document.getElementById( 'publish' ).click();
	}
}
</script>

<?php
/*
 * Form for editing real estate json definition post
*/

global $post;

if ( !($model = $this->convert($post->post_content)) )
	echo _e( 'json format error', 'lisette-jdp' ) . ' - ' . json_last_error();
?>

<form>

<!-- Key fields -->

<p><b>Основные параметры</b></p>
<p>
	<select class='list' id='lisette_jdp_deal' onchange='change_category(this); replace_content_by(this);'>
		<?php $this->show( $model->deal, self::$options['deal'] ); ?>
	</select>

	<select class='list' id="lisette_jdp_what" 
		onchange='change_category(this); 
			hide_all_and_display(this.value, "type"); 
			replace_content_by(this); 
			set_default_roooms_by ( this );
			show_appendix( this.value );' 
		onfocus='show_appendix( this.value );'>
		<?php $this->show( $model->what, self::$options['what'] ); ?>
	</select>

	<select class='list' id="lisette_jdp_type_room"  onchange='change_category(this); replace_content_by(this);'
		style='display:<?php echo $model->what == 'room' ? 'inline' : 'none'; ?>;' >
		<?php $this->show( $model->what, self::$options['type']['room'] ); ?>
	</select>
	
	<select class='list' id="lisette_jdp_type_flat" onchange='change_category(this); replace_content_by(this);'
		style='display:<?php echo $model->what == 'flat' ? 'inline' : 'none'; ?>;' >
		<?php $this->show( $model->what, self::$options['type']['flat'] ); ?>
	</select>
	
	<select class='list' id="lisette_jdp_type_house" onchange='change_category(this); replace_content_by(this);'
		style='display:<?php echo $model->what == 'house' ? 'inline' : 'none'; ?>;' >
		<?php $this->show( $model->what, self::$options['type']['house'] ); ?>
	</select>

	<select class='list' id='lisette_jdp_type_lot' onchange='change_category(this); replace_content_by(this);'
		style='display:<?php echo $model->what == 'lot' ? 'inline' : 'none'; ?>;' >
		<?php $this->show( $model->what, self::$options['type']['lot'] ); ?>
	</select>

</p>

<p>
	<span class='label'>Площадь м2 (участок в сот)</span>
	<input class='digit' type='text' id='lisette_jdp_total' value='<?php echo $model->total; ?>' 
		onchange='replace_content_by(this);' />
</p>

<p>
	<span class='label'>Цена руб.</span>
	<input class='digit' type='text' id='lisette_jdp_price' value='<?php echo $model->price; ?>' 
		onchange='replace_content_by(this);' />
</p>

<!-- /Key fields -->

<!-- Address -->

<p><b>Адрес</b></p>
<p>
	<select class='list' id="lisette_jdp_country" onchange='replace_content_by(this); address_is_changed();'>
		<?php $this->show( $model->country, self::$options['country'] ); ?>
	</select>

	<select class='list' id="lisette_jdp_state" onchange='replace_content_by(this); address_is_changed();'>
		<?php $this->show( $model->state, self::$options['state'] ); ?>
	</select>

	<select id="lisette_jdp_city" 
		onchange='hide_all_and_display(this.value, "district"); replace_content_by(this); address_is_changed();'>
		<?php $this->show( $model->city, self::$options['city'] ); ?>
	</select>

	<select id="lisette_jdp_district_Kazan" onchange='replace_content_by(this); address_is_changed();'
		style='display:<?php echo $model->city == 'Kazan' ? 'inline' : 'none'; ?>;'>
		<?php $this->show( $model->district, self::$options['district']['Kazan'] ); ?>
	</select>

	<select id="lisette_jdp_district_Naberezhnye-Chelny" onchange='replace_content_by(this); address_is_changed();'
		style='display:<?php echo $model->city == 'Naberezhnye-Chelny' ? 'inline' : 'none'; ?>;'>
		<?php $this->show( $model->district, self::$options['district']['Naberezhnye-Chelny'] ); ?>
	</select>
</p>
<p>
	<span class='label'>Насел. пункт</span>
	<input type="text" id="lisette_jdp_locality" value="<?php echo $model->locality; ?>" 
		onchange='replace_content_by(this); address_is_changed();' />
</p>
<p>
	<span class='label'>Улица, дом</span>
	<input type="text" id="lisette_jdp_street" value="<?php echo $model->street; ?>" 
		onchange='replace_content_by(this); address_is_changed();' />
</p>

<!-- /Address -->

<!-- Appendix -->
<div id='block_appendix'>

<p><b>Дополнительные параметры</b></p>
<p id='block_rooms'>
	<span class='label'>Количество комнат</span>
	<select class='list' id='lisette_jdp_rooms' 
		onchange='change_category(this); clean="rooms"; replace_content_by(this); clean="";' >
		<?php $this->show( $model->rooms, self::$options['rooms'] ); ?>
	</select>
</p>

<p>
	<span id='block_floor'>
		<span class='label'>этаж</span>
		<input class='digit' type='text' id='lisette_jdp_floor' value='<?php echo $model->floor; ?>' 
			onchange='replace_content_by(this);' />
	</span>
	<span class='label'>этажей</span>
	<input class='digit' type='text' id='lisette_jdp_floors' value='<?php echo $model->floors; ?>' 
		onchange='replace_content_by(this);' />
</p>

<p id='block_square'>
	<span class='label'>Площадь жилая м2</span>
	<input class='digit' type='text' id='lisette_jdp_living' value='<?php echo $model->living; ?>' 
		onchange='replace_content_by(this);' />
	<span class='label'>кухня м2</span>
	<input class='digit' type='text' id='lisette_jdp_kitchen' value='<?php echo $model->kitchen; ?>' 
		onchange='replace_content_by(this);' />
</p>

<p id='block_lot'>
	<span class='label'>Площадь участка сот</span>
	<input class='digit' type='text' id='lisette_jdp_lot' value='<?php echo $model->lot; ?>' 
		onchange='replace_content_by(this);' />
</p>

<p>
	<span id='block_project'>
		<span class='label'>Проект</span>
		<select class='list' id="lisette_jdp_project" onchange='replace_content_by(this);'>
			<?php $this->show( $model->project, self::$options['project'] ); ?>
		</select>
	</span>
	<span class='label'>Материал</span>
	<select class='list' id="lisette_jdp_material" onchange='replace_content_by(this);'>
		<?php $this->show( $model->material, self::$options['material'] ); ?>
	</select>
</p>

</div>
<!-- /Appendix -->

<!-- Contacts -->

<p><b>Контакты</b></p>
<p>
	<span class='label'>Телефон</span>
	<input type='text' id='lisette_jdp_phone' value='<?php echo $model->phone; ?>' onchange='replace_content_by(this);' />
</p>

<p>
	<span class='label'>E-mail</span>
	<input type='text' id='lisette_jdp_email' value='<?php echo $model->email; ?>' onchange='replace_content_by(this);' />
</p>

<!-- /Contacts -->

<!-- Description -->
<p><b>Описание</b></p>
<textarea style='width:100%;height:200px;' id='lisette_jdp_description' 
	onchange='delimiter=""; replace_content_by(this); delimiter=","; '><?php echo $model->description; ?></textarea>
<!-- /Description -->

<!-- Photos -->
<p><b>Фотографии</b></p>
<?php 
$shortcode = $this->get_shortcode( $post->post_content );
$metaslider = '';
if ( $shortcode ) {
	$slider = $this->get_slider_term( $shortcode, 'ml-slider' );
	$images = $this->get_images( $shortcode );
	foreach( $images as $image ) {
		echo '<img class="thumbnail" src="' . $image[0] . '" />';
	}
} elseif ( isset($_GET['id']) ) {
	$metaslider =  '[metaslider id=' . $_GET['id'] . "]\n";
	$post->post_content = $metaslider . $post->post_content;
}
?>
<p>
	<span class='label'>Short-code</span>
	<input type='text' value='<?php echo $shortcode ? $shortcode : $metaslider; ?>' onchange='replace_slider_by(this.value);' />
	<a href='<?php echo admin_url() . 'admin.php?page=metaslider' . ( isset($slider) ? '&id=' . $slider->slug : '&add=true' ) . '&post=' . $post->ID; ?>'>Добавить / удалить фотографии</a>
</p>
<!-- /Photos -->

<p>
	<input type="button" class="button button-primary button-large" id='lisette_jdp_save' value="Сохранить" />
	-- press here for control --> 
	<a onclick='var editor = document.getElementById( "lisette_jdp_editor" );  
		editor.style.display = editor.style.display == "none" ? "block" : "none";'>
		<?php echo _e('content editor'); ?>
	</a>
</p>

<input id='lisette_jdp_lng' type='hidden' value='<?php echo $model->lng; ?>'>
<input id='lisette_jdp_lat' type='hidden' value='<?php echo $model->lat; ?>'>

</form>

<br/>

<div id='lisette_jdp_editor' style='display:none;'>
