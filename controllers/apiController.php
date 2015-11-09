<?php

class apiController extends testProjectController {
	
	public $name = 'api';
	
	public function actionCreateCategory() {
		$errors = array();
		$apiAttributes = array('name' => '', 'description' => '');
		$attributes = array_intersect_key($_GET, $apiAttributes);
		$category = new categories($attributes);
		if (!$category->validate()) {
			$errors = $category->getValidationErrors();
		}
		if (!$errors) {
			if (!$category->save()) {
				$errors[] = 'The category could not be saved';
			} else {
				$attributes = array('id', 'name', 'created', 'modified', 'description');
				$data = array($this->returnValues($category, $attributes));
			}
		}
		if ($errors) {
			$this->renderApi(false, null, $errors);
		} else {
			$this->renderApi(true, $data);
		}
	}
	
	public function actionDeleteCategory() {
		$errors = array();
		if (!isset($_GET['value'])) {
			$errors[] = 'A category ID must be supplied';
		} else if (!is_numeric($_GET['value'])) {
			$errors[] = "Provided category ID must be an integer";
		} else if ($category = categories::model()->getbyPK($_GET['value'])) {
			if ($category->delete()) {
				$data = array();
			} else {
				$errors[] = 'Could not delete the category';
			}
		} else {
			$errors[] = 'Could not find category with ID of ' . $_GET['value'];
		}
		if (count($errors) > 0) {
			$this->renderApi(false, null, $errors);
		} else {
			$this->renderApi(true, $data);
		}
	}
	
	public function actionGetCategories() {
		$categories = categories::model()->getAll();
		$data = array();
		foreach ($categories as $category) {
			$attributes = array('id', 'name', 'created', 'modified', 'description');
			$data[] = $this->returnValues($category, $attributes);
		}
		$this->renderApi(true, $data);
	}
	
	public function actionGetCategory() {
		$errors = array();
		if (!isset($_GET['value'])) {
			$errors[] = 'A category ID must be supplied';
		} else if (!is_numeric($_GET['value'])) {
			$errors[] = "Provided category ID must be an integer";
		} else if ($category = categories::model()->getbyPK($_GET['value'])) {
			$attributes = array('id', 'name', 'created', 'modified', 'description');
			$data = array($this->returnValues($category, $attributes));
		} else {
			$errors[] = 'Could not find category with ID of ' . $_GET['value'];
		}
		if (count($errors) > 0) {
			$this->renderApi(false, null, $errors);
		} else {
			$this->renderApi(true, $data);
		}
	}
	
	public function actionUpdateCategory() {
		$errors = array();
		if (!isset($_GET['value'])) {
			$errors[] = 'A category ID must be supplied';
		} else if (!is_numeric($_GET['value'])) {
			$errors[] = "Provided category ID must be an integer";
		} else if ($category = categories::model()->getbyPK($_GET['value'])) {
			$updateAttributes = array('name' => null, 'description' => null);
			foreach ($updateAttributes as $updateAttribute => $value) {
				if (isset($_GET[$updateAttribute])) {
					$updateAttributes[$updateAttribute] = $_GET[$updateAttribute];
				} else {
					unset($updateAttributes[$updateAttribute]);
				}
			}
			$category->setAttributes($updateAttributes);
			if ($category->save()) {
				$attributes = array('id', 'name', 'created', 'modified', 'description');
				$data = array($this->returnValues($category, $attributes));
			} else {
				$errors[] = 'The category could not be updated';
			}
		} else {
			$errors[] = 'Could not find category with ID of ' . $_GET['value'];
		}
		if (count($errors) > 0) {
			$this->renderApi(false, null, $errors);
		} else {
			$this->renderApi(true, $data);
		}
	}
	
