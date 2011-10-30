<?php

/**
 * Amazon Email Delivery library for FuelPHP
 *
 * @package		Amazon
 * @version		1.1
 * @author		Rob McCann (rob@robmccann.co.uk)
 * @link		http://github.com/unforeseen/fuel-amazon-ses
 * 
 */

Autoloader::add_classes(array(
	'Email_Driver_Ses' => __DIR__.'/classes/email/driver/ses.php'
));
