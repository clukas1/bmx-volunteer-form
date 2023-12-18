<?php

readonly class Volunteer {

	private string $name;
	private string $email;
	private string $phone;
	private string $comment;

	public function __construct( array $data ){
		$this->set_name( $data['name'] );
		$this->set_email( $data['email'] );
		$this->set_phone( $data['phone'] );
		$this->set_comment( $data['comment'] ?? '' );
	}

	public function get_name(): string {
		return $this->name;
	}

	public function set_name( string $name ): void {
		$this->name = $name;
	}

	public function get_email(): string {
		return $this->email;
	}

	public function set_email( string $email ): void {
		$this->email = $email;
	}

	public function get_phone(): string {
		return $this->phone;
	}

	public function set_phone( string $phone ): void {
		$this->phone = $phone;
	}

	public function get_comment(): string {
		return $this->comment;
	}

	public function set_comment( string $comment ): void {
		$this->comment = $comment;
	}

}