	public function actionCreateProduct() {
		$errors = array();
		$apiAttributes = array('name' => '', 'description' => '', 'price' => '', 'category' => '');
		$attributes = array_intersect_key($_GET, $apiAttributes);
		$product = new products($attributes);
		if (!$product->validate()) {
			$errors = $product->getValidationErrors();
		}
		if (!$errors) {
			$product = new products(array(
				'name' => $_GET['name'], 
				'description' => $_GET['description'],
				'category' => $_GET['category'],
				'price' => $_GET['price']
			));
			if (!$product->save()) {
				$errors[] = 'The product could not be saved';
			} else {
				$attributes = array('id', 'name', 'created', 'modified', 'description');
				$datum = $this->returnValues($product, $attributes);
				$datum['category_name'] = $product->category->name;
				$datum['category_id'] = $product->category->id;
				$data = array($datum);
			}
		}
		if ($errors) {
			$this->renderApi(false, null, $errors);
		} else {
			$this->renderApi(true, $data);
		}
	}
	
	public function actionDeleteProduct() {
		$errors = array();
		if (!isset($_GET['value'])) {
			$errors[] = 'A product ID must be supplied';
		} else if (!is_numeric($_GET['value'])) {
			$errors[] = "Provided product ID must be an integer";
		} else if ($product = product::model()->getbyPK($_GET['value'])) {
			if ($product->delete()) {
				$data = array();
			} else {
				$errors[] = 'Could not delete the product';
			}
		} else {
			$errors[] = 'Could not find product with ID of ' . $_GET['value'];
		}
		if (count($errors) > 0) {
			$this->renderApi(false, null, $errors);
		} else {
			$this->renderApi(true, $data);
		}
	}
	
	public function actionGetProducts() {
		$products = products::model()->getAll();
		$data = array();
		foreach ($products as $product) {
			$attributes = array('id', 'name', 'created', 'modified', 'description');
			$datum = $this->returnValues($product, $attributes);
			$datum['category_name'] = $product->category->name;
			$datum['category_id'] = $product->category->id;
			$data[] = $datum;
		}
		$this->renderApi(true, $data);
	}
	
	public function actionGetProduct() {
		$errors = array();
		if (!isset($_GET['value'])) {
			$errors[] = 'A product ID must be supplied';
		} else if (!is_numeric($_GET['value'])) {
			$errors[] = "Provided product ID must be an integer";
		} else if ($product = products::model()->getbyPK($_GET['value'])) {
			$attributes = array('id', 'name', 'created', 'modified', 'description');
			$datum = $this->returnValues($product, $attributes);
			$datum['category_name'] = $product->category->name;
			$datum['category_id'] = $product->category->id;
			$data = array($datum);
		} else {
			$errors[] = 'Could not find product with ID of ' . $_GET['value'];
		}
		if (count($errors) > 0) {
			$this->renderApi(false, null, $errors);
		} else {
			$this->renderApi(true, $data);
		}
	}
	
	public function actionUpdateProduct() {
		$errors = array();
		if (!isset($_GET['value'])) {
			$errors[] = 'A product ID must be supplied';
		} else if (!is_numeric($_GET['value'])) {
			$errors[] = "Provided product ID must be an integer";
		} else if ($product = products::model()->getbyPK($_GET['value'])) {
			$updateAttributes = array(
				'name' => null, 
				'description' => null, 
				'price' => null, 
				'category_id' => null
			);
			foreach ($updateAttributes as $updateAttribute => $value) {
				if (isset($_GET[$updateAttribute])) {
					$updateAttributes[$updateAttribute] = $_GET[$updateAttribute];
				} else {
					unset($updateAttributes[$updateAttribute]);
				}
			}
			$product->setAttributes($updateAttributes);
			if ($product->save()) {
				$attributes = array('id', 'name', 'created', 'modified', 'description');
				$datum = $this->returnValues($product, $attributes);
				$datum['category_name'] = $product->category->name;
				$datum['category_id'] = $product->category->id;
				$data = array($datum);
			} else {
				$errors[] = 'The product could not be updated';
			}
		} else {
			$errors[] = 'Could not find product with ID of ' . $_GET['value'];
		}
		if (count($errors) > 0) {
			$this->renderApi(false, null, $errors);
		} else {
			$this->renderApi(true, $data);
		}
	}
	
	private function returnValues($object, $values) {
		$result = array();
		foreach ($values as $value) {
			$result[$value] = $object->$value;
		}
		return $result;
	}
	
}