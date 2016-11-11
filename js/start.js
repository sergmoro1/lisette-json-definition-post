/**
 * Description: Initialization & events binding.
 * 
 * Attributes and events depends on Realty Agency template.
 * If you have other set of attributes change events declarations.
 * 
 * Used to: ./plugins/lisette-json-definition-post/views/edit.php
 * Author: sergmoro1@ya.ru
 * Author URI: http://vorst.ru
 */
window.onload = function() {
	// set categories
	lisette_jdp_edit.set_categories();
	// show additional field depends on 'lisette_jdp_what' value
	lisette_jdp_edit.show_appendix( document.getElementById('lisette_jdp_what').value );
	// add save button
	document.getElementById( 'lisette_jdp_save' ).onclick = function() {
		document.getElementById( 'publish' ).click();
	}

	// events & reactions
	
	set_event('lisette_jdp_deal', function() {
		lisette_jdp_edit.change_category(this); 
		lisette_jdp_edit.replace_content_by(this);
	});

	set_event('lisette_jdp_what', function() {
		lisette_jdp_edit.change_category(this); 
		lisette_jdp_edit.hide_all_and_display(this.value, 'type'); 
		lisette_jdp_edit.replace_content_by(this); 
		lisette_jdp_edit.set_default_roooms_by (this);
		lisette_jdp_edit.show_appendix(this.value);
	});
	document.getElementById('lisette_jdp_what').onfocus = function() {
		lisette_jdp_edit.show_appendix(this.value);
	}

	set_events('lisette_jdp_type_', ['room', 'flat', 'house', 'lot'], function() {
		lisette_jdp_edit.change_category(this); 
		lisette_jdp_edit.replace_content_by(this);
	});

	set_events('lisette_jdp_', ['total', 'price'], function() {
		lisette_jdp_edit.replace_content_by(this);
	});

	set_events('lisette_jdp_', ['country', 'state', 'district_Kazan', 'district_Naberezhnye-Chelny', 'locality', 'street'], function() {
		lisette_jdp_edit.replace_content_by(this);
		lisette_jdp_address.changed();
	});
	set_event('lisette_jdp_city', function() {
		lisette_jdp_edit.hide_all_and_display(this.value, 'district'); 
		lisette_jdp_edit.replace_content_by(this); 
		lisette_jdp_address.changed();
	});

	set_event('lisette_jdp_rooms', function() {
		lisette_jdp_edit.change_category(this);
		lisette_jdp_edit.set_clean('rooms');
		lisette_jdp_edit.replace_content_by(this);
		lisette_jdp_edit.set_clean('');
	});
	set_events('lisette_jdp_', ['floor', 'floors', 'living', 'kitchen', 'lot', 'project', 'material', 'phone', 'email'], function() {
		lisette_jdp_edit.replace_content_by(this);
	});

	set_event('lisette_jdp_description', function() {
		lisette_jdp_edit.set_delimiter(''); 
		lisette_jdp_edit.replace_content_by(this); 
		lisette_jdp_edit.set_delimiter(',');
	});

	set_event('lisette_jdp_slider', function() {
		lisette_jdp_edit.replace_slider_by(this.value);
	});

	/*
	 * Set onchange event for one field
	 */
	function set_event(field_name, callback) {
		document.getElementById(field_name).onchange = callback;
	}

	/*
	 * Set onchange event for fields
	 */
	function set_events(prefix, field_names, callback) {
		for(var i = 0; i < field_names.length; i++)
			document.getElementById(prefix + field_names[i]).onchange = callback;
	}
}
