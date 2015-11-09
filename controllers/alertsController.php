<?php

class alertsController extends testProjectController {
	
	public $name = 'alerts';
	
	public $accessControl = array(
		'index' => array(
			'allow', '@'
		),
		'create' => array(
			'allow', '@'
		),
		'edit' => array(
			'allow', '@'
		),
		'view' => array(
			'allow', '@'
		),
		'delete' => array(
			'allow', '@'
		),
	);
	
	/**
	 * Prepares a list of alerts and renders appropriately
	 * 
	 * Unlike the products index, this index does not attempt to paginate or sort
	 */
	public function actionIndex() {
		$models = alerts::model()->getAll();
		$this->render('index', array('models' => $models));
	}
	
	/**
     * Creates a new alert in the database
     * 
     * If no data has been posted, a form with the needed fields will be rendered.  If the data has
     * been posted, an attempt will be made to save.  If successful, the user will be redirected to the
     * 'view' page for the new record, otherwise they will be notified of the failure.
     */
	public function actionCreate() {
		if (isset($_POST['model'])) { //check if data has been posted
			$model = new alerts($_POST['model']);
			if ($model->save()) { //redirect to alert details page if successful
				testProject::setAlert('The alert ' . $model->name . ' was successfully created.', 'success');
				$this->redirect('alerts/view/' . $model->id);
			} else { //create the alert message to notify of failure
				testProject::setAlert('We\'re sorry, but your changes could not be saved.  Please try again.', 'danger');
			}
		} else {
			$this->render('create');
		}
	}
	
	/**
	 * Lists the full set of attributes for a single alert 
	 * 
	 * If no record id is given by the query string or if one cannot be found matching the supplied
	 * id, the user will be redirected to the index with a notification telling them the record could
	 * not be found.  Otherwise, the detail page for the alert will be shown.
	 */
	public function actionView() {
		if (isset($_GET['value'])) {
			$id = $_GET['value'];
			if ($model = alerts::model()->getByPK($id)) {
				$this->render('view', array('model' => $model));
			} else {
				testProject::setAlert('The requested item does not exist.  Please try again.', 'warning');
				$this->redirect('alerts');
			}
		} else {
			testProject::setAlert('The requested item does not exist.  Please try again.', 'warning');
			$this->redirect('alerts');
		}
	}
	
	/**
	 * Allows the user to make changes to an existing alert
	 * 
	 * If data has been posted, an attempt will be made to save the changes to the database.  The user
	 * will then be redirected to the alert view with a notification whether the changes were saved
	 * or not.  If no data has been posted, the method will attempt to render a form with the editable
	 * fields for the alert indicated via ID in the query string.  If no ID is given in the query string
	 * or if a alert matching that ID cannot be found in the database, the user will be redirected to the
	 * alert index.
	 */
	public function actionEdit() {
		if (isset($_POST['model'])) {
			$model = alerts::model()->getByPK($_POST['model']['id']);
			$model->setAttributes($_POST['model']);
			if ($model->save()) {
				testProject::setAlert('The changes were saved successfully.', 'success');
			} else {
				testProject::setAlert('We\'re sorry but your changes were not saved.  Please try again.', 'danger');
			}
			$this->redirect('alerts/view/' . $model->id);
		} else {
			if (isset($_GET['value'])) {
				$model = alerts::model()->getByPK($_GET['value']);
				$this->render('edit', array('model' =>$model));
			} else {
				$this->redirect('alerts/index');
			}
		}
	}
	
	/**
	 * Deletes a alert
	 * 
	 * If no alert ID is passed or if it does not match a valid product ID in the database, an error
	 * message is generated.  Otherwise the alert is deleted and a success message is generated.  In either
	 * case the user is redirected to the alerts index.
	 */
	public function actionDelete() {
		testProject::setAlert('There was a problem with your request.  Please try again.', 'error');
		if (isset($_GET['value'])) {
			$model = alerts::model()->getByPK($_GET['value']);
			$name = $model->name;
			if ($model->delete()) {
				testProject::setAlert('The item ' . $name . ' was deleted.', 'info');
			}
		}
		$this->redirect('alerts/index');
	}
	
}