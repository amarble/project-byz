<?php

class vendorsController extends testProjectController {
	
	public $name = 'vendors';
	
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
	 * Prepares a list of vendors and renders appropriately
	 * 
	 * Unlike the products index, this index does not attempt to paginate or sort
	 */
	public function actionIndex() {
		$models = vendors::model()->getAll();
		$this->render('index', array('models' => $models));
	}
	
	/**
     * Creates a new vendor in the database
     * 
     * If no data has been posted, a form with the needed fields will be rendered.  If the data has
     * been posted, an attempt will be made to save.  If successful, the user will be redirected to the
     * 'view' page for the new record, otherwise they will be notified of the failure.
     */
	public function actionCreate() {
		if (isset($_POST['model'])) { //check if data has been posted
			$model = new vendors($_POST['model']);
			if ($model->save()) { //redirect to vendor details page if successful
				testProject::setAlert('The vendor ' . $model->name . ' was successfully created.', 'success');
				$this->redirect('vendors/view/' . $model->id);
			} else { //create the alert message to notify of failure
				testProject::setAlert('We\'re sorry, but your changes could not be saved.  Please try again.', 'danger');
				$this->render('create');
			}
		} else {
			$this->render('create');
		}
	}
	
	/**
	 * Lists the full set of attributes for a single vendor 
	 * 
	 * If no record id is given by the query string or if one cannot be found matching the supplied
	 * id, the user will be redirected to the index with a notification telling them the record could
	 * not be found.  Otherwise, the detail page for the vendor will be shown.
	 */
	public function actionView() {
		if (isset($_GET['value'])) {
			$id = $_GET['value'];
			if ($model = vendors::model()->getByPK($id)) {
				$this->render('view', array('model' => $model));
			} else {
				testProject::setAlert('The requested item does not exist.  Please try again.', 'warning');
				$this->redirect('vendors/index');
			}
		} else {
			testProject::setAlert('The requested item does not exist.  Please try again.', 'warning');
			$this->redirect('vendors/index');
		}
	}
	
	/**
	 * Allows the user to make changes to an existing vendor
	 * 
	 * If data has been posted, an attempt will be made to save the changes to the database.  The user
	 * will then be redirected to the vendor view with a notification whether the changes were saved
	 * or not.  If no data has been posted, the method will attempt to render a form with the editable
	 * fields for the vendor indicated via ID in the query string.  If no ID is given in the query string
	 * or if a vendor matching that ID cannot be found in the database, the user will be redirected to the
	 * vendor index.
	 */
	public function actionEdit() {
		if (isset($_POST['model'])) {
			$model = vendors::model()->getByPK($_POST['model']['id']);
			$model->setAttributes($_POST['model']);
			if ($model->save()) {
				testProject::setAlert('The changes were saved successfully.', 'success');
			} else {
				testProject::setAlert('We\'re sorry but your changes were not saved.  Please try again.', 'danger');
			}
			$this->redirect('vendors/view/' . $model->id);
		} else {
			if (isset($_GET['value'])) {
				$model = vendors::model()->getByPK($_GET['value']);
				$this->render('edit', array('model' =>$model));
			} else {
				$this->redirect('vendors/index');
			}
		}
	}
}