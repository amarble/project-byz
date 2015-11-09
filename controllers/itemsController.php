<?php

class itemsController extends testProjectController {
	
	public $name = 'items';
	
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
	 * Prepares a list of products and renders accordingly
	 * 
	 * If the request comes from an AJAX request containing pagination data, the resultant table will be 
	 * paginated appropriately.  Otherwise, a default pagination state (no sorting, no filtering, page 1)
	 * will be created.
	 */
	public function actionIndex() {
		$statuses = statuses::model()->getAll();
		$vendors = vendors::model()->getAll();
		$tags = tags::model()->getAll();
		$showColumns = Auth::User()->getColumns();
		if (count($showColumns) == 0) $showColumns = array(1,2,3);
		$columnHeaders = headers::model()->getAll();
		$allHeaders = array();
		foreach ($columnHeaders as $header) {
			$allHeaders[$header->id] = $header;
		}
		$headers = array(
				'show' => $showColumns,
				'headers' => $allHeaders
			);
		$pagination = array('limit' => array(0,10), 'filter' => 'All categories', 'sortAttribute' => null, 'sortDirection' => null);
		if (!empty($_GET['ajax'])) { //handle ajax requests
			//get the pagination data from the query string
			if (isset($_GET['headers'])) {
				$headers['show'] = explode(',', $_GET['headers']);
				Auth::User()->setColumns($_GET['headers']);
			}
			$sortHeader = headers::model()->getbyPK($_GET['sortAttribute']);
			$pagination['sortAttribute'] = $sortHeader->sortName;
			$pagination['sortDirection'] = $_GET['sortDirection'];
			$pagination['limit'] = array($_GET['paginationPageNumber'], $_GET['paginationPerPage']);
			$pagination['filter'] = $_GET['filter'];
			$conditions = null;
			$params =  null;
			//if there is a filter in place, create the conditions and parameters needed 
			if ((!empty($pagination['filter']) && ($pagination['filter']) != 'All categories')) {
				$conditions = "category = :category";
				$category = categories::model()->getByAttribute('name', $pagination['filter']);
				$params = array('category' => $category->id);
			}
			$models = items::model(true)->getAll($conditions, $params, $pagination);
			$count = items::model()->getCount($conditions, $params);
			$pagination['count'] = $count;
			$pagination['sortAttribute'] = $_GET['sortAttribute'];
			$this->renderPartial('table', array('data' => $models, 
												'statuses' => $statuses, 
												'pagination' => $pagination,
												'vendors' => $vendors,
												'tags' => $tags,
												'headers' => $headers
												
											));	
		} else { //handle default requests
			
			$models = items::model(true)->getAll(null, null, $pagination);
			$count = items::model()->getCount(null);
			$pagination['count'] = $count;
			$this->render('index', array(
				'models' => $models, 
				'statuses' => $statuses, 
				'vendors' => $vendors, 
				'tags' => $tags, 
				'pagination' => $pagination,
				'headers' => $headers
			));
		}
	}
	
    /**
     * Creates a new product in the database
     * 
     * If no data has been posted, a form with the needed fields will be rendered.  If the data has
     * been posted, an attempt will be made to save.  If successful, the user will be redirected to the
     * 'view' page for the new record, otherwise they will be notified of the failure.
     */
	public function actionCreate() {
		if (isset($_POST['model'])) { //check if data has been posted
			$model = new items($_POST['model']);
			if ($model->save()) { //redirect to product view if successful
				testProject::setAlert('The item ' . $model->name . ' was successfully created.', 'success');
				$this->redirect("items/view/{$model->id}");
			} else { //create the alert message to notify of failure
				testProject::setAlert('We\'re sorry, but your changes could not be saved.  Please try again.', 'danger');
			}
		}
		//show the form
		$statuses = statuses::model()->getAll();
		$vendors = vendors::model()->getAll();
		$tags = tags::model()->getAll();
		$this->render('create', array('statuses' => $statuses, 'vendors' => $vendors, 'tags' => $tags));
	}
	
