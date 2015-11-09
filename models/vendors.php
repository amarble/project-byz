<?php
/**
 * vendors model class file
 * 
 * @author alan.marble
 */

/**
 * vendors model class
 * 
 * The vendors model class contains the basic definition for the vendors model, with some
 * methods overriding the base testProjectModel class for extra functionality.
 * 
 */
class vendors extends testProjectModel {
	
	protected $attributes = array(

	);
	
	protected $validators = array(

	);
	
	protected $pk = 'id';
	
	protected $name = "vendors";
	
	/**
	 * Checks if the vendor is new, and sets the created and modified times appropriately,
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