<?php
/**
 * tags model class file
 * 
 * @author alan.marble
 */

/**
 * tags model class
 * 
 * The tags model class contains the basic definition for the tags model, with some
 * methods overriding the base testProjectModel class for extra functionality.
 * 
 */
class headers extends testProjectModel {
	
	protected $attributes = array(
	
	);
	
	protected $validators = array(
	
	);
	
	protected $pk = 'id';
	
	protected $name = "headers";
	
	protected $filterByUser = false;
	
	/**
	 * Checks if the tag is new, and sets the created and modified times appropriately,
	 * prior to saving.
	 * @see testProjectModel::beforeSave()
	 */
	public function beforeSave() {
		if ($this->isNew) {
			$this->created = date("Y-m-d H:i:s");
			$this->modified = $this->created;
			$this->user = Auth::User()->id;
		} else {
			$this->modified = date("Y-m-d H:i:s");
		}
		return true;
	}
	
}