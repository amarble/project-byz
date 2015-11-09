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
class items extends testProjectModel {

	protected $tagIds = array();
	
	protected $attributes = array(

	);
	
	protected $validators = array(

	);
	
	protected $pk = 'id';
	
	protected $name = "items";
	
	//the relationship to the categories model
	protected $relationships = array(
		'status' => array(
			'model' => 'statuses',
			'relationship' => 'belongsTo',
		),
		'vendor' => array(
			'model' => 'vendors',
			'relationship' => 'belongsTo',
		),
	);
	
	public function __construct($attributes = array(), $deep = false) {
		parent::__construct($attributes, $deep);
		$query = Query::build()->
							select('tag')->
							from('itemTags')->
							where('item', '=', 'item', true)->
							bind('item', $this->id)->
							get();
		$tags = array();
		foreach ($query as $row) {
			if ($deep) {
				$tags[] = tags::model()->getByPK($row['tag']);
			} else {
				$tags[] = $row['tag'];
			}
		}
		$this->attributes['tags'] = $tags;
		
	}
	
	/**
	 * Checks if the product is new, and sets the created and modified times appropriately,
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
		$tags = isset($this->attributes['tags']) ? $this->attributes['tags'] : array();
		foreach ($tags as $i => $tag) {
			if (!is_numeric($tag)) {
				$tagModel = new tags(array('name' => $tag));
				$tagModel->save();
				$tags[$i] = $tagModel->id;
			} else {
				$tags[$i] = intval($tag);
			}
		}
		$this->tagIds = $tags;
		unset($this->attributes['tags']);
		if (!is_numeric($this->vendor)) {
			$vendor = new vendors(array('name' => $this->vendor));
			$vendor->save();
			$this->vendor = $vendor->id;
		}
		$this->cleanDates(array('purchaseDate', 'saleDate', 'soldDate'));
		
		return true;
	}
	
	private function cleanDates($dateFields) {
		foreach ($dateFields as $dateField) {
			if (!$this->attributes[$dateField]) {
				unset ($this->attributes[$dateField]);
			} else {
				$this->attributes[$dateField] = date("Y-m-d", strtotime($this->attributes[$dateField]));
			}
		}
	}
	
	public function afterSave() {
		$query = Query::build()->
							from('itemTags')->
							where('item', '=', 'item', true)->
							bind('item', $this->id)->
							delete();
		foreach ($this->tagIds as $tag) {
			$query = Query::build()->
								from('itemTags')->
								set('item', 'item', true)->
								set('tag', 'tag', true)->
								bind('item', $this->id)-> 
								bind('tag', $tag)->
								insert();
		}			
	}
	
	private function validateDate($date) {
		$d = DateTime::createFromFormat('m-d-Y', $date);
		return $d && $d->format('m-d-Y') == $date;
	}
	
	public function split($quantity) {
		if ($this->quantity > $quantity) {
			$split = clone $this;
			$split->quantity = $quantity;
			$this->quantity = $this->quantity - $quantity;
			$this->save();
			$split->save();
			return $split;
		} else {
			return false;
		}
	}
	
	public function __clone() {
		unset($this->attributes[$this->pk]);
		$this->isNew = true;
		if (isset($this->attributes['tags'])) $this->attributes['tags'] = array_reverse($this->attributes['tags']);
	}
	
}