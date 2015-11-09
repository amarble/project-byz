<?php

class tagsController extends testProjectController {
	
	public $name = 'tags';
	
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
	 * Prepares a list of tags and renders appropriately
	 * 
	 * Unlike the products index, this index does not attempt to paginate or sort
	 */
	public function actionIndex() {
		$models = tags::model()->getAll();
		$this->render('index', array('models' => $models));
	}
	
	/**
     * Creates a new tag in the database
     * 
     * If no data has been posted, a form with the needed fields will be rendered.  If the data has
     * been posted, an attempt will be made to save.  If successful, the user will be redirected to the
     * 'view' page for the new record, otherwise they will be notified of the failure.
     */
	public function actionCreate() {
		if (isset($_POST['model'])) { //check if data has been posted
			$model = new tags($_POST['model']);
			if ($model->save()) { //redirect to tag details page if successful
				testProject::setAlert('The tag ' . $model->name . ' was successfully created.', 'success');
				$this->redirect('tags/view/' . $model->id);
			} else { //create the alert message to notify of failure
				testProject::setAlert('We\'re sorry, but your changes could not be saved.  Please try again.', 'danger');
			}
		} else {
			$this->render('create');
		}
	}
	
	/**
	 * Lists the full set of attributes for a single tag 
	 * 
	 * If no record id is given by the query string or if one cannot be found matching the supplied
	 * id, the user will be redirected to the index with a notification telling them the record could
	 * not be found.  Otherwise, the detail page for the tag will be shown.
	 */
	public function actionView() {
		if (isset($_GET['value'])) {
			$id = $_GET['value'];
			if ($model = tags::model()->getByPK($id)) {
				$this->render('view', array('model' => $model));
			} else {
				testProject::setAlert('The requested item does not exist.  Please try again.', 'warning');
				$this->redirect('tags');
			}
		} else {
			testProject::setAlert('The requested item does not exist.  Please try again.', 'warning');
			$this->redirect('tags');
		}
	}
	
	/**
	 * Allows the user to make changes to an existing tag
	 * 
	 * If data has been posted, an attempt will be made to save the changes to the database.  The user
	 * will then be redirected to the tag view with a notification whether the changes were saved
	 * or not.  If no data has been posted, the method will attempt to render a form with the editable
	 * fields for the tag indicated via ID in the query string.  If no ID is given in the query string
	 * or if a tag matching that ID cannot be found in the database, the user will be redirected to the
	 * tag index.
	 */
	public function actionEdit() {
		if (isset($_POST['model'])) {
			$model = tags::model()->getByPK($_POST['model']['id']);
			$model->setAttributes($_POST['model']);
			if ($model->save()) {
				testProject::setAlert('The changes were saved successfully.', 'success');
			} else {
				testProject::setAlert('We\'re sorry but your changes were not saved.  Please try again.', 'danger');
			}
			$this->redirect('tags/view/' . $model->id);
		} else {
			if (isset($_GET['value'])) {
				$model = tags::model()->getByPK($_GET['value']);
				$this->render('edit', array('model' =>$model));
			} else {
				$this->redirect('tags/index');
			}
		}
	}
	
	/**
	 * Deletes a tag
	 * 
	 * If no tag ID is passed or if it does not match a valid product ID in the database, an error
	 * message is generated.  Otherwise the tag is deleted and a success message is generated.  In either
	 * case the user is redirected to the tags index.
	 */
	public function actionDelete() {
		testProject::setAlert('There was a problem with your request.  Please try again.', 'error');
		if (isset($_GET['value'])) {
			$model = tags::model()->getByPK($_GET['value']);
			$name = $model->name;
			if ($model->delete()) {
				testProject::setAlert('The item ' . $name . ' was deleted.', 'info');
			}
		}
		$this->redirect('tags/index');
	}
	
}