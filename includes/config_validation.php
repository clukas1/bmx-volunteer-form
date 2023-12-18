<?php
defined('ABSPATH') or exit;

include_once( 'includes/process_form.php' );

require_once('ConfigObject.php');
require_once('FormItem.php');
require_once('Section.php');
require_once('Input.php');
require_once('Volunteer.php');

const CONFIG_PATH = ABSPATH . 'data/config.php';
const FORM_DATA_PATH = ABSPATH . 'data/form.json';

// check if config file exists
if (!file_exists(CONFIG_PATH)) {
	die('Config file not found. Please create a config.php file in the root directory.');
}

require_once CONFIG_PATH;

// check if config file is valid
if ( ! CONFIG instanceof ConfigObject ) {
	die('Config file is invalid. Please check your config.php file.');
}

// check if form.json file exists
if (!file_exists( FORM_DATA_PATH ) ) {
	die('Form data file not found. Please create a form.json file in the root directory.');
}

if( isset( $_POST['submit'] ) ){
	echo 'processing form';
	process_form();
}

// check if form.json file is valid
$form_data = json_decode(file_get_contents(FORM_DATA_PATH), true);

define( "FORM_DATA", new Section( $form_data, 1 ) );

