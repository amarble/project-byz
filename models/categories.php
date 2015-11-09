<?php
/**
 * categories model class file
 * 
 * @author alan.marble
 */

/**
 * categories model class
 * 
 * The categories model class contains the basic definition for the categories model, with some
 * methods overriding the base testProjectModel class for extra functionality.
 * 
 */
class categories extends testProjectModel {
	
	protected $attributes = array(
		'id' => '', 
		'name' => '', 
		'created' => '', 
		'modified' => '', 
		'description' => '',
	);
	
	protected $validators = array(
		'name' => array('required'),
		'description' => array('required'),
	);
	
	protected $pk = 'id';
	
	protected $name = "categories";
	
	/**
	 * Deletes all products associated with this category prior to deleting the category
	 * @see testProjectModel::beforeDelete()
	 */
	public function beforeDelete() {
		$products = products::model()->getAll();
		foreach ($products as $product) {
			if ($product->category->id == $this->id) $product->delete();
		}
		return true;
	}
	
	/**
	 * Checks if the category is new, and sets the created and modified times appropriately,
	 * prior to saving.
	 * @see testProjectModel::beforeSave()
	 */
	public function beforeSave() {
		if ($this->isNew) {
			$this->created = date("Y-m-d H:i:s");
			$this->modified = $this->created;
		} else {
			$this->modified = date("Y-m-d H:i:s");
		}
		return true;
	}
	
}