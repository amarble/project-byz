<?php

class defaultController extends testProjectController {
	
	public $name = 'default';	
	
	public function actionIndex() {
		$this->render('index');
	}
	
		
	public function actionLogin() {
		$this->render('login', null, 'front');
	}
	
	public function actionLogout() {
	
	}
	
}