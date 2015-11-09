<?php

class Auth {

	public static function User() {
		$response = false;
		if (isset($_SESSION['token'])) {
			$token = $_SESSION['token'];
			$response = users::model()->getByAttribute('sessionToken', $token);
		}
		return $response;
	}
	
	public static function login($token) {
		$_SESSION['token'] = $token;
	}
	
	public static function logout() {
		unset($_SESSION['token']);
	}

}