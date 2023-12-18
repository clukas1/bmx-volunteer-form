<?php

defined('ABSPATH') or exit;

function process_form(): void {
	try {
		$name = sanitize_string( $_POST['name'] ?? '' );
		$email = sanitize_email( $_POST['email'] ?? '' );
		$phone = sanitize_phone( $_POST['phone'] ?? '' );
		$comment = sanitize_string( $_POST['comment'] ?? '' );
	} catch ( InvalidArgumentException $e ) {
		define( 'BOOTSTRAP_ALERT', '<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>');
		return;
	}

	if( empty( $name ) ){
		define( 'BOOTSTRAP_ALERT', '<div class="alert alert-danger" role="alert">Name is required</div>');
		return;
	}

	$volunteer = [
		'name' => $name,
		'email' => $email,
		'phone' => $phone,
	];

	if ( $comment ) {
		$volunteer['comment'] = $comment;
	}

	$shifts = [];
	foreach ( $_POST as $key => $value ) {
		if ( str_starts_with( $key, 'shift_' ) ) {
			$keys = explode( '_', $key );
			$shifts[] = array_slice( $keys, 1 );
		}
	}

	if ( empty( $shifts ) ) {
		define( 'BOOTSTRAP_ALERT', '<div class="alert alert-danger" role="alert">You must select at least one shift</div>');
		return;
	}

	$form_data = json_decode(file_get_contents(FORM_DATA_PATH), true);

	foreach ( $shifts as $shift ) {
		try {
			appendToRegisteredVolunteers($form_data, $shift, $volunteer);
		} catch ( Exception $e ) {
			define( 'BOOTSTRAP_ALERT', '<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>');
			return;
		}
	}

	print_r($form_data);

	define('BOOTSTRAP_ALERT', '<div class="alert alert-success" role="alert">Thank you for registering!</div>');

	file_put_contents(FORM_DATA_PATH, json_encode($form_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

}

function appendToRegisteredVolunteers(&$data, $indices, $volunteerData) {
	print_r($indices);
	$currentItem = &$data;
	foreach ($indices as $index) {
		$currentItem = &$currentItem['items'][$index];
	}
	$numVolunteers = $currentItem['numberVolunteers'] ?? 0;
	print_r($currentItem);
	if (!isset($currentItem['registeredVolunteers'])) {
		$currentItem['registeredVolunteers'] = [];
	}



	if (count($currentItem['registeredVolunteers']) >= $numVolunteers) {
		throw new InvalidArgumentException('Shift is already full');
	}
	$currentItem['registeredVolunteers'][] = $volunteerData;
}


function sanitize_string( string $string ): string {
	return trim( htmlspecialchars( $string ) );
}

function sanitize_email( string $email ): string {
	// check if email is valid
	if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
		throw new InvalidArgumentException( 'Email is invalid' );
	}

	return filter_var( $email, FILTER_SANITIZE_EMAIL );
}

function sanitize_phone( string $phone ): string {
	// allow only numbers and + sign
	$number = preg_replace( '/[^0-9+]/', '', $phone );
	// check if length between 10 and 13
	if ( strlen( $number ) < 10 || strlen( $number ) > 13 ) {
		throw new InvalidArgumentException( 'Phone number must be between 10 and 13 digits' );
	}

	// when + sign is present, check if it is in the first position
	if ( ! str_starts_with( $number, '+' ) ) {
		throw new InvalidArgumentException( 'Phone number must start with a + sign' );
	}

	// check if more than one + sign is present
	if ( substr_count( $number, '+' ) > 1 ) {
		throw new InvalidArgumentException( 'Phone number must contain only one + sign' );
	}

	return $number;
}
