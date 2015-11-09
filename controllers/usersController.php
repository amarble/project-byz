<?php

class usersController extends testProjectController {
	
	public $name = 'users';	
	
	public function actionIndex() {
		$this->render('index');
	}
		
	public function actionLogin() {
		if (isset($_POST['model'])) { //check if data has been posted
			if ($model = users::model()->login($_POST['model']['username'], $_POST['model']['password'])) { //attempt to log in
				testProject::setAlert("Welcome back!", 'success');
				$this->render('created');
			} else { //create the alert message to notify of failure
				testProject::setAlert("Invalid login credentials, please try again.", 'danger');
				$this->render('login');
			}
		} else {
			$model = new users();
			$this->render('login');
		}
	}
	
	public function actionLogout() {
		$model = Auth::User();
		$model->sessionExpire = date("Y-m-d H:i:s");
		$model->save();
		Auth::logout();
		testProject::setAlert("You have been logged out.", 'success');
		$this->redirect('users/login');
	}
	
	public function actionCreate() {
		if (isset($_POST['model'])) { //check if data has been posted
			$errors = array();
			$existing = users::model()->getByAttribute('username', $_POST['model']['username']);
			if ($existing) $errors[] = "That username is already in use.";
			$existing = users::model()->getByAttribute('email', $_POST['model']['email']);
			if ($existing) $errors[] = "An account has already been registered to that email address.";
			if ($_POST['model']['password'] !== $_POST['model']['repeatPassword']) $errors[] = "Passwords must match";
			$model = new users($_POST['model']);
			if (!$model->validate()) $errors = array_merge($errors, $model->validationErrors);
			if (count($errors) > 0) {
				$errorMessage = "Please correct the following errors to continue:<ul>";
				foreach ($errors as $error) {
					$errorMessage .= "<li>$error</li>";
				}
				$errorMessage .= "</ul>";
				testProject::setAlert($errorMessage, 'danger');
				$this->render('create', array('model' => $model));
			}
			if ($model->save()) { //redirect to created page if successful
				$this->render('created');
			} else { //create the alert message to notify of failure
				testProject::setAlert('We\'re sorry, but your changes could not be saved.  Please try again.', 'danger');
				$this->render('create', array('model' => $model));
			}
		} else {
			$model = new users();
			$this->render('create', array('model' => $model));
		}
	}
	
}