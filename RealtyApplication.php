<?php
/**
 * @author - Sergey Morozov <sergmoro1@ya.ru>
 * @license - MIT
 * 
 * Class for preparing posts with json definitions for the real estate property records.
 * 
 */

require(dirname(__FILE__) . '/LisetteJDPApplication.php');
require(dirname(__FILE__) . '/ApplicationInterface.php');

class RealtyApplication extends LisetteJDPApplication implements ApplicationInterface
{
	// should be shown for every post
 	const THUMBNAIL = true;
 	// JSON definitions have addresses that should be geocoded
 	const GEOCODER = true;
 	
 	// custom xml
	public $feeds = ['avito'];
	 
	/* 
	 * Processing of conditions
	 * @param object $model
	 * @return boolean fits or not model under the condition
	 */
	public function condition($model) {
		$p = $this->params;
		return ( ( $p['deal'] == '*' || $p['deal'] === $model->deal ) && ( $p['what'] == '*' || $p['what'] === $model->what ) && 
			$model->price >= $p['p1'] && $model->price <= $p['p2'] );
	}
	
	/* 
	 * Pre-format model, add calculated atributes
	 * @param object $model
	 */
	public function format($model, $xml=false) {
		if ( !$xml ) {
			// set address, skip default state and country
			$model->address = $model->street . ' ' . 
				( ( isset( $model->locality ) && $model->locality<>'' ? $model->locality . ' ' :  '' ) . $this->getOption($model, 'city') ) . 
				( $model->state == 'Tatarstan' ? '' : ' ' . $this->getOption($model, 'state') ) .
				( $model->country == 'Russia' ? '' : ' ' . $this->getOption($model, 'country') );
			// format price
			$model->pricePerItem = $model->total > 0 ? number_format($model->price / $model->total, 0, '', ' ') : __('Square is not defined');
			$model->priceUnit = $model->what == 'lot' ? __('100m2') : __('m2');
			$model->priceTotal = number_format($model->price, 0, '', ' ');
			// project, material
			$model->project = $model->project == '-' ? '-' : $this->getOption($model, 'project');
			$model->material = $model->material == '-' ? '-' : $this->getOption($model, 'material');
		}
		
		return $model;
	}

	/* 
	 * Set point with balloon definition for the Yandex Map
	 * @param object $model
	 * $return array coordinates & balloon definition
	 */
	public function yaPoint($model) {
		return [ 
			'lng' => (float) $model->lng, 'lat' => (float) $model->lat, 
			'icon' => '', 'header' => $model->priceTotal, 
			'body' => $model->title, 'footer' => $model->phone,
		];
	}
}

