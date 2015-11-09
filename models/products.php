<?php
/**
 * products model class file
 * 
 * @author alan.marble
 */

/**
 * products model class
 * 
 * The products model class contains the basic definition for the products model, with some
 * methods overriding the base testProjectModel class for extra functionality.
 * 
 */
class products extends testProjectModel {
	
	protected $attributes = array(
		'id' => '',
		'name' => '', 
		'description' => '',
		'price' => '',
		'created' => '', 
		'modified' => '', 
		'category' => '',
	);
	
	protected $validators = array(
		'name' => array('required'),
		'description' => array('required'),
		'price' => array('required', 'numeric'),
		'category' => array('required', 'isCategory'),
	);
	
	public function validateIsCategory($attribute, $message = 'attribute is not a valid category') {
		$category = categories::model()->getByPK($this->attributes[$attribute]);
		if (!$category) {
			return str_replace('attribute', $attribute, $message);
		} else {
			return false;
		}
	}
	
	protected $pk = 'id';
	
	protected $name = "products";
	
	//the relationship to the categories model
	protected $relationships = array(
		'category' => array(
			'model' => 'categories',
			'relationship' => 'belongsTo',
		),
	);
	
	/**
	 * Checks if the product is new, and sets the created and modified times appropriately,
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