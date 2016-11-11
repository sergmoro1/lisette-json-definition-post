<?php
/**
 * @author - Sergey Morozov <sergmoro1@ya.ru>
 * @license - MIT
 * 
 */

interface ApplicationInterface
{

    /**
     * Check is model fitted to conditions. 
     * The model is compared with the specified parameters.
     * Parameters are $_GET and setted by LisetteJDPApplication::setPatams().
     * 
     * @param object $model
     * @return boolean whether model fits to condition
     */
	public function condition($model);

    /**
     * Calculate new attributes.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param object $model
     * @param boolean $xml if true return model as is else calculate new attributes or modify existing
     * @return object
     */
	public function format($model, $xml = false);
	
    /**
     * Return key => value array with 'lng', 'lat' - coordinates, 'head', 'body', 'footer', 'icon' - balloon.
     *
     * @param object $model
     * @return array of point definition
     */
	public function yaPoint($model);
}