	/**
	 * Lists the full set of attributes for a single product 
	 * 
	 * If no record id is given by the query string or if one cannot be found matching the supplied
	 * id, the user will be redirected to the index with a notification telling them the record could
	 * not be found.  Otherwise, the detail page for the product will be shown.
	 */
	public function actionView() {
		if (isset($_GET['value'])) { //check if an ID is provided
			$id = $_GET['value'];
			if ($model = items::model(true)->getByPK($id)) { //record matching the ID is found
				$this->render('view', array('model' => $model));
			} else { //no matching record found
				testProject::setAlert('The requested item does not exist or you do not have permission to access it.  Please try again.', 'warning');
				$this->redirect('items');
			}
		} else { //no ID provided
			testProject::setAlert('The requested item does not exist or you do not have permission to access it.  Please try again.', 'warning');
			$this->redirect('items');
		}
	}
	
	/**
	 * Allows the user to make changes to an existing product
	 * 
	 * If data has been posted, an attempt will be made to save the changes to the database.  The user
	 * will then be redirected to the product view with a notification whether the changes were saved
	 * or not.  If no data has been posted, the method will attempt to render a form with the editable
	 * fields for the product indicated via ID in the query string.  If no ID is given in the query string
	 * or if a product matching that ID cannot be found in the database, the user will be redirected to the
	 * product index.
	 */
	public function actionEdit() {
		if (isset($_POST['model'])) { //check that data has been posted
			$model = items::model()->getByPK($_POST['model']['id']);
			$model->setAttributes($_POST['model']);
			if ($model->save()) { //set the alert based on success of the save
				testProject::setAlert('The changes were saved successfully.', 'success');
				$this->redirect("items/view/{$model->id}");
			} else {
				testProject::setAlert('We\'re sorry but your changes were not saved.  Please try again.', 'danger');
				$this->redirect("items/view/{$model->id}");
			}
			//redirect to product view
			$this->redirect('items/view/' . $model->id);
		} else { //if no data is posted
			if (isset($_GET['value'])) {  //attempt to load record based on ID and render edit form
				$model = items::model()->getByPK($_GET['value']);
				$statuses = statuses::model()->getAll();
				$vendors = vendors::model()->getAll();
				$tags = tags::model()->getAll();
				$this->render('edit', array('model' =>$model, 'statuses' => $statuses, 'vendors' => $vendors, 'tags' => $tags));
			} else { //if no record found, redirect to product index
				$this->redirect('items');
			}
		}
	}
	
	public function actionSplit() {
		if (isset($_POST['model'])) { //check that data has been posted
			$model = items::model()->getByPK($_POST['model']['id']);
			if ($split = $model->split($_POST['split'])) { //split the grouping
				testProject::setAlert('The group was split successfully.', 'success');
				$this->redirect("items/view/{$split->id}");
			} else {
				testProject::setAlert('We\'re sorry but the items could not be split.', 'danger');
				$this->redirect("items/view/{$model->id}");
			}
		} else { //if no data is posted
			if (isset($_GET['value'])) {  //attempt to load record based on ID and render edit form
				$model = items::model()->getByPK($_GET['value']);
				$this->render('split', array('model' =>$model));
			} else { //if no record found, redirect to product index
				$this->redirect('items');
			}
		}
	}
	
	/**
	 * Deletes a product
	 * 
	 * If no product ID is passed or if it does not match a valid product ID in the database, an error
	 * message is generated.  Otherwise the product is deleted and a success message is generated.  In either
	 * case the user is redirected to the products index.
	 */
	public function actionDelete() {
		testProject::setAlert('There was a problem with your request.  Please try again.', 'error');
		if (isset($_GET['value'])) { //check if ID is provided
			$model = items::model()->getByPK($_GET['value']);
			$name = $model->name;
			if ($model->delete()) { //attempt to delete the item
				testProject::setAlert('The item ' . $name . ' was deleted.', 'info');
			}
		}
		$this->redirect('items/index');
	}
	
}