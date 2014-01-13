/*
 * Description: When field value is changed, json definition in content is chenged too - { .. field:"value", ... }
 * Used to: ./plugins/lisette-json-definition-post/views/edit.php
 * Author: sergmoro1@ya.ru
 * Author URI: http://lisette.su
*/

/* GLOBAL Variables */

options = {
	/* what 'type' field should be shown depends on 'lisette_jdp_what' */
	'type' : ['room', 'flat', 'house', 'lot'], // 
	/* towns with districts */
	'district' : ['Kazan', 'Naberezhnye-Chelny'], 
};

/* all fields that can be shown in appendix */
all_fields = ['rooms', 'floor', 'square', 'lot', 'project'];

/* fields that should be hide in appendix from the value of 'lisette_jdp_what' */
fields_to_hide_by = {
	'room' : ['rooms', 'square', 'lot'],
	'flat' : ['lot'],
	'house' : ['floor', 'project']
};
/* appendix block background depends on 'lisette_jdp_what' value */
div_background_by = { 'room' : '#f1f1f1', 'flat' : '#f0ffff', 'house' : '#fff8dc' };

/* cateories defaults depends on 'lisette_jdp_what' value */
low_level_defaults = {'flat' : '2hand', 'house' : 'cottage', 'room' : 'separate', 'lot' : 'private'};
rooms_defaults = {'flat' : '1rooms', 'house' : '5rooms', 'room' : '-', 'lot' : '-'};

/* set cateories onload event */
categories = []; // [{'term_id' : id, 'slug' : name, 'parent' : id} ...]
slug = {}; // {name : id, ...}

/* json values delimiter */
delimiter = ',';
/* value that should be deleted from string */
clean = '';

/* 
 * When 'lisette_jdp_what' is changed we have to change appendix block and fields.
*/
function show_appendix( value ) {
	block = document.getElementById( 'block_appendix' );
	if ( value == 'lot' )
		/* no appendix */
		block.style.display = 'none';
	else {
		/* show block */
		block.style.display = 'block';
		/* change block color */
		block.style.background = div_background_by[value];
		var hide;
		/* show or hide fields by block name = value */
		for ( var i=0; i < all_fields.length; i++ ) {
			hide = false;
			for ( var j=0; j < fields_to_hide_by[value].length; j++ ) {
				if ( all_fields[i] == fields_to_hide_by[value][j] )
					hide = true;
			}
			block = document.getElementById( 'block_' + all_fields[i] );
			if ( hide ) 
				block.style.display = 'none';
			else {
				if ( block.tagName == 'SPAN' )
					block.style.display = 'inline-block';
				else
					block.style.display = 'block';
			}
		}
	}
}

/* 
 * When 'lisette_jdp_what' is changed we have to change 'lisette_jdp_type ...'.
 * Type depends on real estate. One should be shown and other's hide. 
*/
function hide_all_and_display( one, field ) {
	var select;
	var empty = true;
	for ( var i=0; i < options[field].length; i++ ) {
		select = document.getElementById( "lisette_jdp_" + field + "_" + options[field][i] );
		if ( one == options[field][i] ) {
			select.style.display = 'inline';
			replace_content_by( select );
			empty = false;
		} else
			select.style.display = 'none';
	}
	if( empty )
		replace_by( field, "");
}

/* 
 * When slider shortcode is changed we have to replace information about slider in content or
 * add it to the head.
*/
function replace_slider_by(value) {
	var content = document.getElementById( "content" );
	var re = new RegExp ( /\[metaslider id=\d+\]/ );
	if ( re.test( content.value ) )
		content.value.replace( re, value );
	else
		content.value =  value + content.value;
}

/* 
 * JSON definition post has { name: "string", name: digit ... } notation.
 * So, we have to find name in content by part of obj.id and replace value by obj.value.
*/
function replace_content_by(obj) {
	var name = obj.id.match(/lisette_jdp_([A-Za-z0-9]+)/)[1];
	if ( clean == '' )
		replace_by(name, obj.value);
	else {
		var re = new RegExp ( clean );
		var value = obj.value.replace( re, '' );
		replace_by(name, value);
	}
}

function replace_by(name, value) {
	var content = document.getElementById( "content" );
	if ( /^\d+(\.\d{0,2})?$/.test ( value ) ) { // digit
		var re = new RegExp ( name + ':\.*,' );
		content.value = content.value.replace(re, name + ':' + value + delimiter);
	} else { // string
		var re = new RegExp ( name + ':"\.*"' + delimiter, 'm' );
		/* description is the last field, so no delimiter. and we have to clean '" simbols */
		if ( delimiter == '' )
			value = value.replace( /["']/mg, '' );
		content.value = content.value.replace(re, name + ':"' + value + '"' + delimiter);
	}
}

/* 
 * Set categories and slug.
 * @array - json string of associative array.
*/
function set_categories( array ) {
	if ( categories.length == 0 ) {
		categories = array;
		for ( var i=0; i < categories.length; i++ )
			slug[categories[i].slug] = categories[i].term_id;
	}
}

/* 
 * Different rooms defaults for 'lisette_jdp_what'.
 * We have to switch off all rooms categories and set default checkbox.
*/
function set_default_roooms_by( what ) {
	var default_value = rooms_defaults[what.options[what.selectedIndex].value];
	var rooms = document.getElementById( 'lisette_jdp_rooms' );
	for ( var i=0; i < rooms.options.length; i++ ) {
		id = 'in-category-' + slug[rooms.options[i].value];
		if ( ! (check_box = document.getElementById( id )) ) 
			continue;
		if ( rooms.options[i].value == default_value ) {
			rooms.options.selectedIndex = i;
			check_box.checked = 1;
		} else
			check_box.checked = 0;
	}
}

/* 
 * When obj has changed the categories should changed too.
*/
function change_category( obj ) {
	var choiced = {}; var other = []; var k = 0;
	for ( var i=0; i < categories.length; i++ ) {
		category = categories[i];
		var in_group = false;
		/* definition group of categories */
		for ( var j=0; j < obj.options.length; j++ ) {
			if ( obj.options[j].value == category.slug )
				in_group = true;
		}
		if ( in_group ) {
			id = 'in-category-' + category.term_id;
			check_box = document.getElementById( id );
			if ( obj.options[obj.selectedIndex].value == category.slug ) {
				check_box.checked = 1;
				choiced['id'] = category.term_id;
				choiced['value'] = category.slug;
			} else {
				check_box.checked = 0;
				other[k++] = category.term_id;
			}
		}
	}
	/* choice one category in group and unchoice other */
	var first_in_next_level = 0;
	for ( var i=0; i < categories.length; i++ ) {
		category = categories[i];
		if ( category.parent == choiced.id ) {
			id = 'in-category-' + category.term_id;
			check_box = document.getElementById( id );
			if ( category.slug == low_level_defaults[choiced.value] )
				check_box.checked = 1;
			else
				check_box.checked = 0;
		}
		for ( var j=0; j < other.length; j++ ) {
			if ( category.parent == other[j] ) {
				id = 'in-category-' + category.term_id;
				check_box = document.getElementById( id );
				check_box.checked = 0;
			}
		}
	}
}
